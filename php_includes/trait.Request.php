<?php

trait postRequest{

	public function sanatize($var){
		$res = [];
		if(is_array($var)){
			foreach($var as $wathi){
				$res[] = $this->db_conx->real_escape_string($wathi);
			}
			return $res;
		}else if(is_string($var)){
			$response = $this->db_conx->real_escape_string($var);
			return $response;
		}
	}

	public function postRequest($post){
		$index = [];
		$value = [];
		foreach ($post as $name => $val)
		{	
			$index[] = $name;
			$value[] = $val;
		}

		if($index[0] == "sub_name" && $index[1] == "gm"){
			echo $this->addSub($value[0], $value[1]);
		}

		if($index[0] == "gm" && $index[1] == "checkForSub"){
			echo $this->getSubPerGm($value[0]);
		}

		if($index[0] == "sub_id" && $index[1] == "feeder_name"){
			echo $this->addNewFeeder($value[0], $value[1]);
		}

		if($index[0] == "getFeedersFromSub"){
			echo $this->getFeedersPerSub($value[0]);
		}

		if($index[0] == "removeSubId" && $index[1] == "wathi_removeSubId"){
			echo $this->removeSubstation($value[0], $value[1]);
		}

		if($index[0] == "removeFeederId" && $index[1] == "wathi_removeFeedId"){
			echo $this->removeFeeder($value[0], $value[1]);
		}

		if($index[0] == "editSubId" && $index[1] == "newEditSub"){
			echo $this->editSub($value[0], $value[1]);
		}

		if($index[0] == "editFeedId" && $index[1] == "newEditFeed"){
			echo $this->editFeed($value[0], $value[1]);
		}

		if($index[0] == "id" && $index[1] == "table" && $index[2] == "column"){
			echo $this->addReport($value[0], $value[1], $value[2]);
		}

		if($index[0] == "feed_id" && $index[1] == "day" && $index[2] == "month" && $index[3] == "year"){
			echo $this->addDailyReport($value[0], $value[1], $value[2], $value[3]);
		}

		if($index[0] == "feed_id" && $index[1] == "msg"){
			return $this->addDailyReportMsg($value[0], $value[2], $value[3], $value[4]);
		}
	}

	public function getNos(){
		$sql = "SELECT id, name, contact FROM contact";
		$query = $this->db_conx->query($sql);
		$numbers = [];
		while($row = $query->fetch_row()){
			$numbers[$row[0]] = $row[2];
			$name[$row[0]] = $row[1];
		}
		return [$numbers, $name];
	}

	public function calNo($id){
		$sql = "SELECT auth1,auth2,auth3,auth4,auth5,auth6 FROM msg WHERE feed_id = '$id'";
		$query = $this->db_conx->query($sql);
		$numbers = [];
		while($row = $query->fetch_assoc()){
			$numbers[] = $row;
		}

		foreach($numbers as $value){
		  	foreach($value as $key => $value){
		    	if($value != 0){
					$numbers[] = $value;
		    	}
		    }
		}
		return $numbers;		
	}

	public function phoneNo($id, $sub_feed, $date, $phoneOri){
		$phone = $phoneOri[0]; $names = $phoneOri[1];
		$array = $this->calNo($id);
		//echo $id . '-';
		foreach($sub_feed[$id] as $key => $value){
			$feeder = $value; $substation = $key;
			$msg = urlencode('The Feeder '.$value.' of '.$key.' Substation, was found to be supplying power for less than 5 hrs on '.$date.'. Kindly report the solution/reason.');
			$msgP = urlencode('The Feeder '.$value.' of '.$key.' Substaion, was found to be supplying power for less than 5 hrs on '.$date.'. All the concerns have been notified.');
		}

		$content = [];
		$defaultPhn = [1,2,3,4];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		echo "<h5 style='text-success'>Report for the Feeder ".$feeder." of ".$substation." Substation, dated - ".$date." :</h5><ul>";

		foreach($defaultPhn as $phn){
			curl_setopt($ch, CURLOPT_URL, "http://203.212.70.200/smpp/sendsms?username=manipurstate1&password=sejt8634&to=".$phone[$phn]."&from=MSPDCL&text=".$msgP);
			$sent = curl_exec($ch);
			echo "<li>".$sent." to ".$names[$phn]."</li>";
		}

		foreach($array as $wathi){
			$returnRes = 0;
			if (is_array($wathi) || is_object($wathi)){
				foreach($wathi as $val){
					if($val != 0){
						curl_setopt($ch, CURLOPT_URL, "http://203.212.70.200/smpp/sendsms?username=manipurstate1&password=sejt8634&to=".$phone[$val]."&from=MSPDCL&text=".$msg);
						$sent = curl_exec($ch);
						echo '<li>'.$sent. ' to '. $names[$val] . '.</li>';
					}
				}
			}
		}
		curl_close($ch);
		echo "</ul>";
	}

}
