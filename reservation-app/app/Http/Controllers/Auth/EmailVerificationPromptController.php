<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        if($request->user()->hasVerifiedEmail())
        {
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
        }else{
           return Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
        }
        
    }
}
