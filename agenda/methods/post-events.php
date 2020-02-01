<?php
session_start();

if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}

if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color'])){
	
if( (!isset($_POST['token'])) || ($_POST['token'] != $_SESSION['token'])) { //prevent CSRF
	exit("CSRF Detected.");
}

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