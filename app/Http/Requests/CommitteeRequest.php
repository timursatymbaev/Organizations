<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommitteeRequest extends FormRequest
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
            'committee_name' => 'required|max:255|string',
            'ministry_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'committee_name.required' => 'Название комитета является обязательным.',
            'committee_name.max' => 'Максимальная длина названия комитета не должна превышать 255 символов.',
            'committee_name.string' => 'Название комитета должно являться строкой.',
            'ministry_id.required' => 'Идентификатор министерства для прикрепления является обязательным.'
        ];
    }
}
