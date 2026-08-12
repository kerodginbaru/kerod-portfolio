<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $service = $this->route('service');

        return [
            'title' => ['sometimes', 'required', 'string', 'max:120'],
            'slug' => ['sometimes', 'required', 'string', 'max:140', 'alpha_dash', Rule::unique('services', 'slug')->ignore($service?->id)],
            'description' => ['sometimes', 'required', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:60'],
            'featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
