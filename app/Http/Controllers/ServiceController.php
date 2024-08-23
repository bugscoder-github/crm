<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Yajra\DataTables\Datatables;

use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Display the server sided service information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return DataTable data
     */
    public function datatables()
    {
        $team = me()->currentTeam();

        $query = Service::where('team_id', $team->id);

        return Datatables::of($query)->make(true);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("Service/Index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
		return $this->renderForm();
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
		return $this->renderForm($service);
    }

    public function renderForm(Service $service = null) {
		if ($service == null) { $service = new Service(); }

		return Inertia::render("Service/Form", [
			"form" => $service,
			"success" => session("success") ?? ""
		]);
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        $result = $this->save($request);

        return $this->goto($result->id, __("Service created succesfully."));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $result = $this->save($request, $service);

        return $this->goto($result->id, __("Service updated succesfully."));
    }

    public function save(ServiceRequest $request, Service $service = null) {
		$team = me()->currentTeam();
		
		$data = $request->validated();

        if ($service) {
            $service->update($data);
        } else {
            $service = $team->services()->create($data);
        }

		return $service;
	}

	public function goto($id, $message) {
        return redirect()
            ->route("service.edit", ['service' => $id])
            ->withInput()
            ->with("success", $message);
	}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        return $service->delete();
    }
}
