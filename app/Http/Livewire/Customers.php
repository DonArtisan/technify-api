<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{
    use WithPagination;

    protected $queryString = ['search' => ['except' => '']];

    public bool $isEdit = false;

    public string $search = '';

    public bool $showModal = false;

    public array $data = [
        'address',
        'dni',
        'first_name',
        'last_name',
        'phone',
    ];

    protected array $rules = [
        'data.address' => ['nullable'],
        'data.dni' => ['required'],
        'data.first_name' => ['required'],
        'data.last_name' => ['required'],
        'data.phone' => ['nullable'],
    ];

    public ?Customer $customerToEdit = null;

    public function save(): void
    {
        $this->validate();

        $data = Arr::only($this->data, [
            'address',
            'dni',
            'first_name',
            'last_name',
            'phone',
        ]);

        if ($this->isEdit) {
            $this->customerToEdit->update($data);

            $this->reset();

            $this->dispatchBrowserEvent('wire::message', ['message' => 'cliente actualizado.']);

            return;
        }

        Customer::create($data);

        $this->reset();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'cliente guardado.']);
    }

    public function edit(int $userId): void
    {
        $customer = Customer::find($userId);

        if (! $customer) {
            return;
        }

        $this->customerToEdit = $customer;
        $this->isEdit = true;
        $this->showModal = true;

        $this->data = $customer->only([
            'address',
            'dni',
            'first_name',
            'last_name',
            'phone',
        ]);
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
        $customers = Customer::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('first_name', 'ilike', "%$search%");
            })
            ->where('is_admin', false)
            ->latest()
            ->paginate();

        return view('livewire.customers', compact('customers'));
    }
}
