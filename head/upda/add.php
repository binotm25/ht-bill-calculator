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

$res = new UpdateGraph($db_conx);
$query = $res->addAll();

if(isset($_POST['column']) && isset($_POST['table']) && isset($_POST['id'])){
    if($_POST['column'] == "" || $_POST['table'] == "" || $_POST['id'] == ""){
      echo "boom";
      exit();
    }else{
        echo $res->addReport($_POST['id'], $_POST['column'], $_POST['table']);
        exit();
    }
}

$noti = new Noti($db_conx);
$message = $noti->getMessages();
$messageCount = $message[0];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Feeder Reports</title>
    <link href="/css/pace/SideBardataurl.css" rel="stylesheet" />
    <script src="/js/pace.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/local.css" />
</head>
<body>
<div id="wrapper">
<img src="/img/equilizer.gif" class="img-responsive" id="loading">
        <?php include_once('nav.php'); ?><h5 class="text-center" id="count"></h5>
    <div id="page-wrapper">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                      <th>Sl. No.</th>
                      <th>Feeder Name</th>
                      <th>Substation Name</th>
                      <th style="width:25%;">Interruption Report - <?= $res->getMonthName($res->column).' -'.date('Y'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                  $count = 1;
                  while($row = $query->fetch_assoc()){
                    echo
                    '<tr>
                        <td>'. $count .'</td>
                        <td>'.$row["feeder"].'</td>
                        <td>'.$row["name"].'</td>
                        <td>
                            <input type="number" min="0" max="730" maxlength="3" id="'.$row["id"].'" class="form-control" placeholder="Interruption Hours" />
                        </td>
                    </tr>'; $count++;
                    }
                ?>
                </tbody>
            </table>
            <button id="submit" class="btn btn-primary pull-right">Add Report to the Database</button>
        </div>
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
                <button type="button" id="submit-yes" class="btn btn-success" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/components-setup.min.js"></script>
<script>
  $("#count").text("Total Number of Feeders : <?= $count; ?>");
  $("#add").addClass('active');
  var column = "<?= $res->column; ?>";
  var table = "<?= $res->table; ?>";
</script>
<script src="/js/form.js"></script>
</body>
</html>
