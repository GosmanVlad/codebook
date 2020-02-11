<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/****************************************/
if(!isset($_SESSION['user'])) { echo("<script>location.href = '".URL."home';</script>"); } 
/****************************************/
echo"<title>Lista membrilor - ".WEBNAME."</title>";
/*****************************************/
$nrmembers = $db->prepare('SELECT * FROM `members`');
$nrmembers->execute();
$membrii_pagina = 20;
if(isset($_GET['pag'])){
$page_no = $_GET['pag'];
}
else{
echo("<script>location.href = '".URL."memberlist/1';</script>");
$page_no=1;
}
$page_next = $page_no +1;
$page_prev = $page_no -1;

$start = ($page_no - 1) * $membrii_pagina;
$end_page = $membrii_pagina;

$ultima_pagina = ceil($nrmembers->rowCount()/$membrii_pagina);
$ultima_pagina_real = ceil($nrmembers->rowCount()/$membrii_pagina);

$memberlist = $db->prepare("SELECT * FROM `members` ORDER by rang LIMIT $start, $end_page");
$memberlist->execute();
/***************************************/
$username = $_SESSION['user'];
if(isset($_GET["mode"])) $mode=$_GET["mode"];
else $_GET["mode"] = NULL;
/***************************************/
Website_Header();
echo'<body><div id="all">';
include('modules/menu.php');
echo '<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Dezvolta-te cu ajutorul tutorialelor noastre</h1>
            </div>
          </div>
        </div>
      </div>';
/******************************/
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar"><div id="blog-listing-medium" class="col-md-9">';
if(isset($_POST['sssssdas']))
{
	$nick = htmlspecialchars($_POST['nick'], ENT_QUOTES, "UTF-8");
	$acc = $db->prepare("SELECT * FROM `members` WHERE name LIKE '%{$nick}%'");
	$acc->execute();
echo'<div class="well">
	';
	if($acc->rowCount() != 0)
	{
		/*foreach($acc as $row)
		{
			$id = $row['id'];
			$name = $row['name'];
			echo("<script>location.href = '".URL."profile/$id-$name';</script>");
		}*/
		echo "<center><h5>Rezultate gasite (".$acc->rowCount().")</h5></center>";
		echo "<div class='table-responsive'>
                    <table class='table'><tbody id='myTable'>";
		echo "<tr><td><b>Nume</b></td><td><B>Func&#355;ie</b></td><td><B>Ac&#355;iuni</b></td></tr>";
		 foreach ($acc as $row) 
		 {
			$nickname = ucfirst($row['name']);
			$rang = $row['rang'];
			$id = $row['id'];
			$avatar = $row['avatar'];
			/****************************************/	
			if($rang == 0) { $rangname = 'Fondator';}
			else if($rang == 1) { $rangname = 'Administrator';  }
			else if($rang == 2) { $rangname = 'Editor';  }
			else if($rang == 3) { $rangname = 'Utilizator'; }
			else if($rang == 4) { $rangname = 'Utilizator interzis'; }
			/****************************************/
			echo "<tr style='border-bottom: solid #ccc 1px;'>";
			echo "<td><img src='".URL."uploads/avatars/$avatar' style='border-radius:50px;margin-right:3px;'> <a style='text-decoration: none;color:black";
			if($rang == 4) echo" ' href='".URL."profile/$id-$nickname'><strong><i>$nickname</i></strong></a></b>";
			else echo" ' href='".URL."profile/$id-$nickname'><strong>$nickname</strong></a></b>";
			echo "</td><td>$rangname</td>";
			echo "<td><a href='".URL."profile/$id-$nickname' class='btn btn-sm btn-primary'><i class='fa fa-user'></i> Profil</a> <a href='".URL."newpm/$nickname' class='btn btn-sm btn-danger'><i class='fa fa-envelope'></i> Send PM</a></td>";

			echo "</tr>";

		}
echo'</tbody></table></div>';
	}
	else echo '<div role="alert" class="alert alert-danger">Nu s-a gasit utilizatorul cautat.</div>';
	echo'</div>';
}
echo'<div class="well">
	
		<h5>List&#259; membrii (Total &#238;nregistrati: '.$nrmembers->rowCount().')</h5><hr>';
		echo "<div class='table-responsive'>
                    <table class='table'><tbody id='myTable'>";
		echo "<tr><td><b>Nume</b></td><td><B>Func&#355;ie</b></td><td><B>Ac&#355;iuni</b></td></tr>";
		 foreach ($memberlist as $row) 
		 {
			$nickname = ucfirst($row['name']);
			$rang = $row['rang'];
			$id = $row['id'];
			$avatar = $row['avatar'];
			/****************************************/	
			if($rang == 0) { $rangname = 'Fondator';}
			else if($rang == 1) { $rangname = 'Administrator';  }
			else if($rang == 2) { $rangname = 'Editor';  }
			else if($rang == 3) { $rangname = 'Utilizator'; }
			else if($rang == 4) { $rangname = 'Utilizator interzis'; }
			/****************************************/
			echo "<tr style='border-bottom: solid #ccc 1px;'>";
			echo "<td><img src='".URL."uploads/avatars/$avatar' style='border-radius:50px;margin-right:3px;'> <a style='text-decoration: none;color:black";
			if($rang == 4) echo" ' href='".URL."profile/$id-$nickname'><strong><i>$nickname</i></strong></a></b>";
			else echo" ' href='".URL."profile/$id-$nickname'><strong>$nickname</strong></a></b>";
			echo "</td><td>$rangname</td>";
			echo "<td><a href='".URL."profile/$id-$nickname' class='btn btn-sm btn-primary'><i class='fa fa-user'></i> Profil</a> <a href='".URL."newpm/$nickname' class='btn btn-sm btn-danger'><i class='fa fa-envelope'></i> Send PM</a></td>";

			echo "</tr>";

		}
