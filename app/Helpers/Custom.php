<?php
use Spatie\Permission\Models\Role;

if (!function_exists('getConfig')) {
	function getConfig($config) {
		return config($config);
	}
}

if (!function_exists('getRoles')) {
	function getRoles() {
		return Role::get();
	}
}

if (!function_exists('me')) {
	function me() {
		return auth()->user();
	}
}

if (!function_exists('isAdmin')) {
	function isAdmin() {
		return me()->isAdmin();
	}
}

if (!function_exists('isMine')) {
	function isMine($id) {
		if ($id == me()->id) { return true; }
		return false;
	}
}
?>