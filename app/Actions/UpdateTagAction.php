<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Todo;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

final class UpdateTagAction
{
    use AsAction;

    public function handle(int $todo, array $data): Todo
    {
        $todoModel = Todo::whereId($todo)->firstOrFail();
        $todoModel->update($data);
        if (!isset($data['tags'])) {
            $data['tags'] = [];
        }
        /** @var Collection $tags */
        $tags = StoreTagsAction::run($data['tags']);
        $todoModel->tags()->sync($tags->pluck('id'));
        return $todoModel;
    }
}
