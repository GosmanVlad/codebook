<?php
function display_comments($category,$sectionid)
{
global $db;
if(isset($_SESSION['user'])) 
{ 
$nickname = $_SESSION['user'];
}
/******************************************/
$allcomm = $db->prepare('SELECT * FROM `comments` WHERE `categ` = :categ AND `sectionid`=:sectionid ORDER BY `id` desc limit 0,65');
$allcomm->execute(array('categ' => $category, 'sectionid' => $sectionid));
$nr_coloane = $allcomm->rowCount();

/* Categ 1 - Tutoriale C++
Categ 2 - Tutoriale HTML */
/******************************************/
if($nr_coloane == "0")
{
	echo "<p>Nu exist&#259; &#238;nc&#259; comentarii!</p>";
}
else
{
$bg = '#fff';

$ziua = date("d");
$anul = date("Y");
$luna = date("M");
$ora = date("H:i");

$nume_luna = array(
"Jan" => "Ianuarie",
"Feb" => "Februarie",
"Mar" => "Martie",
"Apr" => "Aprilie",
"May" => "Mai",
"Iun" => "Iunie",
"Jul" => "Iulie",
"Aug" => "August",
"Sep" => "Septembrie",
"Oct" => "Octombrie",
"Nov" => "Noiembrie",
"Dec" => "Decembrie");

$luna = $nume_luna[$luna]; 
$ASTAZI = "$ziua $luna $anul - $ora";
echo"<div class='well'><div id='comments'>";
foreach ($allcomm as $rand) 
{

$comm_id = $rand['id'];
$comm_id_selectare = $rand['sectionid'];
$comm_text = ucfirst($rand['comment']);
$comm_user = $rand['user'];
$comm_data = $rand['data'];
$comm_userid = $rand['userid'];
$comm_ip = $rand['ip'];
$comm_categ = $rand['categ'];
/******************************************/
$getuser = $db->prepare('SELECT * FROM `members` WHERE `name` = :name');
$getuser->execute(array('name' => $comm_user));
/*****************************************/
foreach ($getuser as $row) 
{
		$rang = $row['rang'];
		$avatar1 = $row['avatar'];
		$userid = $row['id'];
		/******************************************/
		$getmedals = $db->prepare("SELECT * FROM `playermedals` WHERE `userid` = $userid");
		$getmedals->execute(array('name' => $comm_user));
		/****************************************/	
		if($rang == 0) { $rangname = '<b><span class="label label-red">Fondator</span></b>';  }
		else if($rang == 1) { $rangname = '<b><span class="label label-orange">Administrator</span></b>'; }
		else if($rang == 2) { $rangname = '<b><span class="label label-blue">Editor</span></b>';}
		else if($rang == 3) { $rangname = ''; }
		else if($rang == 4) { $rangname = '<b><span class="label label-black">Banat</span></b>';}
		/****************************************/
}

if ($bg == '#fff') $bg = '#eee';
  else $bg = '#fff';
  
/*if ($bd == '#ccc') $bd = '#fff';
  else $bd = '#ccc';*/
echo'<div class="row comment">';
echo'<div class="col-sm-3 col-md-2 text-center-xs">';
if($avatar1 == "".URL."default.png" or $avatar1 == "") echo "<p><img src='".URL."uploads/images/default_avatar.png' class='img-fluid rounded-circle' alt='' /></p>";
else echo "<p><img src='".URL."uploads/avatars/mici/$avatar1' class='img-fluid rounded-circle' alt='' /></p>";
echo'</div>';

echo'<div class="col-sm-9 col-md-10">';
echo'<div class="text-uppercase" style="font-size: 16px;">'.$comm_user.'</div>
                    <p class="posted" style="font-size: 13px;"><i class="fa fa-clock-o"></i> '.$comm_data.'</p>
                    <p style="font-size: 14px;">'.$comm_text.'</p>';
					if(isset($_SESSION['user'])) 
{ 
	if($_SESSION['admin'] > 0 || $_SESSION['editor'] > 0)
	{
	echo'<div style="float:right;"><a href="'.URL.'comentator.php?page=delete&rid='.$comm_id.'&sectionid='.$comm_id_selectare.'&type='.$comm_categ.'&userid='.$comm_userid.'&delete=true"><img src="'.URL.'images/newdel.gif"></img></a></div>';
	}
}
echo'</div>';
echo'</div><hr>';
}
echo"</div></div>";
 }
} // End daca exista comentarii	\
?>