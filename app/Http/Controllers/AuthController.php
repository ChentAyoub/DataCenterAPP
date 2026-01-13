<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
            
            return redirect()->intended('dashboard');
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
            'password' => ['required', 'min:6', 'confirmed'] 
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        
        $formFields['role'] = 'internal_user';

        
        $user = User::create($formFields);

        
        Auth::login($user);

        return redirect('/dashboard');
    }
}