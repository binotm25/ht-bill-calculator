<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
require_once('../../php_includes/class.DB.php');
require_once('../../php_includes/class.Settings.php');
require_once "../../php_includes/class.Noti.php";
$db = new dbConx();
$db_conx = $db->db;

if(!$db->userOk()){
  header('Location: ../log/login');
  exit();
}

$res = new settings($db_conx);

if($_POST){
    $result = $res->postRequest($_POST);
    exit();
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
    <title>Settings</title>
    <link href="/css/pace/SideBardataurl.css" rel="stylesheet" />
    <script src="/js/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/local.css" />
    <style>
    </style>
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
    <div class="row">
      <div class="col-sm-6 text-right">
        <button id="addSub" class="btn btn-success wathi-form">Add A New Sub-Station</button>
      </div>
      <div class="col-sm-6 text-left">
        <button id="removeSub" class="btn btn-danger wathi-form">Remove A Sub-Station</button>
      </div>
    </div>
    <div class="gap80"></div>
    <div class="row">
      <div class="col-sm-6 text-right">
        <button id="editSub" class="btn btn-success wathi-form">Edit A Sub-Station</button>
      </div>
      <div class="col-sm-6 text-left">
        <button id="editFeeder" class="btn btn-danger wathi-form">Edit A Feeder</button>
      </div>
    </div>
    <div class="gap80"></div>
    <div class="row">
        <div class="col-sm-6 text-right">
          <button id="addFeeder" class="btn btn-success wathi-form">Add A New Feeder</button>
        </div>
        <div class="col-sm-6 text-left">
          <button id="removeFeeder" class="btn btn-danger wathi-form">Remove A Feeder</button>
        </div>
    </div>
</div>

<div class="gap80"></div>

<div class="modal fade" id="modalLarge-addSub" tabindex="-1" role="dialog" aria-labelledby="modalLargeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalLargeLabel">Add a new Sub-Station</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onclick="return false">
                  <div class="form-group">
                    <label for="sub-name" class="col-sm-4 control-label">Name of The Sub-Station</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="sub-name" placeholder="Sub-Station">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="gm" class="col-sm-4 control-label">Circle</label>
                    <div class="col-sm-8">
                      <select name="gm" id="gm" class="form-control">
                        <option value="">Choose a Circle</option>
                        <option value="1">GM-I</option>
                        <option value="2">GM-II</option>
                        <option value="3">GM-III</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                      <button type="submit" class="btn btn-success" id="addSubForm">Add Sub-Station</button>
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalLarge-addFeeder" tabindex="-1" role="dialog" aria-labelledby="modalLargeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalLargeLabel">Add a new Feeder</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onclick="return false">
                  <div class="form-group">
                    <label for="feeder-name" class="col-sm-4 control-label">Name of The Feeder</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="feeder-name" placeholder="Feeder Names">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="gmForFeeder" class="col-sm-4 control-label">Circle</label>
                    <div class="col-sm-8">
                      <select name="gmForFeeder" id="gmForFeeder" class="form-control">
                        <option value="">Choose a Circle</option>
                        <option value="1">GM-I</option>
                        <option value="2">GM-II</option>
                        <option value="3">GM-III</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group sub-form">
                    <label for="feed" class="col-sm-4 control-label">Sub-Station</label>
                    <div class="col-sm-8">
                      <select name="sub-form" id="sub-form" class="form-control">
                        <option value="">Choose Respective Sub-Station</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                      <button type="submit" class="btn btn-success" id="addNewFeeder">Add Feeder</button>
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modalLarge-removeSub" tabindex="-1" role="dialog" aria-labelledby="modalLargeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalLargeLabel">Remove Sub-Station/Sub-Stations</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onclick="return false">
                  <div class="form-group">
                    <label for="feed" class="col-sm-4 control-label">Sub-Station</label>
                    <div class="col-sm-8">
                      <select name="remove-Sub" id="remove-Sub" class="form-control" multiple="multiple" size="10">
                        <?php
                          $query = $res->getSubAll();
                          while($row = $query->fetch_assoc()){
                            echo "<option value='".$row['id']."'>".$row['name']."</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                      <button type="submit" class="btn btn-danger" id="remove-substation">Remove Sub-Station</button>
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modalLarge-removeFeeder" tabindex="-1" role="dialog" aria-labelledby="modalLargeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalLargeLabel">Remove Feeder/Feeders</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onclick="return false">
                  <div class="form-group">
                    <label for="sub_id_tobe" class="col-sm-4 control-label">Substation</label>
                    <div class="col-sm-8">
                      <select name="sub_id_tobe" class="form-control sub_id_tobe">
                        <option value="">Choose the Sub-Station</option>
                        <?php
                          $query = $res->getSubAll();
                          while($row = $query->fetch_assoc()){
                            echo "<option value='".$row['id']."'>".$row['name']."</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group sub-form-feed">
                    <label for="remove-feed" class="col-sm-4 control-label">Feeder</label>
                    <div class="col-sm-8">
                      <select id="remove-feed" class="form-control new-feed" multiple="multiple" size="10">
                        <option value="">Choose the Feeder</option>
                        
                      </select>
                    </div>
                  </div>
                  <!-- <div class="form-group ">
                    <label for="remove-feed" class="col-sm-4 control-label">Feeder</label>
                    <div class="col-sm-8">
                      <select name="remove-feed" id="remove-feed" class="form-control" multiple="multiple" size="10">
                        <option value="">Choose the Feeder</option>
                        <?php
                          $query = $res->getFeedersAll();
                          while($row = $query->fetch_assoc()){
                            echo "<option value='".$row['id']."'>".$row['feeder']."</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div> -->
                  <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                      <button type="submit" class="btn btn-danger" id="remove-feeder">Remove Feeder</button>
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalLarge-editSub" tabindex="-1" role="dialog" aria-labelledby="modalLargeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalLargeLabel">Edit Sub-Station/Sub-Stations</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onclick="return false">
                  <div class="form-group">
                    <label for="edit-Sub" class="col-sm-4 control-label">Sub-Station</label>
                    <div class="col-sm-8">
                      <select name="edit-Sub" id="edit-Sub" class="form-control" multiple="multiple" size="10">
                        <?php
                          $query = $res->getSubAll();
                          while($row = $query->fetch_assoc()){
                            echo "<option value='".$row['id']."'>".$row['name']."</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group user-edit" id="sub-edit">
                    <label for="new-edited-sub" class="col-sm-4 control-label">Sub-Station</label>
                      <div class="col-sm-8">
                        <input type="text" placeholder="Your New Edited Version" id="new-edited-sub" class="form-control">
                      </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                      <button type="submit" class="btn btn-danger" id="edit-substation">Edit Sub-Station</button>
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modalLarge-editFeeder" tabindex="-1" role="dialog" aria-labelledby="modalLargeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalLargeLabel">Edit Feeder/Feeders</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onclick="return false">
                  <div class="form-group">
                    <label for="sub_id_tobe" class="col-sm-4 control-label">Substation</label>
                    <div class="col-sm-8">
                      <select name="sub_id_tobe" class="form-control sub_id_tobe">
                        <option value="">Choose the Sub-Station</option>
                        <?php
                          $query = $res->getSubAll();
                          while($row = $query->fetch_assoc()){
                            echo "<option value='".$row['id']."'>".$row['name']."</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group sub-form-feed">
                    <label for="edit-feed" class="col-sm-4 control-label">Feeder</label>
                    <div class="col-sm-8">
                      <select name="feed-edit" id="edit" class="form-control new-feed" multiple="multiple" size="10">
                        <option value="">Choose the Feeder</option>
                        
                      </select>
                    </div>
                  </div>
                  <div class="form-group user-edit" id="feed-edit">
                    <label for="new-edited-feed" class="col-sm-4 control-label">Feeder</label>
                      <div class="col-sm-8">
                        <input type="text" placeholder="Your New Edited Version" id="new-edited-feed" class="form-control">
                      </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                      <button type="submit" class="btn btn-danger" id="edit-feeder">Edit Feeder</button>
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalBasic" tabindex="-1" role="dialog" aria-labelledby="modalBasicLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalBasicLabel"></h4>
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

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/components-setup.min.js"></script>
<script>
  $("#settings").addClass('active');
  var column = "<?= $res->column; ?>";
  var table = "<?= $res->table; ?>";
</script>
<script src="/js/settings.js"></script>
</div>
</body>