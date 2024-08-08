<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
use App\Models\Team;
use Inertia\Inertia;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $teams = Team::orderByRaw('LOWER(name)')->get();

        return Inertia::render('Team/Index', [
            'teams' => $teams
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
    public function edit(Team $team) {
        return $this->renderForm($team);
    }

	public function renderForm(Team $team = null) {
        if ($team == null) { $team = new Team(); }

		return Inertia::render('Team/Form', [
			'team' => $team,
			'success' => session('success') ?? '',
		]);
	}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamRequest $request) {
        $result = $this->save($request);

        return $this->goto($result->id, "Team created successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamRequest $request, Team $team) {
        $result = $this->save($request, $team);

        return $this->goto($result->id, "Team updated successfully");
    }

    public function save(TeamRequest $request, Team $team = null) {
        $data = $request->validated();
        
        if ($team == null) { $team = new Team(); }
        // dd($team->id);
        $team = Team::updateOrCreate(['id' => $team->id], $data);

        return $team;
    }

	public function goto($id, $message) {
        return redirect()
            ->route("team.edit", $id)
            ->withInput()
            ->with("success", $message);
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
