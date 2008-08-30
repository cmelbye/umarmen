<?php
class Hooks_Exception extends Exception {}

class Hooks {
	
	public static function get_instance() {
		if( !is_object( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public static function add($command_name, $module_name) {
		$datastore = DataStore::get_instance();
		try {
			$datastore->put('hook', $command_name, $module_name);
		} catch(DataStore_Exception $e) {
			throw new Hooks_Exception('Unable to store new hook');
		}
	}
	
	public static function get($command_name) {
		$datastore = DataStore::get_instance();
		try {
			$module_name = $datastore->get('hook', $command_name, true);
		} catch(DataStore_DoesNotExist_Exception $e) {
			return false;
		}
		return $module_name;
	}
	
	public static function remove($command_name) {
		$datastore = DataStore::get_instance();
		try {
			$datastore->delete('hook', $command_name);
		} catch(DataStore_Exception $e) {
			throw new Hooks_Exception('Unable to remove hook');
		}
	}
	
	public static function listHooks() {
		$datastore = DataStore::get_instance();
		return self::$hooks;
	}
	
}
?>