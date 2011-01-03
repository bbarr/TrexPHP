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
		return ($id) ? $_SESSION['users'][$id] : $_SESSION['users'];
	}
}

?>