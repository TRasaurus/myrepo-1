<?php //Call to get photos for gallery
$userphotos = "";
			function cleanString($caption="")
				{
					$removeunderscore = str_replace("_"," ",$caption);
					$removedash = str_replace("-"," ",$removeunderscore);
					$removedimensions = str_replace("1366x768","",$removedash);
					$cleanstring = str_replace(".jpg","",$removedimensions);	
					return $cleanstring;
				}
$sql = "SELECT filename FROM photos WHERE user='$u' ORDER BY uploaddate ASC";
$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	{
		$id = $row['id'];
		echo '<div id="container">
					<div id="userphotos">
						<a href="uploads/'. $id .'"  title="'.cleanString($id).'" class="thickbox">
							<img onclick="photoShowcase('.$row[filename].')" src="user/'.$u.'/'.$row[filename].'"width="100" height="100" alt="pic" />
						</a>
					</div>	
				<div id="info"><strong>' .cleanString($id).'</div>
			  </div>';
		}
	}
?>