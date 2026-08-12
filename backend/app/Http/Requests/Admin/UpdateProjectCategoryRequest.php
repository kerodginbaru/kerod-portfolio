<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $category = $this->route('project_category');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'slug' => ['sometimes', 'required', 'string', 'max:120', 'alpha_dash', Rule::unique('project_categories', 'slug')->ignore($category?->id)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
