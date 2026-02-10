<?php

namespace App\Http\Requests\ConnectionConfiguration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConnectionConfigurationRequest extends FormRequest
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
            'configuration_type_id' => ['required', 'int', 'exists:configuration_types,id'],
            'servers_in_ids' => ['required',  Rule::when(is_array(request('servers_in_ids')), [
                'array',
                'servers_in_ids.*' => ['required', 'int', 'exists:servers,id'],
            ], [
                // Otherwise, validate it as a single integer
                'int',
            ])],
            'servers_in_ids.*' => ['required', 'int', 'exists:servers,id'],
            'servers_out_ids' => ['required', 'array'],
            'servers_out_ids.*' => ['required', 'int', 'exists:servers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле обязательно для заполнения',
            'name.max' => 'Максимальная длинна поля 255 символов',
            'configuration_type_id.required' => 'Поле обязательно для заполнения',
            'configuration_type_id.exists' => 'Значение должно быть из списка типов конфигураций',
            'servers_in_ids.required' => 'Поле обязательно для заполнения',
            'servers_in_ids.exists' => 'Значение должно быть из списка серверов',
            'servers_out_ids.required' => 'Поле обязательно для заполнения',
            'servers_out_ids.exists' => 'Значение должно быть из списка серверов',
        ];
    }
}
