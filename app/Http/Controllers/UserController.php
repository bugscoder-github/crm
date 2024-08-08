<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

use App\Models\Role;
use App\Models\Team;

class UserController extends Controller {
	public function index() {
		if (!isAdmin() && !isOwner()) { abort(403); }

		$users = User::orderByRaw('LOWER(name)');
		// dd($users->get()->toArray());
		if (isAdmin()) {
			$users = $users->where('current_team_id', me()->current_team_id);
		}


		// dd(me()->hasRole('Admin'));

		return Inertia::render('User/Index', [
			'users' => $users->get()->toArray()
		]);
	}

	public function create() {
		if (!isAdmin() && !isOwner()) { abort(403); }
		
		return $this->renderForm();
	}

	public function edit(User $user) {
		if (!isAdmin() && !isOwner() && !isMine($user->id)) { abort(403); }

		// $user['roles'] = $user->roles()->where('team_id', me()->currentTeam()->id)->first();
		// dd($user);

		return $this->renderForm($user);
	}

	public function renderForm(User $user = null) {
		// dd(me()->current_team_id);
		$roles = (isAdmin()) ? Role::where('name', '!=', 'Owner')->get() : Role::get();
		$teams = (isAdmin()) ? Team::where('id', me()->current_team_id)->get() : Team::get();

		return Inertia::render('User/Form', [
			'user' => $user == null ? new User() : $user,
			'teams' => $teams,
			'roles' => $roles,
			'success' => session('success') ?? '',
		]);
	}

	///

	public function store(UserRequest $request) {
		$result = $this->save($request);
		// dd($result->id);

		return redirect()->route('user.edit', $result->id)->withInput()->with('success', 'User created successfully.');
	}

	public function update(UserRequest $request, User $user) {
		$result = $this->save($request, $user);

		return redirect()->route('user.edit', $result->id)->withInput()->with('success', 'User updated successfully.');
	}

	public function save(UserRequest $request, User $user = null) {
		$data = $request->validated();
		$role = $data['role'] > 0 ? Role::where('id', $data['role'])->firstOrFail() : '';
		$team_id = isOwner() ? $data['current_team_id'] : me()->current_team_id;

		if ($user) {
			// \DB::connection()->enableQueryLog();
			$data['password'] = $data['password'] ? Hash::make($data['password']) : $user->password;
			if (!isAdmin() && !isOwner() && isset($data['current_team_id'])) { unset($data['current_team_id']); }

			$user->update($data);
			// dd(\DB::getQueryLog());

			if ((isAdmin() || isOwner()) && !isMine($user->id)) {
				$user->syncRoles([$role], $team_id);
			}
		} else {
			if (!isAdmin() && !isOwner()) { abort(403); }

			$user = User::create([
				'name'     => $data['name'],
				'email'    => $data['email'],
				'password' => Hash::make($data['password']),
				'current_team_id' => $team_id
			]);

			$user->syncRoles([$role], $team_id);
		}

		return $user;
	}

	///

	public function destroy(User $user) {
		if (isMine($user->id)) { abort(403); }
		if (!isAdmin() && !isOwner()) { abort(403); }

		$user->delete();
		return redirect()->route('user.index')->with('success', 'User deactivated successfully.');
	}


    public function show(User $user) {
    	return $user;
    }
}