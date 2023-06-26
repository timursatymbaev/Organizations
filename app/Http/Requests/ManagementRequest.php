<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagementRequest extends FormRequest
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
                'management_name' => ['required', 'max:255', 'string', 'unique:managements,management_name'],
                'ministry_id' => ['required'],
                'committee_id' => ['required']
            ],
            'PUT' => [
                'management_name' => ['required', 'max:255', 'string'],
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
            'management_name.required' => 'Название управления является обязательным.',
            'management_name.max' => 'Название управления не должно превышать 255 символов.',
            'management_name.string' => 'Название управления должно являться строкой.',
            'ministry_id' => 'Идентификатор министерства является обязательным.',
            'committee_id' => 'Идентификатор комитета является обязательным.',
            'management_name.unique' => 'Управление с таким названием уже существует.'
        ];
    }
}
