<?php
// It is important for any file that includes this file, to have
// check_login_status.php included at its very top.

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
		$envelope = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/note_still.jpg" width="22" height="12" alt="Notes"></a>';
    } else {
		$envelope = '<a href="notifications.php" title="You have new notifications"><img src="images/note_flash.gif" width="22" height="12" alt="Notes"></a>';
	}
	
    $loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a> 
	
	<a href="logout.php">Log Out</a>';
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
}
</style>


	
	    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <div class="nav-collapse collapse">
            <ul class="nav">
                  <li class="">
                    <a href="./index.php">Home</a>
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
                    <a href="./users.php">Users</a>
                  </li>
              
                  <li class="">
                    <a href="./investors.php">Investors</a>
                  </li>
                  <li class="">
                    <a href="login.php" class="">Log In</a> 
                  </li>
                   <li class="">
                    <a href="./signup.php">Sign Up</a>
                  </li>  
                  <li class="form-search">
            		<form  class="input-append"> 			
                            <input type="text" class="span2 search-query">
                            <button type="submit" class="btn">Search</button>          
              		</form>
                  </li>
               
             </ul>
          </div>
        </div>
      </div>
    </div>
	
	
	
	
	
	
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <div class="nav-collapse collapse">
            <ul class="nav">
                  <li class="">
                    <a href="./index.php">Home</a>
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
                    <a href="./users.php">Users</a>
                  </li>
              
                  <li class="">
                    <a href="./investors.php">Investors</a>
                  </li>
                  <li class="">
                    <a href="login.php" class="">Log In</a> 
                  </li>
                   <li class="">
                    <a href="./signup.php">Sign Up</a>
                  </li>  
                  <li class="form-search">
            		<form  class="input-append"> 			
                            <input type="text" class="span2 search-query">
                            <button type="submit" class="btn">Search</button>          
              		</form>
                  </li>
               
             </ul>
          </div>
        </div>
      </div>
    </div>