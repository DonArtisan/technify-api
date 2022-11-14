<?php

namespace App\Support\Livewire;

trait HasGeneralMethods
{
    public string $search = '';

    public bool $showModal = false;

    public function showAddModal(): void
    {
        $this->showModal = true;

        $this->resetExcept(['showModal']);
    }

    public function resetValues(): void
    {
        $this->reset();
    }
}
