<?php
namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagCollection;

class TodoTagsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Todo $todo)
    {
        $this->authorize('view', $todo);

        $search = $request->get('search', '');

        $tags = $todo
            ->tags()
            ->search($search)
            ->latest()
            ->paginate();

        return new TagCollection($tags);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Todo $todo
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Todo $todo, Tag $tag)
    {
        $this->authorize('update', $todo);

        $todo->tags()->syncWithoutDetaching([$tag->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Todo $todo
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Todo $todo, Tag $tag)
    {
        $this->authorize('update', $todo);

        $todo->tags()->detach($tag);

        return response()->noContent();
    }
}
