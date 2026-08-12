<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $posts = BlogPost::query()
            ->published()
            ->with('category')
            ->orderByDesc('published_at')
            ->paginate(10);

        return $this->success(BlogPostResource::collection($posts), 'Posts retrieved successfully.');
    }

    public function show(BlogPost $blogPost): JsonResponse
    {
        if ($blogPost->status !== 'published') {
            return $this->error('Post not found.', 404);
        }

        return $this->success(new BlogPostResource($blogPost), 'Post retrieved successfully.');
    }
}
