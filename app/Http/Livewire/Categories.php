<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Support\Livewire\HasGeneralMethods;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;
    use HasGeneralMethods;

    protected $queryString = ['search' => ['except' => '']];

    public bool $isEdit = false;

    public string $name = '';

    public ?Category $categoryToEdit = null;

    protected array $rules = [
        'name' => ['required', 'min:2']
    ];

    public function edit(int $categoryId): void
    {
        /** @var Category $product */
        $category = Category::find($categoryId);

        if (! $category) {
            return;
        }

        $this->categoryToEdit = $category;
        $this->isEdit = true;
        $this->showModal = true;

        $this->name = $category->name;
    }

    public function save()
    {
        if ($this->isEdit) {
            $this->categoryToEdit->update(['name' => $this->name]);

            return;
        }

        $this->validate();

        Category::query()->create(['name' => $this->name]);
    }

    public function render(): View
    {
        $categories = Category::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'ilike', "%$search%");
            })
            ->latest()
            ->paginate();

        return view('livewire.categories', compact('categories'));
    }
}
