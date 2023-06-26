<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommitteeRequest extends FormRequest
{
    /**
     * Указывает, авторизован ли пользователь совершать какие-либо действия.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Получает правила проверки, применимые к запросу.
     *
     * @return string[]
     */
    public function rules(): array
    {
        return match ($this->getMethod()) {
            'POST' => [
                'committee_name' => ['required', 'max:255', 'string', 'unique:committees,committee_name'],
                'ministry_id' => 'required'
            ],
            'PUT' => [
                'committee_name' => ['required', 'max:255', 'string'],
                'management_id_add' => ['required_without:management_id_remove'],
                'management_id_remove' => ['required_without:management_id_add']
            ],
            default => []
        };
    }

    /**
     * Получает сообщения для пользователя в случае неудачной валидации данных.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'committee_name.required' => 'Название комитета является обязательным.',
            'committee_name.max' => 'Максимальная длина названия комитета не должна превышать 255 символов.',
            'committee_name.string' => 'Название комитета должно являться строкой.',
            'ministry_id.required' => 'Идентификатор министерства для прикрепления является обязательным.',
            'committee_name.unique' => 'Комитет с таким названием уже существует.'
        ];
    }
}
