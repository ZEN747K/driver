<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Helpers\DriverAuthHelper;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DriverController;

class DriverAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $driver = Driver::where('email', $request->email)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check driver status before generating token
        $statusCheck = DriverAuthHelper::checkDriverStatus($driver);
        if (!$statusCheck['can_login']) {
            return response()->json([
                'success' => false,
                'message' => $statusCheck['message'],
                'status' => $driver->status,
                'can_login' => false
            ], 403);
        }

        // Generate token (not stored in DB)
        $token = DriverAuthHelper::generateToken($driver->id);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'endcoded_token' => DriverController::exportEncoded($driver->id),
        ]);
    }

    public function getProfile(Request $request)
    {
        $authResult = DriverAuthHelper::checkDriverAuth($request);

        if (!$authResult['valid']) {
            return DriverAuthHelper::authResponse($authResult['message']);
        }

        return response()->json([
            'success' => true,
            'driver' => $authResult['driver'],
            'status' => $authResult['driver']->status
        ]);
    }

    public function checkStatus(Request $request)
    {
        $authResult = DriverAuthHelper::checkDriverAuth($request);

        if (!$authResult['valid']) {
            return DriverAuthHelper::authResponse($authResult['message']);
        }

        $statusInfo = DriverAuthHelper::checkDriverStatus($authResult['driver']);

        return response()->json([
            'success' => true,
            'driver_id' => $authResult['driver']->id,
            'status' => $authResult['driver']->status,
            'authorized' => $statusInfo['authorized'],
            'message' => $statusInfo['message'],
            'can_login' => $statusInfo['can_login']
        ]);
    }
}
