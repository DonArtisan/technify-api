<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class Customer extends Model
{
    use HasFactory;
    use HasRolesAndAbilities;

    protected $fillable = [
        'address',
        'dni',
        'first_name',
        'last_name',
        'phone',
    ];

    public function sales(): MorphMany
    {
        return $this->morphMany(ProductSale::class, 'buyerable');
    }
}
