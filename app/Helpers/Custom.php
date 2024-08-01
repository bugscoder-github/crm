<?php
use Spatie\Permission\Models\Role;
use App\Models\Metadata;

if (!function_exists("getMeta")) {
	function getMeta($type = '', $label = '') {
		if ($type != '') {
			$result = Metadata::where('metadata_type', $type);
			if ($label != '') {
				$result = $result->where('metadata_label', $label);
			}
		}
		else {
			$result = Metadata::get();
		}

		$meta = [];
		foreach ($result as $key => $value) {
			$meta[$value['metadata_type']][$value['metadata_label']][] = array(
				'id' => $value['metadata_id'],
				'label' => $value['metadata_label'],
				'value' => $value['metadata_value']
			);
		}

		return $meta;
	}
}

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
		return me()->role_names[0] == 'Admin' ? true : false;
	}
}

if (!function_exists('isMine')) {
	function isMine($id) {
		if ($id == me()->id) { return true; }
		return false;
	}
}

if (!function_exists('amount_format')) {
	function amount_format($amt) {
		return number_format($amt, 2, '.', ',');
	}
}
?>