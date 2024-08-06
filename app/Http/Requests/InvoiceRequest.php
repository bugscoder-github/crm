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
			"quotation_id" => [],
			"invoice_name" => [],
			"invoice_phone" => [],
			"invoice_company" => [],
			"invoice_premiseType" => [],
			"invoice_email" => [],
			"invoice_deliveryAddress" => [],
			"invoice_billingAddress" => [],
			"invoice_tnc" => [],
			'invoice_remark' => [],
			'invoice_sstPct' => [],
            // 'invoice_items' => ['array'],
            'invoice_items.*.invoiceItem_desc' => [],
            'invoice_items.*.invoiceItem_ppu' => [],
            'invoice_items.*.invoiceItem_qty' => [],
		];

        if ($this->isMethod('put')) {
            $rules['invoice_items.*.invoiceItem_id'] = 'sometimes|exists:invoice_items,invoiceItem_id';
        }

        return $rules;
    }
}
