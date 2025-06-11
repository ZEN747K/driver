<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::all();
        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'service_type' => 'required|in:car,motorcycle,delivery',
            'id_card' => 'required|file',
            'driver_license' => 'required|file',
            'face_photo' => 'required|file',
            'vehicle_registration' => 'required|file',
            'compulsory_insurance' => 'required|file',
            'vehicle_insurance' => 'required|file',
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

        $driver = Driver::create(array_merge($data, $paths));

        return redirect()->route('drivers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $driver = Driver::findOrFail($id);
        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->update($request->only('approved'));
        return redirect()->route('drivers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Approve the specified driver.
     */
    public function approve(string $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->approved = true;
        $driver->save();
        return redirect()->route('drivers.index');
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
}
