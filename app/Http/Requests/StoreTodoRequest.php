<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'schedule_id' => ['nullable', 'integer', 'exists:schedules,id'],
            'title' => ['required', 'string', 'max:255'],
            'completed' => ['nullable', 'boolean'],
            'due_date' => ['nullable', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $scheduleId = $this->integer('schedule_id');

            if (! $scheduleId) {
                return;
            }

            if (! $this->user()->schedules()->whereKey($scheduleId)->exists()) {
                $validator->errors()->add('schedule_id', 'Schedule tidak ditemukan atau bukan milik user.');
            }
        });
    }
}
