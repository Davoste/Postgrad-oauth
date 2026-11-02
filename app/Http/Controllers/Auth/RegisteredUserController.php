<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'surname'            => ['required', 'string', 'max:100'],
            'first_name'         => ['required', 'string', 'max:100'],
            'email'              => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'email_confirmation' => ['required', 'string', 'email', 'same:email'],
            'id_no'              => ['required', 'string', 'max:30', 'unique:users,id_no'],
            'password'           => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email_confirmation.same' => 'Email confirmation does not match the email address.',
        ]);

        $user = User::create([
            'surname'    => $request->surname,
            'first_name' => $request->first_name,
            'name'       => trim($request->first_name . ' ' . $request->surname),
            'email'      => $request->email,
            'id_no'      => $request->id_no,
            'password'   => Hash::make($request->password),
        ]);

        // New self-registrations join as students; their portal tabs stay
        // locked until they complete the admission application and are
        // admitted, at which point a Student record is created for them.
        $user->assignRole('student');

        // Fires the Registered event, which (via EventServiceProvider) sends
        // the built-in email verification / confirmation notice.
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
