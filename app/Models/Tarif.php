<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $fillable = [
        'price'
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
        return $this->belongsTo(User::class, 'id_pressing');
    }
}