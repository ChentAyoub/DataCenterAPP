<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;

class AuthController extends Controller
{
   
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'terms' => 'accepted',
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        $formFields['role'] = 'internal_user';
        $formFields['is_active'] = false; 
        $user = User::create($formFields);
        Notification::create([
            'user_id' => $user->id,
            'message' => 'Welcome! Your account is PENDING approval. You cannot make reservations yet.',
            'type' => 'info',
            'is_read' => false,
        ]);
        Auth::login($user);
        return redirect('/')->with('success', 'Account created! Please wait for Admin approval.');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
}