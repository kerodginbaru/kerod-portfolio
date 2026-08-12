<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSkillRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'skill_category_id' => ['sometimes', 'required', 'exists:skill_categories,id'],
            'name' => ['sometimes', 'required', 'string', 'max:80'],
            'proficiency' => ['nullable', Rule::in(['learning', 'comfortable', 'strong'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
