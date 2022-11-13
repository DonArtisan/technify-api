<?php

namespace App\Http\Livewire;

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
        'ruc',
        'address',
        'agent_name',
        'branch',
        'email',
        'phone_number',
    ];

    protected array $rules = [
        'data.ruc' => ['required'],
        'data.address' => ['required'],
        'data.agent_name' => ['required'],
        'data.branch' => ['required'],
        'data.email' => ['required'],
        'data.phone_number' => ['required'],
    ];

    public ?Supplier $supplierToEdit = null;

    public function save(): void
    {
        $this->validate();

        $data = Arr::only($this->data, [
            'address',
            'agent_name',
            'branch',
            'email',
            'phone_number',
        ]);

        $data['RUC'] = $this->data['ruc'];
        unset($data['ruc']);

        if ($this->isEdit) {
            $this->supplierToEdit->update($data);

            $this->reset();

            $this->dispatchBrowserEvent('wire::message', ['message' => 'supplier updated']);

            return;
        }

        Supplier::create($data);

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

        $this->data = $supplier->only([
            'address',
            'agent_name',
            'branch',
            'email',
            'phone_number',
        ]);
        $this->data['ruc'] = $supplier->RUC;
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
            ->when($this->search, function (Builder $query, $search) {
                $query->where('agent_name', 'ilike', "%$search%");
            })
            ->latest()
            ->paginate();

        return view('livewire.suppliers', compact('suppliers'));
    }
}
