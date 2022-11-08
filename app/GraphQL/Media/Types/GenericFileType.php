<?php

namespace App\GraphQL\Media\Types;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GenericFileType
{
    public function url(Media $media): string
    {
        return $media->getTemporaryUrl(now()->addHour());
    }
}
