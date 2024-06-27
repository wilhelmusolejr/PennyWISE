<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request) {
        // Get the authenticated user using Eloquent
        $user = Auth::user();

        // Authorize user
        if (Auth::id() !== $user->id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'old_password' => 'required|string|min:2',
            'password' => 'nullable|string|min:2|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify the old password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['errors' => ['old_password' => 'The provided password does not match our records.']], 422);
        }

        // Handle file upload
        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }
            $file = $request->file('profile_picture');
            $profilePicturePath = $file->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicturePath;
        }

        // Update user information
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->birthdate = $request->birthdate;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        /** @var \App\Models\User $user **/
        $user->save();

        return response()->json([
            'success' => 'Profile updated successfully!',
            'url' => route('home_dashboard')
        ]);
    }
}
