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
        'tarif_id',
        'status',
        'quantity',
        'total_price',
       

       
        
        // add more attributes here as needed
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->where('role', 'client');
    }

    public function pressing()
    {
        return $this->belongsTo(User::class, 'pressing_id')->where('role', 'pressing');
    }

    public function tarifs()
    {
        return $this->belongsToMany(Tarif::class)
            ->withPivot('quantity', 'price');
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

}
