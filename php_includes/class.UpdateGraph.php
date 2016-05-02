<?php
require_once '../../vendor/autoload.php';
use Carbon\Carbon;
require_once "trait.Request.php";
/**
* 
*/
class UpdateGraph
{
	public $db_conx, $year, $month, $table, $column;

	function __construct($db_conx)
	{
		$this->db_conx = $db_conx;
		$this->year = date('Y');
		$this->month = date('m');
		if($this->month == 1){
			$this->table = 'report_'.$this->year - 1;
			$this->column = 12;
		} else{
			$this->table = 'report_'.$this->year;
			$this->column = $this->month - 1;
		}
	}

	use postRequest;

	public function getMonthName($id){
		$mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
		return $mons[$id];
	}

	public function getSub($id){
		$sql = "SELECT ss_id FROM $this->table WHERE gm='$id' GROUP BY ss_id";
		$query = $this->db_conx->query($sql);
		$ss = [];
		while($row = $query->fetch_assoc()){
			$ss[] = $row['ss_id'];
		}
		return $ss;
	}

	public function getSubAll(){
        $sql = "SELECT * FROM substation ORDER BY name";
        $query = $this->db_conx->query($sql);
        return $query;
	}

	public function getFeedersAll(){
		$sql = "SELECT * FROM feeders ORDER BY feeder";
        $query = $this->db_conx->query($sql);
        return $query;
	}

	public function getSubName($id){
		$sql = "SELECT name FROM substation WHERE id='$id'";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		return $row['name'];
	}

	public function getFeederName($id){
		$sql = "SELECT feeder FROM feeders WHERE id='$id'";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		return $row['feeder'];
	}

	public function getSubDetails($id){
		$sql = "SELECT * FROM $this->table WHERE ss_id = $id";
		$query = $this->db_conx->query($sql);
		$value = []; $name = [];
		while($row = $query->fetch_assoc()){
			
			if($row[$this->column] == NULL){
				$value[] = -50;
			}else{
				$value[] = 730 - $row[$this->column];
			}
		    
		    $name[] = "'".$row['feeder']."'";
		 }
		return array($value, $name);
	}

	public function getAllPerSub($id){
		$sql = "SELECT * FROM $this->table WHERE gm='$id'";
		$query = $this->db_conx->query($sql);
		return $query;
	}

	public function max10($id){
		$sql = "SELECT * FROM $this->table WHERE gm = $id ORDER BY `$this->table`.`$this->column` DESC LIMIT 10";
		$query = $this->db_conx->query($sql);
		$gm = [];
		while($row = $query->fetch_assoc()){
		    $value = $row[$this->column];
		    $gm[] = "{y:". $value . ", collectionAlias: '" .$row['feeder']."'}";
		 }
		return $gm;
	}

	public function min10($id){
		$sql = "SELECT * FROM $this->table WHERE gm = $id AND $this->table.$this->column IS NOT NULL ORDER BY `$this->table`.`$this->column` ASC LIMIT 10";
		$query = $this->db_conx->query($sql);
		$gm = [];
		while($row = $query->fetch_assoc()){
		    $value = $row[$this->column];
		    $gm[] = "{y:". $value . ", collectionAlias: '" .$row['feeder']."'}";
		 }
		return $gm;
	}

	public function all($id){
		$sql = "SELECT * FROM $this->table WHERE gm = $id";
		$query = $this->db_conx->query($sql);
		$value = []; $name = [];
		while($row = $query->fetch_assoc()){
			
			if($row[$this->column] == NULL){
				$value[] = -50;
			}else{
				$value[] = 730 - $row[$this->column];
			}
		    
		    $name[] = "'".$row['feeder']."'";
		 }
		return array($value, $name);
	}

	public function getAll(){
		$sql = "SELECT $this->table.id, $this->table.$this->column, $this->table.feeder, substation.name FROM $this->table LEFT JOIN substation ON $this->table.ss_id = substation.id";
		$query = $this->db_conx->query($sql);
		$value = []; $name = []; $ss = []; $count = 1;
		while($row = $query->fetch_assoc()){
			if($row[$this->column] == NULL){
				$gm[] = "{id:". $row['id'] . ", name: '" .$row['feeder']."', sub:'".$row['name']."', int:'Report Not Submitted'}";
			}else{
				$gm[] = "{id:". $row['id'] . ", name: '" .$row['feeder']."', sub:'".$row['name']."', int:'".$row[$this->column]."'}";
			}
			
		 }
		return $gm;
	}

