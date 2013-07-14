111

<?php
// Protect this script from direct url access
// You may further enhance this protection by checking for certain sessions and other means
if ((!isset($isFriend)) || (!isset($isOwner))){
	exit;
}
// Initialize our ui
$pm_ui = "";
// If visitor to profile is a friend and is not the owner can send you a pm
// Build ui carry the profile id, vistor name, pm subject and comment to js
if($isFriend == true && $isOwner == "no"){
	$pm_ui = "<hr>";
	$pm_ui .= '<input id="pmsubject" onkeyup="statusMax(this,30)" placeholder="Subject of pm..."><br />';
	$pm_ui .= '<textarea id="pmtext" onkeyup="statusMax(this,250)" placeholder="Send '.$u.' a private message"></textarea>';
	$pm_ui .= '<button id="pmBtn" onclick="postPm(\''.$u.'\',\''.$log_username.'\',\'pmsubject\',\'pmtext\')">Send</button>';
}
?>
<script>
function postPm(tuser,fuser,subject,ta){
	var data = _(ta).value;
	var data2 = _(subject).value;
	if(data == "" || data2 == ""){
		alert("Fill all fields");
		return false;
	}
	_("pmBtn").disabled = true;
	var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "pm_sent"){
				alert("Message has been sent.");
				_("pmBtn").disabled = false;
				_(ta).value = "";
				_(subject).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=new_pm&fuser="+fuser+"&tuser="+tuser+"&data="+data+"&data2="+data2);
}
function statusMax(field, maxlimit) {
	if (field.value.length > maxlimit){
		alert(maxlimit+" maximum character limit reached");
		field.value = field.value.substring(0, maxlimit);
	}
}
</script>
<div id="statusui">
  <?php echo $pm_ui; ?>
</div>