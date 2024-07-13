<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExaminationManualStoreFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'education_level_id' => 'required|exists:education_levels,id',
            'title' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'num_exams' => 'required|integer|min:1|max:5',
            'num_questions_per_exam' => 'nullable|integer|min:1',
            'num_alternatives_per_question' => 'nullable|integer|min:1|max:26',
            'notice' => 'nullable|file|mimes:pdf|max:500000',
        ];
    }
}
