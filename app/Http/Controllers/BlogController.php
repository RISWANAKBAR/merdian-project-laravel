<?php

namespace App\Http\Controllers;
use App\Models\Blog;
use App\Models\User;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')->get(); // Eager load the 'user' relationship
    
        $blogs->transform(function ($blog) {
            $blogData = [
                'id' => $blog->id,
                'title' => $blog->title,
                'description' => $blog->description,
                'user_id' => $blog->user_id,
                'user_name' => $blog->user->name, // Assuming 'name' is the attribute you want
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ];
    
            return $blogData;
        });
    
        return response()->json($blogs, 200);
    }

    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        return response()->json($blog, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $blog = Blog::create($request->all());

        return response()->json($blog, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->update($request->all());

        return response()->json($blog, 200);
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully'], 200);
    }

    public function getBlogs($userId)
    {
        $blogs = Blog::where('user_id', $userId)->get();
        return $blogs;
    }
}
