<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class ContactUsController extends Controller {
    public function contactUsCreate() {
        return Inertia::render("ContactUs", [
            "success" => session("success"),
        ]);
    }

    public function contactUsStore(ContactUsRequest $request) {
        $data = $request->validated();

        $data["lead_source"] = "Web";

        LeadService::checkCustomerCreateLead($data);
        
        $count = Lead::selectRaw('user_id, count(*) as count')->where('read_at', null)->groupBy('user_id')->get()->toArray();
        foreach($count as $key => $value) {
            $notificationData = [
                'message' => 'new registered user',
                'count' => $value['count'],
                'time' => now(),
            ];
            Cache::put("newLead_{$value['user_id']}", $notificationData, now()->addMinutes(1));
        }

        return redirect()
            ->route("contactus.create")
            ->with("success", "Lead created successfully.");
    }
}
