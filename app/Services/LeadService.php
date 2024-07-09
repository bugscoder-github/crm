<?php
namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use App\Models\Customer;
use App\Models\LeadComment;

class LeadService
{
    public static function getLastUserIdByCustomerId($customer_id)
    {
        return Lead::where("fkcustomer_id", $customer_id)
            ->orderBy("lead_createdAt", "desc")
            ->first()->user_id ?? 0;
    }

    public static function checkCustomerCreateLead($data) {
        //check if customer exist, else create new -> return customer_id
        $customerId = CustomerService::getCustomerIdByPhone(
            $data["lead_phone"]
        );
        $isOldCustomer = $customerId > 0 ? true : false;
        if ($customerId == 0 && config("custom.lead.ENABLE_AUTO_CREATE_CUSTOMER") == true) {
            $customerId = Customer::create([
                "customer_phone" => $data["lead_phone"],
                "customer_name" => $data["lead_name"],
            ])->customer_id;
        }

        $oldUserId = User::count() > 1 && $customerId > 0 ? self::getLastUserIdByCustomerId($customerId) : 0;
        if (auth()->check() && request()->routeIs("lead.store")) {
            $oldUserId = isAdmin() ? $data["user_id"] : me()->id;
        }

        if ($oldUserId == 0 && getConfig("custom.lead.ENABLE_AUTO_ASSIGN_SALESPERSON") == true) {
            //assign to the least lead salesperson
            //assign by round robin
        }

        //preparing data to create lead
        $data = array_merge($data, [
            "lead_isOldCustomer" => $isOldCustomer,
            "fkcustomer_id" => $customerId,
            "user_id" => $oldUserId,
            "lead_createdBy" => (auth()->check() && request()->routeIs("lead.store")) ? me()->id : 0,
        ]);

        //create lead
        $result = Lead::create($data);

        self::sysCreateComment($result->lead_id, "New lead created.");

        //update customer table src modal
        if ($isOldCustomer == false) {
            CustomerService::setCustomerSrcModal(
                $customerId,
                "leads",
                $result->lead_id
            );
        }

        return $result;
    }

    public static function sysCreateComment($lead_id, $comment) {
        LeadComment::create([
            'user_id' => -1,
            'lead_id' => $lead_id,
            'leadComment_comment' => $comment
        ]);
    }
}
