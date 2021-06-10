<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponseFactory;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyEmailController extends Controller
{
    /**
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return view('auth.verify-email');
    }

    /**
     * Send a new email verification notification.
     *
     * @param Request $request
     * @param ApiResponseFactory $responseFactory
     * @return RedirectResponse|Response
     */
    public function store(Request $request, ApiResponseFactory $responseFactory)
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                return $responseFactory->createSuccess(null, __('auth.email_verified_already'));
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        if ($request->expectsJson()) {
            return $responseFactory->createSuccess(null, __('auth.verification_link_sent'));
        }

        return back()->with('status', __('auth.verification_link_sent'));
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param EmailVerificationRequest $request
     * @return RedirectResponse
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME)
                             ->with('status', __('auth.email_verified_already'));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME)
                         ->with('status', __('auth.email_verified'));
    }
}
