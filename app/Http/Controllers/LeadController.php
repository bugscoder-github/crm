<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\User;

use App\Http\Requests\LeadRequest;
use App\Services\LeadService;
use Inertia\Inertia;

use Spatie\Activitylog\Models\Activity;

class LeadController extends Controller {
    public function index() {
        $leads = Lead::with('user');
        if (!isOwner()) {
            $leads = $leads->whereHas('user', function($query) {
                $query->where('current_team_id', me()->current_team_id);
            });
        }
        if (!isAdminOrOwner()) {
            $leads = $leads->where("user_id", me()->id);
        }

        return Inertia::render("Lead/Index", [
            "leads" => $leads->latest()->get(),
        ]);
    }

    public function create() {
    	return $this->renderForm();
    }

    public function edit(Lead $lead) {
        // dd($lead->user_id);
        if (!isAdminOrOwner() && !isMine($lead->user_id)) { abort(403); }

        if (isMine($lead->user_id) && $lead->read_at == null) {
            $lead->update([
                "read_at" => now(),
                "lead_status" => 2
            ]);
            // LeadService::leadLog($lead->lead_id, "Lead read by assigned person");
        }

        $lead["comment"] = LeadComment::orderBy('leadComment_id', 'desc')->where("lead_id", $lead->lead_id)->get();
        $userId = $lead["comment"]->pluck("user_id");
        $userList = User::whereIn("id", $userId)->get()->makeHidden(['password']);
        foreach($lead['comment'] as $key => $value) {
        	$name = "";
         	$role = "";
          	$id = 0;
        	foreach($userList as $k => $v) {
         		if ($v['id'] != $value['user_id']) { continue; }
           		$name = $v['name'];
             	$role = $v['role_names'];
              	$id = $v['id'];
         	}
        	$lead['comment'][$key]['user'] = array(
         		'id' => $id,
         		'name' => $name,
           		'role' => $role
         	);
        }

        return $this->renderForm($lead);
    }

    public function renderForm(Lead $lead = null) {
        $lead = ($lead == null) ? new Lead() : $lead;
        $user = [me()];
        if (isAdminOrOwner()) {
            $user = User::where('current_team_id', me()->current_team_id)->whereHasRole('Sales')->get();
        }

        return Inertia::render("Lead/Form", [
            "log" => $lead->lead_id > 0 ? Activity::whereRaw("log_name = 'lead' and subject_id = '{$lead->lead_id}'")->get() : [],
            "lead" => $lead,
            "users" => $user,
            "meta" => [
                "sources" => getConfig("custom.lead.source"),
            ],
            "success" => session("success") ?? "",
        ]);
    }

    //

    public function store(LeadRequest $request)
    {
        $result = $this->save($request);

        return redirect()
            ->route("lead.edit", $result->lead_id)
            ->withInput()
            ->with("success", "Lead created successfully.");
    }

    public function update(LeadRequest $request, Lead $lead)
    {
        $result = $this->save($request, $lead);

        return redirect()
            ->route("lead.edit", $result->lead_id)
            ->withInput()
            ->with("success", "Lead updated successfully.");
    }

    public function save(LeadRequest $request, Lead $lead = null) {
        $data = $request->validated();

        if ($lead) {
            if ($lead->lead_status == 3) { return; }
            $lead->update($data);
        } else {
            $lead = LeadService::checkCustomerCreateLead($data);
        }

        return $lead;
    }

    //

    public function destroy(Lead $lead) {
        if (!isAdmin() && !isMine($lead->user_id)) { abort(403); }
        if ($lead->lead_status == 3) { return; }
        // if (!isAdmin()) { abort(403); }

        $lead->delete();
        return redirect()->route("lead.index")->with("success", "Lead deactivated successfully.");
    }

    public function show(Lead $lead) {
        return $lead;
    }

    public function leadMarkDone(Lead $lead) {
		// $result = Lead::findOrFail($leadId);
        // if ($result == false) { return; }
		$lead->update([
            'done_at' => now(),
            'lead_status' => 4
        ]);
        // LeadService::leadLog($leadId, "Lead marked as done.");
	}

    public function leadReopen(Lead $lead) {
		// $result = Lead::findOrFail($leadId);
        // if ($result == false) { return; }
		$lead->update([
            'done_at' => null,
            'lead_status' => 3
        ]);
        // LeadService::leadLog($leadId, "Lead reopened.");
	}
}
