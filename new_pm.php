<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/****************************************/
if(!isset($_SESSION['user'])) { echo("<script>location.href = '".URL."autentificare';</script>"); } 
/***************************************/
$username = $_SESSION['user'];
$allpms = $db->prepare('SELECT * FROM `pm` WHERE `name2` = :name2 ORDER BY `id` DESC');
$allpms->execute(array('name2' => $username));
$necitite = $db->prepare('SELECT * FROM `pm` WHERE `name2` = :name2 AND `readmessage` = 0');
$necitite->execute(array('name2' => $username));
/*****************************************/
echo"<title>Mesaj privat noi - ".WEBNAME."</title>";
/*****************************************/
Website_Header();
echo'<body><div id="all">';
include('modules/menu.php');
echo '<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Mesagerie privata</h1>
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
/*********************************/
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
	if (isset($_POST['send_pm']))
	{
		$problem = 0;
		if($_SESSION['reply'])
		{
			$reply_msgto = $_SESSION['reply_msgto'];
			$findname = $db->prepare("select * from members where name = :name");
			$findname->execute(array('name'=>$reply_msgto));
		}
		else
		{
			$to = $_POST['msgto'];
			$findname = $db->prepare("select * from members where name = :name");
			$findname->execute(array('name'=>$to));
		}
		if($findname->rowCount() < 1)
		{
			echo '<center><b><font color=red>Numele nu a fost gasit in baza de date</font></b></center><hr>';
			$problem = 1;
		}
		if($problem == 0)
		{
			$searchiduser = $db->prepare('SELECT * FROM `members` WHERE `name` = :name');
			$searchiduser->execute(array('name' => $username));
			
			foreach($searchiduser as $trow)
			{
				$titlu = $_POST['titlu'];
				$to = $_POST['msgto'];
				$msg = $_POST['msg'];
				$time = time();
				$userid = $trow['id'];
				
				if($_SESSION['reply'])
				{
					$sql = $db->prepare("insert into pm (name1, userid, name2, title, message, timestamp,isreply) values ('$username','$userid','$to','$titlu','$msg','$time',1)");
					$sql->execute();
					$_SESSION['reply'] = 0;
				}
				else
				{
					$sql = $db->prepare("insert into pm (name1, userid, name2, title, message, timestamp) values ('$username','$userid','$to','$titlu','$msg','$time')");
					$sql->execute();
				}
				if($sql)
				{
					echo("<script>location.href = '".URL."inbox';</script>");
				}
			}
		}
	}
	/***********************************/
	if(isset($_GET['toname']) && isset($_GET['sendpm']))
	{
		$pmto = $_GET['toname'];
		echo'<form action="" method="post">
		<label>Subiect:</label> 
		<textarea class="form-control" name="titlu" rows="1" cols="50" ></textarea>
		<br /><br />
		
		<label>Catre:</label> 
		<input class="form-control" name="msgto" value="'.$pmto.'" readonly=readonly></input>
		 <br /><br />
		
		<label>Mesaj:</label> 
		<textarea id="textarea1" class="form-control" name="msg" rows="10" cols="70"></textarea>
		<br /><br />
		
		<button class="btn btn-success" type="submit" name="send_pm"><i class="fa fa-mail-forward"></i> Trimite</button>
		</form>';
	}
	else{?>
	<form action='' method='post'>
		<label>Subiect:</label> 
		<textarea class="form-control" name='titlu' rows="1" cols="50"></textarea>
		<br /><br />
		
		<label>Catre:</label> 
		<textarea class="form-control" name='msgto' rows="1" cols="50"></textarea>
		<br /><br />
		
		<label>Mesaj:</label>
		<textarea id="textarea1" name='msg' rows=5"></textarea>
		<br /><br />
		
		<button class="btn btn-success" type="submit" name="send_pm"><i class="fa fa-mail-forward"></i> Trimite</button>
	</form>
	<?php
	}
echo'</div></div></div></div></div>';
/*********************************/
Footer();
echo"</div></div></div>";
AnotherScripts();
echo'<script type="text/javascript" src="'.URL.'js/wysiwyg/scripts/wysiwyg.js"></script>
	<script type="text/javascript" src="'.URL.'wysiwyg/scripts/wysiwyg-settings.js"></script>';
echo "<script> WYSIWYG.attach('textarea1'); </script>";
echo"</body>";