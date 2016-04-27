<?php
include_once("php_includes/classDB.php");
date_default_timezone_set('Asia/Kolkata');
$objDb = new dbConx();
if($objDb->user_ok == false){
	echo 'Not Logged in!';
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="/css/customlogin.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
</head>
<body>
	<img src="/img/equilizer.gif" class="img-responsive" id="loading">
	<div class="pop-back"></div>
	<br />
	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1 mayai">
	<h3 class="text-center">Login to Update/Insert Interruption Reports per Feeder</h3>
	<form class="form-horizontal wathi" method="post">
		<div class="form-group">
		    <label for="username" class="col-sm-4 control-label">Username</label>
		    <div class="col-sm-6">
		      	<input type="email" name="username" id="username" required="required" class="form-control" />
		    </div>
		</div>
		<div class="form-group">
		  	<label for="password" class="col-sm-4 control-label">Password</label>
		  	<div class="col-sm-6">
		  		<input type="password" id="password" name="password" class="form-control" required="required" >
		  	</div>
		</div>
		  
		<div class="form-group">
		    <div class="col-sm-offset-5 col-sm-2">
		      <button type="submit" id="submit" class="btn btn-primary">Login</button>
		    </div>
		</div>
	</form>
	</div>

	<script type="text/javascript" src="/js/components-setup.min.js"></script>
</body>
</html>