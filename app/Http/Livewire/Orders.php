<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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

    public bool $showModal = false;

    public bool $showModalSupplier = false;

    public int $orderIdToDelete = 0;

    public ?Supplier $supplierSelected = null;

    public array $data = [
        'amount',
        'order_status',
        'required_date',
        'seller_id',
        'supplier_id',
        'tax',
        'total',
    ];

    protected array $rules = [
        'data.amount' => ['required'],
        'data.order_status' => ['required'],
        'data.required_date' => ['required'],
        'data.seller_id' => ['required'],
        'data.supplier_id' => ['required'],
        'data.tax' => ['required'],
        'data.total' => ['required'],
    ];

    public ?Order $orderToEdit = null;

    public function save(): void
    {
        $this->validate();

        $data = Arr::only($this->data, [
            'amount',
            'order_status',
            'required_date',
            'seller_id',
            'supplier_id',
            'tax',
            'total',
        ]);

        $data['RUC'] = $this->data['ruc'];
        unset($data['ruc']);

        if ($this->isEdit) {
            $this->orderToEdit->update($data);

            $this->reset();

            $this->dispatchBrowserEvent('wire::message', ['message' => 'order actualizada.']);

            return;
        }

        Order::create($data);

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
            ->when($this->searchBySeller, function (Builder $query, $search) {
                $query->withWhereHas('seller', function (Builder $query) use ($search) {
                    $query->where('first_name', 'ilike', "%$search%");
                });
            })
            ->when($this->searchBySupplier, function (Builder $query, $search) {
                $query->withWhereHas('supplier', function (Builder $query) use ($search) {
                    $query->where('agent_name', 'ilike', "%$search%");
                });
            })
            ->latest()
            ->paginate();

        $suppliers = [];

        if ($this->showModalSupplier) {
            $suppliers = Supplier::query()
                ->when($this->supplierSearch, function (Builder $query, $search) {
                    $query->where('agent_name', 'ilike', "%$search%");
                })
                ->get();
        }

        logger(compact('suppliers'));

        return view('livewire.orders', compact('orders', 'suppliers'));
    }
}
