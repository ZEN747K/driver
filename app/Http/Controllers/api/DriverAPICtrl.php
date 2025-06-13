<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Helpers\DriverAuthHelper;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Storage;


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

        $drivers = Driver::select('id', 'full_name', 'phone', 'email', 'status',
            'id_card_path', 'driver_license_path', 'face_photo_path',
            'vehicle_registration_path', 'compulsory_insurance_path',
            'vehicle_insurance_path')
            ->get();

        $drivers->transform(function ($driver) {
            $this->appendFileUrls($driver);
            return $driver;
        });

        return response()->json([
            'success' => true,
            'drivers' => $drivers
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
            $this->appendFileUrls($driver);
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
        // เก็บ OS ของผู้ใช้
        $os = $_SERVER['HTTP_USER_AGENT'];
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

        $validated['os'] = $os;

        // บันทึกข้อมูล
        $driver = Driver::create($validated);

        return response()->json([
            'message' => 'Driver     created successfully',
            'os' => $os,
            'data' => $driver
        ], 201);
    }

    public function downloadFile(Request $request, $id, $field)
    {
        $authResult = DriverAuthHelper::checkDriverAuth($request);

        if (!$authResult['valid']) {
            return DriverAuthHelper::authResponse($authResult['message']);
        }

        $allowed = [
            'id_card',
            'driver_license',
            'face_photo',
            'vehicle_registration',
            'compulsory_insurance',
            'vehicle_insurance',
        ];

        if (!in_array($field, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Invalid field'], 404);
        }

        try {
            $driver = Driver::findOrFail($id);
            $attribute = $field . '_path';
            $path = $driver->{$attribute};

            if (!$path || !Storage::disk('public')->exists($path)) {
                return response()->json(['success' => false, 'message' => 'File not found'], 404);
            }

            return Storage::disk('public')->download($path);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Driver not found'], 404);
        }
    }

    private function appendFileUrls(Driver $driver)
    {
        $fields = [
            'id_card_path',
            'driver_license_path',
            'face_photo_path',
            'vehicle_registration_path',
            'compulsory_insurance_path',
            'vehicle_insurance_path',
        ];

        foreach ($fields as $field) {
            if ($driver->{$field}) {
                $driver->{$field} = Storage::disk('public')->url($driver->{$field});
            }
        }
    }
}
