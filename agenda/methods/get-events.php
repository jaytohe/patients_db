<?php
session_start();

if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}
if (isset($_GET['start']) && isset($_GET['end'])) {

require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Modify.php');
function strip_time($datestr) {
	try {
		$dtconv = new DateTime($datestr);
	} catch (Exception $e) {exit($e->getMessage());}
	return $dtconv->format("Y-m-d");
}
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Connect.php');
$conn = Connect::getInstance()->getConnection();
$query = "SELECT * FROM events WHERE start >= (?) AND end <= (?)";
try {
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $range_start, $range_end);
} catch (Exception $e) {exit($e->getMessage());}
$range_start = strip_time($_GET['start']);
$range_end = strip_time($_GET['end']);
if(!$stmt->execute()) {echo "Execute failed: (" . $query->errno . ") " . $query->error; exit(); }

$result = $stmt->get_result();
$output_arrays = array();
while($row = $result->fetch_assoc()) { //same technique as that on search.php
	$temp_array = Modify::htmlarrayescape($row);
	$new_obj = (object) $temp_array;
	array_push($output_arrays,$new_obj);
}

// Send JSON to the client.
echo json_encode($output_arrays, JSON_UNESCAPED_UNICODE);
}