<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm(){
        
        return view('login');
     }

public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required'
    ]);

    $user = \App\Models\User::where('email', trim($request->email))->first();

    if ($user && (
            // 1) plain text match (your simple seeder)
            $user->password === $request->password
            // 2) hashed match (if some rows were hashed)
            || Hash::check($request->password, $user->password)
            // 3) optional visible_password column match
            || ((string)($user->visible_password ?? '')) === $request->password
        )) {

        session(['user' => $user]);
        return redirect()->route('dashboard');
    }

    return back()->with('error', 'Invalid email or password.');
}

    public function logout()
    {
        session()->forget('user');
        return redirect()->route('login');
    }
}
