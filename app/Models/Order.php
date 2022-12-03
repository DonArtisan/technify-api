<?php

namespace App\Models;

use App\Enums\AuthorizeEnum;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'authorize_status',
        'order_status',
        'required_date',
        'seller_id',
        'supplier_id',
        'tax',
        'total',
    ];

    protected $casts = [
        'order_status' => OrderStatus::class,
        'authorize_status' => AuthorizeEnum::class,
        'required_date' => 'date',
    ];

    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
