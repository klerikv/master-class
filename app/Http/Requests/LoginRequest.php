<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Правила валидации
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    // Сообщения об ошибках
    public function messages(): array
    {
        return [
            'email.required' => 'Пожалуйста, введите email',
            'email.email' => 'Введите корректный email адрес',
            'password.required' => 'Пожалуйста, введите пароль',
            'password.string' => 'Пароль должен быть строкой',
        ];
    }

    // Атрибуты полей
    public function attributes(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }
}
