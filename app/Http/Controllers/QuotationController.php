<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotationRequest;
use App\Models\Quotation;
use APp\Models\Lead;
use App\Models\QuotationItems;
use Inertia\Inertia;

use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		return Inertia::render("Quotation/Index", [
			"quotation" => Quotation::orderBy('quotation_id', 'desc')->get()
		]);
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

	/**
	 * Display the specified resource.
	 */
	public function show(Quotation $quotation) {
		//
	}

	public function renderForm(Quotation $quotation = null) {
		if ($quotation == null) { $quotation = new Quotation(); }
		if ($quotation->quotation_id == null && request()->get('lead_id') != null) {
			$lead = Lead::where('lead_id', request()->get('lead_id', 0))->firstOrFail();
		}

		return Inertia::render("Quotation/Form", [
			"quotation" => $quotation,
			"quotation_items" => QuotationItems::where("quotation_id", $quotation->quotation_id)->get(),
			"lead" => $lead ?? [],
			"success" => session("success") ?? ""
		]);
	}

	//

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(QuotationRequest $request) {
		$result = $this->save($request);

		if ($result->lead_id != null) {
			Lead::where('lead_id', $result->lead_id)->update([
				'quotation_id' => $result->quotation_id
			]);
		}

        return $this->goto($result->quotation_id, "Quotation created succesfully.");
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(QuotationRequest $request, Quotation $quotation) {
		$result = $this->save($request, $quotation);

        return $this->goto($result->quotation_id, "Quotation updated succesfully.");
	}

	public function save(QuotationRequest $request, Quotation $quotation = null) {
		$data = $request->validated();
		$basic = collect($data)->except('quotation_items')->toArray();
		$items = $data['quotation_items'];
		dd($basic);

		if ($quotation == null) { $quotation = new Quotation(); }
		$quotation = Quotation::updateOrCreate(['quotation_id' => $quotation->quotation_id], $basic);
		
		//Delete item that is not in data[quotation_item]
		$dataItemId = array_filter(array_column($items, 'quotationItem_id'));
		QuotationItems::where('quotation_id', $quotation->quotation_id)->whereNotIn('quotationItem_id', $dataItemId)->delete();
		
		$total = 0;
		foreach($items as $key => $value) {
			if (empty($value['quotationItem_desc'])) { continue; }

			$value['quotation_id'] = $quotation->quotation_id;
			if (!isset($value['quotationItem_id'])) { $value['quotationItem_id'] = null; }
			if ($value['quotationItem_id'] == 0)    { $value['quotationItem_id'] = null; }

			$value['quotationItem_total'] = ($value['quotationItem_ppu'] * $value['quotationItem_qty']);
			$total += $value['quotationItem_total'];

			QuotationItems::updateOrCreate(['quotationItem_id'=> $value['quotationItem_id']], $value);
		}

		$quotation->update([
			'quotation_total' => $total,
			'quotation_sst' => $total*($basic['quotation_sstPct']/100),
			'quotation_grandTotal' => $total*(1+($basic['quotation_sstPct']/100))
		]);

		return $quotation;
	}

	public function goto($id, $message) {
        return redirect()
            ->route("quotation.edit", $id)
            ->withInput()
            ->with("success", $message);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Quotation $quotation)
	{
		//
	}

	function pdf(Quotation $quotation) {
		$attn = "";
		$name = $quotation->quotation_name;
		if (!empty($quotation->quotation_company)) {
			$attn = $name;
			$name = $quotation->quotation_company;
		}

        $data = [
			"quotation_attn" => $attn,
			"quotation_name" => $name,
			"quotation_number" => str_pad((1000+$quotation->quotation_id), 5, "0", STR_PAD_LEFT),
			"quotation_billingAddress" => $quotation->quotation_billingAddress,
			"quotation_deliveryAddress" => $quotation->quotation_deliveryAddress,
			"quotation_phone" => $quotation->quotation_phone,
			"quotation_email" => $quotation->quotation_email,
			"quotation_total" => amount_format($quotation->quotation_total),
			"quotation_sst" => amount_format($quotation->quotation_sst),
			"quotation_grandTotal" => amount_format($quotation->quotation_grandTotal),
			"quotation_sstPct" => $quotation->quotation_sstPct,
			"quotation_remark" => $quotation->quotation_remark,
			"quotation_tnc" => $quotation->quotation_tnc,
			"quotation_items" => $quotation->items()->get()
		];
        $pdf = PDF::loadView('pdf.quotation', $data);
		return $pdf->stream("quotation_{$quotation->quotation_id}.pdf", array("Attachment" => false));
        // return $pdf->download('myPDF.pdf');
	}
}
