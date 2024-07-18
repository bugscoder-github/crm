<?php

namespace App\Http\Controllers;

use App\Http\Requests\MetadataRequest;
use App\Models\Metadata;
use Inertia\Inertia;

class MetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Inertia::render("Metadata/Index", [

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return Inertia::render("Metadata/Form", [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MetadataRequest $request) {
        // dd($request->validated());
        $metadata = Metadata::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Metadata $metadata)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Metadata $metadata)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MetadataRequest $request, Metadata $metadata)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Metadata $metadata)
    {
        //
    }
}
