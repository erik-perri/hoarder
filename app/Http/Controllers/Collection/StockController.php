<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponseFactory;
use App\Models\Collection;
use Illuminate\Http\Response;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Collection $collection
     * @param ApiResponseFactory $responseFactory
     * @return Response
     */
    public function index(Collection $collection, ApiResponseFactory $responseFactory): Response
    {
        $stock = $collection->stock()->paginate(30);

        return $responseFactory->createListFromPaginator($stock);
    }
}
