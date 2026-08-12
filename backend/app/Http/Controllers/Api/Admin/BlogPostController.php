<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', BlogPost::class);

        $posts = BlogPost::query()->with('category')->orderByDesc('created_at')->paginate(20);

        return $this->success(BlogPostResource::collection($posts), 'Posts retrieved successfully.');
    }

    public function store(StoreBlogPostRequest $request): JsonResponse
    {
        $this->authorize('create', BlogPost::class);

        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['author_id'] = $request->user()->id;

        $post = BlogPost::create($data);

        return $this->success(new BlogPostResource($post), 'Post created successfully.', 201);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost): JsonResponse
    {
        $this->authorize('update', $blogPost);

        $blogPost->update($request->validated());

        return $this->success(new BlogPostResource($blogPost), 'Post updated successfully.');
    }

    public function destroy(BlogPost $blogPost): JsonResponse
    {
        $this->authorize('delete', $blogPost);

        $blogPost->delete();

        return $this->success(null, 'Post deleted successfully.');
    }
}
