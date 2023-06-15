<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'items',
        'pressing_id',
        'tarif_id',
        'status',
        'total_price',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function pressing()
    {
        return $this->belongsTo(User::class, 'pressing_id');
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }

    public function details()
    {
        return $this->hasMany(Detail::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
}
