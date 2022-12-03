<?php

namespace App\Http\Livewire;

use App\Enums\ProductStatus;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Model;
use App\Models\Product;
use App\Support\Livewire\Products\Catalog;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;
    use WithFileUploads;
    use Catalog;

    protected $queryString = ['search' => ['except' => '']];

    public bool $isEdit = false;

    public string $search = '';

    public bool $showModal = false;

    public int $productIdToDelete = 0;

    public array $data = [
        'brand_id',
        'category_id',
        'color_id',
        'description',
        'model_name',
        'name',
        'sale_price',
    ];

    protected array $rules = [
        'data.brand_id' => ['required', 'exists:brands,id'],
        'data.category_id' => ['required', 'exists:categories,id'],
        'data.color_id' => ['nullable', 'exists:colors,id'],
        'data.description' => ['required', 'string', 'min:6'],
        'data.model_name' => ['required'],
        'data.name' => ['required', 'string', 'min:2'],
        'data.sale_price' => ['required', 'numeric'],
    ];

    public ?Product $productToEdit = null;

    public TemporaryUploadedFile|string $image = '';

    public function save(): void
    {
        if ($this->isEdit) {
            $this->productToEdit->update(Arr::only($this->data, ['name', 'description', 'color_id', 'category_id', 'sale_price']));

            $this->productToEdit->model()->update(Arr::only($this->data, ['model_name', 'brand_id']));

            if ($this->image instanceof TemporaryUploadedFile) {
                $this->productToEdit->addMedia($this->image)->toMediaCollection(Product::MEDIA_COLLECTION_IMAGE);
            }

            $this->reset();

            $this->dispatchBrowserEvent('wire::message', ['message' => 'Product updated']);

            return;
        }

        $this->validate();

        $data = Arr::only($this->data, ['name', 'description', 'color_id', 'category_id', 'sale_price']);
        $data['status'] = ProductStatus::ACTIVE;

        /** @var Model $model */
        $model = Model::create(Arr::only($this->data, ['model_name', 'brand_id']));

        /** @var Product $product */
        $product = $model->product()->create($data);

        if ($this->image instanceof TemporaryUploadedFile) {
            $product->addMedia($this->image)->toMediaCollection(Product::MEDIA_COLLECTION_IMAGE);
        }

        $this->reset();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'Product saved.']);
    }

    public function deleteProduct(): void
    {
        /** @var Product $product */
        $product = Product::find($this->productIdToDelete);

        if (! $product) {
            return;
        }

        $product->delete();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'Product deleted.']);

        $this->resetValues();
    }

    public function editProduct(int $productId): void
    {
        /** @var Product $product */
        $product = Product::find($productId);

        if (! $product) {
            return;
        }

        $this->productToEdit = $product;
        $this->isEdit = true;
        $this->showModal = true;
        $this->image = $product->getFirstMediaUrl(Product::MEDIA_COLLECTION_IMAGE);

        $this->data = $product->only([
            'category_id',
            'color_id',
            'description',
            'name',
            'sale_price',
        ]);

        $this->data['brand_id'] = $product->model->brand_id;
        $this->data['model_name'] = $product->model->model_name;
    }

    public function showAddModal(): void
    {
        $this->showModal = true;

        $this->resetExcept(['showModal']);
    }

    public function resetValues(): void
    {
        $this->reset();
    }

    public function render(): View
    {
        $brands = Brand::query()->orderBy('name')->get();
        $categories = Category::query()->orderBy('name')->get();
        $colors = Color::query()->orderBy('name')->get();

        $products = Product::query()
            ->addSelect([
                'category_name' => Category::query()->select('name')->whereColumn('id', 'products.category_id')->take(1),
                'model_name' => Model::query()->select('model_name')->whereColumn('id', 'products.model_id')->take(1),
            ])
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'ilike', "%$search%");
            })
            ->latest()
            ->paginate();

        return view('livewire.products', compact(
            'brands',
            'categories',
            'colors',
            'products'
        ));
    }
}
