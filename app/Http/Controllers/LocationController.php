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
            'form' => $location,
			'success' => session('success') ?? '',
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
        $result = $this->save($request);

        return $this->goto($result->id, 'Location created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationRequest $request, Location $location) {
        $result = $this->save($request, $location);

        return $this->goto($result->id, 'Location updated successfully.');
    }

    public function save(LocationRequest $request, Location $location = null) {
        if ($location == null) { $location = new Location(); }
        $data = $request->validated();

        return $location->updateOrCreate(['id' => $location->id], $data);
    }

	public function goto($id, $msg = '') {
		return redirect()
				->route('location.edit', $id)
				->withInput()
				->with('success', $msg);
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
