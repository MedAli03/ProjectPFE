<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'tarif_id',
        'commande_id',
        'total_price',
        // add other fields as needed
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }
}
