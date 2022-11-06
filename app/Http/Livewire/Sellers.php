<?php

namespace App\Http\Livewire;

use App\Models\Seller;
use App\Models\User;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;

class Sellers extends Component
{
    use WithPagination;

    protected $queryString = ['search' => ['except' => '']];

    public string $search = '';

    public bool $showModal = false;

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

    public function addUser(): void
    {
        $this->validate();

        $data = Arr::only($this->data, ['first_name', 'last_name', 'hired_at', 'email']);

        Seller::create([
            ...$data,
            'carnet' => getRandomCarnet(),
            'password' => bcrypt('1234')
        ]);

        $this->reset();

        $this->dispatchBrowserEvent('wire::message', ['message' => 'user saved.']);
    }

    public function showUserModal(): void
    {
        $this->showModal = true;

        $this->resetExcept(['showModal']);
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
