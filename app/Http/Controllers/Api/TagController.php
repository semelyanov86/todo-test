<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Transformers\TagTransformer;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagCollection;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

final class TagController extends Controller
{

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize('view-any', Tag::class);

        $search = $request->get('search', '');

        $tags = Tag::search($search)
            ->latest()
            ->paginate();

        return fractal($tags, new TagTransformer())->respond();
    }

    public function store(TagStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize('create', Tag::class);

        $validated = $request->validated();

        $tag = Tag::create($validated);

        return fractal($tag, new TagTransformer())->respond(Response::HTTP_CREATED);
    }

    public function show(Request $request, Tag $tag): \Illuminate\Http\JsonResponse
    {
        $this->authorize('view', $tag);

        return fractal($tag, new TagTransformer())->respond();
    }

    public function update(TagUpdateRequest $request, Tag $tag): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $tag);

        $validated = $request->validated();

        $tag->update($validated);

        return fractal($tag, new TagTransformer())->respond(Response::HTTP_ACCEPTED);
    }

    public function destroy(Request $request, Tag $tag): \Illuminate\Http\Response
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return response()->noContent();
    }
}
