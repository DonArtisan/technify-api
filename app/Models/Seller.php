<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Seller extends Authenticatable implements HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use HasRolesAndAbilities;
    use InteractsWithMedia;
    use SoftDeletes;

    public const MEDIA_COLLECTION_PICTURE = 'picture';

    protected $fillable = [
        'carnet',
        'hired_at',
        'password',
        'seller_id',
    ];

    protected $casts = [
        'hired_at' => 'date',
    ];

    public function name(): Attribute
    {
        return Attribute::get(
            fn () => trim(sprintf('%s %s', $this->person->first_name, $this->person->last_name))
        );
    }

    public function profilePhotoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->getFirstMediaUrl(self::MEDIA_COLLECTION_PICTURE));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_PICTURE)
            ->useFallbackUrl(asset('images/user-placeholder.png'))
            ->acceptsMimeTypes(['image/jpeg', 'image/jpg', 'image/png'])
            ->singleFile();
    }

    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(ProductSale::class, 'seller_id');
    }
}