	public function getAvg(){
		$sql = "SELECT AVG($this->table.$this->column) as Wathi, substation.name FROM $this->table LEFT JOIN substation ON $this->table.ss_id = substation.id GROUP BY ss_id";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		$value = []; $name = [];
		while($row = $query->fetch_assoc()){
			if($row['Wathi'] == NULL){
				$value[] = -50;
			}else{
				$value[] = $row['Wathi'];
			}
			
		    $name[] = "'".$row['name']."'";
		 }
		return array($value, $name);
	}

	public function getAvgPerSub($id){
		$sql = "SELECT AVG($this->table.$this->column) as Wathi, substation.name FROM $this->table LEFT JOIN substation ON $this->table.ss_id = substation.id WHERE $this->table.gm = '$id' GROUP BY ss_id";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		$value = []; $name = [];
		while($row = $query->fetch_assoc()){
			if($row['Wathi'] == NULL){
				$value[] = -50;
			}else{
				$value[] = $row['Wathi'];
			}
			
		    $name[] = "'".$row['name']."'";
		 }
		return array($value, $name);
	}

	public function addAll(){
		$sql = "SELECT $this->table.id, $this->table.feeder, substation.name FROM $this->table LEFT JOIN substation ON $this->table.ss_id = substation.id WHERE $this->table.$this->column IS NULL ORDER BY $this->table.ss_id";
		$query = $this->db_conx->query($sql);
		return $query;
	}

	public function updateAll(){
		if(date('d') > 5){
			return false;
		}else{
			$sql = "SELECT $this->table.id, $this->table.$this->column, $this->table.feeder, substation.name FROM $this->table LEFT JOIN substation ON $this->table.ss_id = substation.id WHERE ($this->table.$this->column IS NOT NULL) ORDER BY $this->table.ss_id";
			$query = $this->db_conx->query($sql);
			return $query;
		}
	}

	// This is for updating the daily details in the details table according to the feeders table
		public function updateMinai(){
		$exe = [];
		$sql = "SELECT id FROM feeders";
		$query = $this->db_conx->query($sql);
		while($row = $query->fetch_row()){
			$exe[] = $row[0];
			// $sql = "INSERT INTO daily (feeder_id, month) VALUES('$id', '4')";
			// $query = $this->db_conx->query($sql);
			echo $row[0];
		}

		foreach($exe as $wathi){
			$sql = "INSERT INTO daily (feeder_id, month) VALUES('$wathi', '4')";
			$query = $this->db_conx->query($sql);
		}
	}

	public function dailyReports(){
		$exe = []; $feed_id = [];
		$sql = "SELECT feeders.id, feeders.feeder, substation.name FROM feeders LEFT JOIN substation ON feeders.ss_id = substation.id ORDER BY substation.id";
		$query = $this->db_conx->query($sql);
		while($row = $query->fetch_row()){
			$id = $row[0]; $name = $row[1]; $ss_id = $row[2];
			$exe[$id] = [$ss_id => $name];
			$feed_id[] = $id;
		}

		return [$exe, $feed_id];

	}

	public function dailyReportsMsg(){
		$exe = [];
		$sql = "SELECT feeders.id, feeders.feeder, substation.name FROM feeders LEFT JOIN substation ON feeders.ss_id = substation.id";
		$query = $this->db_conx->query($sql);
		while($row = $query->fetch_row()){
			$id = $row[0]; $name = $row[1]; $ss_id = $row[2];
			$exe[$id] = [$ss_id => $name];
		}

		return $exe;

	}

	public function getColumnName($col){
		switch ($col) {case '01':$col = 1;break;case '02':$col = 2;break;case '03':$col = 3;break;case '04':$col = 4;break;case '05':$col = 5;break;case '06':$col = 6;break;case '07':$col = 7;break;case '08':$col = 8;break;case '09':$col = 9;break;}
		return $col;
	}

