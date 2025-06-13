<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;

class get_userAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::all();

        $drivers->transform(function ($driver) {
            $this->appendFileUrls($driver);
            return $driver;
        });

        return response()->json($drivers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Driver::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->appendFileUrls($user);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
