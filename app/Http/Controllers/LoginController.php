<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function token(Request $request) {
        $input = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'name' => 'required|max:255'
        ]);

        $user = User::where('email', $input['email'])->first();

        if (empty($user) || !Hash::check($input['password'], $user->password)) {
            return response()->json(['message' => 'The provided credentials do not match our records.'], 422);
        };

        $token = $user->createToken($input['name']);
        
        return response()->json(['token' => $token->plainTextToken]);
    }

    public function login(Request $request) {
        $input = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (!Auth::attempt($input)) {
            return response()->json(['errors' => [
                'email' => ['The provided credentials do not match our records.']
            ]], 422);
        }
        
        $request->session()->regenerate();

        return response()->json(['message' => 'Login successful.']);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
