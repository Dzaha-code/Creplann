<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['sometimes', 'required', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->has('category_id') || $this->input('category_id') === null || $this->input('category_id') === '') {
                return;
            }

            $categoryId = $this->integer('category_id');

            if (! $this->user()->categories()->whereKey($categoryId)->exists()) {
                $validator->errors()->add('category_id', 'Category tidak ditemukan atau bukan milik user.');
            }
        });
    }
}
