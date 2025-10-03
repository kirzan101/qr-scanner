<?php

namespace App\Http\Requests;

use App\Helpers\Helper;
use App\Models\Profile;
use App\Models\User;
use App\Rules\UniqueIgnoringSoftDeletes;
use App\Traits\TrimsInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class ProfileFormRequest extends FormRequest
{
    use TrimsInputTrait;

    /**
     * Prepare the data for validation.
     *
     * This method is called before validation occurs.
     * It allows us to modify the input data, such as trimming whitespace.
     */
    protected function prepareForValidation()
    {
        $this->merge($this->trimInputs($this->all()));
    }

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
            'username' => [
                'required',
                'string',
                'max:50',
                new UniqueIgnoringSoftDeletes(User::class, 'username', $this->user_id)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                new UniqueIgnoringSoftDeletes(User::class, 'email', $this->user_id)
            ],
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'unique_identifier' => 'required|string|max:50|unique:profiles,unique_identifier',
            'property_id' => 'required|integer|exists:properties,id',
            'location_id' => 'required|integer|exists:locations,id',
            'department_id' => 'required|integer|exists:departments,id',
            'position' => 'required|string|max:50',

        ];
    }
}
