<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueueData extends Model
{
    use HasFactory;

    protected $table = 'queue_Data';

    protected $fillable = [
        'customer_id',
        'customer_phone',
        'pickup_location',
        'destination',
        'first_time',
        'status',
        'pickup_time',
    ];

    protected $casts = [
        'first_time' => 'datetime',
        'pickup_time' => 'datetime',
    ];
}