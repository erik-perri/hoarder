<?php

namespace App\Http\Controllers\Collectible;

use App\Http\Controllers\Controller;
use App\Models\Collectible;
use Illuminate\Contracts\View\View;

class CollectiblesController extends Controller
{
    /**
     * @return View
     */
    public function view(): View
    {
        $collectibles = Collectible::latest()->paginate(30);

        return view('collectible.collectibles', ['collectibles' => $collectibles]);
    }
}
