<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('project')) ?? false;
    }

    public function rules(): array
    {
        return [
            // mimes + image together validates both the file extension and
            // the actual file content/MIME type, not just the extension a
            // client claims. 4MB cap keeps storage and delivery in check.
            'image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'alt_text' => ['nullable', 'string', 'max:150'],
            'caption' => ['nullable', 'string', 'max:200'],
            'is_cover' => ['boolean'],
        ];
    }
}
