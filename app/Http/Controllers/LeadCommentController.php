<?php

namespace App\Http\Controllers;

use App\Models\LeadComment;
use App\Models\Lead;
use App\Http\Requests\LeadCommentRequest;
use Illuminate\Http\Request;

class LeadCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadCommentRequest $request, Lead $lead)
    {
        $data = $request->validated();

        $data["lead_id"] = $lead->lead_id;
        $data["user_id"] = me()->id;
        LeadComment::create($data);

        $lead->update([
            "leadComment_count" => LeadComment::where("lead_id", $lead->lead_id)->count(),
        ]);

        return redirect()
            ->route("lead.edit", $lead->lead_id)
            ->with("success", "Message updated");
    }

    /**
     * Display the specified resource.
     */
    public function show(LeadComment $leadComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeadComment $leadComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeadComment $leadComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeadComment $leadComment)
    {
        // dd($leadComment->user_id);
        if ($leadComment->user_id == -1) { abort(403); }
        $leadComment->delete();
    }
}
