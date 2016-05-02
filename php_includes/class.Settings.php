<?php
require_once "class.UpdateGraph.php";
require_once "trait.Request.php";
/**
* 
*/
class settings extends UpdateGraph
{	
	use postRequest;

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
		$name = ucwords(preg_replace('#[^a-z0-9-() ]#i', '', $name));
		$id = preg_replace('#[^0-9]#', '', $gm);
		if($name == ""){ 
			return "Feeder name is required!";
			exit();
		}
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

	public function getFeedersPerSub($sub){
		$sub = preg_replace('#[^0-9]#', '', $sub);
		$result = [];
		$sql = "SELECT id, feeder FROM feeders WHERE ss_id = '$sub'";
		$query = $this->db_conx->query($sql);
		while($row = $query->fetch_assoc()){
			$result[] = [$row['id'] => $row['feeder']];
		}
		return json_encode($result);
	}

	public function addNewFeeder($sub, $feeder){
		$sub = preg_replace('#[^0-9]#', '', $sub);
		$feeder = ucwords(preg_replace('#[^a-z0-9- ]#i', '', $feeder));
		if($feeder == ""){ 
			return "Feeder name is required!";
			exit();
		}
		$sql = "SELECT COUNT(id) AS wathi FROM feeders WHERE ss_id = '$sub' AND feeder = '$feeder'";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		if($row['wathi'] > 0){
			return "Sorry this name has already been taken for this particular Sub-Station, please use another one!";
		}else{
			$sql = "INSERT INTO feeders (ss_id, feeder) VALUES ('$sub', '$feeder')";
			$query = $this->db_conx->query($sql);
			$lastId = $this->db_conx->insert_id;
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
					$query = $this->db_conx->query("SELECT month FROM daily WHERE id = 1");
					$row = $query->fetch_row();
					$sql = "INSERT INTO daily (feeder_id, month) VALUES ('$lastId', '$row[0]')";
					$query = $this->db_conx->query($sql);
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
			if($_SESSION['attr'] == 9){
				$sql = "DELETE FROM substation WHERE id = '$id'";
				$query = $this->db_conx->query($sql);
			}else{
				$sql = "INSERT INTO noti (sender_id, reciever_id, sub_id, feeder_id, message, matam, logic) VALUES ('$user', '$gm_id','$sub', NULL,'delete', now(), '1')";
				$query = $this->db_conx->query($sql);
				if(!$query){
					$error = false;
				}
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

			$sql = "SELECT feeder FROM feeders WHERE id = '$id'";
			$query = $this->db_conx->query($sql);
			$row = $query->fetch_assoc();
			$feeder_name = $row['feeder'];


			if($_SESSION['attr'] == 9){
				$sql = "DELETE FROM feeders WHERE id = '$id'";
				$query = $this->db_conx->query($sql);
				$sql = "DELETE FROM $this->table WHERE ss_id = '$sub' AND feeder = '$feeder_name'";
				$query = $this->db_conx->query($sql);
			}else{
				$sql = "INSERT INTO noti (sender_id, reciever_id, sub_id, feeder_id, message, matam, logic) VALUES ('$user', '$gm_id','$sub', '$id','delete', now(), '1')";
				$query = $this->db_conx->query($sql);
				if(!$query){
					$error = false;
				}
			}
		}
		if(!$error){
			return $this->db_conx->error;
		}else{
			return "sent";
		}
	}

	public function editSub($sub, $new){
		$sub = preg_replace('#[^0-9]#', '', $sub);
		$new = ucwords(preg_replace('#[^a-z0-9-() ]#i', '', $new));

		$sql = "UPDATE substation SET name = '$new' WHERE id ='$sub[0]'";
		$query = $this->db_conx->query($sql);
		if(!$query){
			return "no";
		}else{
			return "yes";
		}
	}

	public function editFeed($feed, $new){
		$sub = preg_replace('#[^0-9]#', '', $feed[0]);
		$feed = preg_replace('#[^0-9]#', '', $feed[1]);
		$new = ucwords(preg_replace('#[^a-z0-9- ]#i', '', $new));

		$sql = "SELECT feeder FROM feeders WHERE id = '$feed'";
		$query = $this->db_conx->query($sql);
		$row = $query->fetch_assoc();
		$feeder_name = $row['feeder'];
		$sql = "UPDATE feeders SET feeder = '$new' WHERE id ='$feed'";
		$query = $this->db_conx->query($sql);
		if(!$query){
			return $this->db_conx->error;
		}else{
			$sql = "UPDATE $this->table SET feeder = '$new' WHERE ss_id ='$sub' AND feeder = '$feeder_name'";
			$query = $this->db_conx->query($sql);
			if(!$query){
				return $this->db_conx->error;
			}else{
				return "yes";
			}
		}
	}

}