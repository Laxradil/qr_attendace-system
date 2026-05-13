<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                
                // Check user role and redirect accordingly
                $user = Auth::user();
                if ($user->isAdmin()) {
                    return redirect()->intended('/admin');
                } elseif ($user->isProfessor()) {
                    return redirect()->intended('/professor');
                } elseif ($user->isStudent()) {
                    return redirect()->intended('/student/dashboard');
                }
                return redirect()->intended('/dashboard');
            }
        } catch (\Illuminate\Database\QueryException | \PDOException $exception) {
            return back()->withErrors([
                'email' => 'Unable to login: database driver not installed or connection is misconfigured. Please verify DB_CONNECTION and your PHP database extensions.',
            ])->withInput($request->except('password'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'student',
                'username' => strtolower(str_replace(' ', '_', $request->name)),
            ]);

            Auth::login($user);
            return redirect('/student/dashboard');
        } catch (\Illuminate\Database\QueryException | \PDOException $exception) {
            return back()->withErrors([
                'email' => 'Unable to register: database driver not installed or connection is misconfigured. Please verify DB_CONNECTION and your PHP database extensions.',
            ])->withInput($request->except('password'));
        }
    }
}