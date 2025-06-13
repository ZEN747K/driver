<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Helpers\DriverAuthHelper;
use App\Http\Controllers\Controller;

class DriverAPICtrl extends Controller
{
    public function index(Request $request)
    {
        // Validate driver token and check status
        $authResult = DriverAuthHelper::checkDriverAuth($request);

        if (!$authResult['valid']) {
            return DriverAuthHelper::authResponse($authResult['message']);
        }

        // Only approved drivers can see other drivers
        if (!isset($authResult['driver']) || !$authResult['driver'] || $authResult['driver']->status !== 'approved') {
            return DriverAuthHelper::authResponse('Access denied. Account not approved.', 403);
        }

        return response()->json([
            'success' => true,
            'drivers' => Driver::select('id', 'name', 'email', 'status')->get()
        ]);
    }

    public function show(Request $request, $id)
    {
        $authResult = DriverAuthHelper::checkDriverAuth($request);

        if (!$authResult['valid']) {
            return DriverAuthHelper::authResponse($authResult['message']);
        }

        try {
            $driver = Driver::findOrFail($id);
            return response()->json([
                'success' => true,
                'driver' => $driver
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found'
            ], 404);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // This would be for admin use - checking if requester is admin
        $authResult = DriverAuthHelper::checkDriverAuth($request);

        if (!$authResult['valid']) {
            return DriverAuthHelper::authResponse($authResult['message']);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,suspended'
        ]);

        try {
            $driver = Driver::findOrFail($id);
            $driver->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Driver status updated',
                'driver' => $driver
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found'
            ], 404);
        }
    }
    public function store(Request $request)
    {
        // Validate ข้อมูล
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:drivers,email',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'password' => 'required|string|min:6',

            'bank_account' => 'nullable|string',
            'id_card_path' => 'nullable|string',
            'driver_license_path' => 'nullable|string',
            'face_photo_path' => 'nullable|string',
            'vehicle_registration_path' => 'nullable|string',
            'compulsory_insurance_path' => 'nullable|string',
            'vehicle_insurance_path' => 'nullable|string',
            'service_type' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        // บันทึกข้อมูล
        $driver = Driver::create($validated);

        return response()->json([
            'message' => 'Driver created successfully',
            'data' => $driver
        ], 201);
    }
}
