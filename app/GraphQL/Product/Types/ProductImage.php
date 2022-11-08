<?php

namespace App\GraphQL\Product\Types;

use App\Models\Product;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductImage
{
    public function __invoke(Product $product): ?Media
    {
        /** @var Media $media */
        $media = $product->getFirstMedia(Product::MEDIA_COLLECTION_IMAGE);

        return $media;
    }
}
