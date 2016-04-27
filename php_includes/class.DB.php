<?php
/**
* connecting to the database class
*/

class dbConx
{
	private $energy, $hDB, $passAdder, $adder;
	private $domain = "localhost";
	private $username = "binot";
	private $password = "wangoi_admin";
	private $database = "ht_mspdcl";
	public $db;
	public $user_ok = false;
	public $log_id = "";
	public $log_username = "";
	public $log_password = "";
	public $last_login = "";
	
	public function __construct()
	{	
		$this->db = $this->connect($this->domain, $this->username, $this->password, $this->database);
	}

	public function userOk(){
		if(isset($_SESSION["userid"]) && isset($_SESSION["username"]) && isset($_SESSION["password"]) && isset($_SESSION['last_login'])) {
			$this->log_id = preg_replace('#[^0-9]#', '', $_SESSION['userid']);
			$this->log_email = preg_replace('#[^a-z0-9@.]#i', '', $_SESSION['email']);
			$this->log_password = preg_replace('#[^a-z0-9]#i', '', $_SESSION['password']);
			$this->last_login = preg_replace('#[^0-9a-z-/ ]#', '', $_SESSION['last_login']);
			// Verify the user
			$user_ok = $this->checkUser($this->db, $this->log_id, $this->log_email, $this->log_password);
			return $user_ok;
		}		
	}

	public function connect($domain, $username, $password, $database){
		$db = new mysqli($domain, $username, $password, $database);
		if(mysqli_connect_errno()){
			echo mysqli_connect_error();
		}
		return $db;
	}

	public function conn(){
		return $this->db;
	}

	public function checkUser($db,$id,$u,$p){
		$sql = "SELECT ip FROM users WHERE id='$id' AND email='$u' AND password='$p' AND activated='1' LIMIT 1";
	    $query = mysqli_query($db, $sql);
	    $numrows = mysqli_num_rows($query);
		if($numrows > 0){
			return true;
		}else{
			return false;
		}
	}

	private function adder(){
		return 'mspdcl';
	}

	public function login($email, $password){
		$email 		= preg_replace('#[^a-z0-9@.]#i', '', $email);
		$this->adder = $this->adder();
		$p = md5($this->adder.''.$password);
		$sql = "SELECT id, name, password, last_login, attr FROM users WHERE email='$email' AND activated='1' LIMIT 1";
        $query = mysqli_query($this->db, $sql);
        $row = mysqli_fetch_row($query);
        $db_id = $row[0];
        $db_username = $row[1];
        $db_pass_str = $row[2];
        $last_login = $row[3];
        $new_login_status = date("Y-m-d H:i:s");
        if($p != $db_pass_str){
            return "Login Failed";
        } else {
            // CREATE THEIR SESSIONS AND COOKIES
            $_SESSION['userid'] = $db_id;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $db_username;
            $_SESSION['attr'] = $row[4];
            $_SESSION['password'] = $db_pass_str;
            $_SESSION['last_login'] = $last_login;
            /*
            setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
            setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
            setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE); 
            setcookie("attrib", $attrib, strtotime( '+30 days' ), "/", "", "", TRUE);
            */
            // UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
            $ip = $_SERVER['REMOTE_ADDR'];
            $sql = "UPDATE users SET ip='$ip', last_login=now() WHERE name='$db_username' LIMIT 1";
            if($query = mysqli_query($this->db, $sql)){
            	return "success";
            }else{
            	return mysqli_error($this->db);
            }
            
        }
	}

	public function checkAttr(){
		if($_SESSION['attr'] < 2){
			return false;
		}else if($_SESSION['attr'] >= 2){
			return true;
		}
	}

	public function logOut(){
		session_destroy();
	}
}