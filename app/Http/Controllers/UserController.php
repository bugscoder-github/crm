<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Spatie\Permission\Models\Role;

class UserController extends Controller {
	public function index() {
		if (isAdmin() == false) { abort(403); }

		$users = User::orderByRaw('LOWER(name)')->get();
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

		if ($user) {
			$user->update([
				'name'     => $data['name'],
				'email'    => $data['email'],
				'password' => $data['password'] ? Hash::make($data['password']) : $user->password,
			]);

			if (auth()->user()->role_names[0] == 'Admin' && Auth()->id() != $user->id) {
				$user->syncRoles($data['role']);
			}
		} else {
			$user = User::create([
				'name'     => $data['name'],
				'email'    => $data['email'],
				'password' => Hash::make($data['password']),
			]);


			if (auth()->user()->role_names[0] == 'Admin') {
				$user->assignRole($data['role']);
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