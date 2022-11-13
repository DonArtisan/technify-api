<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use App\Support\Livewire\HasGeneralMethods;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Brands extends Component
{
    use HasGeneralMethods;
    use WithPagination;

    protected $queryString = ['search' => ['except' => '']];

    public bool $isEdit = false;

    public string $name = '';

    public ?Brand $brandToEdit = null;

    protected array $rules = [
        'name' => ['required', 'min:2']
    ];

    public function edit(int $colorId): void
    {
        /** @var Brand $product */
        $color = Brand::find($colorId);

        if (! $color) {
            return;
        }

        $this->brandToEdit = $color;
        $this->isEdit = true;
        $this->showModal = true;

        $this->name = $color->name;
    }

    public function save(): void
    {
        if ($this->isEdit) {
            $this->brandToEdit->update(['name' => $this->name]);

            $this->resetValues();

            return;
        }

        $this->validate();

        Brand::query()->create(['name' => $this->name]);

        $this->resetValues();
    }

    public function render(): View
    {
        $brands = Brand::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'ilike', "%$search%");
            })
            ->latest()
            ->paginate();

        return view('livewire.brands', compact('brands'));
    }
}
