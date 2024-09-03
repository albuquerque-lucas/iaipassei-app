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
            'num_exams' => 'required|integer|min:1',
            'num_questions_per_exam' => 'nullable|integer|min:1',
            'num_alternatives_per_question' => 'nullable|integer|min:1|max:26',
            'notice' => 'nullable|file|mimes:pdf|max:500000',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo ":attribute" é obrigatório.',
            'exists' => 'O :attribute selecionado não é válido.',
            'string' => 'O campo ":attribute" deve ser um texto.',
            'max' => 'O campo ":attribute" não pode exceder :max caracteres.',
            'integer' => 'O campo ":attribute" deve ser um número inteiro.',
            'min' => 'O campo ":attribute" deve ser pelo menos :min.',
            'file' => 'O campo ":attribute" deve ser um arquivo.',
            'mimes' => 'O campo ":attribute" deve ser um arquivo do tipo :values.',
        ];
    }

    public function attributes(): array
    {
        return [
            'education_level_id' => 'Nível de Escolaridade',
            'title' => 'Título',
            'institution' => 'Instituição',
            'num_exams' => 'Número de Provas',
            'num_questions_per_exam' => 'Número de Questões por Prova',
            'num_alternatives_per_question' => 'Número de Alternativas por Questão',
            'notice' => 'Edital',
        ];
    }
}
