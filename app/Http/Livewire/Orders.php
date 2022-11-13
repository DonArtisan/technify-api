<?php

namespace App\Http\Livewire;

use App\Enums\AuthorizeEnum;
use App\Enums\OrderStatus;
use App\Models\Model;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    protected $queryString = ['searchBySeller' => ['except' => ''], 'searchBySupplier' => ['except' => '']];

    public bool $isEdit = false;

    public string $searchBySeller = '';
    public string $searchBySupplier = '';
    public string $supplierSearch = '';
    public string $productSearch = '';

    public bool $showModal = false;
    public bool $showModalSupplier = false;

    public int $orderIdToDelete = 0;

    public ?Supplier $supplierSelected = null;

    public array $modelsIdSelected = [];

    public array $quantities = [];

    public ?string $requiredDate = null;

    public array $data = [
        'requiredDate',
    ];

    protected array $rules = [
        'requiredDate' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
    ];

    public ?Order $orderToEdit = null;

    public function save(): void
    {
        $this->validate();

        $data = Arr::only($this->data, [
            'required_date',
            'supplier_id',
        ]);

        if ($this->isEdit) {
            $this->orderToEdit->update($data);

            $this->reset();

            $this->dispatchBrowserEvent('wire::message', ['message' => 'order actualizada.']);

            return;
        }

        /** @var Seller $seller */
        $seller = Auth::user();

        DB::beginTransaction();
        try {
            /** @var Order $order */
            $order = $seller->orders()->create([
                'amount' => collect($this->quantities)->sum(),
                'order_status' => OrderStatus::PENDING(),
                'required_date' => $this->requiredDate,
                'supplier_id' => $this->supplierSelected->id,
                'authorize_status' => AuthorizeEnum::APPROVED(),
                'tax' => 0,
                'total' => 0,
            ]);

            $productIds = Model::query()
                ->with('product:id,model_id,name')
                ->whereIn('id', $this->modelsIdSelected)
                ->get()
                ->pluck('product.id');

            $data = $productIds->map(function (int $id) {
                return [
                    'product_id' => $id,
                    'quantity' => $this->quantities[$id],
                    'price' => 0
                ];
            });

            $order->orderDetails()->createMany($data);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
        }

        $this->reset();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'order guardada.']);
    }

    public function delete(): void
    {
        /** @var Order $order */
        $order = Order::find($this->supplierIdToDelete);

        if (! $order) {
            return;
        }

        $order->delete();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'order borrada.']);

        $this->resetValues();
    }

    public function edit(int $userId): void
    {
        $order = Order::find($userId);

        if (! $order) {
            return;
        }

        $this->orderToEdit = $order;
        $this->isEdit = true;
        $this->showModal = true;

        $this->data = $order->only([
            'address',
            'agent_name',
            'branch',
            'email',
            'phone_number',
        ]);
        $this->data['ruc'] = $order->RUC;
    }

    public function showAddModal(): void
    {
        $this->showModal = true;

        $this->resetExcept(['showModal']);
    }

    public function removeModel(int $modelId)
    {
        unset($this->modelsIdSelected[$modelId]);
    }

    public function selectModel(int $modelId)
    {
        $this->modelsIdSelected[$modelId] = $modelId;
    }

    public function selectSupplier(int $supplierId)
    {
        $this->supplierSelected = Supplier::find($supplierId);

        $this->showModalSupplier = false;
    }

    public function resetModalSupplier()
    {
        $this->showModalSupplier = false;
    }

    public function resetValues(): void
    {
        $this->reset();
    }

    public function render(): View
    {
        $orders = Order::query()
            ->with(['supplier', 'seller'])
            ->when($this->searchBySeller, function (Builder $query, $search) {
                $query->whereHas('seller', function (Builder $query) use ($search) {
                    $query->where('first_name', 'ilike', "%$search%");
                });
            })
            ->when($this->searchBySupplier, function (Builder $query, $search) {
                $query->whereHas('supplier', function (Builder $query) use ($search) {
                    $query->where('agent_name', 'ilike', "%$search%");
                });
            })
            ->latest()
            ->paginate();

        $suppliers = [];
        $models = [];
        $modelsSelected = [];

        if ($this->supplierSearch) {
            $suppliers = Supplier::query()
                ->where('agent_name', 'ilike',"%$this->supplierSearch%")
                ->get();
        }

        if ($this->productSearch) {
            $models = Model::query()
                ->with('product:id,model_id,name', 'brand:id,name')
                ->whereNotIn('id', $this->modelsIdSelected)
                ->where(function (Builder $query) {
                    $query->where('model_name', 'ilike', "%$this->productSearch%")
                        ->orWhere(function (Builder $query) {
                            $query->where(function (Builder $query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('name', 'ilike', "%$this->productSearch%");
                                });
                            })
                                ->orWhere(function (Builder $query) {
                                    $query->whereHas('brand', function ($query) {
                                        $query->where('name', 'ilike', "%$this->productSearch%");
                                    });
                                });
                        });
                })
                ->get();

            $modelsSelected = Model::query()
                ->with('product:id,model_id,name', 'brand:id,name')
                ->whereIn('id', $this->modelsIdSelected)
                ->get();
        }

        return view('livewire.orders', compact('orders', 'suppliers', 'models', 'modelsSelected'));
    }
}
