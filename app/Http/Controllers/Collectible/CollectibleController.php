<?php

namespace App\Http\Controllers\Collectible;

use App\Http\Controllers\Controller;
use App\Models\Collectible;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class CollectibleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|View
     */
    public function index(): View
    {
        $collectibles = Collectible::latest()->paginate(30);

        return view('collectible.collectibles', ['collectibles' => $collectibles]);
    }
}
