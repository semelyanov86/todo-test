<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Tag;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

final class StoreTagsAction
{
    use AsAction;

    public function handle(array $tags): Collection
    {
        $result = collect([]);
        foreach ($tags as $tag) {
            $result->push(Tag::firstOrCreate(['name' => $tag]));
        }
        return $result;
    }
}
