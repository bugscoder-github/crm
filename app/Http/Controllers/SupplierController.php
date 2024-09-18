<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Inertia\Inertia;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Inertia::render('Supplier/Index', [
            'result' => Supplier::get()
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
    public function edit(Supplier $supplier) {
        return $this->renderForm($supplier);
    }

    public function renderForm(Supplier $supplier = null) {
        if ($supplier == null) { $supplier = new Supplier(); }

        return Inertia::render('Supplier/Form', [
            'form' => $supplier,
			'success' => session('success') ?? '',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    //

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request) {
        $result = $this->save($request);

        return $this->goto($result->id, 'Supplier created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier) {
        $result = $this->save($request, $supplier);

        return $this->goto($result->id, 'Supplier updated successfully.');
    }

    public function save(SupplierRequest $request, Supplier $supplier = null) {
        if ($supplier == null) { $supplier = new Supplier(); }
        $data = $request->validated();

        return $supplier->updateOrCreate(['id' => $supplier->id], $data);
    }

	public function goto($id, $msg = '') {
		return redirect()
				->route('supplier.edit', $id)
				->withInput()
				->with('success', $msg);
	}

    //

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
