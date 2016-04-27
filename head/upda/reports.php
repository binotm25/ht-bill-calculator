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
$noti = new Noti($db_conx);
$message = $noti->getMessages();
$messageCount = $message[0];

$res = new UpdateGraph($db_conx);
$gm1 = ($res->max10(1)); 
$gm2 = ($res->max10(2)); 
$gm3 = ($res->max10(3));
$gm1all = $res->all(1); 
$gm2all = $res->all(2); 
$gm3all = $res->all(2); 
$getAvg = $res->getAvg();

if(isset($_POST['message_id']) && isset($_POST['logic'])){
    echo $noti->updateNoti($_POST['message_id'], $_POST['logic']);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interruptions Reports</title>
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
            <!-- <div class="row">
                <div class="col-lg-12">
                    <h1>Dashboard <small>Dashboard Home</small></h1>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Welcome to the admin dashboard! Feel free to review all pages and modify the layout to your needs. 
                        <br />
                        This theme uses the <a href="https://www.shieldui.com">ShieldUI</a> JavaScript library for the 
                        additional data visualization and presentation functionality illustrated here.
                    </div>
                </div>
            </div> -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> 10 Most Power Intruption GM-I - <?= $res->getMonthName($res->column); ?></h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart7"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> 10 Most Power Intruption GM-II - <?= $res->getMonthName($res->column); ?></h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart8"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> 10 Most Power Intruption GM-III - <?= $res->getMonthName($res->column); ?></h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart2"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <a href="circle.php?gm=1">
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Power Supplied For the Month of <?= $res->getMonthName($res->column); ?> - GM-I</h3>
                            </a>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart4"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        <a href="circle.php?gm=2">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Power Supplied For the Month of <?= $res->getMonthName($res->column); ?> - GM-II</h3>
                        </a>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart9"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        <a href="circle.php?gm=3">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Power Supplied For the Month of <?= $res->getMonthName($res->column); ?> - GM-III</h3>
                        </a>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart10"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Avg. Power Interruptions Of All the Substation. - <?= $res->getMonthName($res->column); ?></h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Traffic Estimations for last 30 days</h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart1"></div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Last Interruption Report!</h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-grid1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
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
        var gm1 = [<?php echo implode(', ', $gm1); ?>];
        var gm2 = [<?php echo implode(', ', $gm2); ?>];
        var gm3 = [<?php echo implode(', ', $gm3); ?>];
        var gm1total = [<?= implode(', ', $gm1all[0]); ?>];
        var gm1totalName = [<?= implode(', ', $gm1all[1]); ?>];
        var gm2total = [<?= implode(', ', $gm2all[0]); ?>];
        var gm2totalName = [<?= implode(', ', $gm2all[1]); ?>];
        var gm3total = [<?= implode(', ', $gm3all[0]); ?>];
        var gm3totalName = [<?= implode(', ', $gm3all[1]); ?>];
        var performance = [89, 43, 34, 22, 12, 33, 4, 17, 22, 34, 54, 67];
        var avgSub  = [<?php echo implode(', ', $getAvg[0]); ?>];
        var avgSubName = [<?php echo implode(', ', $getAvg[1]); ?>];
        var gridData = [<?php echo implode(', ', $res->getAll()); ?>];
    </script>
    <script type="text/javascript" src="/js/graphData.js"></script>
    <script src="/js/update.js"></script>
</body>
</html>
