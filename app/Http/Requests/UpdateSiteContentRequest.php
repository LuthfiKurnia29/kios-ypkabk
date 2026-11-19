<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hero_image_alt' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:500'],
            'hero_subtitle' => ['nullable', 'string', 'max:1000'],
            'abk_trained_count' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'hero_image.image' => 'File hero harus berupa gambar.',
            'hero_image.mimes' => 'Format gambar hero harus JPG, PNG, atau WebP.',
            'hero_image.max' => 'Ukuran gambar hero maksimal 2 MB.',
            'hero_image_alt.max' => 'Teks alternatif gambar maksimal 255 karakter.',
            'hero_title.max' => 'Judul hero maksimal 500 karakter.',
            'hero_subtitle.max' => 'Subjudul hero maksimal 1000 karakter.',
            'abk_trained_count.integer' => 'Jumlah ABK terlatih harus berupa bilangan bulat.',
            'abk_trained_count.min' => 'Jumlah ABK terlatih tidak boleh negatif.',
        ];
    }
}
