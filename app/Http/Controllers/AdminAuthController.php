<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function showRegisterForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'email' => 'required|email|unique:admins,email',
            'document' => 'required|file',
            'password' => 'required|confirmed|min:6',
        ]);

        $path = $request->file('document')->store('admins', 'public');

        $admin = Admin::create([
            'name' => $data['name'],
            'age' => $data['age'],
            'email' => $data['email'],
            'document_path' => $path,
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(60),
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->route('drivers.index')->with('success', 'Login completed');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();
        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $admin->api_token = Str::random(60);
        $admin->save();

        Auth::guard('admin')->login($admin);

        return redirect()->route('drivers.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
