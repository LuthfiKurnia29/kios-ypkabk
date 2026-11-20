<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUnboxingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Prepare inputs for validation.
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
            'platform' => ['nullable', 'string', 'in:youtube,instagram,tiktok'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
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
            'url' => 'link video YouTube / Instagram / TikTok',
            'platform' => 'platform video',
            'thumbnail' => 'foto thumbnail video',
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
            'url.required' => 'Tautan video YouTube, Instagram, atau TikTok wajib diisi.',
            'url.url' => 'Format tautan video tidak valid.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Format thumbnail harus berupa JPG, JPEG, PNG, atau WEBP.',
            'thumbnail.max' => 'Ukuran file thumbnail maksimal 5 MB.',
        ];
    }
}
