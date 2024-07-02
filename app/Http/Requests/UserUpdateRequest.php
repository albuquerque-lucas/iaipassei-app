<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ajuste conforme necessário
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
            'account_plan_id' => 'nullable|exists:account_plans,id',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $this->route('user'),
            'profile_img' => 'nullable|image|max:2048',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $this->route('user'),
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'sex' => 'nullable|string|max:50',
            'sexual_orientation' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:50',
            'race' => 'nullable|string|max:50',
            'disability' => 'nullable|string|max:255',
        ];
    }
}
