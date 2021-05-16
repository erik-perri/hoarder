<?php

namespace App\Http\Controllers\Collectible;

use App\Http\Controllers\Controller;
use App\Models\Collectible;
use Illuminate\Contracts\View\View;

class CategoriesController extends Controller
{
    /**
     * @param Collectible $collectible
     * @return View
     */
    public function view(Collectible $collectible): View
    {
        $categories = Collectible\Category::whereCollectibleId($collectible->id)->paginate(30);

        return view('collectible.categories', [
            'collectible' => $collectible,
            'categories' => $categories,
        ]);
    }
}
