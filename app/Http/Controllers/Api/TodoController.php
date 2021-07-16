<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Resources\TodoResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoCollection;
use App\Http\Requests\TodoStoreRequest;
use App\Http\Requests\TodoUpdateRequest;

class TodoController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Todo::class);

        $search = $request->get('search', '');

        $todos = Todo::search($search)
            ->latest()
            ->paginate();

        return new TodoCollection($todos);
    }

    /**
     * @param \App\Http\Requests\TodoStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TodoStoreRequest $request)
    {
        $this->authorize('create', Todo::class);

        $validated = $request->validated();

        $todo = Todo::create($validated);

        return new TodoResource($todo);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Todo $todo)
    {
        $this->authorize('view', $todo);

        return new TodoResource($todo);
    }

    /**
     * @param \App\Http\Requests\TodoUpdateRequest $request
     * @param \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function update(TodoUpdateRequest $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        $validated = $request->validated();

        $todo->update($validated);

        return new TodoResource($todo);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Todo $todo)
    {
        $this->authorize('delete', $todo);

        $todo->delete();

        return response()->noContent();
    }
}
