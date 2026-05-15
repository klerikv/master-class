<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMasterClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        $masterClass = $this->route('masterClass');
        return auth()->check() && 
               auth()->user()->isInstructor() && 
               $masterClass && 
               $masterClass->instructor_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:500'],
            'price' => ['required', 'integer', 'min:0', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'Введите описание мастер-класса',
            'description.string' => 'Описание должно быть текстом',
            'description.max' => 'Описание не может быть длиннее 500 символов',
            
            'price.required' => 'Укажите стоимость мастер-класса',
            'price.integer' => 'Стоимость должна быть целым числом и не выше 100 000 рублей',
            'price.min' => 'Стоимость не может быть отрицательной',
            'price.max' => 'Стоимость не может превышать 100 000 рублей',
        ];
    }

    public function attributes(): array
    {
        return [
            'description' => 'Описание',
            'price' => 'Стоимость',
        ];
    }
}