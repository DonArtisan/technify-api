<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => DeliveryStatus::class,
    ];

    public function productSale(): BelongsTo
    {
        return $this->belongsTo(ProductSale::class);
    }
}
