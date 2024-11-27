<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string',
        ]);

        $blog->comments()->create($validated);

        return response()->json(['message' => 'Comment added successfully!'], 201);
    }

    public function index(Blog $blog)
    {
        return response()->json($blog->comments()->latest()->get());
    }

    public function destroy(Blog $blog, Comment $comment)
    {
         // Ensure the comment belongs to the specified blog
        if ($comment->blog_id !== $blog->id) {
            return response()->json(['message' => 'Unauthorized or comment does not belong to this blog.'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully!']);
    }
}
