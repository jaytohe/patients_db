<?php
session_start();
$nag_error1="Username or password is wrong.";
$nag_error2="Please double-check and try again.";
if(isset($_GET['u'])) {
$usrparam=rawurldecode($_GET['u']);
$usrparam = stripcslashes(htmlspecialchars($usrparam));
}
if(isset($_GET['conn'])) {
	$nag_error1="A MySQL error has occured.";
	$na_error2="Please contact your administrator.";
}
include 'index.php';
?>