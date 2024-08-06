<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Quotation;
use App\Models\QuotationItems;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
		return Inertia::render("Invoice/Index", [
			"invoice" => Invoice::orderBy('invoice_id', 'desc')->get()
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
		if ($invoice->invoice_id == null && request()->get('quotation_id') != null) {
			$quotation = Quotation::where('quotation_id', request()->get('quotation_id', 0))->firstOrFail();
            $quotationItems = [QuotationItems::where("quotation_id", request()->get('quotation_id', 0))->firstOrFail()];
		}

        // dd($quotationItems);

		return Inertia::render("Invoice/Form", [
            'invoice' => $invoice,
            'invoice_items' => $invoice->items()->get() ?? [],
            'quotation' => $quotation ?? [],
            'quotation_items' => $quotationItems ?? [],
            'success' => session("success") ?? ""
		]);
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request) {
		$result = $this->save($request);

		// if ($result->lead_id != null) {
		// 	Lead::where('lead_id', $result->lead_id)->update([
		// 		'quotation_id' => $result->quotation_id
		// 	]);
		// }

        return $this->goto($result->invoice_id, "Invoice created succesfully.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceRequest $request, Invoice $invoice) {
        $result = $this->save($request, $invoice);

        return $this->goto($result->invoice_id, "Invoice updated succesfully.");
    }

    public function save(InvoiceRequest $request, Invoice $invoice = null) {
		$data = $request->validated();
		$basic = collect($data)->except('invoice_items')->toArray();
		$items = $data['invoice_items'];
        
		if ($invoice == null) { $invoice = new Invoice(); }
		$invoice = Invoice::updateOrCreate(['invoice_id' => $invoice->invoice_id], $basic);

		//Delete item that is not in data[quotation_item]
		$dataItemId = array_filter(array_column($items, 'invoiceItem_id'));
		InvoiceItems::where('invoice_id', $invoice->invoice_id)->whereNotIn('invoiceItem_id', $dataItemId)->delete();
		
		$total = 0;
		foreach($items as $key => $value) {
			if (empty($value['invoiceItem_desc'])) { continue; }

			$value['invoice_id'] = $invoice->invoice_id;
			if (!isset($value['invoiceItem_id'])) { $value['invoiceItem_id'] = null; }
			if ($value['invoiceItem_id'] == 0)    { $value['invoiceItem_id'] = null; }

			$value['invoiceItem_total'] = ($value['invoiceItem_ppu'] * $value['invoiceItem_qty']);
			$total += $value['invoiceItem_total'];

			InvoiceItems::updateOrCreate(['invoiceItem_id'=> $value['invoiceItem_id']], $value);
		}

		$invoice->update([
			'invoice_total' => $total,
			'invoice_sst' => $total*($basic['invoice_sstPct']/100),
			'invoice_grandTotal' => $total*(1+($basic['invoice_sstPct']/100))
		]);

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
