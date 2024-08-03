<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductServiceRequest;
use Illuminate\Http\Request;
use App\Models\ProductService;

class ProductServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductServiceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductService $productService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductService $productService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductServiceRequest $request, ProductService $productService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductService $productService)
    {
        //
    }

	public function search(Request $request) {
        $query = $request->input('query');
        $result = [];
        if (empty($query)) {
            $result = ProductService::all();
        } else {
            $result = ProductService::where('productService_desc', 'like', '%'.$query.'%')->get();
        }
        
		return $result;
	}
}
