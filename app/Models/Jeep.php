<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jeep extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'model_name',
        'status',
    ];

    public function assignments()
    {
        return $this->hasMany(BookingAssignment::class);
    }
}
