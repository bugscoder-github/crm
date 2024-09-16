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
            'form' => $supplier
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
        $this->save($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier) {
        $this->save($request, $supplier);
    }

    public function save(SupplierRequest $request, Supplier $supplier = null) {
        if ($supplier == null) { $supplier = new Supplier(); }
        $data = $request->validated();

        $supplier->updateOrCreate(['id' => $supplier->id], $data);
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
