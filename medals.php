<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/*****************************************/
echo"<title>".WEBNAME." - ".WEBTITLE."</title>";
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
/*****************************************/
$medals = $db->prepare('SELECT * FROM `medals`');
$medals->execute();
/****************************************/
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar">';
echo'<div class="col-md-3 mt-4 mt-md-0">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Medaliile tale</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">';
				  if(isset($_SESSION['user']))
				  {
					$user = $_SESSION['user'];
					$search = $db->prepare("SELECT * FROM `playermedals` WHERE `name` = '$user'");
					$search->execute();
					if($search->rowCount()!=0)
					{
						foreach($search as $row)
						{
							$user_id = $row['id']; 
							$medalid = $row['medalid']; 
							$medals = $db->prepare("SELECT * FROM `medals` WHERE `id` = '$medalid'");
							$medals->execute();
							$row2 = $medals->fetch();
							$photo = $row2['photo'];
							echo'<li class="nav-item"><img src="'.URL.'uploads/medals/'.$photo.'"></img></li>';
						}
					}
					else echo 'Nu ai nici o medalie!';
				}
				else echo 'Nu esti autentificat pentru a-ti afisa medaliile';
                 echo' </ul>
                </div>
              </div>
            </div>';
			
echo'<div class="col-md-9"><div class="well">
                <div id="text-page">
		<center><h3>Despre medalii</h3><br><div style="font-size:16px;">Orice utilizator al acestei platforme web are dreptul de a primi o medalie.
		Aceste medalii pot fi procurate de la echipa administrativa, in cazul in care
		se constata activitatea ta pe termen lung. <br>Asa cum poti primi o medalie, asa o poti pierde! In cazul in care se observa lipsa ta de activitate, oricand ti se poate retrage medalia.<br>
		Citeste cu atentie descrierea fiecarei medalii si constata in ce categorie te aflii, daca este cazul!<br>
		Pentru mai multe informatii accesati pagina de <a href="'.URL.'contact">contact</a>.</div></center><hr>
		<center><table class="table table-hover"><tbody>';
		echo'<tr><td><b>Medalie</b></td><td><b>Descriere</b></td><td><b>Beneficiari</b></td></tr>';
		foreach($medals as $row)
		{
			$id = $row['id'];
			$mname = $row['medalname'];
			$photo = $row['photo'];
			$description = $row['description'];
			echo"<tr><td style='widht:20%'>$mname<br> <img src='uploads/medals/$photo'></img> </td>";
			echo"<td>$description</td><td>";
			/***************************************/
			$searchuser = $db->prepare("SELECT * FROM `playermedals` WHERE `medalid` = $id");
			$searchuser->execute();
			/***************************************/
			if($searchuser -> rowCount() != 0)
			{
				foreach($searchuser as $mrow)
				{
					$name = $mrow['name'];
					$userid = $mrow['userid'];
					if($searchuser->rowCount()==1)
						echo"<a href='profile.php?page=profile&userid=$userid'>$name</a>";
					else
						echo"<a href='profile.php?page=profile&userid=$userid'>$name, </a>";
				}
			}
			else echo"-";
			echo"</td></tr>";
		}
		echo'</tbody></table></center>
		
		';
echo'</div></div></div></div></div>';
/*********************************/
Footer();
echo"</div></div></div>";
AnotherScripts();
echo"</body>";