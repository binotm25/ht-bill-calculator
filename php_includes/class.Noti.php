<?php

/**
* 
*/
class Noti
{
	
	public $db_conx, $table, $column;

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

	public function getFeederName($id){
		$sql = "SELECT feeder FROM feeders WHERE id = '$id'";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		return $row['feeder'];
	}

	public function getMessages(){
		$user_id = $_SESSION['userid'];
		$sql = "SELECT * FROM noti WHERE reciever_id = '$user_id'";
		$query = $this->db_conx->query($sql);
		$row = "SELECT COUNT(id) as wathi FROM noti WHERE reciever_id = '$user_id' AND reply IS NULL";
		$countQuery = $this->db_conx->query($row);
		$result = $countQuery->fetch_assoc();
		return array($result['wathi'], $query);
	}

	public function updateNoti($id, $logic){
		$id = preg_replace('#[^0-9]#', '', $id);
		$logic = preg_replace('#[^0-9]#', '', $logic);
		if($logic == 1){
			$sql = "SELECT message, sub_id, feeder_id FROM noti WHERE id = '$id' LIMIT 1";
			$query = $this->db_conx->query($sql);
			$row = $query->fetch_assoc();
			$ss_id = $row['sub_id'];
			if($row['message'] == 'delete'){
				if($row['feeder_id']){
					$feeder_id = $row['feeder_id'];
					$feeder_name = $this->getFeederName($feeder_id);
					$sql = "DELETE FROM feeders WHERE id = '$feeder_id' LIMIT 1";
					$query1 = $this->db_conx->query($sql);
					$sql = "DELETE FROM $this->table WHERE ss_id = '$ss_id' AND feeder = '$feeder_name' LIMIT 1";
					$query2 = $this->db_conx->query($sql);
					if(!$query1 || !$query2){
						return $this->db_conx->error;
						exit();
					}
				}else{
					$sql = "DELETE FROM substation WHERE id = '$ss_id' LIMIT 1";
					$query = $this->db_conx->query($sql);
					if(!$query){
						return $this->db_conx->error;
						exit();
					}
				}
			}
			$sql = "UPDATE noti SET reply = '1' WHERE id = '$id' LIMIT 1";
			$query = $this->db_conx->query($sql);
			if(!$query){
				return $this->db_conx->error;
				exit();
			}
		}else{
			$sql = "UPDATE noti SET reply = '0' WHERE id = '$id' LIMIT 1";
			$query = $this->db_conx->query($sql);
			if(!$query){
				return $this->db_conx->error;
				exit();
			}
		}

		$sql = "UPDATE noti SET logic = '0' WHERE id = '$id' LIMIT 1";
		$query = $this->db_conx->query($sql);
		if(!$query){
			return $this->db_conx->error;
			exit();
		}

		return 'success';
		
	}
}