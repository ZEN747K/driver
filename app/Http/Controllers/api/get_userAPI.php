<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class get_userAPI extends Controller
{
    /**
     * Secret key used to sign JWT tokens.
     */
    private string $jwtSecret;

    public function __construct()
    {
        $this->jwtSecret = env('JWT_SECRET', 'my-secret-key');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Driver::all());
    }

    /**
     * Authenticate a driver and generate a JWT token.
     */
    public function authenticate(Request $request, string $id)
    {
        $driver = Driver::find($id);
        if (! $driver) {
            return response()->json(['error' => 'Driver not found'], 404);
        }

        $credentials = $request->only([
            'full_name',
            'phone',
            'email',
            'birthdate',
            'gender',
            'password_for_profile',
        ]);

        foreach ($credentials as $key => $value) {
            if ($driver->{$key} != $value) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        }

        $payload = array_merge(['id' => $driver->id], $credentials);

        $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');

        return response()->json(['token' => $jwt]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $token = $request->bearerToken() ?? $request->query('token');

        if (! $token) {
            return response()->json(['error' => 'Token required'], 401);
        }

        try {
            $payload = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        if ($payload->id != $id) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $driver = Driver::find($id);
        if (! $driver) {
            return response()->json(['error' => 'Driver not found'], 404);
        }

        foreach ([
            'full_name',
            'phone',
            'email',
            'birthdate',
            'gender',
            'password_for_profile',
        ] as $field) {
            if ($driver->{$field} != ($payload->{$field} ?? null)) {
                return response()->json(['error' => 'Invalid token'], 401);
            }
        }

        return response()->json($driver);
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
