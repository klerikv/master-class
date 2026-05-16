<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[А-Яа-яЁёA-Za-z\s\-]+$/u', 'min_words:2'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'phone' => [
                'required',
                'string',
                'regex:/^\+7\d{10}$/',
                'unique:users,phone',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Пожалуйста, укажите ваше ФИО',
            'full_name.string' => 'ФИО должно быть текстом',
            'full_name.max' => 'ФИО не может быть длиннее 255 символов',
            'full_name.regex' => 'ФИО может содержать только буквы, пробелы и дефисы',
            'full_name.min_words' => 'Пожалуйста, введите минимум фамилию и имя',

            'email.required' => 'Пожалуйста, укажите email адрес',
            'email.email' => 'Введите корректный email адрес',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован',

            'password.required' => 'Пожалуйста, введите пароль',
            'password.min' => 'Пароль должен содержать минимум 6 символов',
            'password.confirmed' => 'Пароли не совпадают',

            'phone.required' => 'Пожалуйста, укажите номер телефона',
            'phone.string' => 'Номер телефона должен быть строкой',
            'phone.regex' => 'Номер телефона должен быть в формате +7XXXXXXXXXX',
            'phone.unique' => 'Пользователь с таким номером телефона уже зарегистрирован',
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'ФИО',
            'email' => 'Email',
            'password' => 'Пароль',
            'phone' => 'Номер телефона',
        ];
    }
}
