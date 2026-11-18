<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUnboxingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Prepare inputs for validation (support nama_barang, item_name, link).
     */
    protected function prepareForValidation(): void
    {
        $title = $this->input('nama_barang') ?? $this->input('item_name') ?? $this->input('title');
        $url = $this->input('link') ?? $this->input('url');

        $updates = [];
        if ($title !== null) {
            $updates['title'] = is_string($title) ? trim($title) : $title;
        }
        if ($url !== null) {
            $updates['url'] = is_string($url) ? trim($url) : $url;
        }

        if (! empty($updates)) {
            $this->merge($updates);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:500'],
            'platform' => ['nullable', 'string', 'in:youtube,instagram'],
            'recorded_at' => ['nullable', 'date'],
            'is_visible' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'nama barang',
            'url' => 'link video YouTube / Instagram',
            'platform' => 'platform video',
            'recorded_at' => 'tanggal dokumentasi',
            'is_visible' => 'status tampil',
            'description' => 'keterangan',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Nama barang wajib diisi.',
            'url.required' => 'Tautan video YouTube atau Instagram wajib diisi.',
            'url.url' => 'Format tautan video tidak valid.',
        ];
    }
}