echo'</tbody></table></div>';
echo"<nav class='d-flex justify-content-center'><ul class='pagination'>";

if($nrmembers->rowCount() > $membrii_pagina){

/*if($_GET['pag'] == 1 or $page_no == 1){
echo " <li class='page-item'><a class='page-link'><i class='fa fa-angle-double-left'></i></a></li>"; // start precedenta
}
else{
echo "<li class='page-item'><a class='page-link' href='".URL."memberlist/$page_prev'><i class='fa fa-angle-double-left'></i></a></li>";
}//end precedenta

if($_GET['pag'] == $ultima_pagina){//start urmatoarea
echo "<li class='page-item'><a class='gray'><i class='fa fa-angle-double-right'></i></a></li>";
}
else{
echo "<li class='page-item'><a class='page-link' href='".URL."memberlist/$page_next'><i class='fa fa-angle-double-right'></i></A></li>";
}//end*/

if($ultima_pagina >= 10){
$ultima_pagina=10;
}

for($go_pagini=1; $go_pagini<=$ultima_pagina; $go_pagini++){
if($go_pagini == $_GET['pag'] or $go_pagini == $page_no){
echo "<li class='page-item'><a class='page-link'>$go_pagini</a></li>";

}
else{

echo "<li class='page-item'><a class='page-link' href='".URL."memberlist/$go_pagini'>$go_pagini</a></li>";
 }

}
  if($ultima_pagina > 9){
echo " ...<a class='pag_no' href='".URL."memberlist/$ultima_pagina_real'>$ultima_pagina_real</a>";
  } 
}
echo"</ul></nav>";
echo'</div></div>';
echo'<div class="col-md-3"><strong>Cauta un user:</strong>';
	echo'<form role="form" method="post" action="" autocomplete="off">
                <div class="input-group">
                  <input name="nick" type="text" class="form-control">
                  <div class="input-group-append">
                    <button type="submit" name="sssssdas" class="btn btn-secondary"><i class="fa fa-send"></i></button>
                  </div>
                </div>
              </form>';
	echo'</div>';
/*********************************/
echo'</div></div></div>';
Footer();
echo"</div></div></div></div>";
AnotherScripts();