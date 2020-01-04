<?php
if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color'])){
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Connect.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Modify.php');
$conn = Connect::getInstance()->getConnection();
$query ='INSERT INTO events VALUES (DEFAULT, (?), (?), (?), (?), (?))';

function strip_time($datestr) {
	$arr = explode(" ",$datestr);
	return Modify::dateconv($arr[0])." ".$arr[1];
}
try {
$stmt = $conn->prepare($query);
$stmt->bind_param('sssss', $title, $description, $color, $start, $end);
} catch (Exception $e) {exit($e->getMessage());}

$title = $_POST['title'];
$description = $_POST['description'];
$color = $_POST['color'];
$start = strip_time($_POST['start']);
$end = strip_time($_POST['end']);

if(!$stmt->execute()) {echo "Execute failed: (" . $query->errno . ") " . $query->error; exit(); }
header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>