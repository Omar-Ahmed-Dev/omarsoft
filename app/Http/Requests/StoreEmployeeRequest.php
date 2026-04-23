<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'img'   => 'nullable|string', // حالياً بنبعت لينك صورة، فـ string تمام

            // الـ pid هنا لازم نتأكد إنه موجود فعلاً في جدول الموظفين
            // وبنستخدم nullable عشان "المدير الكبير" ملوش pid
            'pid'   => 'nullable|exists:employees,id',
        ];
    }
}
