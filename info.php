<?php

session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}

/* We need the client_id in both GET and POST request. */
if (empty($_GET['id'])) {
	exit();
	} else if(!is_numeric($_GET['id'])) {exit();}; 
$id = $_GET['id'];
$refresh=0;
$method = $_SERVER['REQUEST_METHOD']; //GET REQUEST METHOD. DISTINGUISH BTWN GET AND POST.

/* START OF GET METHOD CODE */
if ($method == 'GET') {
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Modify.php');
$modinfo = new Modify();
$queries = ["SELECT * FROM clients WHERE client_id=(?)", "SELECT * FROM phones WHERE client_id=(?)"];
$out = $modinfo->add($queries[0],'i',array($id),1);
$result = $out->fetch_assoc();	//gets result array of query
	/* If result is empty, client doesn't exist => Stop execution */
if ($result == NULL) {
	echo "ID is invalid.";
	exit();
}
$result['birth_date'] = $modinfo->dateconv($result['birth_date']);

$res = $modinfo->add($queries[1], 'i', array($id),1);
$num_of_phones=0;

// We need to declare the phones_table, phones and owners variables cuz they may be emty i.e. while loop below won't be executed.
// When we reach a stable release of the crm, phone numbers will be a required field so it cannot be null.
$phones_table = $phones = $owners = Array();
while ($row = $res->fetch_row()) {
	$phones_table[] = $row;
	$num_of_phones=$num_of_phones+1;
}
foreach ($phones_table as $key) {
	$phones[] = $key[1];
	$owners[] = $key[2];
}
}
/* END OF GET METHOD CODE  */

/* START OF POST METHOD CODE */

else if ( ($method == 'POST') && (isset($_POST['submit'])) ) { //this runs when users has filled out all necessary* forms.
	if(($_POST['dt1'] || $_POST['dt2'] || $_POST['dt3']) == "" ) {
		echo "Required fields haven't been filled out."; //ONLY FOR DEBUGGING. MUST CHANGE ERROR HANDLING.
		exit();
	}
	else {
		require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Modify.php');
		$modconn = new Modify();
		$queries = ["UPDATE clients SET first_name=(?), last_name=(?), diagnosis=(?), birth_date=(?), address=(?), med_history=(?), fam_history=(?) WHERE client_id=(?)","DELETE FROM phones WHERE client_id=(?)", "INSERT INTO phones VALUES((?),(?),(?))"];
		$params = [];
		for ($i=1; $i<8; $i++) {
			$params[] = $_POST['dt'.$i];
		}
		$params[3]= $modconn->dateconv($params[3]);
		$params[]=$id;
		$modconn->add($queries[0],'sssssssi',$params);
		$modconn->add($queries[1],'i', array($id));
		$modconn->phones($queries[2],'iss', $id);
		$refresh =1; //Refresh page to show new content.
	}
}

/* END OF POST METHOD CODE */
?>
<!DOCTYPE HTML>
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
	<script src="/js/jquery.repeatable.js"></script>
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
	<p class="is-pulled-right is-relative"><a class="button is-success is" href="/visits.php?id=<?=$id?>"><i class="fas fa-door-open"></i>&nbsp;Visits</a></p>
  <ul>
    <li><a href="/index.php">Home</a></li>
    <li class="is-active"><a href="#" aria-current="page">I.D.&nbsp;<?=$result['client_id']?></a></li>
  </ul>
  
</nav>

  <!-- Right side -->
  <div class="container">
    
  </div>

<form class="form-horizontal" action="" method="post">
<fieldset>

<!-- Form Name -->
<legend></legend>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt1">Name</label>
  <div class="control">
    <input id="dt1" name="dt1" type="text" value="<?=$result['first_name']?>" placeholder="Enter name:" class="input " required="">
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt2">Surname</label>
  <div class="control">
    <input id="dt2" name="dt2" type="text" value="<?=$result['last_name']?>" placeholder="Enter surname:" class="input " required="">
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt4">Date of Birth</label>
  <div class="control">
    <input id="dt4" name="dt4" type="text" value="<?=$result['birth_date']?>" placeholder="DD/MM/YY" class="input ">
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt5">Address</label>
  <div class="control">
    <input id="dt5" name="dt5" type="text" value="<?=$result['address']?>" placeholder="Enter a valid address" class="input ">
    
  </div>
</div>

<fieldset class="phone_nums">
<label class="label" for="repeatable" style="display:inline-block;">Phone Number(s)&nbsp;</label>
<input type="button" class="add" value="+1" style="display:inline-block;" />
		<div class="repeatable"></div>
</fieldset>
	<script type="text/template" id="phone_nums">
				<div class="field-group row">
					<input name="phone_nums[{?}][task]" id="task_{?}" "type="text" placeholder="Eg. (+32)6743207899" class="input ">
					<input name="phone_nums[{?}][owner]" id="owner_{?}" type="text" placeholder="(Optional) Whose number is this?" class="input ">
					<p>&nbsp;</p>
					<button class="delete" value="Remove" />
				</div>
			</script>
			<!-- <input name="phone_nums[{?}][uniqaydee]" id="uniqaydee_{?}" type="hidden" value=""> -->
<!-- Text input-->
<div class="field">
  <label class="label" for="dt3">Diagnosis</label>
  <div class="control">
    <input id="dt3" name="dt3" type="text" value="<?=$result['diagnosis']?>" placeholder="Enter diagnosis: " class="input " required="">
    
  </div>
</div>

<!-- Textarea -->
<div class="field">
  <label class="label" for="dt6">Medical History</label>
  <div class="control">                     
    <textarea class="textarea" id="dt6" name="dt6" ><?=$result['med_history']?></textarea>
  </div>
</div>

<!-- Textarea -->
<div class="field">
  <label class="label" for="dt7">Family History</label>
  <div class="control">                     
    <textarea class="textarea" id="dt7" name="dt7"><?=$result['fam_history']?></textarea>
  </div>
</div>

<!-- Button -->
<div class="field">
  <label class="label" for="submit"></label>
  <div class="control">
    <button  d="submit" name="submit" class="button is-info">Update Record</button>
  </div>
</div>
</fieldset>
</form>
<br>
 <button id="delete_r" name="delete_r" class="button is-secondary is-danger is-light"> Remove Record</button>
	</div>
	</div>
	</section>
</body>
<script>
 $(function(){
	 $(".phone_nums .repeatable").repeatable({
	 addTrigger: ".phone_nums .add",
	 deleteTrigger: ".phone_nums .delete",
	 template: "#phone_nums",
	 min: <?=$num_of_phones?>,
	 max: 10
	});

var phones = <?php echo json_encode($phones); ?>;
var owners = <?php echo json_encode($owners); ?>;
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

$("input[id^='task_'").each(function(index, element) { 
$(element).val(phones[index]);
});
$("input[id^='owner_'").each(function(index, element) { 
$(element).val(owners[index]);
});
var client_id = getUrlParameter('id');
$('#delete_r').click(function() { 
var arr_id = [];
arr_id.push(client_id);
if (confirm("WARNING! THIS ACTION CANNOT BE UNDONE. Proceed?")) {
		$.ajax({ 
		url: '/classes/Delete.php',
		type: 'POST',
		data: {table: "0", ids_to_delete : arr_id},
		dataType : 'JSON',
		success: function(response) {
				alert("The following IDs have been completely removed."+"\n"+response.id);
				window.location.href = "/index.php";
		}
			
		});
}
});
});


</script>
</html>
