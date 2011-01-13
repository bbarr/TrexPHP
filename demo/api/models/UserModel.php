<?php

class UserModel {
	
	public function __construct() {
		if (!isset($_SESSION['users'])) {
			$_SESSION['users'] = array('users' => array());
		}
	}
	
	public function create($name) {
		if (isset($_SESSION['users'][$name])) {
			return false;
		}
		return $_SESSION['users'][$name] = array('name' => $name);
	}
	
	public function update() {
		
	}
	
	public function delete($name) {
		if (!isset($_SESSION['users'][$name])) {
			return false;
		}
		else {
			unset($_SESSION['users'][$name]);
			return true;
		}
	}
	
	public function fetch($id = null) {
		if ($id !== null) {
			if (is_array($_SESSION['users'])) {
				if (array_key_exists($id, $_SESSION['users'])) {
					return $_SESSION['users'][$id];
				}
			}
			return false;
		}
		else {
			return $_SESSION['users'];
		}
	}
}

?>