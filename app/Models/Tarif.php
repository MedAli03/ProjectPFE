<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'id_service',
        'id_article',
        'id_pressing'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article');
    }

    public function pressing()
    {
    return $this->belongsTo(User::class, 'id_pressing')->where('role', 'pressing');
    }


    // public function commandes()
    // {
    //     return $this->belongsToMany(Commande::class, 'details')
    //                 ->withPivot('quantity')
    //                 ->withTimestamps();
    // }
}
