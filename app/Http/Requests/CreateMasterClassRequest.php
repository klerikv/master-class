<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\MasterClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateMasterClassRequest extends FormRequest
{
    public function authorize(): bool
    {

        return auth()->check() && auth()->user()->isInstructor();
    }

    public function rules(): array
    {
        return [
            'craft_type_id' => ['required', 'exists:craft_types,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'date' => ['required', 'date', 'after_or_equal:tomorrow'],
            'time_slot' => ['required', Rule::in(array_keys(MasterClass::TIME_SLOTS))],
            'max_participants' => ['required', 'integer', 'min:1', 'max:100'],
            'price' => ['required', 'integer', 'min:0', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return [
            'craft_type_id.required' => 'Выберите вид творчества',
            'craft_type_id.exists' => 'Выбранный вид творчества не существует',

            'title.required' => 'Введите название мастер-класса',
            'title.string' => 'Название должно быть текстом',
            'title.max' => 'Название не может быть длиннее 255 символов',

            'description.required' => 'Введите описание мастер-класса',
            'description.string' => 'Описание должно быть текстом',
            'description.max' => 'Описание не может быть длиннее 500 символов',

            'date.required' => 'Укажите дату проведения мастер-класса',
            'date.date' => 'Введите корректную дату',
            'date.after_or_equal' => 'Дата не может быть раньше завтрашнего дня',

            'time_slot.required' => 'Выберите время проведения',
            'time_slot.in' => 'Выберите корректное время из списка',

            'max_participants.required' => 'Укажите количество мест в группе',
            'max_participants.integer' => 'Количество мест должно быть целым числом',
            'max_participants.min' => 'В группе должно быть минимум 1 место',
            'max_participants.max' => 'В группе может быть максимум 100 мест',

            'price.required' => 'Укажите стоимость мастер-класса',
            'price.integer' => 'Стоимость должна быть целым числом и не выше 100 000 рублей',
            'price.min' => 'Стоимость не может быть отрицательной',
            'price.max' => 'Стоимость не может превышать 100 000 рублей',
        ];
    }

    public function attributes(): array
    {
        return [
            'craft_type_id' => 'Вид творчества',
            'title' => 'Название мастер-класса',
            'description' => 'Описание',
            'date' => 'Дата',
            'time_slot' => 'Время',
            'max_participants' => 'Количество мест',
            'price' => 'Стоимость',
        ];
    }

    // Дополнительная проверка после основной валидации
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Проверяем, не занят ли уже этот слот
            $isOccupied = MasterClass::where('instructor_id', $this->user()->id)
                ->where('date', $this->date)
                ->where('time_slot', $this->time_slot)
                ->exists();

            if ($isOccupied) {
                $validator->errors()->add(
                    'time_slot',
                    'Это время уже занято вами на выбранную дату. Пожалуйста, выберите другое время.'
                );
            }
        });
    }
}
