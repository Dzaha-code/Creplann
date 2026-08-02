<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateTodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'schedule_id' => ['sometimes', 'nullable', 'integer', 'exists:schedules,id'],
            'title' => ['sometimes', 'required', 'string', 'max:100'],
            'completed' => ['sometimes', 'boolean'],
            'due_date' => ['sometimes', 'nullable', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->has('schedule_id') || $this->input('schedule_id') === null) {
                return;
            }

            $scheduleId = $this->integer('schedule_id');

            if (! $this->user()->schedules()->whereKey($scheduleId)->exists()) {
                $validator->errors()->add('schedule_id', 'Schedule tidak ditemukan atau bukan milik user.');
            }
        });
    }
}
