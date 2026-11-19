<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'spent_at' => ['required', 'date', 'before_or_equal:today'],
            'description' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'spent_at.required' => 'Tanggal pengeluaran wajib diisi.',
            'spent_at.date' => 'Tanggal pengeluaran tidak valid.',
            'spent_at.before_or_equal' => 'Tanggal pengeluaran tidak boleh di masa depan.',
            'description.required' => 'Keterangan wajib diisi.',
            'description.max' => 'Keterangan maksimal 255 karakter.',
            'category.max' => 'Kategori maksimal 100 karakter.',
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.integer' => 'Jumlah harus berupa angka bulat.',
            'amount.min' => 'Jumlah minimal Rp 1.',
        ];
    }
}
