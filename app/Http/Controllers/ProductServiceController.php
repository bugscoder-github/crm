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
			'success' => session('success') ?? '',
            'category' => Category::generateOptions()
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
        $result = $this->save($request);

        return $this->goto($result->id, 'Product/Servicce created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductServiceRequest $request, ProductService $productService) {
        $result = $this->save($request, $productService);

        return $this->goto($result->id, 'Product/Servicce updated successfully.');
    }

    public function save(ProductServiceRequest $request, ProductService $productService = null) {
        if ($productService == null) { $productService = new productService(); }
        $data = $request->validated();

        return $productService->updateOrCreate(['id' => $productService->id], $data);
    }

	public function goto($id, $msg = '') {
		return redirect()
				->route('productService.edit', $id)
				->withInput()
				->with('success', $msg);
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
