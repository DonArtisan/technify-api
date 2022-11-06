<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public function __construct(public bool $open = false, public string $size = 'md', public ?string $cleanAction = null)
    {
        //
    }

    public function render(): View
    {
        return view('components.modal');
    }
}
