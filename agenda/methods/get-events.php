<?php

if (isset($_GET['start']) && isset($_GET['end'])) {


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
while($row = $result->fetch_object()) {
	array_push($output_arrays,$row);
}

// Send JSON to the client.
echo json_encode($output_arrays, JSON_UNESCAPED_UNICODE);
}