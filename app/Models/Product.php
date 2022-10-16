<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'id');
    }

    public function model()
    {
        return $this->belongsTo(Model::class, 'id');
    }
}
