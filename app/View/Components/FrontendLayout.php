<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FrontendLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('layouts.frontend');
    }
}
