<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest {
	public $isMine;

	protected function prepareForValidation() {
		$this->isMine = false;
		if ($this->route('user')) {
			if (Auth()->id() == $this->route('user')->id ?? 0) {
				$this->isMine = true;
			}	
		}


		if ($this->password == null) { $this->request->remove('password'); }
		if ($this->isMine == true) { $this->request->remove('role'); }
	}

	/**
	* Determine if the user is authorized to make this request.
	*/
	public function authorize(): bool {
		$isAuth = true;
		if (request()->routeIs('user.store')  && !isAdminOrOwner()) { $isAuth = false; }
		if (request()->routeIs('user.update') && (!isAdminOrOwner() && !$this->isMine)) { $isAuth = false; }

		return $isAuth;
	}

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	*/
	public function rules(): array {
		$passwordRule = request()->routeIs('user.update') ? 'sometimes' : 'required';
		$rolesRule    = ((isAdmin() || isOwner()) && !$this->isMine) ? 'required' : '';
		$teamRule     = isOwner() ? 'required' : '';

		return [
			'name'     => ['required', 'string', 'max:255'],
			'email'    => ['required', 'email', Rule::unique('users')->ignore($this->route('user'))],
			'password' => [$passwordRule, 'confirmed'],
			'role'     => [$rolesRule],
			'current_team_id' => [$teamRule],
			// 'email' => ['required', 'email', 'unique:users,email,'.$this->route('user')],
			// 'password' => 'nullable|string|min:8|confirmed',
		];
	}

	public function messages() {
		return [
			'name.required' => 'The name field is required.',
			'email.required' => 'The email field is required.',
			'email.unique' => 'This email is already taken.',
			'password.min' => 'The password must be at least 8 characters.',
			'password.confirmed' => 'The password confirmation does not match.',
			'role.required' => 'The role field is required.',
			'current_team_id.required' => 'The team field is required.'
		];
	}
}
