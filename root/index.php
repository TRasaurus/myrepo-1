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
// If the page requestor is logged in, send them to their page
//if($user_ok = true){
//	header("location: http://www.projectmadcrowd.com/");
 //   exit();
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <head>
    <meta charset="UTF-8">
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
                                   
    <!-- Custom Style -->
<style>
 	/*CUSTOMIZE THE CAROUSEL*/
    /* Carousel base class */
    .carousel {
      margin-bottom: 60px;
    }

    .carousel .container {
      position: relative;
      z-index: 9;
    }

    .carousel-control {
      height: 80px;
      margin-top: 0;
      font-size: 120px;
      text-shadow: 0 1px 1px rgba(0,0,0,.4);
      background-color: transparent;
      border: 0;
      z-index: 10;
    }

    .carousel .item {
      height: 500px;
    }
    .carousel img {
      position: absolute;
      top: 0;
      left: 0;
      min-width: 100%;
      height: 500px;
    }

    .carousel-caption {
      background-color: transparent;
      position: static;
      max-width: 550px;
      padding: 0 20px;
      margin-top: 200px;
    }
    .carousel-caption h1,
    .carousel-caption .lead {
      margin: 0;
      line-height: 1.25;
      color: #fff;
      text-shadow: 0 1px 1px rgba(0,0,0,.4);
    }
    .carousel-caption .btn {
      margin-top: 10px;
    }
	
	    /* Featurettes
    ------------------------- */
    .featurette-divider {
      margin: 80px 0; /* Space out the Bootstrap <hr> more */
    }
    .featurette {
      padding-top: 120px; /* Vertically center images part 1: add padding above and below text. */
      overflow: hidden; /* Vertically center images part 2: clear their floats. */
    }
    .featurette-image {
      margin-top: -120px; /* Vertically center images part 3: negative margin up the image the same amount of the padding to center it. */
    }

    /* Give some space on the sides of the floated elements so text doesn't run right into it. */
    .featurette-image.pull-left {
      margin-right: 40px;
    }
    .featurette-image.pull-right {
      margin-left: 40px;
    }
	/* RESPONSIVE CSS*/

    @media (max-width: 979px) {

      .container.navbar-wrapper {
        margin-bottom: 0;
        width: auto;
      }
      .navbar-inner {
        border-radius: 0;
        margin: -20px 0;
      }

      .carousel .item {
        height: 500px;
      }
      .carousel img {
        width: auto;
        height: 500px;
      }

      .featurette {
        height: auto;
        padding: 0;
      }
      .featurette-image.pull-left,
      .featurette-image.pull-right {
        display: block;
        float: none;
        max-width: 40%;
        margin: 0 auto 20px;
      }
</style>
</head>

<body>

<!--Top Navbar-->
	<?php include_once("template_pageTop.php"); ?>

   

 	<!--BEGIN Carousel-->
    <div id="myCarousel" class="carousel slide">
      <div class="carousel-inner">
        <div class="item active">
          <img src="assets/img/cover.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>mADcrowd</h1>
              <p class="lead">Create. Share. Publish.</p>

              <a href="#signup" role="button" class="btn btn-large btn-primary" data-toggle="modal">Sign Up Today!</a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="assets/img/cover.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Crowdsourced Advertising</h1>
              <p class="lead">mADcrowd lets you share your ideas with the world.</p>
              <a class="btn btn-large btn-primary" href="about.html">Learn more</a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="assets/img/cover.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>MADison Avenue at your fingure tips.</h1>
              <p class="lead">Share your idea for great ads.</p>
              <a class="btn btn-large btn-primary" href="gallery.html">Browse gallery</a>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
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
    
 
 

 <!-- BEGIN Footer -->
	<?php include_once("template_pageBottom.php"); ?>      

</body>
</html>

