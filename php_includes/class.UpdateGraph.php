<?php

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

}