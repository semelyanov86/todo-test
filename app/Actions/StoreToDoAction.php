<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Todo;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

final class StoreToDoAction
{
    use AsAction;

    public function handle(array $data): Todo
    {
        if (!isset($data['tags'])) {
            $data['tags'] = [];
        }
        $todo = Todo::create($data);
        /** @var Collection $tags */
        $tags = StoreTagsAction::run($data['tags']);
        $todo->tags()->sync($tags->pluck('id'));
        return $todo;
    }
}
