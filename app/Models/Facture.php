<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'client_id',
        'pressing_id',
        'numero',
        'date',
        'total',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->where('role', 'client');
    }

    public function pressing()
    {
        return $this->belongsTo(User::class, 'pressing_id')->where('role', 'pressing');
    }
}
