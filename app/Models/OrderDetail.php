<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'gain',
        'order_id',
        'price',
        'product_id',
        'quantity',
        'unit_price_with_gain',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