	/**
	 * Gets all the report for the Day before Today from the Database
	 * @param  [integer] $col   [This is day no. from the date.]
	 * @param  [integer] $month [This is the month no. from the date]
	 * @return [array]        [An array of daily interrruption reports of all the Feeders will be retun ]
	 */
	public function getDailyReports($col, $month){
		$val = []; $col = $this->getColumnName($col);
		if($col == 0 && $month == 0){
			$today = Carbon::yesterday();
			$col = $today->format('d');
			$month = $today->format('m');
			// var_dump($today .' - '. $col .' - ' . $month);
			// die();
			$col = $this->getColumnName($col);
			if($col == 1){
				// Checking if the current Month has been updated!
				$query = $this->db_conx->query("SELECT month FROM daily WHERE id = 1");
				$row = $query->fetch_row(); if($row[0] != $month){
					// As we come to a new month we will set all the values to null to start a new data table
					$sql = "UPDATE daily SET daily.month = '$month',daily.1 = NULL, daily.2 = NULL,daily.3 = NULL,daily.4 = NULL,daily.5 = NULL,daily.6 = NULL,daily.7 = NULL,daily.8 = NULL,daily.9 = NULL,daily.10 = NULL,daily.11 = NULL,daily.12 = NULL,daily.13 = NULL,daily.14 = NULL,daily.15 = NULL,daily.16 = NULL,daily.17 = NULL,daily.18 = NULL,daily.19 = NULL,daily.20 = NULL,daily.21 = NULL,daily.22 = NULL,daily.23 = NULL,daily.24 = NULL,daily.25 = NULL,daily.26 = NULL,daily.27 = NULL,daily.28 = NULL,daily.29 = NULL,daily.30 = NULL,daily.31 = NULL";
					$query = $this->db_conx->query($sql);
				}
				// End of Nulling the table
			}
			$sql = "SELECT daily.feeder_id, daily.$col FROM daily";
		}else{
			$sql = "SELECT daily.feeder_id, daily.$col FROM daily WHERE daily.month = '$month'";
		}
		$query = $this->db_conx->query($sql);
		if($query){
			while($row = $query->fetch_row()){
				$val[$row[0]] = $row[1];
			}
		}

		return $val;
	}

	public function addReport($id, $column, $table){
		$id = preg_replace('#[^0-9-,]#', '', $id);
		$column = preg_replace('#[^0-9]#', '', $column);
		$table = preg_replace('#[^a-z0-9_]#', '', $table);
		$minai = [];
		if($column != $this->column || $table != $this->table){
			return $column .'-'.$table;
		}else{
			$id = explode(",",$id);
			foreach($id as $key) {
				$int = explode('-',$key);
				$minai[] = $int[0];
				$id = $int[0];
				$int = $int[1];
				if($int != "" || $int != NULL){
					$sql = "UPDATE $this->table SET $this->table.$this->column = '$int' WHERE $this->table.id = '$id'";
					$query = $this->db_conx->query($sql);
					if(!$query){
						return 'Your Report insertion was failed at the id - '.$id;
						exit();
					}
				}
			}
			return json_encode($minai);
		}
	}

	public function addDailyReportMsg($id, $day, $month, $year){
		$sub_feed = $this->dailyReportsMsg();
		$id = preg_replace('#[^0-9-.,]#', '', $id);
		$day = preg_replace('#[^0-9]#', '', $day);
		$day = $this->getColumnName($day);
		$month = preg_replace('#[^0-9]#', '', $month);
		$year = preg_replace('#[^0-9]#', '', $year);
		$minai = [];
		$date = $day.'-'.$month.'-'.$year;
		$id = explode(",",$id);
		$phone = $this->getNos();

		foreach($id as $key) {
			$int = explode('-',$key);
			$id = $int[0];
			$int = $int[1];
			if($int != "" && $int < 5){
				$res = $this->phoneNo($id, $sub_feed, $date, $phone);
			}
		}

		return $res;
	}

	public function addDailyReport($id, $day, $month, $year){
		$id = preg_replace('#[^0-9-.,]#', '', $id);
		$day = preg_replace('#[^0-9]#', '', $day);
		$day = $this->getColumnName($day);
		$month = preg_replace('#[^0-9]#', '', $month);
		$year = preg_replace('#[^0-9]#', '', $year);
		$minai = [];
		$id = explode(",",$id);
		foreach($id as $key) {
			$int = explode('-',$key);
			$minai[] = $key;
			$id = $int[0];
			$int = $int[1];
			$col = $day;
			if($int){
				if($int > 24){$int = 24;}
				$col = $day;
				$sql = "UPDATE daily SET daily.$col = '$int', daily.month = '$month' WHERE daily.id = '$id'";
				$query = $this->db_conx->query($sql);
				if(!$query){
					return 'Your Report insertion was failed at the id - '.$id . ' with the error msg - '. $this->db_conx->error;
					//return 'Your Report insertion was failed at the id - '.$id;
					exit();
				}
			}
		}

		return "Everything Went Perfect!";
	}

	

}