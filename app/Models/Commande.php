<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [

        'client_id',
        'pressing_id',
        // 'article_id',
        // 'service_id',
        'status',
        // 'quantity',
        'total_amount',

       
        
        // add more attributes here as needed
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
