<?php
/*
* Mysql database class - only one connection alowed
*/
class Connect {
	private $_conn;
	private static $_instance; // Change credentials here.
	private $_srv = "localhost";
	private $_dbusr = "root";
	private $_dbpass = "mysql";
	private $_dbname = "patients_myderm";
	/*
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	// Constructor
	private function __construct() {
		//mysqli_report(MYSQLI_REPORT_ALL);
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		try {
		$this->_conn = new mysqli($this->_srv, $this->_dbusr, 
			$this->_dbpass, $this->_dbname);
	    } catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }
	// Get mysqli connection
	public function getConnection() {
		return $this->_conn;
	}
}
?>
