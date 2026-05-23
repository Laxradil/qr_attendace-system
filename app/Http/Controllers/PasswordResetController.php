<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'Email address not found.');
        }

        // Generate a unique token
        $token = Str::random(64);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Store the token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // In production, send email here
        // For now, we'll show the reset link in development mode
        $resetLink = route('password.reset.form', ['token' => $token]);
        
        if (env('APP_ENV') === 'local' || env('APP_ENV') === 'development') {
            return back()->with('success', 'Reset link: <a href="' . $resetLink . '" style="color: #00b894; text-decoration: underline;">Click here to reset password</a>');
        }
        
        return back()->with('success', 'If an account exists with that email, a password reset link has been sent.');
    }

    /**
     * Show the reset password form
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // Check if token exists and is recent (within 24 hours)
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetToken) {
            return back()->withInput()->with('error', 'This password reset token is invalid.');
        }

        // Check if token has expired (24 hours)
        if (now()->diffInHours($resetToken->created_at) > 24) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withInput()->with('error', 'This password reset token has expired.');
        }

        // Verify the token
        if (!Hash::check($request->token, $resetToken->token)) {
            return back()->withInput()->with('error', 'This password reset token is invalid.');
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Your password has been reset successfully. Please log in.');
    }
}
