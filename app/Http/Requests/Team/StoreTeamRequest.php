<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
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
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'social_networks' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.required' => 'The role field is required.',
            'role_id.exists' => 'The role does not exist.',
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 50 characters.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 100 characters.',
            'social_networks.required' => 'The social networks field is required.',
        ];
    }

    protected function passedValidation()
    {
        $this->replace([
            'role_id' => $this->role_id,
            'name' => $this->name,
            'description' => $this->description,
            'social_networks' => json_encode($this->social_networks),
        ]);
    }
}
