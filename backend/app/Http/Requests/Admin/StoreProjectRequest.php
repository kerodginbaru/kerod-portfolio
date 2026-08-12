<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Project::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:170', 'alpha_dash', Rule::unique('projects', 'slug')],
            'short_description' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'problem' => ['nullable', 'string'],
            'solution' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:project_categories,id'],
            'status' => ['required', Rule::in(['completed', 'in_development', 'planned', 'archived'])],
            'featured' => ['boolean'],
            'year' => ['nullable', 'integer', 'min:2015', 'max:'.(date('Y') + 1)],
            'github_url' => ['nullable', 'url', 'max:255'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'architecture' => ['nullable', 'string'],
            'challenges' => ['nullable', 'string'],
            'lessons_learned' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'technology_ids' => ['nullable', 'array'],
            'technology_ids.*' => ['exists:technologies,id'],
        ];
    }
}
