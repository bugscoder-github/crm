<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

use App\Services\CalculationService;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
		return Inertia::render("Invoice/Index", [
			"invoice" => Invoice::orderBy('id', 'desc')->get()
		]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
		return $this->renderForm();
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice) {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice) {
		return $this->renderForm($invoice);
    }

	public function renderForm(Invoice $invoice = null) {
		if ($invoice == null) { $invoice = new Invoice(); }
		if ($invoice->id == null && request()->get('quotation_id') != null) {
			$quotation = me()->currentTeam()->quotations()->where('id', request()->get('quotation_id', 0))->firstOrFail();
		}
		
		if (!$invoice->id) {
			if ($quotation ?? null) {
				$invoice = new Invoice(
					collect($quotation->toArray())->except([
						'id', 
						'team_id',
						'lead_id',
						'quotation_type',
						'frequency_type',
						'frequency',
						'quotation_number',
						'quotation_date',
					])->toArray()
				);
				$invoice->quotation_id = $quotation->id;
				$items = $quotation->items()->where('is_enabled', 1)->get();
				$invoice['discounts'] = $quotation->discounts;
				$invoice['taxes'] = $quotation->taxes;
			} else {
				$items = $invoice->items;
				$invoice['discounts'] = $invoice->discounts;
				$invoice['taxes'] = $invoice->taxes;
			}
		} else {
			$items = $invoice->items;
			$invoice['discounts'] = $invoice->discounts;
			$invoice['taxes'] = $invoice->taxes;
		}

		// Amend Items For Vue Select Use
		foreach ($items as $key => $item) {
			$items[$key]['discounts'] = $item->discounts()->select([
				'name',
				'description',
				'discount_type',
				'amount',
			])->get();
		}
		$invoice['items'] = $items;
		

		// TODO: To be adjusted
		$currency = me()->currentTeam()->getTeamPrimary();

		$invoice['currency'] = $currency->iso3;
		$invoice['currency_symbol'] = $currency->symbol;

		return Inertia::render("Invoice/Form", [
            "form" => $invoice,
			"itemTemplate" => new InvoiceItem(),
            'quotation' => $quotation ?? null,
            'success' => session("success") ?? ""
		]);
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request) {
		$result = $this->save($request);

        return $this->goto($result->id, "Invoice created succesfully.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceRequest $request, Invoice $invoice) {
        $result = $this->save($request, $invoice);

        return $this->goto($result->id, "Invoice updated succesfully.");
    }

    public function save(InvoiceRequest $request, Invoice $invoice = null) {
		$team = me()->currentTeam();
		
		$data = $request->validated();

		$invoiceData = CalculationService::estimate(
            $data,
			$team,
			false
		);

		$invoiceData = collect($invoiceData);
		$discounts = $invoiceData->only('discounts');
		$taxes = $invoiceData->only('taxes');

		if (!$invoice) {
       		// Create Quotation
			$sequence = $team->findSequenceType('invoice');
			$invoiceData['invoice_number'] = $sequence->prefix . $sequence->number . $sequence->suffix;

			$invoice = $team->invoices()->create($invoiceData->except('items')->toArray());

			// Update Sequence Running Numbers
			$sequence->update([
				'number' => $sequence->number + 1
			]);
		} else {
        	// Update Quotation
			$invoice->update($invoiceData->except('items')->toArray());
			// Refresh Database
			$invoice->refresh();
		}

		// Clear Items
		$invoice->items()->delete();
		// Clear Discounts
		$invoice->discounts()->delete();

		// Create Customer Discounts
		foreach ($discounts['discounts'] as $key => $discount) {
            $discount['invoice_discount_type'] = 'invoice';
            $discount['invoice_discount_id'] = $invoice->id;
            $invoice->discounts()->create($discount);
        }

		// Create Customer Taxes
		foreach ($taxes['taxes'] as $key => $tax) {
            $invoice->taxes()->create($tax);
        }

		$items = $invoiceData->only('items')->toArray()['items'];

		// Setup Item Data
        foreach ($items as $item) {
            $item = collect($item);

			// Get Item Discounts
            $itemDiscount = $item->pull('discounts');

            // Create Quotation Items
            $invoiceItem = $invoice->items()->create($item->toArray());

			// Create Item Discount
            if ($itemDiscount) {
                foreach ($itemDiscount as $discount) {
                    $discount['invoice_discount_type'] = 'invoice_item';
                    $discount['invoice_discount_id'] = $invoiceItem->id;
                    $invoice->discounts()->create($discount);
                }
            }
        }

		return $invoice;
    }

	public function goto($id, $message) {
        return redirect()
            ->route("invoice.edit", $id)
            ->withInput()
            ->with("success", $message);
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    public function invoiceMarkPaid(Invoice $invoice) {
		$invoice->update(['invoice_paidAt' => now()]);
	}

    public function invoiceMarkApproved(Invoice $invoice) {
		$invoice->update([
            'invoice_approvedAt' => now(),
            'invoice_approvedBy' => me()->id
        ]);
	}

	function pdf(Invoice $invoice) {
		$attn = "";
		$name = $invoice->invoice_name;
		if (!empty($invoice->invoice_company)) {
			$attn = $name;
			$name = $invoice->invoice_company;
		}

        $data = [
			"quotation_attn" => $attn,
			"quotation_name" => $name,
			"quotation_number" => str_pad((1000+$invoice->invoice_id), 5, "0", STR_PAD_LEFT),
			"quotation_billingAddress" => $invoice->invoice_billingAddress,
			"quotation_deliveryAddress" => $invoice->invoice_deliveryAddress,
			"quotation_phone" => $invoice->invoice_phone,
			"quotation_email" => $invoice->invoice_email,
			"quotation_total" => amount_format($invoice->invoice_total),
			"quotation_sst" => amount_format($invoice->invoice_sst),
			"quotation_grandTotal" => amount_format($invoice->invoice_grandTotal),
			"quotation_sstPct" => $invoice->invoice_sstPct,
			"quotation_remark" => $invoice->invoice_remark,
			"quotation_tnc" => $invoice->invoice_tnc,
			"quotation_items" => $invoice->items()->get()
		];
        $pdf = PDF::loadView('pdf.quotation', $data);
		return $pdf->stream("quotation_{$invoice->invoice_id}.pdf", array("Attachment" => false));
        // return $pdf->download('myPDF.pdf');
	}
}
