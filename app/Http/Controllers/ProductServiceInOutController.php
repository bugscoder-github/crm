<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductServiceInOutRequest;
use App\Models\ProductService;
use App\Models\ProductServiceInOut;
use Inertia\Inertia;

class ProductServiceInOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Inertia::render('ProductServiceInOut/Index', [
            'result' => ProductServiceInOut::with('ProductService.category')->get()
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
    public function edit(ProductServiceInOut $productServiceInOut) {
        return $this->renderForm($productServiceInOut);
    }

    public function renderForm(ProductServiceInOut $productServiceInOut = null) {
        if ($productServiceInOut == null) { $productServiceInOut = new ProductServiceInOut(); }

        $products = ProductService::with('category')->get();
        
        return Inertia::render('ProductServiceInOut/Form', [
            'form' => $productServiceInOut,
            'products' => $products
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductServiceInOut $productServiceInOut)
    {
        //
    }

    //

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductServiceInOutRequest $request) {
        $this->save($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductServiceInOutRequest $request, ProductServiceInOut $productServiceInOut) {
        $this->save($request, $productServiceInOut);
    }

    public function save(ProductServiceInOutRequest $request, ProductServiceInOut $productServiceInOut = null) {
        if ($productServiceInOut == null) { $productServiceInOut = new ProductServiceInOut(); }
        $data = $request->validated();

        $productServiceInOut->updateOrCreate(['id' => $productServiceInOut->id], $data);
    }

    //

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductServiceInOut $productServiceInOut)
    {
        //
    }
}
