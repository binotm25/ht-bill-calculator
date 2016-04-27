<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
require_once('../../php_includes/class.DB.php');
require_once('../../php_includes/class.UpdateGraph.php');
require_once "../../php_includes/class.Noti.php";
$db = new dbConx();
$db_conx = $db->db;
if(!$db->userOk()){
    header('Location: ../log/login');
    exit();
}
if(isset($_POST['username']) && isset($_POST['email'])){
    if(empty($_POST['email']) || empty($_POST['username'])){
        echo "minai";
    }else{
        $db->logOut();
        echo "LogOut";
        exit();
    }
}

if(!isset($_GET['gm']) || empty($_GET['gm'])){
    header('Location: reports');
    exit();
}
$gm = preg_replace('#[^0-9]#', '', $_GET['gm']);
$res = new UpdateGraph($db_conx);
$ss = $res->getSub($gm);

$noti = new Noti($db_conx);
$message = $noti->getMessages();
$messageCount = $message[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Light Way Admin</title>
    <link href="/css/pace/SideBardataurl.css" rel="stylesheet" />
    <script src="/js/pace.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/local.css" />
    <link rel="stylesheet" type="text/css" href="https://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
</head>
<body>
    <div id="wrapper">
        
        <?php include_once('nav.php'); ?>
        <div id="page-wrapper">
			<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> 10 Most Power Intruption GM-<?= $gm . ' - ' .$res->getMonthName($res->column); ?></h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> 10 Least Power Intruption GM-<?= $gm . ' - ' .$res->getMonthName($res->column); ?></h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart2"></div>
                        </div>
                    </div>
                </div>
				<div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Total Power Intruption GM-<?= $gm . ' - ' .$res->getMonthName($res->column); ?></h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart3"></div>
                        </div>
                    </div>
                </div>
                <?php
                	$count = 4;
                	foreach($ss as $sub){
            			echo '
							<div class="col-lg-12">
			                    <div class="panel panel-primary">
			                        <div class="panel-heading">
			                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> '.$res->getSubName($sub).' Power Supplied For the Month of '. $res->getMonthName($res->column).' - GM-I</h3>
			                        </div>
			                        <div class="panel-body">
			                            <div id="shieldui-chart'.$count.'"></div>
			                        </div>
			                    </div>
			                </div>
                		';
                		$count++;
                	}
                ?>
            </div>
        </div>
    </div>
    <!-- /#wrapper -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script>$(document).ready(function(){
            $("#reports").addClass('active');
        });</script>
    <!-- you need to include the shieldui css and js assets in order for the charts to work -->
    
    <script type="text/javascript" src="https://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
    <script>
        var max10 = [<?php echo implode(', ', $res->max10($gm)); ?>];
        var min10 = [<?php echo implode(', ', $res->min10($gm)); ?>];
        var avgSub  = [<?php echo implode(', ', $res->getAvgPerSub($gm)[0]); ?>];
        var avgSubName = [<?php echo implode(', ', $res->getAvgPerSub($gm)[1]); ?>];
        var count = "<?php echo $count; ?>";
        var sstotalName = new Array();
        var sstotal = new Array();
        <?php
        $count = 4;
	    	foreach($ss as $sub){
	    		
	    		$substation = $res->getSubDetails($sub);
	    		echo "
					sstotal[".$count."] = [".implode(', ', $substation[0])."];
	        		sstotalName[".$count."] = [".implode(', ', $substation[1])."];
	    		";
	    		$count++;
	    	}
    	?>
    </script>
    
	<script type="text/javascript" src="/js/circle.js"></script>
    <script src="/js/update.js"></script>
</body>
</html>
