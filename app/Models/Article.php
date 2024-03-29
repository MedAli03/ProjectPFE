<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_available '
    ];

    public function tarifs()
    {
        return $this->belongsToMany(Tarif::class)->withPivot('price');
    }

    // public function services()
    // {
    //     return $this->belongsToMany(Service::class)->withPivot('name');
    // }

}
