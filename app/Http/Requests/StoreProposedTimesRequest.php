<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposedTimesRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $proposedTimes = collect($this->input('proposed_times', []))
            ->filter(function ($proposedTime) {
                return ! empty($proposedTime['start_at']) || ! empty($proposedTime['end_at']);
            })
            ->values()
            ->all();

        $this->merge([
            'proposed_times' => $proposedTimes,
        ]);
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'proposed_times' => ['required', 'array', 'min:1', 'max:3'],
            'proposed_times.*.start_at' => ['required', 'date'],
            'proposed_times.*.end_at' => ['required', 'date'],
        ];
    }
}
