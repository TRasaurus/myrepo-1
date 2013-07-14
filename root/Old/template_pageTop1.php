<?php
// It is important for any file that includes this file, to have

 include_once("php_includes/check_login_status.php"); 

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
		$envelope = '<a href="notifications.php" title="Your notifications"><i class="icon-flag"></i></a>';
    } else {
	$envelope = '<a href="notifications.php" title="You have new notifications"><i class="icon-flag icon-white"></i></a>';
	}
    $loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a><a href="logout.php">Log Out</a>';
	
	///New Friend Button
	$sql = "SELECT id FROM friends WHERE user2='$log_username' AND accepted='0' Limit 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_fetch_row($query);
	if ($numrows == 0) {
		$new_friends = '<a href="notifications.php" title="Friend requests"><i class="icon-user "></i>
		</a>';
		} else {
		$new_friends = '<a href="notifications.php" title="Friend requests"><i class="icon-user icon-white"></i></a>';
		}		
}
?><head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    

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
  
</head>
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
                  
                  <li class="">
                   <a href="#notification" role="button" data-toggle="modal">Notifications</a>
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

  <!-- Notification Modal -->
    <div id="notification" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Notifcations</h3>
      </div>
      <div class="modal-body">
        <p>
       <?php include_once("notifcations.php"); ?>
        </p>
      </div>
    </div>




<?php endif; ?>

	