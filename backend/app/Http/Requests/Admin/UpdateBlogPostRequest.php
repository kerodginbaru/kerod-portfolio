<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogPostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $post = $this->route('blog_post');

        return [
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'title' => ['sometimes', 'required', 'string', 'max:180'],
            'slug' => ['sometimes', 'required', 'string', 'max:200', 'alpha_dash', Rule::unique('blog_posts', 'slug')->ignore($post?->id)],
            'excerpt' => ['sometimes', 'required', 'string', 'max:280'],
            'content' => ['sometimes', 'required', 'string'],
            'status' => ['sometimes', 'required', Rule::in(['draft', 'published', 'archived'])],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
