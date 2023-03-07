<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pressing extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'country',
        'phone',
        'email',
        
    ];

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
