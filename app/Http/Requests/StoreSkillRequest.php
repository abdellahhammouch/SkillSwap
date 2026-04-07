<?php

namespace App\Http\Requests;

use App\Enums\SkillLevelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => [
                'required',
                Rule::in([
                    SkillLevelEnum::BEGINNER->value,
                    SkillLevelEnum::INTERMEDIATE->value,
                    SkillLevelEnum::ADVANCED->value,
                ]),
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
