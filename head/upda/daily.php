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

$res = new UpdateGraph($db_conx);
$query = $res->addAll();

if($_POST){
    $result = $res->postRequest($_POST);
    exit();
}

$noti = new Noti($db_conx);
$message = $noti->getMessages();
$messageCount = $message[0];

$data_tables = $res->dailyReports();
$var = $data_tables[0];
$feed_id = $data_tables[1];

$today = date('d');
if($today == 1){
  echo "<script>alert('Warning: Its been detected that today will be the last day you will be uploading the datas of this Month. So, it is recommended that you download the all data after uploads have been done. Please note that all the data of this month will be deleted Tomorrow.');
    
  </script>";
}

if(isset($_GET['col']) && isset($_GET['month'])){
  $default = $res->getDailyReports($_GET['col'], $_GET['month']);
}else{
  $default = $res->getDailyReports(0, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Reports</title>
    <link href="/css/pace/SideBardataurl.css" rel="stylesheet" />
    <script src="/js/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/local.css" />
</head>
<body>
<div id="wrapper">
<img src="/img/naruto.gif" class="img-responsive" id="loading">
        <?php include_once('nav.php');
      if(!$db->checkAttr()){
        echo "<h2 class='text-center bg-warning'>You don't have the authorization to Enter this Page!</h2>";
        die();
      }
    ?>
    <h5 class="text-center" id="count"></h5>
    <div class="col-md-10 col-sm-offset-1 form-group">
        <label for="day" class="label-control col-sm-4">Select the day of the Report : </label>
        <div class="col-sm-4">
            <input type="date" name="day" id="day" class="new-form-control" />
        </div>
        <div class="col-sm-2"><button class="btn btn-primary" id="sort">GO</button></div>
    </div>
    <div id="page-wrapper">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                      <th>Sl. No.</th>
                      <th>Feeder Name</th>
                      <th>Substation Name</th>
                      <th style="width:25%;" id="date-day"></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                  $count = 1;
                  foreach($feed_id as $id){
                    foreach($var[$id] as $key => $value){
                      echo '<tr>
                              <td>'.$count.'</td>
                              <td>'.$value.'</td>
                              <td>'.$key.'</td>
                              <td>'; ?>
                                <input type="number" min="0" max="24" maxlength="3" id="<?= $id; ?>" class="form-control" <?php if($default[$id]){ echo "placeholder='".$default[$id]."' disabled='disabled'"; } ?> />
                              </td>
                            </tr>
                    <?php }
                    $count++;
                  }
                ?>
                </tbody>
            </table>
            <button id="submit" class="btn btn-primary pull-right" style="margin-left:4px;">Add Report to the Database</button> 
            <button id="submit-msg" class="btn btn-warning pull-right">Send Report to Concerned</button>
            <br /><br />
            <button id="download" class="btn btn-success pull-right">Download Report From Database</button>
        </div>
        <br /><br />
        <!-- <marquee behavior="" direction="left" onmouseover="this.stop();" onmouseout="this.start();"> -->
          <p>*** You need to download the data for the whole month on the First day of every month or just before you upload the interruption reports to the database of a differnt month. So as to avoid conflict.</p>
        <!-- </marquee> -->
        
    </div>
</div>


<div class="gap80"></div>
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
                <button type="button" id="submit-yes-daily" class="btn btn-success" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modalBasicMsg" tabindex="-1" role="dialog" aria-labelledby="modalBasicLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalBasicLabel">Alert!</h4>
            </div>
            <div class="modal-body">
                <p id="modal_alert_words-msg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit-yes-daily-msg" class="btn btn-success" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/components-setup.min.js"></script>
<script>
  $("#count").text("Total Number of Feeders : <?= $count; ?>");
  $("#daily").addClass('active');
  var column = "<?= $res->column; ?>";
  var table = "<?= $res->table; ?>";
</script>
<script src="/js/moment.min.js"></script>
<script src="/js/form.js"></script>
</body>
</html>
