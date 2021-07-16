<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', Tag::class);

        $search = $request->get('search', '');

        $tags = Tag::search($search)
            ->latest()
            ->paginate(5);

        return view('app.tags.index', compact('tags', 'search'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Tag::class);

        return view('app.tags.create');
    }

    public function store(TagStoreRequest $request)
    {
        $this->authorize('create', Tag::class);

        $validated = $request->validated();

        $tag = Tag::create($validated);

        return redirect()
            ->route('tags.edit', $tag)
            ->withSuccess(__('crud.common.created'));
    }

    public function show(Request $request, Tag $tag)
    {
        $this->authorize('view', $tag);

        return view('app.tags.show', compact('tag'));
    }

    public function edit(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        return view('app.tags.edit', compact('tag'));
    }

    public function update(TagUpdateRequest $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        $validated = $request->validated();

        $tag->update($validated);

        return redirect()
            ->route('tags.edit', $tag)
            ->withSuccess(__('crud.common.saved'));
    }

    public function destroy(Request $request, Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
