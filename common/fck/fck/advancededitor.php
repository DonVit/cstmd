<?php
header('Content-type: text/html; charset="utf-8"');
include("lib/fck/fckeditor.php") ;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title>FCKeditor - Sample</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="robots" content="noindex, nofollow">
		<link href="sample.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
<form method="post">
<table align="center" width="90%" >
<tr><td>
<?php

$text="enter the text here";

if (isset($_GET['id'])){

$id=$_GET['id'];

$link = mysql_connect('localhost', 'root', 'vxd');
if (!$link) {
    die('Not connected : ' . mysql_error());
}

// make fck the current db
$db_selected = mysql_select_db('fck', $link);
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysql_error());
}

mysql_query("SET CHARACTER SET 'utf8'", $link);


if ($_POST){


	$result = mysql_query('update news set `text`="'.$_POST['FCKeditor1'].'" WHERE id ='.$id);
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	}
}



$result = mysql_query('SELECT * FROM news WHERE id='.$id);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

while ($row = mysql_fetch_assoc($result)) {
    $text=$row['text'];
}


//$text.=$id;
}


$oFCKeditor2 = new FCKeditor('FCKeditor1') ;
$oFCKeditor2->Config["CustomConfigurationsPath"] = "/fck/lib/fck/fckcfg.js" ;
$oFCKeditor2->Height = "300px";
$oFCKeditor2->Config['DefaultLanguage']		= 'en' ;
$oFCKeditor->Config['AutoDetectLanguage'] = false;
//$oFCKeditor2->ToolbarSet = 'MyToolbar';
//$oFCKeditor2->ToolbarSet = "Basic";
$oFCKeditor2->Config['SkinPath'] = "/fck/lib/fck/editor/skins/silver/" ;
$oFCKeditor2->Value = $text;
$oFCKeditor2->Create() ;

?>
</td></tr>
<tr><td>
<input type="submit" value="Submit">
</td></tr>
<tr>
<td>
<?php 
echo $text;
?>
</td>
</tr>
</table>
</form>
<br>
</body>
</html>
