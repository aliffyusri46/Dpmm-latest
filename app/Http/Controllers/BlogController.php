<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;



class BlogController extends Controller
{
    public function index()
    {
         // Retrieve the latest 5 blogs, ordered by created_at descending
            $blogs = Blog::orderBy('created_at', 'desc')
            ->take(5) // Limit to 5 results
            ->get()
            ->map(function ($blog) {
                // Decode the images field from JSON
                $blog->images = json_decode($blog->images, true);
                return $blog;
            });

        // Pass the transformed blogs data to Inertia
        return inertia('Index', [
        'blogs' => $blogs, // Alternatively, you can use $blogs->toArray() for a plain array
        ]);
    }

    public function show(Blog $blog)
    {
        $blog->images = json_decode($blog->images, true);
        return inertia('Show-blog', ['blog' => $blog]);
    }

    public function create()
    {
        
        return inertia('Create-blog');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images' => 'array|max:5', // Ensure it's an array and limit to 5 files
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('blogs', 'public');
                $images[] = $path;
            }
        }

        // Store the blog data
        Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'images' => json_encode($images),
        ]);

        return redirect()->route('index');
    }

    public function edit(Blog $blog)
    {
        $blog->images = json_decode($blog->images, true);
        return inertia('Edit-blog', ['blog' => $blog]);
    }

    public function update(Request $request, Blog $blog)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
    
        $existingImages = $request->existingImages ?? [];
        $newImages = [];
    
        // Handle new image uploads
        if ($request->hasFile('newImages')) {
            foreach ($request->file('newImages') as $image) {
                $path = $image->store('blogs', 'public');
                $newImages[] = $path;
            }
        }
    
        // Combine existing and new images
        $allImages = array_merge($existingImages, $newImages);

        
    
        // Update blog
        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            'images' => json_encode($allImages),
        ]);
    
        return redirect()->route('index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        // Decode the images JSON field to get the image paths
        $images = json_decode($blog->images, true);

        // Delete each image from storage
        if ($images) {
            foreach ($images as $image) {
                \Storage::disk('public')->delete($image);
            }
        }

        // Delete the blog from the database
        $blog->delete();

        // Redirect with a success message
        return redirect()->route('index')->with('success', 'Blog and its images deleted successfully.');

        
    }
}
