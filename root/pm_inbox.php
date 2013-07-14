<?php
include_once("php_includes/check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
$mail = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: index.php");
    exit();	
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
	echo "That user does not exist or is not yet activated, press back";
    exit();	
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){$isOwner = "yes";}
if($isOwner != "yes"){header("location: index.php");exit();}
// Get list of parent pm's not deleted
$sql = "SELECT * FROM pm WHERE 
(receiver='$u' AND parent='x' AND rdelete='0') 
OR 
(sender='$u' AND sdelete='0' AND parent='x' AND hasreplies='1') 
ORDER BY senttime DESC";
$query = mysqli_query($db_conx, $sql);
$statusnumrows = mysqli_num_rows($query);
// Gather data about parent pm's
if($statusnumrows > 0){
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$pmid = $row["id"];
		//div naming
		$pmid2 = 'pm_'.$pmid;
		$wrap = 'pm_wrap_'.$pmid;
		//button naming
		$btid2 = 'bt_'.$pmid;
		//textarea naming
		$rt = 'replytext_'.$pmid;
		//button naming
		$rb = 'replyBtn_'.$pmid;
		$receiver = $row["receiver"];
		$sender = $row["sender"];
		$subject = $row["subject"];
		$message = $row["message"];
		$time = $row["senttime"];
		$rread = $row["rread"];
		$sread = $row["sread"];
	
		// Start to build our list of parent pm's
		$mail .= '<div id="'.$wrap.'" class="pm_wrap">';
		$mail .= '<div class="pm_header">'.$subject.'<br /><br />';
		// Add button for mark as read
		$mail .= '<button onclick="markRead(\''.$pmid.'\',\''.$sender.'\')">Mark As Read</button>';
		// Add Delete button
		$mail .= '<button id="'.$btid2.'" onclick="deletePm(\''.$pmid.'\',\''.$wrap.'\',\''.$sender.'\')">Delete</button></div>';
		$mail .= '<div id="'.$pmid2.'">';//start expanding area
		$mail .= '<div class="pm_post">From: '.$sender.' - '.$time.'<br />'.$message.'</div>';
		
		// Gather up any replies to the parent pm's
		$pm_replies = "";
		$query_replies = mysqli_query($db_conx, "SELECT sender, message, senttime FROM pm WHERE parent='$pmid' ORDER BY senttime ASC");
		$replynumrows = mysqli_num_rows($query_replies);
    	if($replynumrows > 0){
			while ($row2 = mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
				$rsender = $row2["sender"];
				$reply = $row2["message"];
				$time2 = $row2["senttime"];
				$mail .= '<div class ="pm_post">Reply From: '.$rsender.' on '.$time2.'....<br />'.$reply.'<br /></div>';
			}
		}
		// Each parent and child is now listed
		$mail .= '</div>';
		// Add reply textbox
		$mail .= '<textarea id="'.$rt.'" placeholder="Reply..."></textarea><br />';
		// Add reply button
		$mail .= '<button id="'.$rb.'" onclick="replyToPm('.$pmid.',\''.$u.'\',\''.$rt.'\',\''.$rb.'\',\''.$sender.'\')">Reply</button>';
		$mail .= '</div>';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $u; ?>'s Messages</title>

 <!--Style from Bootstrap-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
     <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/css/docs.css" rel="stylesheet">
    <link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet">
        <!-- Icons -->
     <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
     
 <link rel="shortcut icon" href="assets/ico/temp_icon.ico">


<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script src="js/expand_retract.js"></script>
<script language="javascript" type="text/javascript">
function replyToPm(pmid,user,ta,btn,osender){	
	var data = _(ta).value;
	if(data == ""){
		alert("Type something first weenis");
		return false;
	}
	_(btn).disabled = true;
	var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "reply_ok"){
				var rid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				_("pm_"+pmid).innerHTML += '<p><b>Reply by you just now:</b><br />'+data+'</p>';
				expand("pm_"+pmid);
				_(btn).disabled = false;
				_(ta).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=pm_reply&pmid="+pmid+"&user="+user+"&data="+data+"&osender="+osender);
}
function deletePm(pmid,wrapperid,originator){
	var conf = confirm(originator+"Press OK to confirm deletion of this message and its replies");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(wrapperid).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=delete_pm&pmid="+pmid+"&originator="+originator);
}
function markRead(pmid,originator){
	var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "read_ok"){
				alert("Message has been marked as read");
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=mark_as_read&pmid="+pmid+"&originator="+originator);
}
</script>
<style type="text/css">
div.pm_wrap {
	border: 1px solid #333;
	margin-bottom: 5px;
	width: 400px;
	margin-right: auto;
	margin-left: auto;
}
div.pm_header {
	background-color: #CCC;
	padding-left: 20px;
}
div.pm_post {
	margin-top: 10px;
	padding-left: 20px;
}
</style>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<?php echo $mail; ?>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>