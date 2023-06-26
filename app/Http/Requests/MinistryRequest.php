<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinistryRequest extends FormRequest
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
                'ministry_name' => ['required', 'max:255', 'string', 'unique:ministries,ministry_name'],
            ],
            'PUT' => [
                'ministry_name' => ['required', 'max:255', 'string'],
                'committee_id_add' => ['required_without:committee_id_remove'],
                'committee_id_remove' => ['required_without:committee_id_add'],
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
            'ministry_name.required' => 'Поле с названием министерства является обязательным.',
            'ministry_name.max' => 'Максимальная длина названия министерства не должна превышать 255 символов.',
            'ministry_name.string' => 'Название министерсва должно быть строкой.',
            'ministry_name.unique' => 'Министерство с таким названием уже существует.'
        ];
    }
}
