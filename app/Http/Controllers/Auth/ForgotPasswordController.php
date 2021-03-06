<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Responses\ApiResponseFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param ForgotPasswordRequest $request
     * @param ApiResponseFactory $responseFactory
     * @return RedirectResponse|Response
     */
    public function store(ForgotPasswordRequest $request, ApiResponseFactory $responseFactory)
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($request->expectsJson()) {
            if ($status === Password::RESET_LINK_SENT) {
                return $responseFactory->createSuccess(null, __($status));
            }

            return $responseFactory->createFailure(__($status));
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }
}
