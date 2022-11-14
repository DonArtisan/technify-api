<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Model;
use App\Models\ProductSale as Sale;
use App\Models\Seller;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Sales extends Component
{
    use WithPagination;

    protected $queryString = ['searchByProduct' => ['except' => '']];

    public bool $isEdit = false;

    public string $searchByProduct = '';

    public string $customerSearch = '';

    public string $productSearch = '';

    public bool $showModal = false;

    public bool $showModalCustomer = false;

    public int $saleIdToDelete = 0;

    public array $modelsIdSelected = [];

    public array $quantities = [];

    public int $saleIdToApprove = 0;

    public int $saleIdToDisplay = 0;

    public ?Sale $saleToEdit = null;

    public ?Customer $customerSelected = null;

    public float $subtotal = 0;

    public float $total = 0;

    public function calculate()
    {
        $modelsSelected = Model::query()
            ->with('product.price')
            ->whereIn('id', $this->modelsIdSelected)
            ->get();

        $subtotal = 0;

        $modelsSelected->each(function (Model $model) use (&$subtotal) {
            $subtotal += ($model->product->price->price * $this->quantities[$model->id]);
        });

        $this->subtotal = $subtotal;
        $this->total = $subtotal + ($subtotal * 0.15);
    }

    public function save(): void
    {
        /** @var Seller|User $seller */
        $user = Auth::user();

        try {
            DB::beginTransaction();

            /** @var Sale $sale */
            $sale = $this->customerSelected->sales()->create([
                'amount' => collect($this->quantities)->sum(),
                'tax' => 15,
                'total' => $this->total,
            ]);

            $models = Model::query()
                ->with('product.stock')
                ->whereIn('id', $this->modelsIdSelected)
                ->get();

            $data = $models->map(function (Model $model) {
                $product = $model->product;

                return [
                    'product_id' => $product->id,
                    'quantity' => $this->quantities[$model->id],
                    'price' => $product->price->price,
                ];
            });

            $sale->saleDetails()->createMany($data);

            Stock::query()
                ->whereIn('product_id', $models->pluck('product.id'))
                ->each(function (Stock $stock) {
                    $stock->update([
                        'quantity' => $stock->quantity - $this->quantities[$stock->product_id],
                    ]);
                });

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

    public function selectCustomer(int $customerId)
    {
        $this->customerSelected = Customer::find($customerId);

        $this->showModalCustomer = false;
    }

    public function resetModalCustomer()
    {
        $this->showModalCustomer = false;
    }

    public function resetValues(): void
    {
        $this->reset();
    }

    public function resetSaleIds()
    {
        $this->reset('saleIdToApprove', 'saleIdToDisplay');
    }

    public function render(): View
    {
        $sales = Sale::query()
            ->with('buyerable')
            ->where('buyerable_type', 'customer')
            ->latest()
            ->paginate();

        $models = [];
        $modelsSelected = [];

        if ($this->productSearch) {
            $models = Model::query()
                ->withWhereHas('product.stock', function ($query) {
                    $query->where('quantity', '>', 0);
                })
                ->with('brand:id,name', 'product.price')
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
                ->with('product.stock', 'product.price', 'brand:id,name')
                ->whereIn('id', $this->modelsIdSelected)
                ->get();
        }

        $salesToDisplay = null;
        $saleDetails = null;

        if ($this->saleIdToDisplay) {
            $salesToDisplay = Sale::query()
                ->with(
                    'saleDetails.product.model.brand',
                    'saleDetails.product.stock',
                    'supplier',
                    'salesDetails.product.price'
                )
                ->find($this->saleIdToDisplay);

            $saleDetails = $salesToDisplay->saleDetails;
        }

        $customers = [];

        if ($this->customerSearch) {
            $customers = Customer::query()
                ->where('first_name', 'ilike', "%$this->customerSearch%")
                ->get();
        }

        return view('livewire.sales', compact(
            'sales',
            'models',
            'customers',
            'modelsSelected',
            'salesToDisplay',
            'saleDetails'
        ));
    }
}
