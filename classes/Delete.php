<?php

session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}
if( (!isset($_POST['token'])) || ($_POST['token'] != $_SESSION['token'])) {
	exit("CSRF Detected.");
}
if(isset($_POST['table'])) {
$table =  $_POST['table']; // 0 means delete everything related to that client_id from the db. 1 means delete visits for that client.
require_once 'Modify.php';
$mod_delete = new Modify();
$q = ["DELETE FROM clients WHERE client_id=(?)", "DELETE FROM phones WHERE client_id=(?)", "DELETE FROM visits WHERE visit_id= (?)", "DELETE FROM visits WHERE client_id = (?)"];
$ids = $_POST['ids_to_delete'];
if (empty($ids)) {exit("Invalid ID.");};
$return_arr = array('id' => implode(",",$ids));
switch ($table) {
	case 0:
		foreach ($ids as $id_to_be_killed) {
			$mod_delete->add($q[0], 'i', $id_to_be_killed);
			$mod_delete->add($q[1], 'i', $id_to_be_killed);
		}
		echo json_encode($return_arr);
		break;
	case 1:
		foreach ($ids as $id_to_be_killed) {
			$mod_delete->add($q[2], 'i', $id_to_be_killed);
		}
		echo json_encode($return_arr);
		break;
}
};

?>