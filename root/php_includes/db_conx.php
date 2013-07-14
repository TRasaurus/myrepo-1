<?php
$db_conx = mysqli_connect("localhost", "proje174_admin", "Savannah99!!", "proje174_madcrowd");
// Evaluate the connection
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
} 
?>