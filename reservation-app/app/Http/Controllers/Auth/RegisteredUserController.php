<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255',],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string','max:255'],
            'birthdate' => ['required'],
            'identity' => ['max:255'],
        ]);
        $existingUser = User::where('email', $request->email)->first();


        if ($existingUser && $existingUser->password) {
            return redirect()->back()->withErrors(['email' => 'Ez az e-mail cím már regisztrálva van.'])->withInput($request->except('password'));

        }
        elseif ($existingUser && !$existingUser->password) {
            $existingUser->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address? $request->address : null,
                'birthdate' => $request->birthdate,
                'identity_number' => $request->identity ? $request->identity : null,
            ]);
            $user=$existingUser;
        }
        else{
            $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'birthdate' => $request->birthdate,
            'identity_number' => $request->identity,
        ]);
        event(new Registered($user));
        }
        Auth::login($user);

        return redirect(route('login', absolute: false));
    }
}
