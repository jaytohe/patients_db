<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else if (isset($_SESSION['usr_id']) && isset($_SESSION['username'])) {header("Location: /index.php"); exit;}
if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['submit'])) ) {
	try {
		$conn = new mysqli("localhost", "root", "mysql", "crm_users"); //Change credentials here.
	    } catch (Exception $e) {
			echo $e->getMessage();
			exit();
		}
	$usr = $_POST['username'];
	$pass = $_POST['password'];
	if (empty($usr) || empty($pass)) {
		header("Location: /login/error.php");
		exit;
	} else {
		$usr = mysqli_real_escape_string($conn, $usr);
		$usrfail = rawurlencode($usr);
		$pass = mysqli_real_escape_string($conn, $pass);
		$pass = hash("sha256", $pass);
		$query = "SELECT * FROM users WHERE username=? AND password=?";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			header("Location: /login/error.php?u=$usrfail&conn=0");
			exit;
		} else {
			mysqli_stmt_bind_param($stmt, "ss", $usr, $pass);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if ($check = mysqli_fetch_array($result)) {
					session_start();
					$_SESSION['usr_id']=$check['usr_id'];
					$_SESSION['username']=$check['username'];
					//ANTI-CSRF
					$length = 32;
					$_SESSION['token'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
					header("Location: /index.php");
			} else {
				header("Location: /login/error.php?u=$usrfail");
			}
		}
	}
} //else {
	//header("Location: /login.php");
	//exit;
//}
?>
