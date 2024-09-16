<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use Inertia\Inertia;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Inertia::render('Location/Index', [
            'result' => Location::all()
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
    public function edit(Location $location) {
        return $this->renderForm($location);
    }

    public function renderForm(Location $location = null) {
        if ($location == null) { $location = new Location(); }

        return Inertia::render('Location/Form', [
            'form' => $location
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    ///

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationRequest $request) {
        $this->save($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationRequest $request, Location $location) {
        $this->save($request, $location);
    }

    public function save(LocationRequest $request, Location $location = null) {
        if ($location == null) { $location = new Location(); }
        $data = $request->validated();

        $location->updateOrCreate(['id' => $location->id], $data);
    }

    ///

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        //
    }
}
