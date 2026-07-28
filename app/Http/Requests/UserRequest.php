<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route is already protected by the 'auth' middleware group.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // On update the current user's id is ignored by the unique rule.
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            // Required when creating an account, optional when editing one —
            // leaving it blank on the edit form keeps the current password.
            'password' => [
                $userId ? 'nullable' : 'required',
                'confirmed',
                Password::min(12)->mixedCase()->numbers()->symbols(),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.confirmed' => 'The two password fields do not match.',
        ];
    }
}
