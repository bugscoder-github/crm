<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Services\LeadService;
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

        return redirect()
            ->route("contactus.create")
            ->with("success", "Lead created successfully.");
    }
}
