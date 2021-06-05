<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckStatusController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function status(Request $request): Response
    {
        return response([
            'status' => 'success',
            'data' => $request->user(),
        ]);
    }
}
