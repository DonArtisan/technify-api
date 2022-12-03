<?php

namespace App\Http\Livewire;

use App\Models\Person;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;

class Suppliers extends Component
{
    use WithPagination;

    protected $queryString = ['search' => ['except' => '']];

    public bool $isEdit = false;

    public string $search = '';

    public bool $showModal = false;

    public int $supplierIdToDelete = 0;

    public array $data = [
        'home_address',
        'branch',
        'email',
        'dni',
        'first_name',
        'last_name',
        'phone_number',
    ];

    protected array $rules = [
        'data.home_address' => ['required'],
        'data.branch' => ['required'],
        'data.email' => ['required'],
        'data.dni' => ['required'],
        'data.first_name' => ['required'],
        'data.last_name' => ['required'],
        'data.phone_number' => ['required'],
    ];

    public ?Supplier $supplierToEdit = null;

    public function save(): void
    {
        $this->validate();

        $personData = Arr::only($this->data, [
            'email',
            'dni',
            'first_name',
            'last_name',
            'phone_number',
            'home_address'
        ]);

        $data['branch'] = $this->data['branch'];

        if ($this->isEdit) {
            $this->supplierToEdit->person()->update($personData);

            $this->supplierToEdit->update($data);

            $this->reset();

            $this->dispatchBrowserEvent('wire::message', ['message' => 'supplier updated']);

            return;
        }

        $person = Person::create($personData);

        $person->supplier()->create($data);

        $this->reset();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'supplier saved.']);
    }

    public function delete(): void
    {
        /** @var Supplier $supplier */
        $supplier = Supplier::find($this->supplierIdToDelete);

        if (! $supplier) {
            return;
        }

        $supplier->person()->delete();

        $supplier->delete();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'supplier deleted.']);

        $this->resetValues();
    }

    public function edit(int $userId): void
    {
        $supplier = Supplier::find($userId);

        if (! $supplier) {
            return;
        }

        $this->supplierToEdit = $supplier;
        $this->isEdit = true;
        $this->showModal = true;

        $this->data = $supplier->person->only([
            'dni',
            'first_name',
            'last_name',
            'home_address',
            'email',
            'phone_number',
        ]);
        $this->data['branch'] = $supplier->branch;
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
        $suppliers = Supplier::query()
            ->with('person')
            ->when($this->search, function (Builder $query, $search) {
                $query->whereHas('person', function ($query) use ($search) {
                    $query->where('first_name', 'ilike', "%$search%");
                });
            })
            ->latest()
            ->paginate();

        return view('livewire.suppliers', compact('suppliers'));
    }
}
