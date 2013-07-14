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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <head>
    <meta charset="utf-8">
    <title>mADcrowd</title>
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
    
<div>
	<h3>Dude, you just 404'd it!</h3>
    <p>Sorry this page is not available.</p>
    <p>The link may be broken or the page may have been removed</p>
    
</div>


    
<!-- BEGIN Footer -->
  
     <footer>
       	<?php include_once("template_pageBottom.php"); ?>  
      </footer>
</body>
</html>
