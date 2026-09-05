<?php

namespace App\Http\Requests;

use App\Enums\IdeaState;
use Illuminate\Foundation\Http\FormRequest;

class IdeaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim($this->input('title', '')),
            'description' => trim($this->input('description', '')),
            'state' => trim($this->input('state', '')),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // old 'required|string|max:255',
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
                'min:10',
            ],
            'state' => [
                'required',
                'in:' .
                    implode(',', array_map(fn($state) => $state->value, IdeaState::cases())),
            ],
        ];
    }
}
