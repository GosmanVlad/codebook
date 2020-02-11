<?php
include('./includes/online_visitors.php');
/***************************************/
$affiliates = $db->prepare('SELECT * FROM `affiliates` LIMIT 10');
$affiliates->execute();
/***************************************/
$comments = $db->prepare('SELECT * FROM `comments` ORDER BY `id` DESC LIMIT 10');
$comments->execute();
/***************************************/
echo'<div class="row-fluid2"><div id="sidebar"><div class="new-right"><div style="float:left; margin-left:0px;"><div style="width:305px;overflow:hidden;">';
if(!isset($_SESSION['user']))
{
	echo'
	<center><form class="form-signin" role="form" method="post" action="'.URL.'login.php" autocomplete="off">
		<table border="0" width="250"><tbody><tr><td>
			User
		</td><td>
		<input type="text" name="login_username" class="transparent">
		<input type="hidden" name="url" value="">
		</td>
		</tr>
		<tr><td>
		Parola
		</td><td>
		<input type="password" name="login_password" class="transparent">
		</td>
		</tr>
		<tr><td colspan="2">
		</td></tr>
		</tbody></table><br>
		<center><button class="mybtn mybtn-primary" type="submit" name="login_btn">Autentificare</button><hr>
		<a href="recover.php">Ti-ai uitat parola?</a><br>
 <a href="register.php">Inregistreaza un cont nou</a></center>
	</form></center>';
}
else
{
	/****************************************/
	$username = $_SESSION['user'];
	$sideprofile = $db->prepare('SELECT * FROM `members` WHERE `name` = :name');
	$sideprofile->execute(array('name' => $username));
	
	$unreadpm = $db->prepare('SELECT * FROM `pm` WHERE `name2` = :name2 AND `readmessage` = 0');
	$unreadpm->execute(array('name2' => $username));
	/**********************************/
	echo'<div class="sidebox">
		<table><tbody><tr><td>
<div class="sidenav" style="padding:8px;width:266px;"><table cellspacing="0" cellpadding="0"><tbody><tr><td>';
	foreach ($sideprofile as $row) 
	{
		$rang = $row['rang'];
		$avatar = $row['avatar'];
		/****************************************/	
		if($rang == 0) { $rangname = 'Fondator'; }
		else if($rang == 1) { $rangname = 'Administrator'; }
		else if($rang == 2) { $rangname = 'Editor';}
		else if($rang == 3) { $rangname = 'Utilizator'; }
		else if($rang == 4) { $rangname = 'Utilizator interzis';}
		/****************************************/
		echo '<img src="'.URL.'uploads/avatars/mici/'.$avatar.'" style="margin-right:10px;" alt=""></td><td>
		<font size=2>Bun venit <b>'.$username.'</b>! <br> Rang-ul t&#259;u: <b>'.$rangname.'</b><br>';
		if($unreadpm->rowCount() > 0) 
		{
			if($unreadpm->rowCount() == 1) 
				echo'<font size=2 color=red><b>Ai un mesaj nou!</b></font><br>';
			else
				echo'<font size=2 color=red><b>Ai '.$unreadpm->rowCount().' mesaje noi!</b></font><br>';
		}
	echo'<b><a href="memberlist.php">Lista Membrilor</a></b></td></tr></tbody></table>
</font></div>
</td></tr></tbody></table>
<div style="margin-left:5px;margin-bottom:5px;"><font size="2"><a href="medals.php"><i class="icon-info-sign"></i> Despre medalii</a><br>';
if($_SESSION['admin']!=0 || $_SESSION['editor']!=0)
	echo'<i class="icon-user"></i>'.$ONLINE_VISITORS.'</font></div>';
else echo'</font></div>';
		echo'</div><br>';
		}
	/****************************************/
}
/********************************************************/
echo'<div class="chasenav">
	<p style="color:#000;">Comentarii recente</p>
	</div>
	<div style="padding-right:2px;"><table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr valign="top"><td>';
	/***************************************/
	$lascoms = $db->prepare('SELECT * FROM `comments` ORDER BY `id` DESC LIMIT 4');
	$lascoms->execute();
	/***************************************/
	if($lascoms -> rowCount() > 0)
	{
		foreach ($lascoms as $row) 
		{
			$id = $row['id'];
			$comment = $row['comment'];
			$sectionid = $row['sectionid'];
			$categ = $row['categ'];
			$user = $row['user'];
			$userid = $row['userid'];
			$data = $row['data'];
			$result = substr($comment, 0, 155);
			/***********************************/
			$userdata = $db->prepare("SELECT * FROM `members` WHERE `id` = $userid");
			$userdata->execute();
			foreach($userdata as $row2)
			{
				$avatar = $row2['avatar'];
				/***********************************/
				echo'<div class="sidebox">
				<div style="margin-left:3px;">';
				/************************************/
				$section = $db->prepare("SELECT * FROM `tutorials` WHERE `id` = $sectionid");
				$section->execute();
				/***********************************/
				foreach($section as $row3)
				{
					$titlu = curata_url($row3['title']);
					echo'<table cellspacing="1" cellpadding="0">
				<tbody><tr valign="top"><td width="55"><img src="'.URL.'uploads/avatars/mici/'.$avatar.'" width=52px height = 52px style="border-radius:100%;""></img></td>
				<td><div style="font-size:13px;margin-top:5px;color:gray;"><a href="'.URL.'/profile/'.$userid.'-'.$user.'"><font size="3px">'.$user.'</font></a><br>'.$data.'<br>
				In <a href="'.URL.'tutorials/'.$row3['categ'].'/'.$row3['id'].'-'.$titlu.'">'.$row3['title'].'</a></div></td>
				</tr></tbody></table>';
				}echo'
				<div style="font-size:15px;margin-top:5px;color:gray;">'.$result.'...</div>';
				/************************************/
				echo'</div>
					</div>';
			}
		}
	}
	else
	echo '<center><div style="font-size:15px;margin-top:5px;color:gray;">Nu exista comentarii</div></center>';
