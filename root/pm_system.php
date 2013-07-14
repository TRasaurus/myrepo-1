<?php
// Protect this script from direct url access
include_once("../php_includes/check_login_status.php");
if($user_ok != true || $log_username == "") {
	exit();
}
?><?php
// New PM
if (isset($_POST['action']) && $_POST['action'] == "new_pm"){
	// Make sure post data is not empty
	if(strlen($_POST['data']) < 1){
		mysqli_close($db_conx);
	    echo "data_empty";
	    exit();
	}
	// Make sure post data is not empty
	if(strlen($_POST['data2']) < 1){
		mysqli_close($db_conx);
	    echo "data_empty";
	    exit();
	}	
	
	// Clean all of the $_POST vars that will interact with the database
	$fuser = preg_replace('#[^a-z0-9]#i', '', $_POST['fuser']);
	$tuser = preg_replace('#[^a-z0-9]#i', '', $_POST['tuser']);
	$data = htmlentities($_POST['data']);
	$data = mysqli_real_escape_string($db_conx, $data);
	$data2 = htmlentities($_POST['data2']);
	$data2 = mysqli_real_escape_string($db_conx, $data2);
	
	// Make sure account name exists (the profile being posted on)
	$sql = "SELECT COUNT(id) FROM users WHERE username='$tuser' AND activated='1' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	if($row[0] < 1){
		mysqli_close($db_conx);
		echo "$account_no_exist";
		exit();
	}
	//No message to yourself
	if ($log_username == $tuser){
		echo "cannot_message_self";
		exit();
	}
	// Insert the status post into the database now
	$defaultP = "x";
	$sql = "INSERT INTO pm(receiver, sender, senttime, subject, message, parent) 
			VALUES('$tuser','$fuser',now(),'$data2','$data','$defaultP')";
	$query = mysqli_query($db_conx, $sql);
	mysqli_close($db_conx);
	echo "pm_sent";
	exit();
}
?><?php
// Reply To PM
if (isset($_POST['action']) && $_POST['action'] == "pm_reply"){
	// Make sure data is not empty
	if(strlen($_POST['data']) < 1){
		mysqli_close($db_conx);
	    echo "data_empty";
	    exit();
	}
	// Clean the posted variables
	$osid = preg_replace('#[^0-9]#', '', $_POST['pmid']);
	$account_name = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);
	$osender = preg_replace('#[^a-z0-9]#i', '', $_POST['osender']);
	$data = htmlentities($_POST['data']);
	$data = mysqli_real_escape_string($db_conx, $data);
	// Make sure account name exists (the profile being posted on)
	$sql = "SELECT COUNT(id) FROM users WHERE username='$account_name' AND activated='1' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	if($row[0] < 1){
		mysqli_close($db_conx);
		echo "account_no_exist";
		exit();
	}
	// Insert the pm reply post into the database now
	$x = "x";
	$sql = "INSERT INTO pm(receiver, sender, senttime, subject, message, parent)
	        VALUES('$x','$account_name',now(),'$x','$data','$osid')";
	$query = mysqli_query($db_conx, $sql);	
	$id = mysqli_insert_id($db_conx);
	
	if ($log_username != $osender){
		$query2 = mysqli_query($db_conx, "UPDATE pm SET hasreplies='1', rread='1', sread='0' WHERE id='$osid' LIMIT 1");
	} else {
		$query2 = mysqli_query($db_conx, "UPDATE pm SET hasreplies='1', rread='0', sread='1' WHERE id='$osid' LIMIT 1");
	}
	mysqli_close($db_conx);
	echo "reply_ok|$id";
	exit();
}
?><?php
// Delete PM
if (isset($_POST['action']) && $_POST['action'] == "delete_pm"){
	if(!isset($_POST['pmid']) || $_POST['pmid'] == ""){
		mysqli_close($db_conx);
		echo "id_missing";
		exit();
	}
	$pmid = preg_replace('#[^0-9]#', '', $_POST['pmid']);
	if(!isset($_POST['originator']) || $_POST['originator'] == ""){
		mysqli_close($db_conx);
		echo "originator_missing";
		exit();
	}
	$originator = preg_replace('#[^a-z0-9]#i', '', $_POST['originator']);
	// see who is deleting
	if ($originator == $log_username) {
		$updatedelete = mysqli_query($db_conx, "UPDATE pm SET sdelete='1' WHERE id='$pmid' LIMIT 1");
		}
	if ($originator != $log_username) {
		$updatedelete = mysqli_query($db_conx, "UPDATE pm SET rdelete='1' WHERE id='$pmid' LIMIT 1");
		}
	mysqli_close($db_conx);
	echo "delete_ok";
	exit();
}
?><?php
// Mark As Read
if (isset($_POST['action']) && $_POST['action'] == "mark_as_read"){
	if(!isset($_POST['pmid']) || $_POST['pmid'] == ""){
		mysqli_close($db_conx);
		echo "id_missing";
		exit();
	}
	$pmid = preg_replace('#[^0-9]#', '', $_POST['pmid']);
	if(!isset($_POST['originator']) || $_POST['originator'] == ""){
		mysqli_close($db_conx);
		echo "originator_missing";
		exit();
	}
	$originator = preg_replace('#[^a-z0-9]#i', '', $_POST['originator']);
	// see who is marking as read
	if ($originator == $log_username) {
		$updatedelete = mysqli_query($db_conx, "UPDATE pm SET sread='1' WHERE id='$pmid' LIMIT 1");
		}
	if ($originator != $log_username) {
		$updatedelete = mysqli_query($db_conx, "UPDATE pm SET rread='1' WHERE id='$pmid' LIMIT 1");
		}
	mysqli_close($db_conx);
	echo "read_ok";
	exit();
}
?>
