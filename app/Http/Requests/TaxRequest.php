<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxRequest extends FormRequest
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
            'name' => [
                'required',
                'max:255'
            ],
            'currency' => [
                'required',
                'max:255'
            ],
            'tax_type' => [
                'required',
                'max:255',
                'in:inclusive,exclusive'
            ],
            'charge_type' => [
                'required',
                'max:255',
                'in:percentage,amount'
            ],
            'amount' => [
                'required'
            ],
        ];
    }
}
