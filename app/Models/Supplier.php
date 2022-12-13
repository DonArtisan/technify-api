<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch',
        'person_id',
    ];

    public function name(): Attribute
    {
        return Attribute::get(
            fn () => trim(sprintf('%s %s', $this->person->first_name, $this->person->last_name))
        );
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
