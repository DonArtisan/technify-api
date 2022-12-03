<?php

namespace App\Http\Livewire;

use App\Models\Person;
use App\Models\Seller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Sellers extends Component
{
    use WithPagination;

    protected $queryString = ['search' => ['except' => '']];

    public bool $isEdit = false;

    public string $search = '';

    public bool $showModal = false;

    public int $sellerIdToDelete = 0;

    public array $data = [
        'first_name',
        'email',
        'hired_at',
        'last_name',
        'password',
        'home_address',
        'dni',
        'phone_number',
    ];

    protected array $rules = [
        'data.first_name' => ['required'],
        'data.email' => ['required', 'email', 'unique:people,email'],
        'data.hired_at' => ['required', 'before_or_equal:today'],
        'data.dni' => ['required'],
        'data.home_address' => ['required'],
        'data.phone_number' => ['required'],
        'data.last_name' => ['required'],
        'data.password' => ['required'],
    ];

    public ?Seller $sellerToEdit = null;

    public function save(): void
    {
        if ($this->isEdit) {
            $this->validate([
                ...$this->rules,
                'data.password' => ['sometimes'],
                'data.email' => ['required']
            ]);

            $personData = Arr::only($this->data, ['first_name', 'last_name', 'email', 'dni', 'phone_number', 'home_address']);

            if (isset($this->data['password'])) {
                $data['password'] = bcrypt($this->data['password']);
                $this->sellerToEdit->update($data);
            }

            $this->sellerToEdit->person()->update($personData);

            $this->reset();

            $this->dispatchBrowserEvent('wire::message', ['message' => 'usuario actualizado']);

            return;
        }

        $this->validate();

        $data = Arr::only($this->data, ['first_name', 'last_name', 'email', 'dni', 'phone_number', 'home_address']);

        $person = Person::create($data);

        /** @var Seller $seller */
        $seller = $person->seller()->create([
            'hired_at' => $this->data['hired_at'],
            'carnet' => getRandomCarnet(),
            'password' => bcrypt($this->data['password']),
        ]);

        $seller->assign('seller');

        $this->reset();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'usuario guardado.']);
    }

    public function deleteUser(): void
    {
        /** @var Seller $seller */
        $seller = Seller::find($this->sellerIdToDelete);

        if (! $seller) {
            return;
        }

        $seller->delete();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'usuario borrado.']);

        $this->resetValues();
    }

    public function editUser(int $userId): void
    {
        $seller = Seller::find($userId);

        if (! $seller) {
            return;
        }

        $this->sellerToEdit = $seller;
        $this->isEdit = true;
        $this->showModal = true;

        $this->data = $seller->person->only([
            'dni',
            'first_name',
            'last_name',
            'home_address',
            'email',
            'phone_number',
        ]);

        $this->data['hired_at'] = $seller->hired_at->toDateString();
    }

    public function showUserModal(): void
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
        $users = Seller::query()
            ->with('person')
            ->when($this->search, function (Builder $query, $search) {
                $query->whereHas('person', function ($query) use ($search) {
                    $query->where('first_name', 'ilike', "%$search%");
                });
            })
            ->latest()
            ->paginate();

        return view('livewire.sellers', compact('users'));
    }
}
