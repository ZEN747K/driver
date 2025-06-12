<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class VerifyAdminCredentials
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();
        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid Username or Password'], 422);
            }

            return redirect()->back()->withErrors(['email' => 'Invalid Username or Password']);
        }

        $request->attributes->set('admin', $admin);

        return $next($request);
    }
}
