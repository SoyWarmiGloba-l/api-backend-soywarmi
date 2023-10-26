<?php

namespace App\Http\Requests\MedicalCenter;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalCenterRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'opening_datetime' => 'required|date',
            'closing_datetime' => 'required|date',
            'phones' => 'required|string',
        ];
    }
}
