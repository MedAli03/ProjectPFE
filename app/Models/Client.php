<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;


    protected $table = 'clients';
    protected $primaryKey = 'id';

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'country',
        'postal_code'
    ];
}
