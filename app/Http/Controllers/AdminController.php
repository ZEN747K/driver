<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function authorizeSuper()
    {
        if (!auth('admin')->check()) {
            abort(403);
        }
        if (!auth('admin')->user()->is_super) {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorizeSuper();
        $admins = Admin::all();
        return view('admins.index', compact('admins'));
    }

    public function create()
    {
        $this->authorizeSuper();
        return view('admins.create');
    }

    public function store(Request $request)
    {
        $this->authorizeSuper();
        $data = $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string',
            'is_super' => 'sometimes|boolean',
        ]);

        $data['api_token'] = Str::random(60);
        $data['is_super'] = $request->boolean('is_super');
        Admin::create($data);

        return redirect()->route('admins.index');
    }

    public function edit(string $id)
    {
        $this->authorizeSuper();
        $admin = Admin::findOrFail($id);
        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, string $id)
    {
        $this->authorizeSuper();
        $admin = Admin::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'password' => 'nullable|string',
            'is_super' => 'sometimes|boolean',
        ]);
        if ($request->filled('password')) {
            $admin->password = Hash::make($data['password']);
        }
        $admin->name = $data['name'];
        $admin->age = $data['age'];
        $admin->email = $data['email'];
        $admin->is_super = $request->boolean('is_super');
        $admin->save();

        return redirect()->route('admins.index');
    }

    public function destroy(string $id)
    {
        $this->authorizeSuper();
        $admin = Admin::findOrFail($id);
        $admin->delete();
        return redirect()->route('admins.index');
    }
}
