<?php
session_start();
if(isset($_GET['u'])) {
$usr=$_GET['u'];
}
include 'index.php';
if(isset($_SESSION['err'])) { //Login error handling not implemented yet.
}
?>