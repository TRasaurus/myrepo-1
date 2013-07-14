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

	<?php include_once("template_pageTop.php"); ?>
    
    <!--Shameless marketing begin-->
<div class="container">

  <div class="marketing">

    <h1><strong>Meet mADcrowd</strong></h1>
    <p class="marketing-byline">Tired of online advertising? Look no further.</p>

    <div class="row-fluid">
    
    	<div class="span4">
        <img class="marketing-img" src="assets/img/mcce.jpg">
        <h2>Making online ads cool.</h2>
        <p>Let's face it: online advertising was never cool:
        </p>
        	<ul>
        		<li>88% of U.S. Internet users said they have been “flooded” with online ad spam. </li>
                <li>43% will ignore a company completely after seeing two irrelevant ads</li>
                <li>23% will do so after seeing just one.</li>
      </div>
      
      <div class="span4">
        <img class="marketing-img" src="assets/img/mcce.jpg">
        <h2>Online advertising: by the people, for the people.</h2>
        <p>mADcrowds brings online advertising to the people by using the crowd to create ad campaigns for their favorite brands</p>
      </div>
      
      <div class="span4">
        <img class="marketing-img" src="assets/img/mcce.jpg">
        <h2>Users and publishers profit from their content.</h2>
        <p>We believe that users should be rewarded for their creativity: the more popular your ad, the more money you make.  That simple.</p>
      </div>
    </div>

    <hr class="soften">
    
  </div>

 <div class="marketing">

    <p class="marketing-byline">Using the crowd, we create an innovative, demand-driven ad model that is cheaper, more effective, and democratic.</p>

</div>

<!--Shameless marketing end-->
    

        <?php include_once("template_pageBottom.php"); ?>   

</body>
</html>
