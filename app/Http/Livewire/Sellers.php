<?php

namespace App\Http\Livewire;

use App\Models\Seller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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
    ];

    protected array $rules = [
        'data.first_name' => ['required'],
        'data.email' => ['required', 'email', 'unique:users,email', 'unique:sellers,email'],
        'data.hired_at' => ['required', 'before_or_equal:today'],
        'data.last_name' => ['required'],
    ];

    public ?Seller $sellerToEdit = null;

    public function save(): void
    {
        if ($this->isEdit) {
            $this->sellerToEdit->update($this->data);

            $this->reset();

            $this->dispatchBrowserEvent('wire::message', ['message' => 'usuario actualizado']);

            return;
        }

        $this->validate();

        $data = Arr::only($this->data, ['first_name', 'last_name', 'hired_at', 'email']);

        Seller::create([
            ...$data,
            'carnet' => getRandomCarnet(),
            'password' => bcrypt('1234'),
        ]);

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

        $this->data = $seller->only([
            'email',
            'first_name',
            'hired_at',
            'last_name',
        ]);
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
            ->when($this->search, function (Builder $query, $search) {
                $query->where('first_name', 'ilike', "%$search%");
            })
            ->latest()
            ->paginate();

        return view('livewire.sellers', compact('users'));
    }
}
