<?php

namespace App\Http\Controllers\Collectible;

use App\Http\Controllers\Controller;
use App\Models\Collectible;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Collectible $collectible
     * @return RedirectResponse
     */
    public function index(Collectible $collectible): RedirectResponse
    {
        return redirect()->route('collectibles.show', ['collectible' => $collectible]);
    }

    /**
     * Display the specified resource.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @return View
     */
    public function show(Collectible $collectible, Collectible\Category $category): View
    {
        $items = Collectible\Item::where([
            'collectible_id' => $collectible->id,
            'category_id' => $category->id,
        ])->orderByRaw('CAST(field_values->>\'$.collector_number\' AS UNSIGNED)')->paginate(30);

        return view('collectible.category.show', [
            'collectible' => $collectible,
            'category' => $category,
            'items' => $items,
        ]);
    }
}
