<?php

session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}


if (strpos($_SERVER['REQUEST_URI'], '?')) { // check if url has any parameter
	if (isset($_GET['id']) && (!isset($_GET['cid'])) ) {
		if(is_numeric($_GET['id']))  {
			 $state = 1; //run in edit mode.
			 $mode_string = "Edit";
			 $id = $_GET['id'];
		} else {
			exit(); //parameter given wrong.
		}
   
	} else if (!isset($_GET['id']) && (isset($_GET['cid'])) ) {
		if( (is_numeric($_GET['cid'])) )  {
			$state =0; //run in add visit mode.
			$cid = $_GET['cid'];
			$mode_string = "New";
		} else {
			exit(); //parameters are non-numeric.
		}
	} else {exit();}//irrelevant arguments given -> exit
} else {exit();} ;

require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Modify.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/HTML_Visits.php');
$modvisit = new Modify();
$vis = new HTML_Visits();
if($state ==1) {
	$knjm = $modvisit->add("SELECT * FROM visits WHERE visit_id=(?)", 'i', $id,1);
	$visit_data = $knjm->fetch_assoc();
	if ($visit_data == NULL) { //someone is screwing around with the url.
		exit("ID invalid.");
	}
	$cid=$visit_data['client_id']; //cid is not given in url when state=1 (edit mode). Therefore we set it from our mysql query.
	$vis->arr = $visit_data;
}
$outter = $modvisit->add("SELECT first_name,last_name FROM clients WHERE client_id=(?)",'i',$cid,1);
$result = $outter->fetch_assoc();

/* DATE CONVERSION */
$tmpdate = Modify::dateconv($vis->data_get('date'));
$globaldate='';
$globaldate = str_replace('-','/',$tmpdate);
/* --------------- */

$refresh = 0;
//post request means either add new visit or edit old visit. We distinguish between the two 'states' from the url. If only client_id in the url is given...
//...that means that user wants to add a new patient.
// If only visit_id is given that means that the user wants to update an old entry
if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['k39btn'])) ) {
	//print_r($_POST);
	//echo "State is: ".$state;
	$ledata = [];
	if ( ($_POST['dt1'] || $_POST['dt2']) == "" ) {
		exit("Required fields haven't been filled out.");
	} 
	else {
		
	if ($state==0) {
		$query = "INSERT INTO visits VALUES(DEFAULT,(?), (?), (?), (?), (?), (?), (?), (?), (?), (?))";
		$ledata [] = $cid;
		$bind = 'sssssssssb';
		$ledata [] = Modify::dateconv($_POST['dt1']);
		for ($i=2; $i<9; $i++) {
			$ledata [] = $_POST['dt'.$i];
		}
		$ledata [] = null;
		$modvisit->add($query,$bind,$ledata);
		header("Location: /visits.php?id=".$cid);
		
	} else {
		$query = "UPDATE visits SET date=(?),diagnosis=(?),notes=(?), present_symptom=(?), lab=(?), img_test=(?), histology=(?), treatment=(?), attach=(?) WHERE visit_id=(?)";
		$bind = 'ssssssssbi';
		$ledata [] = Modify::dateconv($_POST['dt1']);
		for ($i=2; $i<9; $i++) {
			$ledata [] = $_POST['dt'.$i];
		}
		$ledata [] = null;
		$ledata[] = $id; 
		$modvisit->add($query,$bind,$ledata);
		$refresh = 1;
	}
	
	
}

}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="/css/bulma.css">
	<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script>
	var refresh=<?=$refresh?>;
	if (refresh == 1) {
	window.location.href = window.location.href;
	}
	</script>
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
    <li><a href="/info.php?id=<?=$cid?>" ><?=$result['first_name']?>&nbsp;<?=$result['last_name']?></a></li>
	<li><a href="/visits.php?id=<?=$cid?>" aria-current="page">Visits</a></li>
	<li class="is-active"><a href="#"><?=$mode_string?> Entry</a></li>
  </ul>
</nav>
<form class="form-horizontal" id="leform" action="" method="post">
<fieldset>

<!-- Form Name -->
<legend></legend>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt1">Date<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt1" name="dt1" type="text" placeholder="DD/MM/YYYY" class="input " value="<?=$globaldate;?>" required=>
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt2">Diagnosis<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt2" name="dt2" type="text" class="input " value="<?=$vis->data_get('diagnosis'); ?>" required=>
    
  </div>
