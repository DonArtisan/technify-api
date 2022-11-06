<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class Seller extends Model
{
    use HasApiTokens;
    use HasFactory;
    use HasRolesAndAbilities;

    protected $fillable = [
        'carnet',
        'email',
        'first_name',
        'hired_at',
        'last_name',
        'password',
    ];

    protected $casts = [
        'hired_at' => 'date',
    ];
}
