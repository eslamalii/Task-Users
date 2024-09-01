<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
{
    $tags = Tag::all();
    return response()->json($tags);
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:tags,name',
    ]);

    $tag = Tag::create([
        'name' => $request->name,
    ]);

    return response()->json($tag, 201);
}

public function update(Request $request, Tag $tag)
{
    $request->validate([
        'name' => 'required|string|unique:tags,name,' . $tag->id,
    ]);

    $tag->name = $request->name;
    $tag->save();

    return response()->json($tag);
}

public function destroy(Tag $tag)
{
    $tag->delete();

    return response()->json(['message' => 'Tag deleted successfully']);
}


}