</div>
<!-- Text input-->
<div class="field">
  <label class="label" for="dt4">Present Symptom</label>
  <div class="control">
    <input id="dt4" name="dt4" type="text" value="<?=$vis->data_get('present_symptom'); ?>" class="input ">
    
  </div>
</div>

<!-- Textarea -->
<div class="field">
  <label class="label" for="dt3">Notes</label>
  <div class="control">                     
    <textarea class="textarea" id="dt3" name="dt3"><?=$vis->data_get('notes'); ?></textarea>
  </div>
</div>

<!-- Textarea -->
<div class="field">
  <label class="label" for="dt5">Lab</label>
  <div class="control">                     
    <textarea class="textarea" id="dt5" name="dt5" ><?=$vis->data_get('lab'); ?></textarea>
  </div>
</div>
<!-- Textarea -->
<div class="field">
  <label class="label" for="dt6">Imaging Tests</label>
  <div class="control">                     
    <textarea class="textarea" id="dt6" name="dt6"><?=$vis->data_get('img_test'); ?></textarea>
  </div>
</div>


<!-- Textarea -->
<div class="field">
  <label class="label" for="dt7">Histology</label>
  <div class="control">                     
    <textarea class="textarea" id="dt7" name="dt7"><?=$vis->data_get('histology'); ?></textarea>
  </div>
</div>
<!-- Textarea -->
<div class="field">
  <label class="label" for="dt8">Treatment</label>
  <div class="control">                     
    <textarea class="textarea" id="dt8" name="dt8"><?=$vis->data_get('treatment'); ?></textarea>
  </div>
</div>

<label class="label" for="dt9">Attachment</label>
<div class="file">
  <label class="file-label">
    <input class="file-input" type="file" name="dt9" onchange="if (this.files.length > 0) document.getElementById('filename-dt9').innerHTML = this.files[0].name;" disabled>
    <span class="file-cta">
      <span class="file-icon">
        <i class="fa fa-upload"></i>
      </span>
      <span class="file-label" id="filename-dt9">
        Choose a file…
      </span>
    </span>
  </label>
</div>

<!-- Button -->
<br>

<div class="field">
  <div class="control">                     
    <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
  </div>
</div>

<div class="field">
  <div class="control">
    <button type="submit" id="k39btn" name="k39btn" class="button is-info"><?=$mode_string?> Record</button>
  </div>
</div>

</fieldset>
</form>
 <button id="delete_r" name="delete_r" class="button is-secondary is-danger is-light"> Remove Record</button>
	</div>
	</div>
	</section>
<script>
 $(function(){
	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};
	var visit_id = getUrlParameter('id'); //Get client ID from url. Not good practice. In future we will fetch client_id in a hidden field without requiring JS.
	
	//Start of DELETE function on click of delete button.
	$('#delete_r').click(function() { 
		var arr_id = [];
		var csrftoken =<?php echo "'".$_SESSION['token']."'" ?>;
		arr_id.push(visit_id);
		if (confirm("WARNING! THIS ACTION CANNOT BE UNDONE. Proceed?")) {
		$.ajax({ 
		url: '/classes/Delete.php',
		type: 'POST',
		data: {table: "1", ids_to_delete : arr_id, token: csrftoken},
		dataType : 'JSON',
		success: function(response) {
				alert("The following IDs have been completely removed."+"\n"+response.id);
				window.location.href = '/index.php';
		}
			
		});
	}
	});
	
	//On Submit Function. --Checks Validity of date.
	$("#leform").submit(function( event ) {
		console.log("Processing Submit...");
		// Declare Variables
		var regex_date = /^([1-2][0-9]|(3)[0-1]|[1-9]|(0)[1-9])(\/)(((0)[1-9])|((1)[0-2])|[0-9])(\/)\d{4}$/;
		var date = $("#dt1").val(); //get date value
		
		//Check if date is in correct format (DD/MM/YYYY) with regex.
		if(regex_date.test(date) == false) {
			console.log(date);
			alert("Invalid date. Correct format : DD/MM/YYYY"+"\nExample:  09/06/2019 or 9/6/2019");
			event.preventDefault();
		} else {console.log("Date is good.");}
	
	//END OF SUBMIT FUNCTION
		});

});
</script>
</body>
</html>