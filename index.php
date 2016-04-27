<?php
date_default_timezone_set('Asia/Kolkata');
require_once('php_includes/class.DB.php');
require_once('php_includes/class.Result.php');

$db = new dbConx();
$db_conx = $db->conn();
$res = new result($db_conx);
$substation = $res->getSub($db_conx);

//Get the feeders name
if(isset($_POST['sub'])){
	$feeders = $res->getFeeders($db_conx, $_POST['sub']);
	echo $feeders;
	exit();
}

if(isset($_POST['val']) || isset($_POST['end']) || isset($_POST['start']) || isset($_POST['monthDiff'])){
	$intHour = $res->getIntHour($db_conx, $_POST['val'], $_POST['start'], $_POST['end'], $_POST['monthDiff']);
	echo $intHour;
	exit();
}

// Calculate the bill
if(isset($_POST['days']) && isset($_POST['cat']) && isset($_POST['dtr']) && isset($_POST['totalHour'])){
	if(empty(isset($_POST['cat'])) || empty($_POST['dtr']) || empty($_POST['totalHour']) || empty($_POST['days']) || empty($_POST['startPeriod']) || empty($_POST['endPeriod'])){
	exit();
	}
	echo $res->printResult($_POST['cat'], $_POST['dtr'], $_POST['totalHour'], $_POST['t_loss'], $_POST['days'], $_POST['startPeriod'], $_POST['endPeriod']);
	exit();	
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>HT BILLS</title>
	<link href="/css/pace/SideBardataurl.css" rel="stylesheet" />
    <script src="/js/pace.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/custom.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
</head>
<body>
	<img src="img/equilizer.gif" class="img-responsive" id="loading">
	<div class="pop-back"></div>
	<br />
	<div class="col-xs-6 col-xs-offset-3">
		<img src="img/logo.png" class="img-responsive img-rounded">
	</div>

	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
	<h3 class="text-center">HT Bill Calculator For Unmetered Consumers</h3>
	<form class="form-horizontal wathi" onclick="return false">
		<div class="form-group">
		    <label for="cat" class="col-sm-6 control-label">Category</label>
		    <div class="col-sm-5">
		      	<select class="form-control" id="cat" required="required">
			      	<option value="">Select the Category</option>
			      	<option value="0">Commercial</option>
			      	<option value="1">Public Water Works</option>
			      	<option value="2">Irrigation/Agriculture</option>
			      	<option value="3">Medium Industries</option>
			      	<option value="4">Large Industries</option>
			      	<option value="5">Bulk Supply</option>
		      	</select>
		    </div>
		</div>
		<div class="form-group">
		  	<label for="month" class="col-sm-6 control-label"><abbr  data-toggle="tooltip" data-context="primary" data-trigger="hover" title="Your Bill for the Period">Select the Period</abbr></label>
		  	<div class="col-sm-5">
		  		<input type="text" id="month" name="month" class="form-control" required="required">
		  	</div>
		</div>
		<div class="form-group" id="date_takpa">
		    <div class="col-sm-12 text-center">
		        <label>
					<abbr class="bg-success" id="date_diff" data-toggle="tooltip" data-placement="right" data-context="primary" data-trigger="hover" title="Days Different."></abbr>
				</label>
		  	</div>
		</div>
		<div class="form-group" id="dtr_drop">
		    <label for="dtr" class="col-sm-6 control-label">DTR Capacity (KVA)</label>
		    <div class="col-sm-5">
		    	<option class="form-control choose">Select the DTR Capacity</option>
		      	<select class="form-control dropdown" id="dtr" required="required" size="10" style='display:none;'>
			      	<option value="5">5</option>
			      	<option value="25">25</option>
			      	<option value="63">63</option>
			      	<option value="100">100</option>
			      	<option value="200">200</option>
			      	<option value="250">250</option>
			      	<option value="300">300</option>
			      	<option value="400">400</option>
			      	<option value="450">450</option>
			      	<option value="500">500</option>
			      	<option value="600">600</option>
			      	<option value="630">630</option>
			      	<option value="750">750</option>
			      	<option value="800">800</option>
			      	<option value="1000">1000</option>
			      	<option value="1200">1200</option>
			      	<option value="1500">1500</option>
			      	<option value="3000">3000</option>
			      	<option value="5000">5000</option>
		      	</select>
		    </div>
		</div>
				  
		<div class="form-group" id="billing_takpa">
		    <div class="col-sm-12 text-center">
				<label>
					<abbr class="bg-success" id="bill_cal_kva" data-toggle="tooltip" data-placement="top" data-context="primary" data-trigger="hover" title="Billing Demand for your DTR Capacity which will be used in the calculation of the Energy Consumption."></abbr>
				</label>
		    </div>
		</div>
		<div class="form-group">
			<label for="hour" class="col-sm-6 control-label">Your Substation (33/11 KV)</label>
			<div class="col-sm-5">
				<option class="form-control choose">Choose your Substation</option>
				<select class="form-control dropdown" id="sub" required="required" size="10" style='display:none;'>
					<?php 
					while($result = $substation->fetch_array())
					{
						echo '<option value="'.$result[0].'">'.ucwords(preg_replace('#[^a-z1-9-() ]#i', ' ', $result[2])).'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group feeder">
			<label for="hour" class="col-sm-6 control-label">Your Feeder (11KV)</label>
			<div class="col-sm-5">
				<option class="form-control choose">Choose your Feeder</option>
				<select class="form-control dropdown" id="feeder" required="required" size="10" style='display:none;'>
					
				</select>
			</div>
		</div>
		<div class="form-group" id="hour_takpa">
		    <div class="col-sm-12 text-center">
		        <label>
		          <abbr class="bg-success" id="hour_masing" data-toggle="tooltip" data-placement="top" data-context="primary" data-trigger="hover" title="Your total Supplied Hours for the Month."></abbr>
		        </label>
		    </div>
		</div>
		<div class="form-group">
		    <label for="t_loss" class="col-sm-6 control-label"><b><abbr data-toggle="tooltip" data-context="primary" data-trigger="hover" title="Transformation Loss is the loss occured while converting Energy from HT to LT by the Meter. Will be excluded to Metered consumers Only when the meter is before the Consumer's DTR i.e the HT side!">Transformation Loss</abbr></b></label>
		    <div class="col-sm-5">
		      	<select class="form-control" id="t_loss">
			      	<option value="0">No</option>
			      	<option value="1">Yes</option>
		      	</select>
		    </div>
		 </div>
		  
		<div class="form-group">
		    <div class="col-sm-2 col-sm-offset-4">
		      	<button type="submit" id="submit" class="btn btn-primary center-block">Calculate The Bill For Above Period</button>
		    </div>
		</div>
	</form>
	
	<div id="modal"></div>
	<div class="modal fade" id="modalBasic" tabindex="-1" role="dialog" aria-labelledby="modalBasicLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalBasicLabel">Alert!</h4>
                </div>
                <div class="modal-body">
                    <p id="modal_alert_words"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
	<!-- modalLarge -->
    <div class="modal fade" id="modalbig" tabindex="-1" role="dialog" aria-labelledby="modalLargeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalLargeLabel">How is your bill calculated:</h4>
                </div>
                <div class="modal-body">
                    <p class="text-justify">Your Energy consumption is calculated by using the formula "L x H x F x D" prescribed by the Joint Electricity Regulatory Commision For Manipur and Mizoram vide tarriff order FY 2016-17.</p>
                    <p>Where :</p>
					<ul class="text-justify">
						<li>
							L is the Billing demand. Your Billing demand can be seen right below the DTR Capacity in the Bill Calculator Form.</li>
						<li>
							H is the total number of hours in a month during which power is actually supplied to that consumer through that feeder / through that DTR concerned, whichever is less, (after taking into account all interruptions of power supply). Note :- Interruption shall mean breakdowns of Feeders, Part of Feeder, Distribution Transformer, load sheddings, all types of shut downs which shall be recorded and informed to concerned billing station. 
						</li>
						<li>
							F is the Load Factor prescribed by JERC for different categories of Consumer.
						</li>
						<li>
							D is the Demand Factor prescribed by JERC for different categories of Consumer..
						</li>
					</ul>
					<p class="text-justify">After your energy consumption is calculated it is multiplied by the respective per unit charges.</p>
					<p class="text-justify">After your Unit consumption is converted to Energy Charges it is added by the Fixed/Demand Charge.</p>
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th style="width: 10%; vertical-align:middle;text-align:center;">Sl. No.</th>
									<th style="width: 30%; vertical-align:middle;text-align:center;">Category</th>
									<th style="width: 15%; vertical-align:middle;text-align:center;">Load Factor</th>
									<th style="width: 15%; vertical-align:middle;text-align:center;">Demand Factor</th>
									<th style="width: 15%; vertical-align:middle;text-align:center;">Tariff Rate Per unit</th>
									<th style="width: 15%; vertical-align:middle;text-align:center;">Fixed Charge per Billing Demand</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Commercial</td>
									<td><?php echo $res->energy[0]['f']; ?></td>
									<td><?php echo $res->energy[0]['d']; ?></td>
									<td><?php echo $res->energy[0]['ht_single']; ?></td>
									<td><?php echo $res->energy[0]['fixed_ht']; ?></td>
								</tr>
								<tr>
									<td>2</td>
									<td>Public Water Works</td>
									<td><?php echo $res->energy[1]['f']; ?></td>
									<td><?php echo $res->energy[1]['d']; ?></td>
									<td><?php echo $res->energy[1]['ht_single']; ?></td>
									<td><?php echo $res->energy[1]['fixed_ht']; ?></td>
								</tr>
								<tr>
									<td>3</td>
									<td>Agriculture/Irrigation</td>
									<td><?php echo $res->energy[2]['f']; ?></td>
									<td><?php echo $res->energy[2]['d']; ?></td>
									<td><?php echo $res->energy[2]['ht_single']; ?></td>
									<td><?php echo $res->energy[2]['fixed_ht']; ?></td>
								</tr>
								<tr>
									<td>4</td>
									<td>Medium Industries</td>
									<td><?php echo $res->energy[3]['f']; ?></td>
									<td><?php echo $res->energy[3]['d']; ?></td>
									<td><?php echo $res->energy[3]['ht_single']; ?></td>
									<td><?php echo $res->energy[3]['fixed_ht']; ?></td>
								</tr>
								<tr>
									<td>5</td>
									<td>Large Industries</td>
									<td><?php echo $res->energy[4]['f']; ?></td>
									<td><?php echo $res->energy[4]['d']; ?></td>
									<td><?php echo $res->energy[4]['ht_single']; ?></td>
									<td><?php echo $res->energy[4]['fixed_ht']; ?></td>
								</tr>
								<tr>
									<td>6</td>
									<td>Bulk Supply</td>
									<td><?php echo $res->energy[5]['f']; ?></td>
									<td><?php echo $res->energy[5]['d']; ?></td>
									<td><?php echo $res->energy[5]['ht_single']; ?></td>
									<td><?php echo $res->energy[5]['fixed_ht']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				
					<p class="text-justify">Please note that if the Consumer has Transformation loss then the Transformation loss is applied to the Total Energy consumption before it is converted to charges. Transformation loss is calculated by the formula = (730 x 1.0 x DTR capacity)/100 per month as prescribed in tariff schedule of JERC.</p>
					<p>The end result is your total Bill.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
	
	<script type="text/javascript" src="js/components-setup.min.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
</body>
</html>