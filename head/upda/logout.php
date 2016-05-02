<?php

class logOut {
	public function __construct(){
		session_destroy();
	}
}

$logout = new logOut();
header('Location: ../log/login');