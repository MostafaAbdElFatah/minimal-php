<?php

namespace App\View\Components\idea;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class status-filter extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.idea.status-filter');
    }
}
