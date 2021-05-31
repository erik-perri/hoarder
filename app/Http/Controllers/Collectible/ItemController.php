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
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @return RedirectResponse
     */
    public function index(Collectible $collectible, Collectible\Category $category): RedirectResponse
    {
        return redirect()->route('collectibles.categories.show', ['collectible' => $collectible, 'category' => $category]);
    }

    /**
     * Display the specified resource.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @param Collectible\Item $item
     * @return View
     */
    public function show(Collectible $collectible, Collectible\Category $category, Collectible\Item $item): View
    {
        return view('collectible.item.show', [
            'collectible' => $collectible,
            'category' => $category,
            'item' => $item,
        ]);
    }
}
