<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'color_id',
        'description',
        'discount_id',
        'model_id',
        'name',
        'status',
    ];

    public function discount(): HasOne
    {
        return $this->hasOne(Discount::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attributes(): HasOne
    {
        return $this->hasOne(Attribute::class);
    }

    public function color(): HasOne
    {
        return $this->hasOne(Color::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }
}
