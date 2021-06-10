<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiResponseFactory;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @return View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * @param LoginRequest $request
     * @param ApiResponseFactory $responseFactory
     * @return RedirectResponse|Response
     * @throws ValidationException
     */
    public function store(LoginRequest $request, ApiResponseFactory $responseFactory)
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return $responseFactory->createSuccess($request->user());
        }

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * @param Request $request
     * @param ApiResponseFactory $responseFactory
     * @return RedirectResponse|Response
     */
    public function destroy(Request $request, ApiResponseFactory $responseFactory)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return $responseFactory->createSuccess(null);
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
