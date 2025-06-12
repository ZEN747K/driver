<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{

    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('drivers.index');
        }

        return view('admin.login');
    }

    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (! Auth::guard('admin')->attempt($credentials)) {
           
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid credentials'], 422);
            }
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $admin = Auth::guard('admin')->user();

        $admin->api_token = Str::random(60);
        $admin->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success'   => true,
                'api_token' => $admin->api_token,
            ]);
        }

        return redirect()->route('drivers.index');
    }

    
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}