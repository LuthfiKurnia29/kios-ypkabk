<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'period_type' => ['required', 'in:monthly,weekly'],
            'period' => ['required', 'string'],
            'format' => ['required', 'in:xlsx,pdf'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'period_type.required' => 'Pilihan jenis periode wajib dipilih.',
            'period_type.in' => 'Jenis periode harus bulanan atau mingguan.',
            'period.required' => 'Periode tanggal wajib diisi.',
            'format.required' => 'Format ekspor wajib dipilih.',
            'format.in' => 'Format ekspor harus xlsx atau pdf.',
        ];
    }
}
