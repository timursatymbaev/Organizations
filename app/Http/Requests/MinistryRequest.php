<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinistryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ministry_name' => 'required|max:255|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ministry_name.required' => 'Поле с названием министерства является обязательным.',
            'ministry_name.max' => 'Максимальная длина названия министерства не должна превышать 255 символов.',
            'ministry_name.string' => 'Название министерсва должно быть строкой.',
        ];
    }
}
