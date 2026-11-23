<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'rating',
        'comment',
        'city',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        $relation = $this->belongsTo(Package::class);
        if (\Illuminate\Support\Facades\Schema::hasColumn('packages', 'deleted_at')) {
            return $relation->withTrashed();
        }
        return $relation;
    }
}
