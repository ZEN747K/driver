<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route();
        $name = $route?->getName();

        switch ($name) {
            case 'drivers.store':
                $request->validate([
                    'full_name' => 'required|string',
                    'phone' => 'required|string',
                    'email' => 'nullable|email',
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
                ]);
                break;
            case 'drivers.update':
                $request->validate([
                    'full_name' => 'required|string',
                    'phone' => 'required|string',
                    'email' => 'nullable|email',
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
                ]);
                break;
            case 'drivers.approve':
                $request->validate([
                    'status' => 'in:No_approve,Pending,Approved',
                ]);
                break;
            case 'drivers.download':
                $request->validate([
                    'field' => 'in:id_card,driver_license,face_photo,vehicle_registration,compulsory_insurance,vehicle_insurance',
                ]);
                break;
            case 'admins.store':
                $request->validate([
                    'name' => 'required|string',
                    'email' => 'required|email|unique:admins,email',
                    'password' => 'required|string',
                    'is_super' => 'sometimes|boolean',
                ]);
                break;
            case 'admins.update':
                $adminId = $request->route('admin');
                if (is_object($adminId) && method_exists($adminId, 'getKey')) {
                    $adminId = $adminId->getKey();
                }
                $request->validate([
                    'name' => 'required|string',
                    'email' => 'required|email|unique:admins,email,'.$adminId,
                    'password' => 'nullable|string',
                    'is_super' => 'sometimes|boolean',
                ]);
                break;
        }

        return $next($request);
    }
}
