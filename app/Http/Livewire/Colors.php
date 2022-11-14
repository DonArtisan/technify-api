<?php

namespace App\Http\Livewire;

use App\Models\Color;
use App\Support\Livewire\HasGeneralMethods;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Colors extends Component
{
    use WithPagination;
    use HasGeneralMethods;

    protected $queryString = ['search' => ['except' => '']];

    public bool $isEdit = false;

    public string $name = '';

    public ?Color $colorToEdit = null;

    protected array $rules = [
        'name' => ['required', 'min:2'],
    ];

    public function edit(int $colorId): void
    {
        /** @var Color $product */
        $color = Color::find($colorId);

        if (! $color) {
            return;
        }

        $this->colorToEdit = $color;
        $this->isEdit = true;
        $this->showModal = true;

        $this->name = $color->name;
    }

    public function save()
    {
        if ($this->isEdit) {
            $this->colorToEdit->update(['name' => $this->name]);

            return;
        }

        $this->validate();

        Color::query()->create(['name' => $this->name]);
    }

    public function render(): View
    {
        $colors = Color::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'ilike', "%$search%");
            })
            ->latest()
            ->paginate();

        return view('livewire.colors', compact('colors'));
    }
}
