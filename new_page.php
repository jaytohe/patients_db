<?php

session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}



if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['k39btn'])) ) { 
$query = "INSERT INTO clients VALUES(DEFAULT, (?), (?), (?), (?), (?), (?), (?))";

	if(($_POST['dt1'] || $_POST['dt2'] || $_POST['dt3']) == "" ) { //Server side additional check to ensure data integrity.
		echo "Required fields haven't been filled out."; 
		exit();
	} 
	else {
		require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Modify.php');
		$modclient = new Modify();
		$query1 = "INSERT INTO clients VALUES(DEFAULT, (?), (?), (?), (?), (?), (?), (?))";
		$params = [];
		for ($i=1; $i<8; $i++) {
			$params[] = $_POST['dt'.$i];
		}
		$params[3]= $modclient->dateconv($params[3]);
		$id = $modclient->add($query1,'sssssss',$params,2);
		$query2 = "INSERT INTO phones VALUES(".$id.",(?),(?))";
		$modclient->phones($query2,'ss');
		header("Location: /index.php");
	}
	
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="/css/bulma.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
	<script src="/js/jquery.repeatable.js"></script>
 </head>
<body>
	<nav class="navbar is-danger">
	<div class="container">
		<div class="navbar-brand">
		<a class="navbar-item" href="/index.php">Patients DB</a>
		<a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="menyoo">
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
		</a>
	</div>
	<div id="menyoo" class="navbar-menu">
		<div class="navbar-end">
			<a href="#" class="navbar-item" onclick="alert('Coming soon.');"><i class="fas fa-shield-alt"></i>&nbsp;Security</a>
			<a href="#" class="navbar-item" onclick="alert('Coming soon.');"><i class="far fa-question-circle"></i>&nbsp;Help</a>
			<a href="/logout.php" class="navbar-item"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</a>
		</div>
	</div>
	</div>
	</nav>
	<section class="hero is-white is-fullheight">
	<div class="hero-body">
	<div class="container">
	<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="/index.php">Home</a></li>
    <li class="is-active"><a href="/new.php" aria-current="page">New Entry</a></li>
  </ul>
</nav>
<form class="form-horizontal" id="leform" action="" method="post">
<fieldset>

<!-- Form Name -->
<legend></legend>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt1">Name<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt1" name="dt1" type="text" placeholder="Enter name:" class="input " required>
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt2">Surname<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt2" name="dt2" type="text" placeholder="Enter surname:" class="input " required>
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt4">Date of Birth<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt4" name="dt4" type="text" placeholder="DD/MM/YYYY" class="input " required>
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt5">Address</label>
  <div class="control">
    <input id="dt5" name="dt5" type="text" placeholder="Enter a valid address" class="input ">
    
  </div>
</div>

<fieldset class="phone_nums">
<label class="label" for="repeatable" style="display:inline-block;">Phone Number(s)<p style="color: red; display: inline-block;">*</p>&nbsp;</label>
<input type="button" class="add" value="+1" style="display:inline-block;">
		<div class="repeatable"></div>
</fieldset>
	<script type="text/template" id="phone_nums">
				<div class="field-group row">
					<input name="phone_nums[{?}][task]" id="task_{?}" "type="text" placeholder="Eg. +326743207899" class="input" required>
					<input name="phone_nums[{?}][owner]" id="owner_{?}" type="text" placeholder="(Optional) Whose number is this?" class="input ">
					<p>&nbsp;</p>
					<button class="delete" value="Remove" />
				</div>
	</script>
<!-- Text input-->
<div class="field">
  <label class="label" for="dt3">Diagnosis<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt3" name="dt3" type="text" placeholder="Enter diagnosis: " class="input " required>
    
  </div>
</div>

<!-- Textarea -->
<div class="field">
  <label class="label" for="dt6">Medical History</label>
  <div class="control">                     
    <textarea class="textarea" id="dt6" name="dt6"></textarea>
  </div>
</div>

<!-- Textarea -->
<div class="field">
  <label class="label" for="dt7">Family History</label>
  <div class="control">                     
    <textarea class="textarea" id="dt7" name="dt7"></textarea>
  </div>
</div>

<!-- Button -->
<div class="field">
  <div class="control">
    <button type="submit" id="k39btn" name="k39btn" class="button is-primary">Add Patient</button>
  </div>
</div>

</fieldset>
</form>
	</div>
	</div>
	</section>
</body>
<script>
 $(function(){
	 //Repeat phone number fields -- Repeatable.js script
	 $(".phone_nums .repeatable").repeatable({
	 addTrigger: ".phone_nums .add",
	 deleteTrigger: ".phone_nums .delete",
	 template: "#phone_nums",
	 min: 1,
	 max: 10
	});
	//On Submit Function. --Checks Validity of data input.
	$("#leform").submit(function( event ) {
		console.log("Processing Submit...");
		// Declare Variables
		var regex_date = /^([1-2][0-9]|(3)[0-1]|[1-9]|(0)[1-9])(\/)(((0)[1-9])|((1)[0-2])|[0-9])(\/)\d{4}$/;
		var date = $("#dt4").val(); //get date value
		var regex_phone = /^([(+]*[0-9]+[()+. -]*)$/; //same regex we use in search.php
		
		
		//Check if date is in correct format (DD/MM/YYYY) with regex.
		if(regex_date.test(date) == false) {
			console.log(date);
			alert("Invalid date. Correct format : DD/MM/YYYY"+"\nExample:  09/06/2019 or 9/6/2019");
			event.preventDefault();
		} else {console.log("Date is good.");}
		
		//Check if phone numbers match regex.
		$("[id^=task_]").each(function(index, elem) { //we use jquery's each() to iterate through multiple phone numbers.
			var phone = $(elem).val();
			console.log(phone);
			if((phone.length < 4) || !(phone).match(regex_phone)) {
				alert("Phone number "+(index+1)+" is not correct."+"\n"+"Please check the format or the length of the number.");
				event.preventDefault();
			} else {console.log("Phone number is good.");}
		});
	});
});
</script>
</html>