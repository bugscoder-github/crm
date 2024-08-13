<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstimateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'nullable',
                'integer'
            ],
            'lead_id' => [
                'required',
                'integer'
            ],

            // Preset Data
            'currency' => [
                'required',
                'max:255'
            ],

            // Item Data
            'items.*.id' => [
                'nullable',
                'integer'
            ],
            'items.*.item_type' => [
                'required',
                'in:service,custom'
            ],
            'items.*.service_id' => [
                'nullable',
                'integer'
            ],
            'items.*.name' => [
                'nullable',
            ],
            'items.*.description' => [
                'nullable',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],
            'items.*.unit_amount' => [
                'required',
                'numeric'
            ],
            'items.*.is_enabled' => [
                'boolean',
            ],
        ];
    }
}
