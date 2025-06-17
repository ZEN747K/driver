<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::query();

        if ($search = $request->input('search')) {
            $query->where('full_name', 'LIKE', "%{$search}%");
        }

        $drivers = $query->get();

        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string',
            'phone' => 'required|string|unique:drivers,phone',
            'email' => 'nullable|email|unique:drivers,email',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'password' => 'nullable|string',
            'bank_account' => 'required|digits_between:1,14',
            'service_type' => 'required|in:car,motorcycle,delivery',
            'id_card' => 'required|file',
            'driver_license' => 'required|file',
            'face_photo' => 'required|file',
            'vehicle_registration' => 'required|file',
            'compulsory_insurance' => 'required|file',
            'vehicle_insurance' => 'required|file',
        ], [
            'phone.unique' => 'เบอร์โทรนี้ถูกใช้งานแล้ว',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
        ]);

        $paths = [];
        foreach ([
            'id_card',
            'driver_license',
            'face_photo',
            'vehicle_registration',
            'compulsory_insurance',
            'vehicle_insurance'
        ] as $fileField) {
            $paths[$fileField . '_path'] = $request->file($fileField)->store('drivers', 'public');
        }

        $driver = Driver::create(array_merge($data, $paths, [
            'status' => 'Pending',
        ]));

        return redirect()->route('drivers.index')
            ->with('success', 'เพิ่มข้อมูลคนขับเรียบร้อยแล้ว');
    }

    public function show(string $id)
    {
        $driver = Driver::findOrFail($id);
        return view('drivers.show', compact('driver'));
    }

    public function edit(string $id)
    {
        // เพิ่มแก้ไขตามต้องการ
    }

    public function update(Request $request, string $id)
    {
        $driver = Driver::findOrFail($id);

        $data = $request->validate([
            'full_name' => 'required|string',
            'phone' => 'required|string|unique:drivers,phone,' . $driver->id,
            'email' => 'nullable|email|unique:drivers,email,' . $driver->id,
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'password' => 'nullable|string',
            'bank_account' => 'required|digits_between:1,14',
            'service_type' => 'required|in:car,motorcycle,delivery',
            'status' => 'required|in:No_approve,Pending,Approved',
            'id_card' => 'nullable|file',
            'driver_license' => 'nullable|file',
            'face_photo' => 'nullable|file',
            'vehicle_registration' => 'nullable|file',
            'compulsory_insurance' => 'nullable|file',
            'vehicle_insurance' => 'nullable|file',
        ], [
            'phone.unique' => 'เบอร์โทรนี้ถูกใช้งานแล้ว',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
        ]);

        $paths = [];
        foreach ([
            'id_card',
            'driver_license',
            'face_photo',
            'vehicle_registration',
            'compulsory_insurance',
            'vehicle_insurance'
        ] as $fileField) {
            if ($request->hasFile($fileField)) {
                $paths[$fileField . '_path'] = $request->file($fileField)->store('drivers', 'public');
            }
        }

        $driver->update(array_merge($data, $paths));

        return redirect()->route('drivers.show', $driver)
            ->with('success', 'แก้ไขข้อมูลคนขับเรียบร้อยแล้ว');
    }

    public function destroy(string $id)
    {
        $driver = Driver::findOrFail($id);

        foreach ([
            'id_card_path',
            'driver_license_path',
            'face_photo_path',
            'vehicle_registration_path',
            'compulsory_insurance_path',
            'vehicle_insurance_path',
        ] as $pathField) {
            if ($driver->$pathField) {
                Storage::disk('public')->delete($driver->$pathField);
            }
        }

        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'ลบข้อมูลคนขับเรียบร้อยแล้ว');
    }

    /**
     * Approve the specified driver.
     */
    public function approve(Request $request, string $id)
    {
        $driver = Driver::findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:No_approve,Pending,Approved',
            'remark' => 'nullable|string',
        ]);

        if ($data['status'] === 'No_approve') {
            $request->validate(['remark' => 'required|string']);
            $driver->remark = $data['remark'];
            $driver->remarked_at = now();
        }

        $driver->status = $data['status'];
        $driver->save();

        return redirect()->route('drivers.index')
            ->with('success', 'Driver status updated successfully!');
    }

    /**
     * Download a specific driver document.
     */
    public function download(Driver $driver, string $field)
    {
        $allowed = [
            'id_card',
            'driver_license',
            'face_photo',
            'vehicle_registration',
            'compulsory_insurance',
            'vehicle_insurance',
        ];

        if (!in_array($field, $allowed)) {
            abort(404);
        }

        $attribute = $field . '_path';
        $path = $driver->{$attribute};

        return Storage::disk('public')->download($path);
    }

    public static function exportEncoded($id)
    {
        // 1. ดึงข้อมูลจาก Database
        $driver = Driver::select(
            'id',
            'full_name',
            'phone',
            'email',
            'birthdate',
            'gender',
            'password',
            'bank_account',
            'id_card_path',
            'driver_license_path',
            'face_photo_path',
            'vehicle_registration_path',
            'compulsory_insurance_path',
            'vehicle_insurance_path',
            'service_type',
            'status',
            'os',
            'remark',
            'remarked_at'
        )
            ->find($id);

        if (!$driver) {
            return response()->json(['message' => 'Driver not found'], 404);
        }

        // 2. แปลงเป็น JSON
        $json = json_encode($driver);

        // 3. เข้ารหัสเป็น Base64
        $base64 = base64_encode($json);

        return response()->json([
            'encoded' => $base64,
            'raw' => $driver // แนบ raw ด้วยสำหรับ debug
        ]);
    }
}
