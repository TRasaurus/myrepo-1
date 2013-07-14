<?php
include_once("php_includes/check_login_status.php"); 


//Indicates if you have any new notifications
$notification_list = "";
$sql = "SELECT * FROM notifications WHERE username LIKE BINARY '$log_username' ORDER BY date_time DESC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);


if($numrows < 1){
	$notification_list = "You do not have any notifications";
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$noteid = $row["id"];
		$initiator = $row["initiator"];
		$app = $row["app"];
		$note = $row["note"];
		$date_time = $row["date_time"];
		$date_time = strftime("%b %d, %Y", strtotime($date_time));
		$notification_list .= "<p><a href='user.php?u=$initiator'>$initiator</a> | $app<br />$note</p>";
	}
}
mysqli_query($db_conx, "UPDATE users SET notescheck=now() WHERE username='$log_username' LIMIT 1");
?>
<?php
//Friend Request Notification
$friend_requests = "";
$sql = "SELECT * FROM friends WHERE user2='$log_username' AND accepted='0' ORDER BY datemade ASC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$friend_requests = 'No friend requests';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$reqID = $row["id"];
		$user1 = $row["user1"];
		$datemade = $row["datemade"];
		$datemade = strftime("%B %d", strtotime($datemade));
		$thumbquery = mysqli_query($db_conx, "SELECT avatar FROM users WHERE username='$user1' LIMIT 1");
		$thumbrow = mysqli_fetch_row($thumbquery);
		$user1avatar = $thumbrow[0];
		$user1pic = '<img src="user/'.$user1.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
		if($user1avatar == NULL){
			$user1pic = '<img src="images/avatardefault.jpg" alt="'.$user1.'" class="user_pic">';
		}
		$friend_requests .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
		$friend_requests .= '<a href="user.php?u='.$user1.'">'.$user1pic.'</a>';
		$friend_requests .= '<div class="user_info" id="user_info_'.$reqID.'">'.$datemade.' <a href="user.php?u='.$user1.'">'.$user1.'</a> requests friendship<br /><br />';
		$friend_requests .= '<button onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">accept</button> or ';
		$friend_requests .= '<button onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">reject</button>';
		$friend_requests .= '</div>';
		$friend_requests .= '</div>';
	}
}
?>
<?php
// It is important for any file that includes this file, to have
$pm_n = '<i class="icon-envelope"></i>';
$sql = "SELECT id FROM pm WHERE
(receiver='$log_username' AND parent='x' AND rdelete='0' AND rread='0')
OR
(sender='$log_username' AND sdelete='0' AND parent='x' AND hasreplies='1' AND sread='0') LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if ($numrows > 0) {
		$pm_n = '<a href="pm_inbox.php?u='.$log_username.'" title="Private Message Notification"><i class="icon-envelope icon-white"></i></a>';		
		} else {
			$pm_n = '<a href="pm_inbox.php?u='.$log_username.'" title="Private Message Notification"><i class="icon-envelope"></i></a>';
		}

//New Friends
$new_friends ='<img src="images/note_user.jpg" width="22" height="12" alt="Notes">';
$envelope = '<img src="images/note_dead.jpg" width="22" height="12" alt="Notes" title="This envelope is for logged in members">';

$loginLink = '<a href="login.php" class="">Log In</a> 

<a href="signup.php" class="">Sign Up</a>';

if($user_ok == true) {
	$sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$notescheck = $row[0];
	$sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
    if ($numrows == 0) {
		$envelope = '<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					  		<i class="icon-flag"></i>
					  	</a>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							 '.$notification_list.'
					  </ul>';				
    } else {
	$envelope = '<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					  		<i class="icon-flag icon-white"></i>
					  	</a>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							 '.$notification_list.'
					  </ul>';
	}
    $loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a><a href="logout.php">Log Out</a>';
	///New Friend Button
	$sql = "SELECT id FROM friends WHERE user2='$log_username' AND accepted='0' Limit 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_fetch_row($query);
	if ($numrows == 0) {
		$new_friends = '<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					  		<i class="icon-user"></i>
					  	</a>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							 '.$friend_requests.'
					  </ul>';
		} else {'<a class="dropdown-toggle" data-toggle="dropdown" href="#">	
							<i class="icon-user icon-white"></i>
				</a>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							 '.$friend_requests.'
					  </ul>';
		}		
}
?>
<style>
.form-search .input-append{
  display: inline-block;
  *display: inline;
  margin-bottom: 0;
  vertical-align: middle;
  *zoom: 1;
  padding: 5px 35px;
  position:fixed;
  right:5px;
}
	
	
</style>

