<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lodging extends Model
{
    protected $fillable = ['name', 'address', 'lat', 'lng', 'source'];
}