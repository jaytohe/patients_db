<?php

session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}

//If user logged in following code runs.
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Connect.php');
$conn =Connect::getInstance()->getConnection();
$page = isset($_GET['page']) ? $_GET['page']: 1;
$limit = isset($_GET['limit']) ? $_GET['limit']: 10;
$number_of_records = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS num FROM clients"))['num']; //not safe for error handling. Works for now, will change it in another release.
require($_SERVER['DOCUMENT_ROOT'].'/utility/pages_calc.php');
$query="SELECT first_name,last_name,client_id FROM clients ORDER BY client_id DESC LIMIT ".$first_record.", ".$limit; //$limit $page must be sanitised to prevent mysql injection.
$arr = mysqli_query($conn, $query); //We will fix it in post.


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
			<a href="#" class="navbar-item" onclick="alert('Coming soon.');"><i class="fas fa-shield-alt"></i>&nbsp;Security</a>
			<a href="#" class="navbar-item" onclick="alert('Coming soon.');"><i class="far fa-question-circle"></i>&nbsp;Help</a>
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
<?php 	while($row = mysqli_fetch_assoc($arr)) { // for every row in arr, echo the following?> 
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


<script>
$(document).ready(function() {
		$('input:radio[name=table_choice][value=1]').prop("checked", true);
		//$('#SendServBtn').hide();
		console.log("DOM is ready.");
		var first_name = '';
		var last_name = '';
		var phone = '';
		var diagosis ='';
		var options = {
		url: function (phrase) {
			phrase = encodeURIComponent(phrase);
			console.log(phrase);
			return '/search.php?query=' + phrase;
			
		},
		getValue: function (element) { first_name = $(element).prop("first_name"); last_name=$(element).prop("last_name"); phone=$(element).prop("phone"); return first_name+" "+last_name;},
		//minCharNumber: 3,
		list: {
			maxNumberOfElements: 5,
			showAnimation: {
				type: "normal",
				time: 500,
				callback: function() {}
			},
			hideAnimation: {
				type: "fade",
				time: 400,
				callback: function() {}
			},
			onClickEvent: function() {
				var val = $("#data-remote").getSelectedItemData().client_id;
				window.location.href = 'info.php?id='+val;
			}
		},
		template: {
			type: "custom",
			method: function(value,item) {
				return "<p>Full Name:"+first_name+" "+last_name+"</p><p>Phone :"+" "+phone+"</p>";
			}
		}
		};
		$('#data-remote').easyAutocomplete(options);
		$('input:radio[name=table_choice]').change(function() {
		
        if (this.value == '2') {
		console.log("hey");
        var modeoptions = {
		url: function (phrase) {
			phrase = encodeURIComponent(phrase);
			return '/search.php?query=' + phrase + '&mode=1';
			
		},
		getValue: function (element) { first_name=$(element).prop("first_name"); last_name=$(element).prop("last_name"); diagnosis=$(element).prop("diagnosis"); return first_name+" "+last_name;},
		requestDelay: 500,
		list: {
			maxNumberOfElements: 5,
			showAnimation: {
				type: "normal",
				time: 500,
				callback: function() {}
			},
			hideAnimation: {
				type: "fade",
				time: 400,
				callback: function() {}
			},
			onClickEvent: function() {
				var val = $("#data-remote").getSelectedItemData().visit_id;
				window.location.href = 'visit.php?id='+val;
			}
		},
		template: {
			type: "custom",
			method: function(value,item) {
				return "<p>Full Name :"+ " " +first_name+ " " +last_name+"</p><p>Diagnosis :"+" "+diagnosis+"</p><p>Date :"+" "+item.date+"</p>";
			}
		}
		}
		} else {
		modeoptions = options;
		}
		$('#data-remote').easyAutocomplete(modeoptions);
    });
	//Commit function.
	$('#SendServBtn').click(function() {
		var idee = '';
		var arr_ids = [];
		$('.chickfilla').each(function() { 
			if ($(this).is(':checked') === true) {
				idee = $(this).attr('id');
				idee = idee.replace("chickfilla_", "");
				arr_ids.push(idee);
			}
		});
		if (Array.isArray(arr_ids) && arr_ids.length) {
			if(confirm("Warning! DANGEROUS ACTION! Are you sure you want to delete IDs: "+arr_ids.toString()+" ?")) {
			$.ajax({ 
				url: '/classes/Delete.php',
				type: 'POST',
				data: {table: "0", ids_to_delete : arr_ids},
				dataType : 'JSON',
				success: function(response) {
					alert("The following IDs have been completely removed."+"\n"+response.id);
					window.location.href = window.location.href;
				}
			
			});
			}
		}
		
	});

});
var already_clicked =0;
function OnDelete(elem) {
	
	if (already_clicked ==0) {
	elem.classList.replace('is-info', 'is-warning');
	elem.innerHTML = 'Delete Mode: On';
	alert("You can now remove entries from the database. Proceed with caution!");
	jQuery('#patients_tb td:nth-child(1) a').each(function(key, val) {
		jQuery(val).css('display', 'inline-block');
		var id="chickfilla_";
		id += jQuery(val).text();
		jQuery(val).after("<input type='checkbox' class='chickfilla' style='position: relative; display: inline-block; left: 10px;' id="+id+">");
	});
	jQuery('#SendServBtn').removeAttr("style");
	already_clicked = 1;
	} else {
	jQuery('.chickfilla').remove();
	elem.classList.replace('is-warning', 'is-info');
	elem.innerHTML = 'Delete Mode: Off';
	jQuery('#patients_tb td:nth-child(1) a').each(function(key,val) {
		jQuery(val).css('display', 'block');
	});
	jQuery('#SendServBtn').css("display", "none");
	already_clicked = 0;
	}
}
(function() {
  var burger = document.querySelector('.burger');
  var nav = document.querySelector('#'+burger.dataset.target);
 
  burger.addEventListener('click', function(){
    burger.classList.toggle('is-active');
    nav.classList.toggle('is-active');
  });
})();
</script>
</html>

