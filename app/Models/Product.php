<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
