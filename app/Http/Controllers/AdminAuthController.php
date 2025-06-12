<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        /** @var \App\Models\Admin|null $admin */
        $admin = $request->attributes->get('admin');
        if (! $admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid credentials'], 422);
            }

            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $admin->api_token = JWTAuth::fromUser($admin);
        $admin->save();

        Auth::guard('admin')->login($admin);

        if ($request->expectsJson()) {
            return response()->json(['token' => $admin->api_token]);
        }

        return redirect()->route('drivers.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