<!--Java Script for Notificaitons-->  
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript">
function friendReqHandler(action,reqid,user1,elem){
	var conf = confirm("Press OK to '"+action+"' this friend request.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
				_(elem).innerHTML = "<b>Request Accepted!</b><br />Your are now friends";
			} else if(ajax.responseText == "reject_ok"){
				_(elem).innerHTML = "<b>Request Rejected</b><br />You chose to reject friendship with this user";
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid+"&user1="+user1);
}
</script>
  

<!--This is the top bar if user is NOT logged in-->
<?php if($user_ok== false):?> 



<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">

			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <br/>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
      		</a>
            
   			    <ul class="nav">
                  <li class="">
                    <a href="./index.php">mADcrowd</a>
                  </li>
                 </ul>

          <div class="nav-collapse collapse">
            <ul class="nav">

                  <li class="">
                    <a href="./about.php">About</a>
                  </li>
                  <li class="">
                    <a href="./gallery.php">Gallery</a>
                  </li>
                  <li class="">
                    <a href="./publishers.php">Publishers</a>
                  </li>
                  <li class="">
                    <a href="./users_all.php">Users</a>
                  </li>
                  <li class="">
                    <a href="./investors.php">Investors</a>
                  </li>
                 <li class="">                           
                    <a href="#login" role="button" data-toggle="modal">Log In</a>
                  </li>                
                  <li class="">
                   <a href="#signup" role="button" data-toggle="modal">Sign Up</a>
                  </li>  
                 </ul>
                 <form class="navbar-search pull-right" action="">
         			 <input type="text" class="search-query span2" placeholder="Search">
       			 </form>
          </div>
        </div>
      </div>
    </div>
  
  <!-- Log In Modal -->
    <div id="login" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Log In</h3>
      </div>
      <div class="modal-body">
        <p>
       <?php include_once("login.php"); ?>   
        </p>
      </div>
    </div>
    
      <!-- Sign Up Modal -->
    <div id="signup" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Sign Up</h3>
      </div>
      <div class="modal-body">
        <p>
       <?php include_once("signup.php"); ?>   
        </p>
      </div>
    </div>
    
    
 <!--This is the top bar if user IS logged in-->   
<?php elseif($user_ok == true): ?>
	
<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
      		</a> 
            	<ul class="nav">
                  <li class="">
                  	<?php echo $new_friends; ?>
                  </li>
                  <li class="">
                  	<?php echo $pm_n; ?>
                  </li>
                  <li class="">
                  	<?php echo $envelope; ?>
                  </li>
                  
                  

                  
                </ul>
    
          <div class="nav-collapse collapse">
            <ul class="nav">
                  <li class="">
                    <a href="
                    user.php?u=<?php echo $log_username; ?>">	
					<?php echo $log_username; ?></a>
                  </li>
                  <li class="">
                    <a href="./about.php">About</a>
                  </li>
                  <li class="">
                    <a href="./gallery.php">Gallery</a>
                  </li>
                  <li class="">
                    <a href="./publishers.php">Publishers</a>
                  </li>
                  <li class="">
                    <a href="./users_all.php">Users</a>
                  </li>
                  <li class="">
                    <a href="./investors.php">Investors</a>
                  </li>
                  <li class="">
                    <a href="./php_includes/logout.php">Log Out</a>
                  </li>
                </ul>
       
                 <form class="navbar-search pull-right" action="">
         			 <input type="text" class="search-query span2" placeholder="Search">
       			 </form>
                  
        </div>
      </div>
    </div>
</div>





<?php endif; ?>

	