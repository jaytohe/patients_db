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
		$pass = mysqli_real_escape_string($conn, $pass);
		$pass = hash("sha256", $pass);
		$query = "SELECT * FROM users WHERE username=? AND password=?";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			$_SESSION['err']="A MySQL error has occured. Please contact your administrator.";
			header("Location: /login/error.php?u=$usr&conn=0");
			exit;
		} else {
			mysqli_stmt_bind_param($stmt, "ss", $usr, $pass);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if ($check = mysqli_fetch_array($result)) {
					session_start();
					$_SESSION['usr_id']=$check['usr_id'];
					$_SESSION['username']=$check['username'];
					header("Location: /index.php");
			} else {
				$_SESSION['err']="The username and password that you entered did not match our records. Please double-check and try again.";
				header("Location: /login/error.php?u=$usr");
			}
		}
	}
} //else {
	//header("Location: /login.php");
	//exit;
//}
?>
