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
}
