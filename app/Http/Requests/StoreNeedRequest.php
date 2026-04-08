<?php

namespace App\Http\Requests;

use App\Enums\SkillLevelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNeedRequest extends FormRequest
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
            'target_level' => [
                'required',
                Rule::in([
                    SkillLevelEnum::BEGINNER->value,
                    SkillLevelEnum::INTERMEDIATE->value,
                    SkillLevelEnum::ADVANCED->value,
                ]),
            ],
        ];
    }
}
