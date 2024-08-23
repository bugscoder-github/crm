<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
    public function rules(): array {
		$rules = [
			// Preset Data
			'invoice_number' => [
				'nullable'
			],
			'invoice_date' => [
				'nullable',
				'date_format:format,Y-m-d'
			],
			'discount_code' => [
				'nullable'
			],
			'currency' => [
				'required',
				'max:255'
			],
			'currency_symbol' => [
				'nullable',
				'max:255'
			],

			'company' => [
				'nullable',
				'max:255'
			],
			'premise_type' => [
				'nullable',
				'max:255'
			],
			'customer_name' => [
				'nullable',
				'max:255'
			],
			'phone' => [
				'nullable',
				'max:255'
			],
			'email' => [
				'nullable',
				'max:255'
			],
			'delivery_address' => [
				'nullable',
				'max:255'
			],
			'billing_address' => [
				'nullable',
				'max:255'
			],
			'is_same_billing_address' => [
				'boolean'
			],

			// Item Data
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
		if ($this->isMethod('post')) {
			$rules['quotation_id'] = 'required';
		}
        if ($this->isMethod('put')) {
            $rules['items.*.id'] = 'sometimes|exists:invoice_items,id';
        }
		
		return $rules;
    }
}
