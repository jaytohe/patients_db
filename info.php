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
$result = $modinfo->htmlarrayescape($result); //prevent XSS injection.
$result['birth_date'] = $modinfo->dateconv($result['birth_date']);
$result['birth_date'] = str_replace("-","/",$result['birth_date']); //Convert - to / so global date format of patients_db (DD/MM/YYYY) is satisfied.
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

else if ( ($method == 'POST') && (isset($_POST['k39btn'])) ) { //this runs when users has filled out all necessary* forms.
	if(($_POST['dt1'] || $_POST['dt2'] || $_POST['dt3']) == "" ) {
		echo "Required fields haven't been filled out."; //ONLY FOR DEBUGGING. MUST CHANGE ERROR HANDLING.
		exit();
	}
	if( (!isset($_POST['token'])) || ($_POST['token'] != $_SESSION['token'])) { //prevent CSRF
	exit("CSRF Detected.");
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
    <title>Info</title>
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


<form class="form" id="leform" action="" method="post">
<fieldset>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt1">Name<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt1" name="dt1" type="text" value="<?=$result['first_name']?>" placeholder="Enter name:" class="input " maxlength="50" required>
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt2">Surname<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt2" name="dt2" type="text" value="<?=$result['last_name']?>" placeholder="Enter surname:" class="input " maxlength="80" required>
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt4">Date of Birth<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt4" name="dt4" type="text" value="<?=$result['birth_date']?>" placeholder="DD/MM/YY" class="input " required>
    
  </div>
</div>

<!-- Text input-->
<div class="field">
  <label class="label" for="dt5">Address</label>
  <div class="control">
    <input id="dt5" name="dt5" type="text" value="<?=$result['address']?>" placeholder="Enter a valid address" class="input " maxlength="80">
    
  </div>
</div>

<fieldset class="phone_nums">
<label class="label" for="repeatable" style="display:inline-block;">Phone Number(s)<p style="color: red; display: inline-block;">*</p>&nbsp;</label>
<input type="button" class="add" value="+1" style="display:inline-block;">
		<div class="repeatable"></div>
</fieldset>
	<script type="text/template" id="phone_nums">
				<div class="field-group row">
					<input name="phone_nums[{?}][task]" id="task_{?}" "type="text" placeholder="Eg. +326743207899" class="input " maxlength="80" required>
					<input name="phone_nums[{?}][owner]" id="owner_{?}" type="text" placeholder="(Optional) Whose number is this?" class="input " maxlength="80">
					<p>&nbsp;</p>
					<button class="delete" value="Remove">
				</div>
			</script>
<!-- Text input-->
<div class="field">
  <label class="label" for="dt3">Diagnosis<p style="color: red; display: inline-block;">*</p></label>
  <div class="control">
    <input id="dt3" name="dt3" type="text" value="<?=$result['diagnosis']?>" placeholder="Enter diagnosis: " class="input " maxlength="80" required>
    
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

<div class="field">
  <div class="control">                     
    <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
  </div>
</div>
<!-- Button -->
<div class="field">
  <div class="control">
    <button type="submit" id="k39btn" name="k39btn" class="button is-info">Update Record</button>
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
var min_phone = <?=$num_of_phones?>;
var phones = <?php echo json_encode($phones); ?>;
var owners = <?php echo json_encode($owners); ?>;
var csrftoken =<?php echo "'".$_SESSION['token']."'" ?>;
</script>
<script src="/js/nav_burger.js"></script>
<script src="/js/urlparameter.js"></script>
<script src="/js/new__info/repeatable_setup.js"></script>
<script src="/js/new__info/get_delete_info.js"></script>
</html>
