<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
        'os'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'date',
        'password' => 'hashed',
    ];
}
