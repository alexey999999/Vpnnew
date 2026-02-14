<?php

namespace App\Http\Requests\Tariff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTariffRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'configurations_ids' => ['required', 'array'],
            'configurations_ids.*' => ['required', 'int', 'exists:connection_configurations,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле обязательно для заполнения',
            'name.max' => 'Максимальная длинна поля 255 символов',
            'configurations_ids.required' => 'Поле обязательно для заполнения',
            'configurations_ids.exists' => 'Значение должно быть из списка конфигураций',
        ];
    }
}
