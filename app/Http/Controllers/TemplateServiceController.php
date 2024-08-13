<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\TemplateService;
use Yajra\DataTables\Datatables;

class TemplateServiceController extends Controller
{
    /**
     * Display the server sided services information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return DataTable data
     */
    public function datatables()
    {
        $team = me()->currentTeam();

        $query = TemplateService::where('team_id', $team->id);

        return Datatables::of($query)->make(true);
    }

    /**
     * Display the server sided services information
     * 
     */
    public function services($id)
    {
        $template = me()->currentTeam()->templateServices()->where('id', $id)->firstOrFail();

        return $template->services()->pluck('id');
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
