<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityRequest extends FormRequest
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
        // On update the current city's id is ignored by the unique rule.
        $cityId = $this->route('city')?->id;

        // Everything except the name is optional: the admin form only asks for
        // the city name. The centre is derived from the city's outlets, and
        // zoom/sort_order fall back to their database defaults (12 and 0).
        // Values that already exist are left untouched when the key is absent.
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('cities', 'name')->ignore($cityId)],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'zoom' => ['nullable', 'integer', 'between:1,20'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
