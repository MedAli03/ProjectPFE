<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name','pressing_id'];

    public function pressing()
    {
        return $this->belongsTo(User::class, 'id_pressing')->where('role', 'pressing');
    }

    public function tarifs()
    {
        return $this->hasMany(Tarif::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
