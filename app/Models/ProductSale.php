<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'total',
    ];

    public function buyerable(): MorphTo
    {
        return $this->morphTo();
    }

    public function productDetails()
    {
        return $this->hasMany(ProductSaleDetail::class);
    }
}
