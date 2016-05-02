<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include_once('../../php_includes/class.DB.php');
$db = new dbConx();
if($db->userOk()){
	header('Location: ../upda/reports');
	exit();
}
if(isset($_POST['email']) && isset($_POST['password'])){
	if(empty($_POST['email']) || empty($_POST['password'])){
		echo "<script>alert('Minai');</script>";
	}else{
		$result = $db->login($_POST['email'], $_POST['password']);
		if($result == 'success'){
			header('Location: ../upda/reports');
			exit();
		}else{
			echo "<script>alert('".$result."');</script>";
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="/css/customlogin.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
<body>
	<img src="/img/equilizer.gif" class="img-responsive" id="loading">
	<div class="pop-back"></div>
	<br />
	<div class="col-xs-6 col-xs-offset-3">
		<img src="/img/logo.png" class="img-responsive img-rounded">
	</div>

	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
	<h3 class="text-center">Login to Update/Insert Interruption Reports per Feeder</h3>
	<form class="form-horizontal wathi" method="post">
		<div class="form-group">
		    <label for="username" class="col-sm-4 control-label">Username</label>
		    <div class="col-sm-6">
		      	<input type="email" name="email" id="email" required="required" class="form-control" />
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
</body>
</html>