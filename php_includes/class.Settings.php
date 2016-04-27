<?php
require_once "class.UpdateGraph.php";
/**
* 
*/
class settings extends UpdateGraph
{
	public function getGmIdFromSub($sub_id){
		$sql = "SELECT gm FROM substation WHERE id = '$sub_id'";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		if($row['gm'] == 1){$gm_id = 10;}else if($row['gm'] == 2){$gm_id = 11;}elseif($row['gm'] == 3){$gm_id = 12;}
		return $gm_id;
	}

	public function getSubIdFromFeeder($id){
		$sql = "SELECT ss_id FROM feeders WHERE id = '$id' LIMIT 1";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		return $row['ss_id'];
	} 

	public function addSub($name, $gm){
		$name = ucwords(preg_replace('#[^a-z0-9- ]#i', '', $name));
		$id = preg_replace('#[^0-9]#', '', $gm);
		$sql = "SELECT COUNT(id) as wathi FROM substation WHERE name = '$name' AND gm = '$gm'";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		if($row['wathi'] > 0){
			return "Sorry this name has already been taken for this particular circle, please use another one!";
		}else{
			$sql = "INSERT INTO substation (gm, name) VALUES ('$gm', '$name')";
			$query = $this->db_conx->query($sql);
			if($query){
				return "added";
			}else{
				return $this->db_conx->error;
			}
		}
		
	}

	public function getSubPerGm($gm){
		$gm = preg_replace('#[^0-9]#', '', $gm);
		$result = [];
		$sql = "SELECT id, name FROM substation WHERE gm = '$gm'";
		$query = $this->db_conx->query($sql);
		while($row = $query->fetch_assoc()){
			$result[] = [$row['id'] => $row['name']];
		}
		return json_encode($result);
	}

	public function addNewFeeder($sub, $feeder){
		$sub = preg_replace('#[^0-9]#', '', $sub);
		$feeder = ucwords(preg_replace('#[^a-z0-9- ]#i', '', $feeder));
		$sql = "SELECT COUNT(id) AS wathi FROM feeders WHERE ss_id = '$sub' AND feeder = '$feeder'";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		if($row['wathi'] > 0){
			return "Sorry this name has already been taken for this particular Sub-Station, please use another one!";
		}else{
			$sql = "INSERT INTO feeders (ss_id, feeder) VALUES ('$sub', '$feeder')";
			$query = $this->db_conx->query($sql);
			if(!$query){
				return $this->db_conx->error;
			}else{
				$sql = "SELECT gm FROM substation WHERE id = '$sub'";
				$query = $this->db_conx->query($sql);
				$row = $query->fetch_assoc();
				$gm = $row['gm'];
				$sql = "INSERT INTO $this->table (gm, ss_id, feeder) VALUES ('$gm', '$sub', '$feeder')";
				$query = $this->db_conx->query($sql);
				if($query){
					return 'added';
				}else{
					return $this->db_conx->error;
				}
			}
		}
	}

	public function removeSubstation($subId){
		$user = $_SESSION['userid'];
		$error = true;
		foreach($subId as $id){
			$sub = preg_replace('#[^0-9]#', '', $id);
			if($sub == ""){
				return "You haven't selected anything!";
				exit();
			}
			$gm_id = $this->getGmIdFromSub($id);
			$sql = "INSERT INTO noti (sender_id, reciever_id, sub_id, feeder_id, message, matam, logic) VALUES ('$user', '$gm_id','$sub', NULL,'delete', now(), '1')";
			$query = $this->db_conx->query($sql);
			if(!$query){
				$error = false;
			}
		}
		if(!$error){
			return $this->db_conx->error;
		}else{
			return "sent";
		}
	}

	public function removeFeeder($subId){
		$user = $_SESSION['userid'];
		$error = true;
		
		foreach($subId as $id){
			$sub = preg_replace('#[^0-9]#', '', $id);
			if($sub == ""){
				return "You haven't selected anything!";
				exit();
			}
			$sub = $this->getSubIdFromFeeder($id);
			$gm_id = $this->getGmIdFromSub($sub);

			$sql = "INSERT INTO noti (sender_id, reciever_id, sub_id, feeder_id, message, matam, logic) VALUES ('$user', '$gm_id','$sub', '$id','delete', now(), '1')";
			$query = $this->db_conx->query($sql);
			if(!$query){
				$error = false;
			}
		}
		if(!$error){
			return $this->db_conx->error;
		}else{
			return "sent";
		}
	}
}