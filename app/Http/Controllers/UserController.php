<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Role;

class UserController extends Controller {
	public function index() {
		if (isAdmin() == false) { abort(403); }

		$users = me()->currentTeam()->users()->orderByRaw('LOWER(name)')->get();

		return Inertia::render('User/Index', [
			'users' => $users
		]);
	}

	public function create() {
		if (isAdmin() == false) { abort(403); }
		
		return $this->renderForm();
	}

	public function edit(User $user) {
		if (isAdmin() == false && !isMine($user->id)) { abort(403); }

		$user['roles'] = $user->roles()->where('team_id', me()->currentTeam()->id)->first();

		return $this->renderForm($user);
	}

	public function renderForm(User $user = null) {
		return Inertia::render('User/Form', [
			'user' => $user == null ? new User() : $user,
			'roles' => Role::get(),
			'success' => session('success') ?? '',
		]);
	}

	///

	public function store(UserRequest $request) {
		$result = $this->save($request);

		return redirect()->route('user.edit', $result->id)->withInput()->with('success', 'User created successfully.');
	}

	public function update(UserRequest $request, User $user) {
		$result = $this->save($request, $user);

		return redirect()->route('user.edit', $result->id)->withInput()->with('success', 'User updated successfully.');
	}

	public function save(UserRequest $request, User $user = null) {
		$data = $request->validated();

		$role = Role::where('id', $data['role'])->firstOrFail();

		if ($user) {
			$user->update([
				'name'     => $data['name'],
				'email'    => $data['email'],
				'password' => $data['password'] ? Hash::make($data['password']) : $user->password,
			]);

			if (me()->isAdmin() && Auth()->id() != $user->id) {
				$user->syncRoles([$role], me()->currentTeam());
			}
		} else {
			$user = User::create([
				'name'     => $data['name'],
				'email'    => $data['email'],
				'password' => Hash::make($data['password']),
				'current_team_id' => me()->currentTeam()->id
			]);


			if (me()->isAdmin()) {
				$user->syncRoles([$role], me()->currentTeam());
			}
		}

		return $user;
	}

	///

	public function destroy(User $user) {
		if (isMine($user->id)) { abort(403); }
		if (!isAdmin())      { abort(403); }

		$user->delete();
		return redirect()->route('user.index')->with('success', 'User deactivated successfully.');
	}


    public function show(User $user) {
    	return $user;
    }
}