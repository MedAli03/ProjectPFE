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
        return $this->belongsTo(Client::class);
    }

    public function pressing()
    {
        return $this->belongsTo(Pressing::class);
    }
}