<?php

namespace App\Http\Livewire;

use App\Enums\AuthorizeEnum;
use App\Enums\OrderStatus;
use App\Models\Model;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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

    public array $prices = [];

    public array $gains = [];

    public ?string $requiredDate = null;

    public int $orderIdToApprove = 0;

    public int $orderIdToDisplay = 0;

    public array $data = [
        'requiredDate',
    ];

    protected array $rules = [
        'requiredDate' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
    ];

    public ?Order $orderToEdit = null;

    public function approveOrder()
    {
        if (! $this->orderIdToApprove) {
            return;
        }

        try {
            DB::beginTransaction();

            /** @var Order $order */
            $order = Order::query()
                ->find($this->orderIdToApprove);

            if ($order->orderDetails()->count() !== count(array_filter($this->gains))) {
                $this->dispatchBrowserEvent('wire::error', ['message' => 'Debe llenar todos los márgenes de ganancia.']);

                return;
            }

            $order->update(['order_status' => OrderStatus::COMPLETED()]);

            $orderDetails = $order->orderDetails()->with('product.stock')->get();

            $orderDetails->each(function (OrderDetail $orderDetail) {
                /** @var Product $product */
                $product = $orderDetail->product;

                if (! $product->stock) {
                    $product->stock()->create(['quantity' => $orderDetail->quantity]);
                } else {
                    $product->stock()->update(['quantity' => $product->stock->quantity + $orderDetail->quantity]);
                }

                $orderDetail->update(['gain' => $this->gains[$product->id], 'unit_price_with_gain' => (int) ($orderDetail->price + ($orderDetail->price * ($this->gains[$product->id] / 100)))]);
                $product->prices()->create(['price' => $orderDetail->price + ($orderDetail->price * ($this->gains[$product->id] / 100))]);
            });

            $this->dispatchBrowserEvent('wire::message', ['message' => 'Solicitud aprobada correctamente.']);

            $this->resetValues();

            DB::commit();
        } catch (\Throwable $exception) {
            logger($exception->getMessage());
            DB::rollBack();

            $this->addError('error', 'No se ha podido aprobar la orden.');
        }
    }

    public function save(): void
    {
        $this->validate();

        /** @var Seller|User $seller */
        $user = Auth::user();

        DB::beginTransaction();
        try {
            /** @var Order $order */
            $order = $user->orders()->create([
                'amount' => collect($this->quantities)->sum(),
                'order_status' => OrderStatus::PENDING(),
                'required_date' => $this->requiredDate,
                'supplier_id' => $this->supplierSelected->id,
                'authorize_status' => AuthorizeEnum::APPROVED(),
                'tax' => 0,
                'total' => 0,
            ]);

            $products = Model::query()
                ->with('product:id,model_id,name,sale_price')
                ->whereIn('id', $this->modelsIdSelected)
                ->get()
                ->pluck('product');

            $total = 0;

            $data = $products->map(function (Product $product) use (&$total) {
                $total += ($product->sale_price * $this->quantities[$product->id]);

                return [
                    'product_id' => $product->id,
                    'quantity' => $this->quantities[$product->id],
                    'price' => $product->sale_price,
                    'gain' => 0,
                    'unit_price_with_gain' => 0,
                ];
            });

            $order->orderDetails()->createMany($data);

            $order->update(['total' => $total]);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            logger($exception->getMessage());

            $this->dispatchBrowserEvent('wire::error', ['message' => 'No se ha podido guardar la orden.']);

            return;
        }

        $this->reset();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'order guardada.']);
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

    public function resetOrderIds()
    {
        $this->reset('orderIdToApprove', 'orderIdToDisplay');
    }

    public function render(): View
    {
        $orders = Order::query()
            ->with(['supplier.person', 'orderable.person'])
            ->when($this->searchBySeller, function (Builder $query, $search) {
                $query->whereHas('orderable.person', function (Builder $query) use ($search) {
                    $query->where('first_name', 'ilike', "%$search%");
                });
            })
            ->when($this->searchBySupplier, function (Builder $query, $search) {
                $query->whereHas('supplier.person', function (Builder $query) use ($search) {
                    $query->where('first_name', 'ilike', "%$search%");
                });
            })
            ->latest()
            ->paginate();

        $suppliers = [];
        $models = [];
        $modelsSelected = [];

        if ($this->supplierSearch) {
            $suppliers = Supplier::query()
                ->with('person')
                ->when($this->supplierSearch, function (Builder $query, $search) {
                    $query->whereHas('person', function ($query) use ($search) {
                        $query->where('first_name', 'ilike', "%$search%");
                    });
                })
                ->get();
        }

        if ($this->productSearch) {
            $models = Model::query()
                ->with('product:id,model_id,name,sale_price', 'brand:id,name')
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
                ->with('product:id,model_id,name,sale_price', 'brand:id,name')
                ->whereIn('id', $this->modelsIdSelected)
                ->get();
        }

        $orderToDisplay = null;
        $orderDetails = null;

        if ($this->orderIdToDisplay) {
            $orderToDisplay = Order::query()
                ->with('orderDetails.product.model.brand', 'supplier.person')
                ->find($this->orderIdToDisplay);

            $orderDetails = $orderToDisplay->orderDetails;
        }

        return view('livewire.orders', compact(
            'orders',
            'suppliers',
            'models',
            'modelsSelected',
            'orderToDisplay',
            'orderDetails'
        ));
    }
}
