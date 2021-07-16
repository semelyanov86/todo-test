<?php

declare(strict_types=1);

namespace App\Transformers;


use App\Models\Todo;

class TodoTransformer extends \League\Fractal\TransformerAbstract
{
    protected $availableIncludes = [
        'tags'
    ];

    public function transform(Todo $todo): array
    {
        return [
            'id' => $todo->id,
            'title' => $todo->title,
            'content' => $todo->content,
            'due_date' => $todo->due_date,
            'is_done' => $todo->is_done
        ];
    }

    public function includeTags(Todo $todo): ?\League\Fractal\Resource\Collection
    {
        if (!$todo->tags) {
            return null;
        }
        return $this->collection(
            $todo->tags,
            new TagTransformer()
        );
    }
}
