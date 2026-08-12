<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('project')) ?? false;
    }

    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'title' => ['sometimes', 'required', 'string', 'max:150'],
            'slug' => ['sometimes', 'required', 'string', 'max:170', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($project?->id)],
            'short_description' => ['sometimes', 'required', 'string', 'max:200'],
            'description' => ['sometimes', 'required', 'string'],
            'problem' => ['nullable', 'string'],
            'solution' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:project_categories,id'],
            'status' => ['sometimes', 'required', Rule::in(['completed', 'in_development', 'planned', 'archived'])],
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
