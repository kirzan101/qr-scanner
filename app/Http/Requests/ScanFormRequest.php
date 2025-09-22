<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_id' => 'required|integer|exists:profiles,id',
            'property_id' => 'required|integer|exists:properties,id',
            'meal_schedule' => 'required|string|in:breakfast,lunch,dinner',
        ];
    }
}
