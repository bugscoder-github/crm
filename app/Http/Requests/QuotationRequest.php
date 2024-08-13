<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
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
			'quotation_number' => [
				'nullable'
			],
			'quotation_type' => [
				'nullable'
			],
			'frequency' => [
				'nullable',
				'integer'
			],
			'frequency_type' => [
				'nullable'
			],
			'quotation_date' => [
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
			'is_shipping_address' => [
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

        if ($this->isMethod('put')) {
            $rules['items.*.id'] = 'sometimes|exists:quotation_items,id';
        }
		
		return $rules;
	}
}
