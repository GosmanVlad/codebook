<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/***************************************/
echo"<title>".WEBNAME." - ".WEBTITLE."</title>";
/***************************************/
$user = $_SESSION['user'];
/************************************************/
$baninfo = $db->prepare("SELECT * FROM `banlist` WHERE `name` = '$user'");
$baninfo->execute();
/************************************************/
	foreach($baninfo as $row)
{
	$id = $row['id'];
	$name = $row['name'];
	$bannedby = $row['bannedby'];
	$reason = $row['reason'];
	$time = date('Y/m/d H:i:s' ,$row['data']);
	$ip = $row['ip'];
	$userid = $row['userid'];
	$adminid = $row['adminid'];
	
	echo"<body style='margin:0px;'>
<br><br><center><img src='./images/banned.png'><br><br>
<font face='Arial'><font color=red size=5>Ai fost banat pe aceasta comunitate !</font><hr><b><u>Informatii:</u></b><br><br>
<b>Nume:</b> $name<br>
<b>Ai fost banat de catre:</b> $bannedby<br>
<b>Motivul banarii:</b> $reason<br>
<b>Data:</b> $time<br>
<b>IP:</b> $ip<br></font>
</center>
</body>";
}
session_unset();
session_destroy();
?>