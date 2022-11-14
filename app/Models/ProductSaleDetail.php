<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'product_id',
        'quantity',
        'sale_id',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(ProductSale::class, 'sale_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
