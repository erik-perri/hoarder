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
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('collectibles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Collectible\Category $category
     * @return View
     */
    public function show(Collectible\Category $category): View
    {
        $items = Collectible\Item::where([
            'collectible_id' => $category->collectible->id,
            'category_id' => $category->id,
        ])->orderByRaw('CAST(field_values->>\'$.collector_number\' AS UNSIGNED)')->paginate(30);

        return view('collectible.category.index', [
            'collectible' => $category->collectible,
            'category' => $category,
            'items' => $items,
        ]);
    }
}
