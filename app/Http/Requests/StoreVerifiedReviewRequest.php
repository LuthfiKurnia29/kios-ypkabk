<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVerifiedReviewRequest extends FormRequest
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
            'source' => ['required', 'string', 'in:buyer,donor'],
            'order_code' => ['nullable', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:100'],
            'quote' => ['required', 'string', 'max:300'],
            'agree_terms' => ['accepted'],
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
            'source.required' => 'Pilih apakah Anda sebagai pembeli atau donatur.',
            'source.in' => 'Sumber transaksi harus berupa pembeli atau donatur.',
            'phone.required' => 'Nomor WhatsApp wajib diisi untuk verifikasi transaksi.',
            'name.required' => 'Nama tampilan wajib diisi.',
            'name.max' => 'Nama tampilan maksimal 100 karakter.',
            'quote.required' => 'Isi ulasan wajib diisi.',
            'quote.max' => 'Isi ulasan maksimal 300 karakter.',
            'agree_terms.accepted' => 'Anda harus menyetujui ulasan untuk ditampilkan.',
        ];
    }
}
