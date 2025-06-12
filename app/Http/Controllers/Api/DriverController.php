<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        return response()->json(
            Driver::select('id','full_name','phone','email','password_for_profile','bank_account','birthdate','gender','service_type','status')->get()
        );
    }

    public function show(string $id)
    {
        $driver = Driver::select('id','full_name','phone','email','password_for_profile','bank_account','birthdate','gender','service_type','status')
            ->findOrFail($id);
        return response()->json($driver);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'password_for_profile' => 'nullable|string',
            'bank_account' => 'required|digits_between:1,14',
            'service_type' => 'required|in:car,motorcycle,delivery',
        ]);
        $data['status'] = 'Pending';

        $driver = Driver::create($data);

        return response()->json($driver, 201);
    }
}

