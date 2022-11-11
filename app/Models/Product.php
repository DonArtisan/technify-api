<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends BaseModel implements HasMedia
{
    use HasFactory;
    use Sluggable;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_IMAGE = 'image';

    protected $fillable = [
        'category_id',
        'color_id',
        'description',
        'discount_id',
        'model_id',
        'name',
        'status',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_IMAGE)
            ->acceptsMimeTypes(['image/jpeg', 'image/jpg', 'image/png'])
            ->singleFile();
    }

    public function sluggable(): array
    {
        return [
            'handle' => [
                'source' => ['name', 'model_id'],
            ],
        ];
    }

    public function discount(): HasOne
    {
        return $this->hasOne(Discount::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attributes(): HasOne
    {
        return $this->hasOne(Attribute::class);
    }

    public function color(): HasOne
    {
        return $this->hasOne(Color::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function productDetails(): HasMany
    {
        return $this->hasMany(ProductSaleDetail::class);
    }
}
