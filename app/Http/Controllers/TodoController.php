<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
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
            ->paginate(5);

        return view('app.todos.index', compact('todos', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Todo::class);

        return view('app.todos.create');
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

        return redirect()
            ->route('todos.edit', $todo)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Todo $todo)
    {
        $this->authorize('view', $todo);

        return view('app.todos.show', compact('todo'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        return view('app.todos.edit', compact('todo'));
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

        return redirect()
            ->route('todos.edit', $todo)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('todos.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
