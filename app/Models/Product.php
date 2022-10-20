<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }

    public function model()
    {
        return $this->belongsTo(Model::class);
    }

    public function category()
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
}
