<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductServiceRequest;
use App\Models\Category;
use App\Models\ProductService;
use Inertia\Inertia;

class ProductServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Inertia::render('ProductService/Index', [
            'result' => ProductService::with('category')->get()
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
    public function edit(ProductService $productService) {
        return $this->renderForm($productService);
    }

    public function renderForm(ProductService $productService = null) {
        if ($productService == null) { $productService = new ProductService(); }

        return Inertia::render('ProductService/Form', [
            'form' => $productService,
            'category' => Category::getCategoryList()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductService $productService)
    {
        //
    }

    //

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductServiceRequest $request) {
        $this->save($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductServiceRequest $request, ProductService $productService) {
        $this->save($request, $productService);
    }

    public function save(ProductServiceRequest $request, ProductService $productService = null) {
        if ($productService == null) { $productService = new productService(); }
        $data = $request->validated();

        $productService->updateOrCreate(['id' => $productService->id], $data);
    }

    //

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(productService $productService)
    {
        //
    }
}
