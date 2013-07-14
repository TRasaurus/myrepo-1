<?php
include_once("php_includes/check_login_status.php");
// Make sure the _GET "u" is set, and sanitize it
$u = "";
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: http://www.projectmadcrowd.com");
    exit();	
}
$photo_form = "";
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
	$photo_form  = '<form id="photo_form" enctype="multipart/form-data" method="post" action="php_parsers/photo_system_test.php">';
	$photo_form .=   '<h4>Hi '.$u.', add a new photo to your gallery</h4>';
	$photo_form .=   '<p><div class="btn btn-primary" type="button"><input type="file" name="photo" accept="image/*" title="" required>Choose Photo</div></p>';
	$photo_form .=   '<p><input type="submit" value="Upload" class="btn btn-primary"></p>';
	$photo_form .= '</form>';
}?><?php
// Select the user galleries
$gallery_list = "";
$sql = "SELECT DISTINCT gallery FROM photos WHERE user='$u'";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) < 1){
	$gallery_list = "This user has not uploaded any photos yet.";
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$gallery = $row["gallery"];
		$countquery = mysqli_query($db_conx, "SELECT COUNT(id) FROM photos WHERE user='$u' AND gallery='$gallery'");
		$countrow = mysqli_fetch_row($countquery);
		$count = $countrow[0];
		$filequery = mysqli_query($db_conx, "SELECT filename FROM photos WHERE user='$u' AND gallery='$gallery' ORDER BY RAND() LIMIT 1");
		$filerow = mysqli_fetch_row($filequery);
		$file = $filerow[0];
		$gallery_list .= '<div>';
		$gallery_list .=   '<div onclick="showGallery(\''.$gallery.'\',\''.$u.'\')">';
		$gallery_list .=     '<img src="user/'.$u.'/'.$file.'" alt="cover photo">';
		$gallery_list .=   '</div>';
		$gallery_list .=   '<b>'.$gallery.'</b> ('.$count.')';
		$gallery_list .= '</div>';
    }
}
?>

<style type="text/css">
input[type="file"] {opacity: 0; width:5px;}
div#galleries{}
div#galleries > div{float:left; margin:20px; text-align:center; cursor:pointer;}
div#galleries > div > div {height:100px; overflow:hidden;}
div#galleries > div > div > img{width:150px; cursor:pointer;}
div#photos{display:none; border:#666 1px solid; padding:20px;}
div#photos > div{float:left; width:125px; height:80px; overflow:hidden; margin:20px;}
div#photos > div > img{width:125px; cursor:pointer;}
div#picbox{display:none; padding-top:36px;}
div#picbox > img{max-width:800px; display:block; margin:0px auto;}
div#picbox > button{ display:block; float:right; font-size:36px; padding:3px 16px;}

<!--CSS for Infinante Scroll Function-->
@charset "utf-8";

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
img{
	border:8px solid #444;
	-webkit-border-radius: 10px;
	
 	width: auto\9;
  	height: auto;
  	max-width: 100%;
  	vertical-align: middle;
  	border: 0;
  	-ms-interpolation-mode: bicubic;
}
img:hover{
	border-color:#555;
	-moz-box-shadow: 0px 0px 15px #111;
	-webkit-box-shadow: 0px 0px 15px #111;
}


div#userphotos > img{width:140px; height:120px; margin:37px 0px 0px 9px;}
</style>  
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script><!--Won't need first part once gallery is built. -->
function showGallery(gallery,user){
	_("galleries").style.display = "none";
	_("section_title").innerHTML = user+'&#39;s '+gallery+' Gallery &nbsp; <button onclick="backToGalleries()">Go back to all galleries</button>';
	_("photos").style.display = "block";
	_("photos").innerHTML = 'loading photos ...';
	var ajax = ajaxObj("POST", "php_parsers/photo_system_test.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			_("photos").innerHTML = '';
			var pics = ajax.responseText.split("|||");
			for (var i = 0; i < pics.length; i++){
				var pic = pics[i].split("|");
				_("photos").innerHTML += '<div><img onclick="photoShowcase(\''+pics[i]+'\')" src="user/'+user+'/'+pic[1]+'" alt="pic"><div>';
			}
			_("photos").innerHTML += '<p style="clear:left;"></p>';
		}
	}
	ajax.send("show=galpics&gallery="+gallery+"&user="+user);
}
function backToGalleries(){
	_("photos").style.display = "none";
	_("section_title").innerHTML = "<?php echo $u; ?>&#39;s Photo Galleries";
	_("galleries").style.display = "block";
}
function photoShowcase(picdata){
	var data = picdata.split("|");
	_("section_title").style.display = "none";
	_("photos").style.display = "none";
	_("picbox").style.display = "block";
	_("picbox").innerHTML = '<button onclick="closePhoto()">x</button>';
	_("picbox").innerHTML += '<img src="user/<?php echo $u; ?>/'+data[1]+'" alt="photo">';
	if("<?php echo $isOwner ?>" == "yes"){
		_("picbox").innerHTML += '<p id="deletelink"><a href="#" onclick="return false;" onmousedown="deletePhoto(\''+data[0]+'\')">Delete this Photo <?php echo $u; ?></a></p>';
	}
}
function closePhoto(){
	_("picbox").innerHTML = '';
	_("picbox").style.display = "none";
	_("photos").style.display = "block";
	_("section_title").style.display = "block";
}
function deletePhoto(id){
	var conf = confirm("Press OK to confirm the delete action on this photo.");
	if(conf != true){
		return false;
	}
	_("deletelink").style.visibility = "hidden";
	var ajax = ajaxObj("POST", "php_parsers/photo_system_test.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "deleted_ok"){
				alert("This picture has been deleted successfully. We will now refresh the page for you.");
				window.location = "photos.php?u=<?php echo $u; ?>";
			}
		}
	}
	ajax.send("delete=photo&id="+id);
}
</script>

<!--JS for scroll function-->
<script>
var contentHeight = 800;
var pageHeight = document.documentElement.clientHeight;
var scrollPosition;
var n = 10;
var xmlhttp;

function putImages(){
	
	if (xmlhttp.readyState==4) 
	  {
		  if(xmlhttp.responseText){
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
					  }
				  }
			  }
		  }
	  }
}
		
		
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
	}
}
</script>
  <div class="row-fluid">
      <div id="photo_form"><?php echo $photo_form; ?></div>
      <p id="section_title"><?php echo $u; ?>&#39;s Photo Galleries</p>
      <div id="galleries"><?php echo $gallery_list; ?></div>    
      <div id="photos"></div>
      <div id="picbox"></div>

  </div>
<!--Scroll function-->  
<div id="header">Infinite Scroll</div>   
  <div id="container">
	<?php include_once("php_parsers/photo_pull_test.php"); ?>
    <p><?php echo $count; ?> Images Displayed</p>
    <br />
  </div>
 
