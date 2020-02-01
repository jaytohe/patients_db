<?php

session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}


if ((isset($_GET['id']))&& (is_numeric($_GET['id']))) { //runs only if GET request AND ID is given in url that is numeric.

$page = isset($_GET['page']) ? $_GET['page']: 1;
$limit = isset($_GET['limit']) ? $_GET['limit']: 10;
$id= $_GET['id'];  //get id from url

require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Modify.php');
$modvisit = new Modify();

$visits = Array(); //defines empty array.
$queries = ["SELECT COUNT(*) as num FROM visits WHERE client_id=(?)", "SELECT visit_id,date,diagnosis FROM visits WHERE client_id=(?) ORDER BY date ASC", "SELECT first_name,last_name FROM clients WHERE client_id=(?)"];
for ($i=0; $i<3; $i++){
	$out = $modvisit->add($queries[$i], 'i', $id,1);
	if ($i==1) {
		while ($row = $out->fetch_row()) {
			array_push($visits, $row); //we will treat the array as an (array/stack) of arrays.
		}	
	} else {
	$result = $out->fetch_assoc();
	if ($i==0) {
	$count = $result['num'];
	}
	$result = $modvisit->htmlarrayescape($result); //prevent XSS injection.
	} 
}
if ($count == 0) {
	$number_of_records = 0;
} else {
	$number_of_records=$count;
}
require($_SERVER['DOCUMENT_ROOT'].'/utility/pages_calc.php');
$list = str_replace('?page=', '?id='.$id.'&page=', $list);
//print_r($visits); DEBUG OUTPUT.
}
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
	<section class="hero">
	<div class="hero-body">
	<div class="container">
	<nav class="breadcrumb" aria-label="breadcrumbs">
	<p class="is-pulled-right is-relative"><a class="button is-primary is-rounded" href="/visit.php?cid=<?=$id?>"><i class="far fa-calendar-plus"></i>&nbsp;Add Visit</a></p>
  <ul>
    <li><a href="/index.php">Home</a></li>
    <li><a href="/info.php?id=<?=$id?>" ><?=$result['first_name']?>&nbsp;<?=$result['last_name']?></a></li>
	<li class="is-active"><a href="#" aria-current="page">Visits</a></li>
  </ul>
</nav>
</div>
<section class="section">
<div class="container">
<nav class="level">
	<div class="level-left">
		<div class="level-item">
			<?php echo $list;?>
		</div>
		<div class="level-item">
			<button class="button is-warning" id="SendServBtn" style="display:none;">Commit</button> 
    </div>
	</div>
	<div class="level-right">
		<div class="level-item">
			<h2 class="subtitle">Records/page :&nbsp;</h2>
			<div class="dropdown is-up is-hoverable">
			<div class="dropdown-trigger">
			<button class="button" aria-haspopup="true" aria-controls="dropdown-menu7">
			<span><?=$limit;?></span>
			<span class="icon is-small">
			<i class="fas fa-angle-up" aria-hidden="true"></i>
			</span>
			</button>
			</div>
			<div class="dropdown-menu" id="dropdown-menu7" role="menu">
			<div class="dropdown-content">
			<a class="dropdown-item" href="/visits.php?page=<?=$page;?>&limit=100&id=<?=$id?>">
				<p>100</p>
			</a>
			<a class="dropdown-item" href="/visits.php?page=<?=$page;?>&limit=50&id=<?=$id?>">
				<p>50</p>
			</a>
			<a class="dropdown-item" href="/visits.php?page=<?=$page;?>&limit=20&id=<?=$id?>">
				<p>20</p>
			</a>
			<a class="dropdown-item" href="/visits.php?page=<?=$page;?>&limit=10&id=<?=$id?>">
				<p>10</p>
			</a>
			</div>
			</div>
			</div>
		</div>
		<div class="level-item">
			<button class="button is-info" id="test" onclick="OnDelete(this)">Delete Mode: Off</button>
		</div>
		</div>
</nav>
<table class="table is-bordered is-hoverable is-fullwidth" id="patients_tb">
	<thead>
	<colgroup>
		<col width="50%">
		<col width="60%">
	</colgroup>
	</thead>
	<tr>
		<th>Date</th>
		<th>Diagnosis</th>
	</tr>
	<tbody>
	<?php if ($number_of_records != 0 ) { 
			while ($row = array_pop($visits)) { 
				$row = Modify::htmlarrayescape($row); //prevent XSS injection.
	?>
	<tr>
		<td><a href="/visit.php?id=<?=$row[0]?>"><?=str_replace("-","/",Modify::dateconv($row[1]))?></a><input type="hidden" value="<?=$row[0]?>" name="visit_id"></td>
		<td><a href="/visit.php?id=<?=$row[0]?>"><?=$row[2]?></a></td>
	</tr>
	<?php  } } else { ?>
	<tr>
	<td colspan="2"><center><p>Hmm. It's very lonely here!</p></center></td>
	<?php }; ?>
	</tr>
	</tbody>
</table>

</div>
</div>
</section>
</div>
</section>
<!-- Burger Menu if mobile device -->
<script src="/js/nav_burger.js"></script>

<script>
var csrftoken =<?php echo "'".$_SESSION['token']."'" ?>;
var choosetable = "1";
var set_mode = 1;
</script>
<script src="/js/index/delete_entries.js"></script>
</body>
</html>


