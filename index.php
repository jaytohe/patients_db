<?php

session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}

//If user logged in following code runs.
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Connect.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Modify.php');
$conn =Connect::getInstance()->getConnection();
$page = isset($_GET['page']) ? $_GET['page']: 1;
$limit = isset($_GET['limit']) ? $_GET['limit']: 10;
$number_of_records = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS num FROM clients"))['num'];
require($_SERVER['DOCUMENT_ROOT'].'/utility/pages_calc.php');
$query="SELECT first_name,last_name,client_id FROM clients ORDER BY client_id DESC LIMIT ?,?";
$modindex = new Modify();
$arr = $modindex->add($query, "ii", array($first_record, $limit),1);


?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
	<link href="/css/easy-autocomplete.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/css/bulma.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
	<script src="/js/jquery.easy-autocomplete.min.js" type="text/javascript"></script>
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
	<br><br>
	<section class="hero">
		<div class="hero-body">
	<div class="container">
	<nav class="level">
	<div class="level-item has-text-centered">
	<a href="/new_page.php" class="button is-medium is-primary"><i class="fas fa-plus-circle"></i>&nbsp;Add Patient</a>
	</div>
	<div class="level-item has-text-centered">
	<a href="/agenda/" class="button is-medium is-danger"><i class="far fa-address-book"></i>&nbsp;My Agenda</a>
	</div>
	</nav>
	</div>
<section class="section">
<div class="container">
<div class="columns is-centered">
<div class="column has-text-centered">
<div class="control">
<label class="radio">
<input type="radio" name="table_choice" value="1">
Clients</label>
<label class="radio">
<input type="radio" name="table_choice" value="2">
Visits</label>
</div>
<div class="field has-addons">
    <div class="control has-icons-right is-expanded">
        <input type="text" class="input" id="data-remote" placeholder="What are you looking for?">
    </div>
    <p class="control">
        <a class="button is-danger"><i class="fas fa-search"></i></a>
    </p>
</div>
<!--<h2>Total number of entries: <?php// echo $total; ?></h2>-->
<br>
<br>
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
			<div class="dropdown is-up is-hoverable">
				<div class="dropdown-trigger">
					<h2 class="subtitle">Records/page :&nbsp;</h2>
				</div>
				<div class="dropdown-menu">
					<div class="dropdown-content">
						<p>Total Number of Records:</p>
						<p><?=$number_of_records?></p>
					</div>
				</div>
			</div>
			
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
			<a class="dropdown-item" href="/index.php?page=<?=$page;?>&limit=100">
				<p>100</p>
			</a>
			<a class="dropdown-item" href="/index.php?page=<?=$page;?>&limit=50">
				<p>50</p>
			</a>
			<a class="dropdown-item" href="/index.php?page=<?=$page;?>&limit=20">
				<p>20</p>
			</a>
			<a class="dropdown-item" href="/index.php?page=<?=$page;?>&limit=10">
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
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Surname</th>
		<!--<th>Actions</th>-->
	</tr>
	<tbody>
<?php 	
	while($row = mysqli_fetch_assoc($arr)) {  //for every row fetched from db
	$row = Modify::htmlarrayescape($row); //escape special html chars in row to prevent XSS
	//Print client , first name , last name present in escaped row.
?>
	<tr>
		<td><a href="/info.php?id=<?=$row['client_id']?>"><?=$row['client_id']?></a></td> 
		<td><a href="/info.php?id=<?=$row['client_id']?>"><?=$row['first_name']?></a></td>
		<td><a href="/info.php?id=<?=$row['client_id']?>"><?=$row['last_name']?></a></td>
	</tr>
<?php }; ?>
	</tbody>
</table>
</div>
</div>
</div>
</section>
</div>
</section>
</body>

<!-- Burger Menu if mobile device -->
<script src="/js/nav_burger.js"></script>

<!-- Import easyAutocomplete Search Settings -->
<script src="/js/index/autocomplete_setup.js"></script>

<!-- Declare csrf token used in delete_entries -->
<script>
var choosetable = "0";
var csrftoken =<?php echo "'".$_SESSION['token']."'" ?>;
var set_mode = 0;
</script>

<!-- Batch Delete-->
<script src="/js/index/delete_entries.js"></script>
</html>

