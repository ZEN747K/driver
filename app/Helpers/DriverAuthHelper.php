<?php

namespace App\Helpers;

use App\Models\Driver;
use Carbon\Carbon;

class DriverAuthHelper
{
    private static $secretKey = 'your-secret-key-here'; // Move to .env in production

    /**
     * Generate token for driver (not stored in DB)
     */
    public static function generateToken($driverId, $sessionTimeout = 3600)
    {
        $tokenData = [
            'driver_id' => $driverId,
            'issued_at' => Carbon::now()->timestamp,
            'expires_at' => Carbon::now()->addSeconds($sessionTimeout)->timestamp,
            'hash' => hash('sha256', $driverId . self::$secretKey . Carbon::now()->timestamp)
        ];

        $jsonToken = json_encode($tokenData);
        return base64_encode($jsonToken);
    }

    /**
     * Validate token and check driver status
     */
    public static function validateDriverToken($token)
    {
        if (!$token) {
            return [
                'valid' => false,
                'message' => 'Token required',
                'driver' => null
            ];
        }

        try {
            // Decode token
            $jsonData = base64_decode($token);
            $tokenData = json_decode($jsonData, true);

            if (!$tokenData || !isset($tokenData['driver_id'])) {
                return [
                    'valid' => false,
                    'message' => 'Invalid token format',
                    'driver' => null
                ];
            }

            // Check token expiration
            if (Carbon::now()->timestamp > $tokenData['expires_at']) {
                return [
                    'valid' => false,
                    'message' => 'Token expired',
                    'driver' => null
                ];
            }

            // Verify token hash (security check)
            $expectedHash = hash('sha256', $tokenData['driver_id'] . self::$secretKey . $tokenData['issued_at']);
            if ($tokenData['hash'] !== $expectedHash) {
                return [
                    'valid' => false,
                    'message' => 'Invalid token signature',
                    'driver' => null
                ];
            }

            // Get driver from database
            $driver = Driver::find($tokenData['driver_id']);
            if (!$driver) {
                return [
                    'valid' => false,
                    'message' => 'Driver not found',
                    'driver' => null
                ];
            }

            // Check driver account status
            $statusCheck = self::checkDriverStatus($driver);
            if (!$statusCheck['authorized']) {
                return [
                    'valid' => false,
                    'message' => $statusCheck['message'],
                    'driver' => $driver,
                    'status' => $driver->status
                ];
            }

            return [
                'valid' => true,
                'message' => 'Token valid',
                'driver' => $driver,
                'token_data' => $tokenData
            ];

        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'Token validation failed',
                'driver' => null
            ];
        }
    }

    /**
     * Check driver account status
     */
    public static function checkDriverStatus(Driver $driver)
    {
        switch (strtolower($driver->status)) {
            case 'pending':
                return [
                    'authorized' => false,
                    'message' => 'Account is pending approval',
                    'can_login' => false
                ];

            case 'approved':
                return [
                    'authorized' => true,
                    'message' => 'Account approved',
                    'can_login' => true
                ];

            case 'rejected':
            case 'return':
                return [
                    'authorized' => false,
                    'message' => 'Account has been returned/rejected. Please contact support.',
                    'can_login' => false
                ];

            case 'suspended':
                return [
                    'authorized' => false,
                    'message' => 'Account is suspended',
                    'can_login' => false
                ];

            default:
                return [
                    'authorized' => false,
                    'message' => 'Invalid account status',
                    'can_login' => false
                ];
        }
    }

    /**
     * Get token from request
     */
    public static function getTokenFromRequest($request)
    {
        return $request->bearerToken() ??
               $request->header('api-token') ??
               $request->input('token');
    }

    /**
     * Authentication response helper
     */
    public static function authResponse($message = 'Authentication required', $status = 401, $additionalData = [])
    {
        return response()->json(array_merge([
            'success' => false,
            'message' => $message,
            'authenticated' => false
        ], $additionalData), $status);
    }

    /**
     * Check if request is authenticated
     */
    public static function checkDriverAuth($request)
    {

        $token = self::getTokenFromRequest($request);
        return self::validateDriverToken($token);
    }
}
