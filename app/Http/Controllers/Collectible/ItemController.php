<?php

namespace App\Http\Controllers\Collectible;

use App\Http\Controllers\Controller;
use App\Models\Collectible;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('collectibles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Collectible\Item $item
     * @return View
     */
    public function show(Collectible\Item $item): View
    {
        return view('collectible.item.index', [
            'collectible' => $item->collectible,
            'category' => $item->category,
            'item' => $item,
        ]);
    }
}
