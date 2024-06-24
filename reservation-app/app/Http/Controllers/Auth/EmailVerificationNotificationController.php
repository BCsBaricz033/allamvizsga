<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if(Auth::user()->role==='admin'){
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }
            elseif(Auth::user()->role==='user'){
                return redirect()->intended(route('user.dashboard', absolute: false));
            }
            elseif(Auth::user()->role==='doctor'){
                return redirect()->intended(route('doctor.riports', absolute: false));
            }
            elseif(Auth::user()->role==='assistant'){
                return redirect()->intended(route('assistant.riports', absolute: false));
            }
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
