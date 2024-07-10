<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotationRequest;
use App\Models\Quotation;
use APp\Models\Lead;
use App\Models\LeadComment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuotationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		return Inertia::render("Quotation/Index", []);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create() {
		return $this->renderForm();
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Quotation $quotation) {
		return $this->renderForm($quotation);
	}

	//

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(QuotationRequest $request) {
		$data = $request->validated();

		$result = Quotation::create($data);
		if (isset($data['lead_id'])) {
			Lead::where('lead_id', $data['lead_id'])->update([
				'quotation_id' => $result->quotation_id
			]);
			LeadComment::create([
				'lead_id' => $data['lead_id'],
				'leadComment_comment' => "Quotation #{$result->quotation_id} created.",
			]);
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(QuotationRequest $request, Quotation $quotation) {
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Quotation $quotation) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Quotation $quotation)
	{
		//
	}

	//

	public function renderForm(Quotation $quotation = null) {
		$lead = [];
		if ($quotation == null) {
			$lead = Lead::where('lead_id', request()->get('lead_id', 0))->firstOrFail();
		}

		return Inertia::render("Quotation/Form", [
			"quotation" => $quotation != null ? $quotation : new Quotation(),
			"lead" => $lead
		]);
	}
	
    public function save(QuotationRequest $request, Quotation $quotation = null) {
		
    }
}
