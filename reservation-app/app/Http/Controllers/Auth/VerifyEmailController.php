<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if(Auth::user()->role==='admin'){
                return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
            }
            elseif(Auth::user()->role==='user'){
                return redirect()->intended(route('user.dashboard', absolute: false).'?verified=1');
            }
            elseif(Auth::user()->role==='doctor'){
                return redirect()->intended(route('doctor.riports', absolute: false).'?verified=1');
            }
            elseif(Auth::user()->role==='assistant'){
                return redirect()->intended(route('assistant.riports', absolute: false).'?verified=1');
            }
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if(Auth::user()->role==='admin'){
            return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
        }
        elseif(Auth::user()->role==='user'){
            return redirect()->intended(route('user.dashboard', absolute: false).'?verified=1');
        }
        elseif(Auth::user()->role==='doctor'){
            return redirect()->intended(route('doctor.riports', absolute: false).'?verified=1');
        }
        elseif(Auth::user()->role==='assistant'){
            return redirect()->intended(route('assistant.riports', absolute: false).'?verified=1');
        }
    }
}
