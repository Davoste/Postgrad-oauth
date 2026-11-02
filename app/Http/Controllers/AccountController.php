<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function editProfile(Request $request)
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'surname'    => ['nullable', 'string', 'max:100'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'      => ['nullable', 'string', 'max:30'],
            'id_no'      => ['nullable', 'string', 'max:30', Rule::unique('users')->ignore($user->id)],
        ]);

        // Keep the display name in sync when surname/first name are edited.
        if (!empty($validated['surname']) && !empty($validated['first_name'])) {
            $validated['name'] = trim($validated['first_name'] . ' ' . $validated['surname']);
        }

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function editPassword()
    {
        return view('account.password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('password.edit')->with('success', 'Password changed successfully.');
    }
}