<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class Customer extends Model
{
    use HasFactory;
    use HasRolesAndAbilities;

    protected $guarded = [];

    public function name(): Attribute
    {
        return Attribute::get(
            fn () => trim(sprintf('%s %s', $this->person->first_name, $this->person->last_name))
        );
    }

    public function sales(): MorphMany
    {
        return $this->morphMany(ProductSale::class, 'buyerable');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
