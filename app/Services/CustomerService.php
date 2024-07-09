<?php 
namespace App\Services;

use App\Models\Customer;

class CustomerService {
    public static function getCustomerIdByPhone($phone) {
    	$result = Customer::where('customer_phone', $phone)->first();
    	return $result->customer_id ?? 0;
    }

    public static function setCustomerSrcModal($customerId, $modalName, $modalId): void {
    	Customer::where('customer_id', $customerId)->update([
			'customer_srcModalName' => $modalName,
			'customer_srcModalId'   => $modalId,
		]);
    }
}