<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSkillCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('skill_categories', 'slug')],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
