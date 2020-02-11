<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/****************************************/
if(!isset($_SESSION['user'])) { echo("<script>location.href = '".URL."home';</script>"); } 
/***************************************/
$username = $_SESSION['user'];
$page=$_GET["page"];
$allpms = $db->prepare('SELECT * FROM `pm` WHERE `name2` = :name2 ORDER BY `id` DESC');
$allpms->execute(array('name2' => $username));
$necitite = $db->prepare('SELECT * FROM `pm` WHERE `name2` = :name2 AND `readmessage` = 0');
$necitite->execute(array('name2' => $username));
/***************************************/
echo"<title>Mesagerie privata - ".WEBNAME."</title>";
/*****************************************/
Website_Header();
echo'<body><div id="all">';
include('modules/menu.php');
echo '<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Profil utilizator</h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="'.URL.'">Home</a></li>
                <li class="breadcrumb-item active">Profil</li>
              </ul>
            </div>
          </div>
        </div>
      </div>';
if($page=="read")
{ 
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar">';	  
echo'<div class="col-md-3 mt-4 mt-md-0">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Comenzi</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">';
						if($necitite->rowCount() == 1) echo'<strong><font color=red>Aveti '.$necitite->rowcount().' mesaj necitit!</font></strong>';
						else if($necitite->rowCount() > 1) echo'<strong><font color=red>Aveti '.$necitite->rowcount().' mesaje necitite!</font></strong>';
						echo'<li class="nav-item"><a href="'.URL.'inbox" class="nav-link"><i class="fa fa-envelope"></i> Mesaje primite ('.$allpms->rowCount().')</a></li>';
						echo'<li class="nav-item"><a href="'.URL.'newpm" class="nav-link"><i class="fa fa-mail-forward"></i> Compune mesaj</a></li>';
					echo' </ul>
                </div>
              </div>
            </div>';
echo'<div class="col-md-9"><div class="well">
                <div id="text-page">
	<center><h4>Mesagerie privata - '.$username.'</h4><hr></center>';
	/***********************************/
	if(isset($_GET['id']) && isset($_GET['read']))
	{
		$id = $_GET['id'];
		$showpm = $db->prepare('SELECT * FROM `pm` WHERE `id` = :id');
		$showpm->execute(array('id' => $id));
		$read = $db->prepare('UPDATE pm SET `readmessage`=1 WHERE `id` = :id');
		$read->execute(array('id' => $id));
		/***************************/
		foreach ($showpm as $row) 
		{
			$id = $row['id'];
			$title = $row['title'];
			$titlu = curata_url($title);
			$msg = $row['message'];
			$from = $row['name1'];
			$time = date('Y/m/d H:i:s' ,$row['timestamp']);
			
			$userid2 = $db->prepare("SELECT * FROM members WHERE `name` = '$from'");
			$userid2->execute();
			$row2 = $userid2->fetch();
			$userid2 = $row2['id']; 
			echo'<b>Subiect:</b> '.$title.'<br>
			<b>De la:</b> <a href="'.URL.'profile/'.$userid2.'-'.$from.'">'.$from.'</a><br>
			<b>Data:</b> '.$time.'<hr>
			'.$msg.'<br>';
		}
		echo'<hr><div style="float:left;"><a href="'.URL.'inbox">&#171; Inapoi la inbox</a></div>';
		echo'<div style="float:right;">';
		if($from != 'CodeBook Team') echo'<a href="'.URL.'readpm/'.$id.'-'.$titlu.'/'.$from.'" class="btn btn-sm btn-info">Raspunde la mesaj</a>';
		echo'<a href="'.URL.'inbox/remove-'.$id.'" class="btn btn-sm btn-danger" style="margin-left:5px;">Sterge</a></div><br>';
	}
	/***********************************/
	if(isset($_GET['reply']) && isset($_GET['title']) && isset($_GET['from']))
	{
		$reply_id = $_GET['reply'];
		$reply_title = $_GET['title'];
		$reply_from = $_GET['from'];
		$_SESSION['reply_msgto'] = $reply_from;
		$_SESSION['reply'] = 1;
		
		echo"<form action='".URL."newpm' method='post'>
			<label>Subiect:<br />
			<input class='form-control' type='text' name='titlu' rows='1' cols='50' value='$reply_title' readonly=readonly></input>
			</label> <br /><br />
			
			<label>Catre:<br />
			<input class='form-control'  name='msgto' rows='1' cols='50' value='$reply_from' readonly=readonly></input>
			</label> <br /><br />
			
			<label>Mesaj:<br />
			<textarea class='form-control' name='msg' rows='10' cols='70'></textarea>
			</label> <br /><br />
			
			<input type='submit' name='send_pm' value=' Trimite mesaj ' class='btn btn-success'>
		</form>";
	}
	/***********************************/
}
echo'</div></div></div></div></div>';
/*********************************/
Footer();
echo"</div></div></div>";
AnotherScripts();
echo"</body>";