<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">            
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Admin Panel</a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li id="reports"><a href="reports"><i class="fa fa-bullseye"></i> Dashboard</a></li>
            <li id="add"><a href="add"><i class="fa fa-tasks"></i> Add New Reports</a></li>                    
            <li id="update"><a href="update"><i class="fa fa-globe"></i> Update Reports</a></li>
            <li id="settings"><a href="settings"><i class="fa fa-list-ol"></i> Settings</a></li>
            <li class="logout"><a href="#"><i class="fa fa-list-ul"></i> Logout</a></li>
            <!-- <li><a href="typography.html"><i class="fa fa-font"></i> Typography</a></li>
            <li><a href="bootstrap-grid.html"><i class="fa fa-table"></i > Bootstrap Grid</a></li>  -->
        </ul>
        <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown messages-dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Unread Messages <span class="badge"><?= isset($messageCount) ? $messageCount: '0'; ?></span> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li class="dropdown-header"><?= isset($messageCount) ? $messageCount . ' Unread Messages' : 'No Message'; ?></li>
                    <?php while($row = $message[1]->fetch_assoc()){
                        echo '
                        <li class="message-preview"><span class="avatar"><i class="fa fa-bell"></i></span>';
                                if($row['message'] == 'delete'){
                                        echo '<span class="message text-danger">';
                                    }else{
                                        echo '<span class="message text-success">';
                                    }
                                if($row['feeder_id'] && $row['reply'] == ''){
                                    echo ucwords($row["message"]) .' Alert For the Feeder '.ucwords($res->getFeederName($row["feeder_id"])).' of the Substation '.ucwords($res->getSubName($row["sub_id"])) .'.</span>';
                                }else if($row['feeder_id'] == '' && $row['reply'] == ''){
                                    echo ucwords($row["message"]).' Alert For the Substation '.ucwords($res->getSubName($row["sub_id"])).'.</span>';
                                } else if($row['reply'] == 1){
                                    echo ucwords($row["message"]).' Alert For the the Substation '.ucwords($res->getSubName($row["sub_id"])).' has been accepted.</span>';
                                } else if($row['reply'] == 0){
                                    echo ucwords($row["message"]).' Alert For the the Substation '.ucwords($res->getSubName($row["sub_id"])).' has been decline.</span>';
                                }
                                if($row['logic'] == 1){
                                    echo '<span data-type="'.$row["id"].'"><button class="btn btn-success logic-noti" data-noti="1">Accept</button>&nbsp<button class="btn btn-danger logic-noti" data-noti="0">Decline</button></span>';
                                }
                            echo'
                        </li>';
                    } ?>
                    <li class="divider"></li>
                    <li class="message-preview">
                        <a href="#">
                            <span class="avatar"><i class="fa fa-bell"></i></span>
                            <span class="message">Security alert</span>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="#">Go to Inbox <span class="badge"><?= isset($messageCount) ? $messageCount: '0'; ?></span></a></li>
                </ul>
            </li>
            <li class="dropdown user-dropdown">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?= $_SESSION['username']; ?><b class="caret"></b></a>
               <ul class="dropdown-menu">
                   <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                   <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
                   <li class="divider"></li>
                   <li class="logout"><a href="#"><i class="fa fa-power-off"></i> Log Out</a></li>
               </ul>
           </li>
        </ul>
    </div>
</nav>