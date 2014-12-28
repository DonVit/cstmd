<?php
// MySQL connection properties
$dbhost="localhost";
$dbname="15211";
$dbuser="15211";
$dbpass="L5Jwuj";

//Adimin Email
$adminname="Gica Petrescu";
$adminemail="gica.petrescu@gmail.com";

//Set to how many results you want displayed per page. 
$page_limit = "14";  

//Directory to upload images
$uploaddir = 'uploaded_images/';

//Connect to MySQL
$dbconnect=mysql_connect ("$dbhost", "$dbuser", "$dbpass") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbname");

?>