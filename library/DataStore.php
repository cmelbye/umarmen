<?php
/**
 * DataStore Class
 * Allows module to store persistent data
 *
 * @author Charles Melbye
 * @version $Id$
 * @copyright Charlie Melbye, 18 August, 2008
 * @package default
 **/

/**
 * Define DocBlock
 **/

class DataStore_Exception extends Exception {}
class DataStore_AlreadyExists_Exception extends Exception {}
class DataStore_DoesNotExist_Exception extends Exception {}

class DataStore {
	
	public $data = array();
	
	protected static $_instance = null;
	
	public static function get_instance() {
		if( !is_object( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function get($application, $key, $needs_to_exist = false) {
		$this->_getData($application);
		if(!isset($this->data[$application][$key]) && $needs_to_exist) {
			throw new DataStore_DoesNotExist_Exception($application . '::' . $key . ' does not exist');
		} else {
			return $this->data[$application][$key];
		}
	}
	
	public function getAll($application, $needs_to_exist = false) {
		$this->_getData($application);
		if(!isset($this->data[$application]) && $needs_to_exist) {
			throw new DataStore_DoesNotExist_Exception('The ' . $application . ' does not exist');
		} else {
			return $this->data[$application];
		}
	}
	
	public function put($application, $key, $value, $needs_to_exist = false, $cant_exist_already = false) {
		$this->_getData($application);
		if($needs_to_exist && !isset($this->data[$application][$key])) {
			throw new DataStore_DoesNotExist_Exception($application . '::' . $key . ' does not exist');
		} else if($cant_exist_already && isset($this->data[$application][$key])) {
			throw new DataStore_AlreadyExists_Exception($application . '::' . $key . ' already exists');
		} else {
			$this->data[$application][$key] = $value;
		}
		$this->_flushData($application);
		return true;
	}
	
	public function delete($application, $key, $needs_to_exist = false) {
		$this->_getData($application);
		if($needs_to_exist && !isset($this->data[$application][$key])) {
			throw new DataStore_DoesNotExist_Exception($application . '::' . $key . ' does not exist');
		} else {
			unset($this->data[$application][$key]);
		}
		$this->_flushData($application);
		return true;
	}
	
	private function _getData($application) {
		$dataFile = './data/' . $application . '.dat';
		if(!file_exists($dataFile)) {
			$dataArray = array();
		} else {
			$data = file_get_contents($dataFile);
			$dataArray = unserialize($data);
		}
		$this->data[$application] = $dataArray;
		return true;
	}
	
	private function _flushData($application) {
		$dataFile = './data/' . $application . '.dat';
		$dataArray = serialize($this->data[$application]);
		return file_put_contents($dataFile, $dataArray);
	}
}
?>