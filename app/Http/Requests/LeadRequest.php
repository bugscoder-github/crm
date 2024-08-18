<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest {
	public $isMine;

	protected function prepareForValidation() {
		$this->isMine = false;
		if ($this->route('user')) {
			if (Auth()->id() == $this->route('user')->id ?? 0) {
				$this->isMine = true;
			}	
		}
	}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
    	$isAuth = true;

    	if (request()->routeIs('lead.update') && (isAdmin() == false && $this->isMine == false)) { $isAuth = false; }

        return $isAuth;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
		$rules = [
			// 'customer_id' => [],
			'lead_companyName' => [],
        	'lead_name' => [],
			'lead_email' => [],
        	'lead_phone' => ['required'],
        	'lead_location' => [],
        	'lead_enquiry' => [],
        	'lead_source' => [],
			'lead_remark' => [],
			'lead_premiseType' => [],
			'lead_serviceType' => [],
        	'user_id' => [],
		];

		if (request()->routeIs('lead.update')) {
			$rules['leadComment_comment'] = [];
		}

		return $rules;
    }
}
