<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\ApiResponseFactory;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param RegisterRequest $request
     * @param ApiResponseFactory $responseFactory
     * @return RedirectResponse|Response
     */
    public function store(RegisterRequest $request, ApiResponseFactory $responseFactory)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $redirectTo = $user instanceof MustVerifyEmail
            ? route('verification.notice', [], ! $request->expectsJson())
            : RouteServiceProvider::HOME; // @phpstan-ignore-line

        if ($request->expectsJson()) {
            return $responseFactory->createSuccess([
                'user' => $user,
                'redirect' => $redirectTo,
            ]);
        }

        return redirect($redirectTo);
    }
}
