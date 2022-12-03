<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'RUC',
        'address',
        'agent_name',
        'branch',
        'email',
        'phone_number',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
