<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'client_id',
        'pressing_id',
        'value'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->where('role', 'client');
    }

    public function pressing()
    {
        return $this->belongsTo(User::class, 'pressing_id')->where('role', 'pressing');
    }
}
