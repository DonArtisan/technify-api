<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class Seller extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRolesAndAbilities;
    use SoftDeletes;

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

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn ($_, $attributes) => $attributes['first_name'].' '.$attributes['last_name']
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
