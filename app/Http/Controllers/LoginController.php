<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request) {
        $input = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'name' => 'required|max:255'
        ]);

        $user = User::where('email', $input['email'])->first();

        if (empty($user) || !Hash::check($input['password'], $user->password)) {
            return response('Invalid credentials.', 401);
        };

        $token = $user->createToken($input['name']);
        
        return response()->json(['token' => $token->plainTextToken]);
 
        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();
 
        //     return redirect()->intended('dashboard');
        // }
 
        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->onlyInput('email');
    }
}
