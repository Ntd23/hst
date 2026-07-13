<?php

namespace Botble\Portfolio\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DemoWebsiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'url_client' => $this->filled('url_client') ? $this->input('url_client') : null,
            'url_admin' => $this->filled('url_admin') ? $this->input('url_admin') : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',

            'url_client' => 'nullable|string|max:255',
            'url_admin' => 'nullable|string|max:255',

            'content' => 'nullable|string',

            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',

            'status' => 'required|in:published,draft',

            'img_full' => 'nullable|string',
            'img_feautrer' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống',
        ];
    }
}
