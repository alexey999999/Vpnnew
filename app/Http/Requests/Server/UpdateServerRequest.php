<?php

namespace App\Http\Requests\Server;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServerRequest extends FormRequest
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
            'server_type_id' => ['required', 'int', 'exists:server_types,id'],
            'protocol_version' => ['required', 'numeric'],
            'ipv4' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'int', 'exists:countries,id'],
            'url' => ['required', 'string', 'max:255'],
            'main_token' => ['required', 'string', 'max:255'],
            'remote_token' => ['required', 'string', 'max:255'],
            'port' => ['nullable', 'int', 'min:0', 'max:65535'],
            'password' => ['nullable', 'string', 'max:255'],
            'encryption_method' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле обязательно для заполнения',
            'name.max' => 'Максимальная длинна поля 255 символов',
            'server_type_id.required' => 'Поле обязательно для заполнения',
            'server_type_id.exists' => 'Значение должно быть из списка типов серверов',
            'protocol_version.required' => 'Поле обязательно для заполнения', 
            'protocol_version.numeric' => 'Поле должно быть числом',
            'ipv4.required' => 'Поле обязательно для заполнения',
            'ipv4.max' => 'Максимальная длинна поля 255 символов',//todo: make check to ipv4
            'country_id.required' => 'Поле обязательно для заполнения',
            'country_id.exists' => 'Значение должно быть из списка стран',
            'url.required' => 'Поле обязательно для заполнения',//todo: make check to url pattern
            'url.max' => 'Максимальная длинна поля 255 символов',
            'main_token.required' => 'Поле обязательно для заполнения',
            'main_token.max' => 'Максимальная длинна поля 255 символов',
            'remote_token.required' => 'Поле обязательно для заполнения',
            'remote_token.max' => 'Максимальная длинна поля 255 символов',
            'port.integer' => 'Поле должно быть числом от 0 до 65535',
            'port.min' => 'Поле должно быть числом от 0 до 65535',
            'port.max' => 'Поле должно быть числом от 0 до 65535',
            'password.string' => 'Поле должно быть строкой',
            'password.max' => 'Максимальная длинна поля 255 символов',
            'encryption_method.string' => 'Поле должно быть строкой',
            'encryption_method.max' => 'Максимальная длинна поля 255 символов',
        ];
    }
}
