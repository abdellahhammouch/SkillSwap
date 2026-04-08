<?php

namespace App\Http\Requests;

use App\Enums\SkillLevelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNeedRequest extends FormRequest
{
    public function authorize(): bool
    {
        $need = $this->route('need');

        return auth()->check() && $need->user_id === auth()->id();
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
            'status' => ['required', Rule::in(['open', 'closed'])],
        ];
    }
}
