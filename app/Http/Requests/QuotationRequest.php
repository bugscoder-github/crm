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
			"lead_id" => [],
			"quotation_name" => [],
			"quotation_phone" => [],
			"quotation_company" => [],
			"quotation_premiseType" => [],
			"quotation_email" => [],
			"quotation_deliveryAddress" => [],
			"quotation_billingAddress" => [],
            // 'quotation_items' => ['array'],
            'quotation_items.*.quotationItem_desc' => [],
            'quotation_items.*.quotationItem_ppu' => [],
            'quotation_items.*.quotationItem_qty' => [],
            'quotation_items.*.quotationItem_total' => [],
		];

        if ($this->isMethod('put')) {
            $rules['quotation_items.*.quotationItem_id'] = 'sometimes|exists:quotation_items,quotationItem_id';
        }
		
		return $rules;
	}
}
