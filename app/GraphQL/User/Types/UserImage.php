<?php

namespace App\GraphQL\User\Types;

use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UserImage
{
    public function __invoke(User $user): ?Media
    {
        /** @var Media $media */
        $media = $user->getFirstMedia(User::MEDIA_COLLECTION_PICTURE);

        return $media;
    }
}
