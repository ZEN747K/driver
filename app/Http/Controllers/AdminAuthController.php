<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    /**
     * แสดงหน้า Login สำหรับ Admin
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('drivers.index');
        }

        return view('admin.login');
    }

    /**
     * ทำการตรวจสอบข้อมูลและเข้าสู่ระบบสำหรับ Admin
     */
    public function login(Request $request)
    {
        // Validate ข้อมูลที่ส่งมา
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // พยายามเข้าสู่ระบบด้วย guard admin
        if (! Auth::guard('admin')->attempt($credentials)) {
            // หากเป็นการร้องขอแบบ JSON ให้ตอบกลับด้วย error
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid credentials'], 422);
            }
            // หากเป็นการร้องขอแบบปกติ ให้กลับไปที่หน้าล็อกอินพร้อมแสดง error ผ่าน flash session
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        // ดึง admin ที่ล็อกอินสำเร็จออกมา
        $admin = Auth::guard('admin')->user();

        // สร้าง API Token แบบสุ่ม (60 ตัวอักษร)
        $admin->api_token = Str::random(60);
        $admin->save();

        // หากเป็นการร้องขอแบบ JSON ให้ส่ง token กลับไปด้วย
        if ($request->expectsJson()) {
            return response()->json([
                'success'   => true,
                'api_token' => $admin->api_token,
            ]);
        }

        return redirect()->route('drivers.index');
    }

    /**
     * ทำการออกจากระบบ Admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}