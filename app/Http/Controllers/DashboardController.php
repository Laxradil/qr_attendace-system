<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is admin, redirect to admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // For regular users/students, show user dashboard
        return view('dashboard', [
            'user' => $user
        ]);
    }
}
