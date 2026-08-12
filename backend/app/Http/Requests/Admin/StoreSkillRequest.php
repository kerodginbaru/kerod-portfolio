<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'skill_category_id' => ['required', 'exists:skill_categories,id'],
            'name' => ['required', 'string', 'max:80'],
            'proficiency' => ['nullable', Rule::in(['learning', 'comfortable', 'strong'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
