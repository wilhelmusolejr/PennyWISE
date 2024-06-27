<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {

    public function login(Request $request) {
        $credentials = [
            'email' => $request->input('login_email'),
            'password' => $request->input('login_password')
        ];

        if (Auth::attempt($credentials)) {
            // Regenerate session token
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'url' => route('home_dashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => [
                'login_email' => ['The provided credentials do not match our records.']
            ]
        ], 422);
    }

    // Handle logout request
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');
    }

    // Handle registration request
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required',
            'signupEmail' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'required|string|min:2|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'signupEmail.unique' => 'The email address has already been taken.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $profilePicturePath = $file->store('profile_pictures', 'public');
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->signupEmail,
            'birthdate' => $request->birthdate,
            'password' => bcrypt($request->password),
            'profile_picture' => $profilePicturePath,
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'url' => route('home_dashboard')
        ]);
    }
}