echo'</td></tr></tbody></table></div><br>';
/********************************************************/
echo'<div class="chasenav">
	<p style="color:#000;">Ultimele tutoriale</p>
	</div>
	<div style="padding-right:2px;"><table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr valign="top"><td>';
	/***************************************/
	$lasttut = $db->prepare('SELECT * FROM `tutorials` WHERE `approved` = 1 ORDER BY `id` DESC LIMIT 7');
	$lasttut->execute();
	/***************************************/
	foreach ($lasttut as $row) 
	{
		$id = $row['id'];
		$title = $row['title'];
		$categ = $row['categ'];
		$user = $row['user'];
		$userid = $row['userid'];
		$data = date('Y/m/d H:i:s' ,$row['data']);
		if($categ == 1) $categname = "Tutoriale C++";
		else if ($categ == 2) $categname = "Tutoriale HTML";
		else if ($categ == 3) $categname = "Tutoriale PHP";
		else if ($categ == 4) $categname = "Tutoriale JavaScript";
		else if ($categ == 5) $categname = "Tutoriale jQuery";
		$titlu = curata_url($title);
		/***********************************/
		echo'<div class="sidebox">
		<div style="margin-left:3px;"><a href="'.URL.'tutorials/'.$categ.'/'.$id.'-'.$titlu.'" class="newz">'.$title.'</a><br>
		<div style="font-size:11px;margin-top:2px;color:gray;">&#206;n 
		<a href="'.URL.'tutorials/'.$categ.'" class="newzin">'.$categname.'</a><br>
		Postat de <a href="'.URL.'profile/'.$userid.'-'.$user.'">'.$user.'</a> 
		</div></div>
		</div>';
	}
	if(isset($_SESSION['user'])) {
echo'<br><center><div style="font-size:13px;"><a href="'.URL.'tutorials/adauga-un-tutorial"><img src="'.URL.'images/addv2.png"> Adaug&#259; tutorialul t&#259;u</img></a></div></center>'; }
echo'</td></tr></tbody></table></div><br>';
/********************************************************/
echo'<div class="chasenav">
		<p style="color:#000;">Promovare</p>
	</div>
	<img src='.URL.'images/promovare.png style="padding: 4px;"></img>';
/********************************************************/
echo'<br><br><div class="chasenav">
		<p style="color:#000;">Parteneriat</p>
	</div><div style="padding:4px;"><div style="width:290px;" class="fixit">';
	foreach ($affiliates as $aff) 
	{
		echo'<div class="affbox">'.$aff['code'].'</div>';
	}
	echo'</div></div><br><br><br><center><a href="affiliates.php">To&#355;i afilia&#355;ii</a></center>';
/********************************************************/
echo'</div></div></div></div></div>';
?>