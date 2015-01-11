<?php 

ini_set("session.cookie_domain", ".casata"); 
session_save_path("C:\\xampp\\tmp\\"); 
session_start(); 

$_SESSION['test'] = 'conf.ro domain'; 

echo '<a href="http://news.casata/fisier-video-test.php">test</a>'; 
echo session_save_path(); 

?> 