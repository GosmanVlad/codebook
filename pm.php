<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/****************************************/
if(!isset($_SESSION['user'])) { echo("<script>location.href = '".URL."autentificare';</script>"); } 
/***************************************/
$username = $_SESSION['user'];
if(isset($_GET['page'])) $page=$_GET["page"];
else $page=$_GET['page'] = NULL;
/***************************************/
$allpms = $db->prepare('SELECT * FROM `pm` WHERE `name2` = :name2 ORDER BY `id` DESC');
$allpms->execute(array('name2' => $username));
$necitite = $db->prepare('SELECT * FROM `pm` WHERE `name2` = :name2 AND `readmessage` = 0');
$necitite->execute(array('name2' => $username));
/***************************************/
echo "<title>Mesagerie privata - ".WEBNAME."</title>";
/****************************************/
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
                <li class="breadcrumb-item active">PM</li>
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
                <div id="text-page">';
if(!$page)
{
	echo'<center><h4>Mesagerie privata - '.$username.'</h4><a href="'.URL.'newpm">Trimite un nou mesaj</a>
	<hr>
		<div id="customer-orders">
					<div class="table-responsive">
					<table class="table table-hover">
                    <thead>
                    <tr>
                    <th></th>
                    <th>Titlu</th>
                    <th>Primit de la</th>
                    <th>Status</th>
                    <th>Data</th>
					<th>Actiuni</th>
                    </tr>
                    </thead>
                    <tbody>';
	foreach ($allpms as $row) 
	{
		$title = $row['title'];
		$from = $row['name1'];
		$userid = $row['userid'];
		$readmsg = $row['readmessage'];
		$time = date('Y/m/d H:i:s' ,$row['timestamp']);
		$isreply = $row['isreply'];
		
		if($readmsg == 0) 
		{
			$icon = '<i class="fa fa-envelope" style="color:#FF0000;"></i>';
			$readmsg = '<b><span class="label label-red">Necitit</span></b>';
		}
		else 
		{
			$icon = '<i class="fa fa-envelope" style="color:#000;"></i>';
			$readmsg = '<span class="label label-green">Citit</span>';
		}
		echo'<tr style="border-bottom: solid #ccc 1px;">
		<td> '.$icon.'</td>';
		if($isreply)echo'<td> <a class="nounder" style="text-decoration: none;" href="'.URL.'readpm/'.$row['id'].'">Reply: '.$title.'</a></td>';
		else echo' <td> <a class="nounder" style="text-decoration: none;" href="'.URL.'readpm/'.$row['id'].'">'.$title.'</a></td>';
		/*************************/
		if($from == 'CodeBook Team') echo'<td>'.$from.'</a></td>';
		else  echo'<td><a class="nounder" style="text-decoration: none;" href="'.URL.'profile/'.$userid.'-'.$from.'">'.$from.'</a></td>';
		echo'<td>'.$readmsg.'</td>
		<td>'.$time.'</td>
		<td><a href="'.URL.'inbox/remove-'.$row['id'].'" class="btn btn-sm btn-danger">Sterge</a> <a href="'.URL.'readpm/'.$row['id'].'" class="btn btn-sm btn-info">Citeste tot</a></td></tr>';
	}	
		echo"</tbody>
                  </table>
                </div></div></div></div>";
}
/***************************************/
elseif($page=="removepm")
{ 
	if(isset($_GET['id']) && isset($_GET['delete']))
	{
		$escaped_id = mysql_real_Escape_String($_GET['id']);
		$update = $db->prepare("DELETE FROM pm where id='$escaped_id'");
		$update->execute();
		if($update)
		{
			echo("<script>location.href = '".URL."inbox';</script>");
		}
	}
}
echo'</div></div></div></div></div>';
/*********************************/
Footer();
echo"</div></div></div>";
AnotherScripts();
echo"</body>";