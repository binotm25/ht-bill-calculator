<?php

//extract data from the post
//set POST variables
http://203.212.70.200/smpp/sendsms?username=manipurstate1&password=sejt8634&to=mobileno&from=MSPDCL&text=yourmessagehereUrlencoded
$msg = urlencode('Checking if this works or not - From HT Bill.');
$phn = ["8131940554"];

// for($i = 0; $i < sizeof($phn); $i++){

// 	$xml = file_get_contents("http://203.212.70.200/smpp/sendsms?username=manipurstate1&password=sejt8634&to=".$phn[$i]."&from=MSPDCL&text=".$msg);
// 	if($xml !== false){
// 		echo "Msg has beeb sent. <br />";
// 	}

// }

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
for($i = 0; $i < sizeof($phn); $i++){
	curl_setopt($ch, CURLOPT_URL, 
	    "http://203.212.70.200/smpp/sendsms?username=manipurstate1&password=sejt8634&to=".$phn[$i]."&from=MSPDCL&text=".$msg
	);
	$content = curl_exec($ch);
	echo $content;
}
curl_close($ch);