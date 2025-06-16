<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFirebase extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'os',
        'token_firebase',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}