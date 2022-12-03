<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'seller_id',
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

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }
}
