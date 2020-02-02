<?php require 'index_int.php';?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>patients_db 2.0</title>
    <link rel="stylesheet" href="/css/bulma.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  </head>
  
<body>
<section class="hero is-white is-fullheight">
<div class="hero-body">
<div class="container has-text-centered">
<div class="column is-4 is-offset-4">
<div class="box">
<figure class="avatar">
<img src="/css/logo.png">
</figure>
<form class="box" action="" method="post">

<div class="field">
  <p class="control has-icons-left has-icons-right">
	<?php if(isset($usrparam)) { ?>
	<input class="input" type="text" name="username" placeholder="Username" value="<?=$usrparam?>">
	<?php } else { ?>
	<input class="input" type="text" name="username" placeholder="Username">
	<?php } ?>
    <span class="icon is-small is-left">
      <i class="fas fa-user"></i>
    </span>
  </p>
</div>
<div class="field">
  <p class="control has-icons-left">
    <input class="input" type="password" name="password" placeholder="Password">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>
<div class="field">
  <p class="control">
    <button type="submit" name="submit" class="button is-rounded" style="background-color: #e60086; color: white;">Login</button>
  </p>
</div>
</form>
</div>
<?php if(isset($nag_error1)) { ?>
<div class="box" style="background-color: red;">
	<p style="color: white; font-weight: bold;"> <?=$nag_error1?> </p>
	<p style="color: white; font-weight: bold;"> <?=$nag_error2?> </p>
<?php } ?>
</div>
</body>
</div>
</div>
</div>
</div>
<p style="position: absolute; bottom: 0; right: 0; color: gray;">Release 2.0 | Feb 2, 2020</p>
</section>
</html>