<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'service_id',
        'article_id',
        'quantity',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
