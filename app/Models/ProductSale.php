<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'tax',
        'total',
    ];

    public function buyerable(): MorphTo
    {
        return $this->morphTo();
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(ProductSaleDetail::class, 'sale_id');
    }
}
