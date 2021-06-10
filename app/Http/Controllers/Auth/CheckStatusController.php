<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckStatusController extends Controller
{
    /**
     * @param Request $request
     * @param ApiResponseFactory $responseFactory
     * @return Response
     */
    public function status(Request $request, ApiResponseFactory $responseFactory): Response
    {
        return $responseFactory->createSuccess($request->user());
    }
}
