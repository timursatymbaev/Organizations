<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return match ($this->getMethod()) {
            'POST' => [
                'name' => ['required', 'max:255', 'string', 'unique:organizations,name'],
                'type' => ['required'],
                'followed_by' => [
                    'required_if:type,Комитет',
                    'required_if:type,Управление',
                ],
                'followed_by_committee' => ['required_if:type,Управление']
            ],
            'PATCH' => [
                'id' => ['required'],
                'name' => ['required', 'max:255', 'string', 'unique:organizations,name'],
                'type' => ['required'],
                'followed_by_remove' => ['required_if:followed_by_add,null',],
                'followed_by_add' => ['required_if:followed_by_remove,null',],
                'followed_by_committee_remove' => ['required_if:followed_by_committee_add,null'],
                'followed_by_committee_add' => ['required_if:followed_by_committee_remove,null'],
            ],
            default => []
        };
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название организации является обязательным.',
            'name.max' => 'Название организации не должно превышать 255 символов.',
            'name.string' => 'Название организации должно являться строкой.',
            'name.unique' => 'Организация с таким названием уже существует.',
            'type.required' => 'Тип организации является обязательным.',
            'followed_by.required_if' => 'Выбор курирующего министерства является обязательным.',
            'followed_by_remove.required_if' => 'Выбор курирующего министерства является обязательным.',
            'followed_by_add.required_if' => 'Выбор курирующего министерства является обязательным.',
            'followed_by_committee.required_if' => 'Выбор курирующего комитета является обязательным.',
            'followed_by_committee_add.required_if' => 'Выбор курирующего комитета является обязательным.',
            'followed_by_committee_remove.required_if' => 'Выбор курирующего комитета является обязательным.',
        ];
    }
}
