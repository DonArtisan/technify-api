<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Person;
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
        'dni',
        'email',
        'first_name',
        'home_address',
        'last_name',
        'phone_number',
    ];

    protected array $rules = [
        'data.dni' => ['required'],
        'data.email' => ['required'],
        'data.first_name' => ['required'],
        'data.home_address' => ['nullable'],
        'data.last_name' => ['required'],
        'data.phone_number' => ['nullable'],
    ];

    public ?Customer $customerToEdit = null;

    public function save(): void
    {
        $this->validate();

        $personData = Arr::only($this->data, [
            'home_address',
            'dni',
            'email',
            'first_name',
            'last_name',
            'phone_number',
        ]);

        if ($this->isEdit) {
            $this->customerToEdit->person()->update($personData);

            $this->reset();

            $this->dispatchBrowserEvent('wire::message', ['message' => 'cliente actualizado.']);

            return;
        }

        $person = Person::create($personData);

        $person->customer()->create();

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

        $this->data = $customer->person->only([
            'dni',
            'email',
            'home_address',
            'first_name',
            'last_name',
            'phone_number',
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
            ->with('person')
            ->when($this->search, function (Builder $query, $search) {
                $query->whereHas('person', function ($query) use ($search) {
                    $query->where('first_name', 'ilike', "%$search%");
                });
            })
            ->latest()
            ->paginate();

        return view('livewire.customers', compact('customers'));
    }
}
