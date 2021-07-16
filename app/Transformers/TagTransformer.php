<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Tag;

final class TagTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform(Tag $tag): array
    {
        return [
            'id' => $tag->id,
            'name' => $tag->name
        ];
    }
}
