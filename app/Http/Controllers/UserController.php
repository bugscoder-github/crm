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
		if (!isAdminOrOwner()) { abort(403); }

		$users = User::orderByRaw('LOWER(name)');
		// dd($users->get()->toArray());
		if (isAdmin()) {
			$users = $users->where('current_team_id', me()->current_team_id);
		}

		return Inertia::render('User/Index', [
			'users' => $users->get()->toArray()
		]);
	}

	public function create() {
		if (!isAdminOrOwner()) { abort(403); }
		
		return $this->renderForm();
	}

	public function edit(User $user) {
		if (!isAdminOrOwner() && !isMine($user->id)) { abort(403); }

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

		// return redirect()->route('user.edit', $result->id)->withInput()->with('success', 'User created successfully.');
		return $this->goto($result->id, 'User created successfully.');
	}

	public function update(UserRequest $request, User $user) {
		$result = $this->save($request, $user);

		// return redirect()->route('user.edit', $result->id)->withInput()->with('success', 'User updated successfully.');
		return $this->goto($result->id, 'User updated successfully.');
	}

	public function save(UserRequest $request, User $user = null) {
		if ($user == null) {
			if (!isAdminOrOwner()) { abort(403); }
			$user = new User();
		}

		$data = $request->validated();
		$role = $data['role'] > 0 ? Role::where('id', $data['role'])->firstOrFail() : '';
		$team_id = isOwner() ? $data['current_team_id'] : me()->current_team_id;
		$data['current_team_id'] = $team_id;
		$data['password'] = !empty($data['password']) ? Hash::make($data['password']) : $user->password;
		
		$user = User::updateOrCreate(['id' => $user->id], $data);
		if (isAdminOrOwner() && !isMine($user->id)) {
			$user->syncRoles([$role], $team_id);
		}

		return $user;
	}

	public function goto($id, $msg = '') {
		return redirect()
				->route('user.edit', $id)
				->withInput()
				->with('success', $msg);
	}

	///

	public function destroy(User $user) {
		if (isMine($user->id)) { abort(403); }
		if (!isAdminOrOwner()) { abort(403); }

		$user->delete();
		return redirect()->route('user.index')->with('success', 'User deactivated successfully.');
	}


    public function show(User $user) {
    	return $user;
    }
}