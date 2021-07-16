<?php
namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoCollection;

class TagTodosController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Tag $tag)
    {
        $this->authorize('view', $tag);

        $search = $request->get('search', '');

        $todos = $tag
            ->todos()
            ->search($search)
            ->latest()
            ->paginate();

        return new TodoCollection($todos);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Tag $tag
     * @param \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tag $tag, Todo $todo)
    {
        $this->authorize('update', $tag);

        $tag->todos()->syncWithoutDetaching([$todo->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Tag $tag
     * @param \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Tag $tag, Todo $todo)
    {
        $this->authorize('update', $tag);

        $tag->todos()->detach($todo);

        return response()->noContent();
    }
}
