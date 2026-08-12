<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTechnologyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $technology = $this->route('technology');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:80'],
            'slug' => ['sometimes', 'required', 'string', 'max:100', 'alpha_dash', Rule::unique('technologies', 'slug')->ignore($technology?->id)],
            'icon' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
