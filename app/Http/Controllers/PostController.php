<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
{
    $posts = Auth::user()->posts()->with('tags')->orderBy('pinned', 'desc')->get();
    return response()->json($posts);
}

    /**
     * Store a newly created post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'nullable|image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array',
        ]);

        // Create a new post for the authenticated user
        $post = Auth::user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'cover_image' => $request->cover_image ? $request->cover_image->store('covers') : null,
            'pinned' => $request->pinned,
        ]);

        // Attach tags to the post
        $post->tags()->attach($request->tags);

        return response()->json($post, 201);
    }

    /**
     * Display the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post)
    {
        // Ensure the post belongs to the authenticated user
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Return the post along with its tags
        return response()->json($post->load('tags'));
    }

    /**
     * Update the specified post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post)
    {
        // Ensure the post belongs to the authenticated user
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the incoming request data
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'cover_image' => 'nullable|image',
            'pinned' => 'sometimes|required|boolean',
            'tags' => 'sometimes|required|array',
        ]);

        // Update the post with the provided data
        $post->update($request->only(['title', 'body', 'pinned']));

        // Update the cover image if provided
        if ($request->has('cover_image')) {
            $post->cover_image = $request->cover_image->store('covers');
            $post->save();
        }

        // Update the tags if provided
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post);
    }

    /**
     * Remove the specified post (soft delete).
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        // Ensure the post belongs to the authenticated user
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Soft delete the post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    /**
     * Display a listing of the trashed (soft-deleted) posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function trashed()
    {
        // Get all soft-deleted posts of the authenticated user
        $posts = Auth::user()->posts()->onlyTrashed()->get();
        return response()->json($posts);
    }

    /**
     * Restore the specified trashed (soft-deleted) post.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        // Find the soft-deleted post by ID for the authenticated user
        $post = Auth::user()->posts()->onlyTrashed()->where('id', $id)->firstOrFail();

        // Restore the post
        $post->restore();

        return response()->json(['message' => 'Post restored successfully']);
    }
}
