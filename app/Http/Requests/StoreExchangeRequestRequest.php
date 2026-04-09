<?php

namespace App\Http\Requests;

use App\Enums\ExchangeRequestTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExchangeRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in([
                    ExchangeRequestTypeEnum::HELP_REQUEST->value,
                    ExchangeRequestTypeEnum::HELP_OFFER->value,
                ]),
            ],
            'need_id' => ['nullable', 'exists:needs,id'],
            'skill_id' => ['nullable', 'exists:skills,id'],
            'message' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:15'],
            'proposed_times' => ['required', 'array', 'min:1', 'max:3'],
            'proposed_times.*.start_at' => ['required', 'date'],
            'proposed_times.*.end_at' => ['required', 'date'],
        ];
    }
}
