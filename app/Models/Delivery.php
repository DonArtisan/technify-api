<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_date',
        'delivery_place',
        'sale_id',
        'status',
    ];

    protected $casts = [
        'status' => DeliveryStatus::class,
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(ProductSale::class, 'sale_id');
    }
}
