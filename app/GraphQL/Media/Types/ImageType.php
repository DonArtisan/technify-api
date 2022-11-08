<?php

namespace App\GraphQL\Media\Types;

use App\Media\Enums\CropRegion;
use App\Media\Enums\ImageContentType;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageType
{
    private const IMAGES_WESERV_URL = 'https://images.weserv.nl';

    private const MAX_HEIGHT = 5760;

    private const MAX_WIDTH = 5760;

    public function height(Media $media): ?int
    {
        return $media->getCustomProperty('height');
    }

    public function originalSrc(Media $media): string
    {
        return $media->getTemporaryUrl(now()->addHour());
    }

    public function transformedSrc(Media $media, array $args): string
    {
        /** @var ?CropRegion $crop */
        $crop = Arr::get($args, 'crop');
        /** @var ?ImageContentType $preferredContentType */
        $preferredContentType = Arr::get($args, 'preferredContentType');
        $height = $this->getHeight(
            askedHeight: Arr::get($args, 'maxHeight'),
            mediaHeight: $media->getCustomProperty('height')
        );
        $width = $this->getWidth(
            askedWidth: Arr::get($args, 'maxWidth'),
            mediaWidth: $media->getCustomProperty('width')
        );

        $params = array_filter(
            [
                'a' => $crop?->value,
                'dpr' => Arr::get($args, 'scale'),
                'fit' => 'cover',
                'h' => $height,
                'output' => $preferredContentType?->value,
                'w' => $width,
            ]
        );

        return $this->buildUrl($media, $params);
    }

    public function width(Media $media): ?int
    {
        return $media->getCustomProperty('width');
    }

    private function buildUrl(Media $media, array $params): string
    {
        return sprintf(
            '%s?%s',
            self::IMAGES_WESERV_URL,
            http_build_query(
                array_merge($params, ['url' => $media->getTemporaryUrl(now()->addHour())])
            )
        );
    }

    private function getHeight(?int $askedHeight, ?int $mediaHeight): int
    {
        return $askedHeight ?: ($mediaHeight ?: self::MAX_HEIGHT);
    }

    private function getWidth(?int $askedWidth, ?int $mediaWidth): int
    {
        return $askedWidth ?: ($mediaWidth ?: self::MAX_WIDTH);
    }
}
