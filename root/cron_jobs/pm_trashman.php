<?php
include_once ("php_includes/db_conx.php");
// Private Message Database Trashman Cron Job
// Delete when receiver has never replied and has deleted
$query = mysqli_query($db_conx, "SELECT id FROM pm WHERE parent='x' AND rdelete='1' AND hasreplies='0'");
$numrows = mysqli_num_rows($query);
if($numrows > 0){
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$id = $row["id"];
		$query2 = mysqli_query($db_conx, "DELETE FROM pm WHERE id='$id'");
	}
}
// Delete when both users have checked delete, also delete replies
$query3 = mysqli_query($db_conx, "SELECT id FROM pm WHERE parent='x' AND sdelete='1' AND rdelete='1'");
$numrows3 = mysqli_num_rows($query3);
if($numrows3 > 0){
	while ($row3 = mysqli_fetch_array($query3, MYSQLI_ASSOC)) {
		$id3 = $row3["id"];
		$query4 = mysqli_query($db_conx, "DELETE FROM pm WHERE id='$id3'");
		// Gather List Of Replies And Delete Them		
		$query5 = mysqli_query($db_conx, "SELECT id FROM pm WHERE parent='$id3'");
		$numrows5 = mysqli_num_rows($query5);
		if($numrows5 > 0){
			while ($row5 = mysqli_fetch_array($query5, MYSQLI_ASSOC)) {
				$id5 = $row5["id"];
				$query6 = mysqli_query($db_conx, "DELETE FROM pm WHERE id='$id5'");
			}
		}		
	}
}
?>