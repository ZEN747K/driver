<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;

class get_userAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Driver::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $driver = Driver::find($id);
        if ($driver) {
            return response()->json($driver);
        } else {
            return response()->json(['error' => 'Driver not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $driver = Driver::find($id);
        if ($driver) {
            $driver->update($request->all());
            return response()->json($driver);
        } else {
            return response()->json(['error' => 'Driver not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
