<?php

function date_cal($last_bill_date, $today){
	$bill_date_yengba = date_create($last_bill_date);
    $ngasi_date = date_create($today);
    $diff = date_diff($ngasi_date,$bill_date_yengba);
    $pellabani = $diff->format("%a");
    $masing = abs($pellabani);
    return $masing;
}

?>