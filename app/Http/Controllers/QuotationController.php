<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotationRequest;
use App\Models\Quotation;
use App\Models\QuotationItem;
use APp\Models\Lead;
use Inertia\Inertia;

use App\Services\CalculationService;

use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		// $string = '{"lead_id":1,"currency":"MYR","items":[{"item_type":"service","service_id":1,"name":null,"description":null,"quantity":3,"unit_amount":0,"is_enabled":true},{"item_type":"service","service_id":3,"name":null,"description":null,"quantity":2,"unit_amount":0,"is_enabled":true},{"item_type":"service","service_id":5,"name":null,"description":null,"quantity":4,"unit_amount":0,"is_enabled":true}]}';
		// $data = json_decode($string, true);
		// dd(
		// 	CalculationService::estimate(
		// 		$data,
		// 		me()->currentTeam()
		// 	)
		// );
		return Inertia::render("Quotation/Index", [
			"quotation" => me()->currentTeam()->quotations()->orderBy('id', 'desc')->get()
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
		if ($quotation->id == null && request()->get('lead_id') != null) {
			$lead = Lead::where('lead_id', request()->get('lead_id', 0))->firstOrFail();
		}

		$quotation['lead_id'] = $lead->lead_id ?? null;
		$items = $quotation->items;
		// Amend Items For Vue Select Use
		foreach ($items as $key => $item) {
            $items[$key]['discounts'] = $item->discounts()->select([
                'name',
                'description',
                'discount_type',
                'amount',
            ])->get();
        }
		$quotation['items'] = $items;
		$quotation['discounts'] = $quotation->discounts;
		$quotation['taxes'] = $quotation->taxes;

		// TODO: To be adjusted
		$currency = me()->currentTeam()->getTeamPrimary();

		$quotation['currency'] = $currency->iso3;
		$quotation['currency_symbol'] = $currency->symbol;

		return Inertia::render("Quotation/Form", [
			"form" => $quotation,
			"itemTemplate" => new QuotationItem(),
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

        return $this->goto($result->id, "Quotation created succesfully.");
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(QuotationRequest $request, Quotation $quotation) {
		$result = $this->save($request, $quotation);

        return $this->goto($result->id, "Quotation updated succesfully.");
	}

	public function save(QuotationRequest $request, Quotation $quotation = null) {
		$team = me()->currentTeam();
		
		$data = $request->validated();

		$quotationData = CalculationService::estimate(
            $data,
			$team,
			false
		);

		$quotationData = collect($quotationData);
		$discounts = $quotationData->only('discounts');
		$taxes = $quotationData->only('taxes');

		if (!$quotation) {
       		// Create Quotation
			$sequence = $team->findSequenceType('quotation');
			$quotationData['quotation_number'] = $sequence->prefix . $sequence->number . $sequence->suffix;

			$quotation = $team->quotations()->create($quotationData->except('items')->toArray());

			// Update Sequence Running Numbers
			$sequence->update([
				'number' => $sequence->number + 1
			]);
		} else {
        	// Update Quotation
			$quotation->update($quotationData->except('items')->toArray());
			// Refresh Database
			$quotation->refresh();
		}

		// Clear Items
		$quotation->items()->delete();
		// Clear Discounts
		$quotation->discounts()->delete();

		// Create Customer Discounts
		foreach ($discounts['discounts'] as $key => $discount) {
            $discount['quotation_discount_type'] = 'quotation';
            $discount['quotation_discount_id'] = $quotation->id;
            $quotation->discounts()->create($discount);
        }

		// Create Customer Taxes
		foreach ($taxes['taxes'] as $key => $tax) {
            $quotation->taxes()->create($tax);
        }

		$items = $quotationData->only('items')->toArray()['items'];

		// Setup Item Data
        foreach ($items as $item) {
            $item = collect($item);

			// Get Item Discounts
            $itemDiscount = $item->pull('discounts');

            // Create Quotation Items
            $quotationItem = $quotation->items()->create($item->toArray());

			// Create Item Discount
            if ($itemDiscount) {
                foreach ($itemDiscount as $discount) {
                    $discount['quotation_discount_type'] = 'quotation_item';
                    $discount['quotation_discount_id'] = $quotationItem->id;
                    $quotation->discounts()->create($discount);
                }
            }
        }

		return $quotation;
	}

	public function goto($id, $message) {
        return redirect()
            ->route("quotation.edit", ['quotation' => $id])
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
