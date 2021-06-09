<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Response;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Collection $collection
     * @return Response
     */
    public function index(Collection $collection): Response
    {
        $stock = $collection->stock()->paginate(30);

        return response([
            'status' => 'success',
            'data' => [
                'meta' => [
                    'items' => $stock->total(),
                    'pages' => $stock->lastPage(),
                ],
                'items' => $stock->items(),
            ],
        ]);
    }
}
