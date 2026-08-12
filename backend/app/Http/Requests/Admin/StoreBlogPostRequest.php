<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:200', 'alpha_dash', Rule::unique('blog_posts', 'slug')],
            'excerpt' => ['required', 'string', 'max:280'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
