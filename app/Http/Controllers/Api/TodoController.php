<?php

namespace App\Http\Controllers\Api;

use App\Actions\StoreToDoAction;
use App\Actions\UpdateTagAction;
use App\Models\Todo;
use App\Transformers\TodoTransformer;
use Illuminate\Http\Request;
use App\Http\Resources\TodoResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoCollection;
use App\Http\Requests\TodoStoreRequest;
use App\Http\Requests\TodoUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', Todo::class);

        $search = $request->get('search', '');

        $todos = Todo::search($search)
            ->searchActive($request->has('is_done') ? $request->boolean('is_done') : null)
            ->orderingByDate($request->input('due_order'), 'due_date')
            ->orderingByDate($request->input('created_at'))
            ->paginate();

        return fractal($todos, new TodoTransformer())->parseIncludes(['tags'])->respond();
    }

    public function store(TodoStoreRequest $request)
    {
        $this->authorize('create', Todo::class);

        $validated = $request->validated();

        $todo = StoreToDoAction::run($validated);

        return fractal($todo, new TodoTransformer())->parseIncludes(['tags'])->respond(Response::HTTP_CREATED);
    }

    public function show(Request $request, Todo $todo): \Illuminate\Http\JsonResponse
    {
        $this->authorize('view', $todo);

        return fractal($todo, new TodoTransformer())->parseIncludes(['tags'])->respond();
    }

    public function update(TodoUpdateRequest $request, int $todo): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $todo);

        $validated = $request->validated();

        $todoModel = UpdateTagAction::run($todo, $validated);

        return fractal($todoModel, new TodoTransformer())->parseIncludes(['tags'])->respond(Response::HTTP_ACCEPTED);
    }

    public function destroy(Request $request, Todo $todo): \Illuminate\Http\Response
    {
        $this->authorize('delete', $todo);

        $todo->delete();

        return response()->noContent();
    }
}
