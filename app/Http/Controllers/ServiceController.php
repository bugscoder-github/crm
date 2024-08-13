<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Yajra\DataTables\Datatables;

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
    public function store(ServiceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $Service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $Service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $Service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $Service)
    {
        //
    }
}
