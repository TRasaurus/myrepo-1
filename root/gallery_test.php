<?php
	$dir = "thumb";
	if(is_dir($dir)){
		if($dd = opendir($dir)){
			while (($f = readdir($dd)) !== false)
				if($f != "." && $f != "..")
					$files[] = $f;
			closedir($dd);} 
	$n = $_GET["n"];
	$response = "";
		for($i = $n; $i<$n+9; $i++){
			$response = $response.$files[$i%count($files)].';';
		}
		echo $response;
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
      
<style> 
@charset "utf-8";
/* CSS Document */

body{
	background:#222;
	color:#666;
}

#header{
	font-family:Arial, Helvetica, sans-serif;
	font-size:24px;
	font-weight:bold;
	text-align:left;
	text-indent:35px;
	margin: 0 auto;
	width:800px;
	margin-bottom:10px;
}

hr{
	margin: 20px;
	border:none;
	border-top: 1px solid #111;
	border-bottom: 1px solid #333;
}

p{
	color:#444;
	text-align:left;
	font-size:10px;
	margin-left: 20px;
	margin-bottom: -10px;
}

a{
	color:#444;
}



#container{
	margin: 0 auto;
	width:800px;
	border:1px solid #333;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	font-family:Verdana, Geneva, sans-serif;
	text-align:center;
}

img{
	border:10px solid #444;
	-moz-border-radius: 5px;
	-webkit-border-radius: 10px;
	margin: 15px;
}

img:hover{
	border-color:#555;
	-moz-box-shadow: 0px 0px 15px #111;
	-webkit-box-shadow: 0px 0px 15px #111;
}

</style>   
     
       
</head>
<?php include_once("template_pageTop.php"); ?>
<body onload="setInterval('scroll();', 250);">
<div id="container">
	<a href="img/Achievements.jpg"><img src="thumb/Achievements.jpg" /></a>
    <a href="img/Bw.jpg"><img src="thumb/Bw.jpg" /></a>
    <a href="img/Camera.jpg"><img src="thumb/Camera.jpg" /></a><br />
    <a href="img/Cat-Dog.jpg"><img src="thumb/Cat-Dog.jpg" /></a>
    <a href="img/CREATIV.jpg"><img src="thumb/CREATIV.jpg" /></a>
    <a href="img/creativ2.jpg"><img src="thumb/creativ2.jpg" /></a><br />
    <a href="img/Earth.jpg"><img src="thumb/Earth.jpg" /></a>
    <a href="img/Endless.jpg"><img src="thumb/Endless.jpg" /></a>
    <a href="img/EndlesSlights.jpg"><img src="thumb/EndlesSlights.jpg" /></a>
    <p>9 Images Displayed | <a href="#header">top</a></p>
    <br />
    <hr />
</div>

<?php include_once("template_pageBottom.php"); ?>  
</body>
</html>


<script>
var contentHeight = 800;
var pageHeight = document.documentElement.clientHeight;
var scrollPosition;
var n = 10;
var xmlhttp;
function putImages(){
	if (xmlhttp.readyState==4) 
	  {if(xmlhttp.responseText){
			 var resp = xmlhttp.responseText.replace("\r\n", ""); 
			 var files = resp.split(";");
			  var j = 0;
			  for(i=0; i<files.length; i++){
				  if(files[i] != ""){
					 document.getElementById("container").innerHTML += '<a href="img/'+files[i]+'"><img src="thumb/'+files[i]+'" /></a>';
					 j++;
				  
					 if(j == 3 || j == 6)
						  document.getElementById("container").innerHTML += '<br />';
					  else if(j == 9){
						  document.getElementById("container").innerHTML += '<p>'+(n-1)+" Images Displayed | <a href='#header'>top</a></p><br /><hr />";
						  j = 0;
}}}}}}	
function scroll(){
	if(navigator.appName == "Microsoft Internet Explorer")
		scrollPosition = document.documentElement.scrollTop;
	else
		scrollPosition = window.pageYOffset;		
	if((contentHeight - pageHeight - scrollPosition) < 500){		
		if(window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest();
		else
			if(window.ActiveXObject)
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			else
				alert ("Bummer! Your browser does not support XMLHTTP!");		    
		var url="getImages.php?n="+n;
		xmlhttp.open("GET",url,true);
		xmlhttp.send();
		n += 9;
		xmlhttp.onreadystatechange=putImages;		
		contentHeight += 800;		
}}
</script>

