<?php

namespace App\Http\Controllers\Collectible;

use App\Http\Controllers\Controller;
use App\Models\Collectible;
use Illuminate\Contracts\View\View;

class ItemsController extends Controller
{
    /**
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @return View
     */
    public function view(Collectible $collectible, Collectible\Category $category): View
    {
        $items = Collectible\Item::where([
            'collectible_id' => $collectible->id,
            'category_id' => $category->id,
        ])->orderByRaw('CAST(field_values->>\'$."collector-number"\' AS UNSIGNED)')->paginate(30);

        return view('collectible.items', [
            'collectible' => $collectible,
            'category' => $category,
            'items' => $items,
        ]);
    }

    /**
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @param Collectible\Item $item
     * @return View
     */
    public function single(Collectible $collectible, Collectible\Category $category, Collectible\Item $item): View
    {
        return view('collectible.item', [
            'collectible' => $collectible,
            'category' => $category,
            'item' => $item,
        ]);
    }
}
