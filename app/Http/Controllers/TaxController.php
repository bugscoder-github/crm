<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxRequest;
use App\Models\Tax;
use Yajra\DataTables\Datatables;

use Inertia\Inertia;

class TaxController extends Controller
{
    /**
     * Display the server sided tax information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return DataTable data
     */
    public function datatables()
    {
        $team = me()->currentTeam();

        $query = Tax::where('team_id', $team->id);

        return Datatables::of($query)->make(true);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("Tax/Index");
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
    public function show(Tax $tax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tax $tax)
    {
		return $this->renderForm($tax);
    }

    public function renderForm(Tax $tax = null) {
		if ($tax == null) { $tax = new Tax(); }

		return Inertia::render("Tax/Form", [
			"form" => $tax,
			"success" => session("success") ?? ""
		]);
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxRequest $request)
    {
        $result = $this->save($request);

        return $this->goto($result->id, __("Tax created succesfully."));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaxRequest $request, Tax $tax)
    {
        $result = $this->save($request, $tax);

        return $this->goto($result->id, __("Tax updated succesfully."));
    }

    public function save(TaxRequest $request, Tax $tax = null) {
		$team = me()->currentTeam();
		
		$data = $request->validated();

        if ($tax) {
            $tax->update($data);
        } else {
            $tax = $team->taxes()->create($data);
        }

		return $tax;
	}

	public function goto($id, $message) {
        return redirect()
            ->route("tax.edit", ['tax' => $id])
            ->withInput()
            ->with("success", $message);
	}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {
        return $tax->delete();
    }
}
