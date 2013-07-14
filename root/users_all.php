<?php
include_once("php_includes/check_login_status.php");
$sql="SELECT username FROM users WHERE activated='1'";
$query = mysqli_query($db_conx, $sql);
$usernumrows = mysqli_num_rows($query);
$userlist = "";
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$u = $row["username"];
    $userlist .= '<a href="user.php?u='.$u.'">'.$u.'</a> &nbsp; | &nbsp';
}
?><?php
$allHTML = '';
$all_view_all_link = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$all_count = $query_count[0];

	$all_users = array();
	$sql = "SELECT user1 FROM friends ORDER BY RAND()";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_users, $row["user1"]);
	}
	$sql = "SELECT user2 FROM friends ORDER BY RAND()";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_users, $row["user2"]);
	}
	
	$orLogic = '';
	foreach($all_users as $key => $user){
			$orLogic .= "username='$user' OR ";
	}
	$orLogic = chop($orLogic, "OR ");
	$sql = "SELECT username, avatar FROM users WHERE $orLogic ORDER BY RAND()";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$all_username = $row["username"];
		$all_avatar = $row["avatar"];
		if($all_avatar != ""){
			$all_pic = 'user/'.$all_username.'/'.$all_avatar.'';
		} else {
			$all_pic = 'images/avatardefault.jpg';
		}
		$allHTML .= '<a href="user.php?u='.$all_username.'"><img id="users" src="'.$all_pic.'" alt="'.$all_username.'" title="'.$all_username.'"></a>';
	}

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <head>
    <meta charset="utf-8">
    <title>mADcrowd Terms</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/css/docs.css" rel="stylesheet">
    <link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet">


    <!-- Icons -->
 <link rel="shortcut icon" href="assets/ico/temp_icon.ico">
 
</head>

<body>

<!-- BEGIN Navbar -->
		<?php include_once("template_pageTop.php"); ?>
    
<!--All users-->
<br></br>

        <div class="hero-unit">
            <p><?php echo $allHTML; ?></p> 
        </div>

<!-- BEGIN Footer -->
  
     <footer>
       	<?php include_once("template_pageBottom.php"); ?>  
      </footer>
</body>
</html>
