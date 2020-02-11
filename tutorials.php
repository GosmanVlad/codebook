<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
require_once('modules/comments/display.php');
/***************************************/
if(isset($_GET["category"])) $ctg=$_GET["category"];
else $ctg = $_GET["category"] = NULL;
if(!isset($_SESSION['user'])) { $_SESSION['admin'] = 0; } 
/***************************************/
Website_Header();
echo'<body><div id="all">';
include('modules/menu.php');
echo '<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Dezvolta-te cu ajutorul tutorialelor noastre</h1>
			  <p style="font-size:16px;margin-bottom: 10px;font-weight: 300;color:#DBDBDB;">Fiecare dintre noi, in fiecare zi, prin orice gest sau fapt, avem de invatat.
			  Astfel, prezentam cunostiintele noastre, le explicam pe intelesul tuturor si le impartasim celorlalti.<br>
			  Daca esti bun in ceea ce faci, dezvolta intr-un astfel de tutorial si impartaseste cu ceilalti.</p>
            </div>
			<div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
				<form role="form" method="post" action="'.URL.'tutoriale/search/cauta-dupa" autocomplete="off">
                <div class="input-group">
                  <input type="text" name="keyw" placeholder="Cauta.." class="form-control">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-info" name="sssssdas"><i class="fa fa-search"></i> Cauta</button>
                  </div>
                </div>
              </form>
			  </ul>
			 </div>
          </div>
        </div>
      </div>';
/******************************/
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar"><div id="blog-listing-medium" class="col-md-9">';
if(!$ctg)
{
echo"<title>Tutoriale coduri, informatii gratuite, dezvoltare in materie de coding - ".WEBNAME."</title>";
/*****************************/
$search = $db->prepare('SELECT * FROM `tutorials` ORDER BY id DESC');
$search->execute();
/****************************/
	 foreach($search as $row)
	 {
		$id = $row['id'];
		$photo = $row['photo'];
		$title = $row['title'];
		$titlu = curata_url($title);
		$author = $row['user'];
		$author_id = $row['userid'];
		$category = $row['categ'];
		$news_stripped = strip_tags($row['tutorial']);
		$shortdesc = substr($news_stripped,0,170);
		/******************/
		$comms = $db->prepare("SELECT * FROM `comments` WHERE `sectionid` = $id");
		$comms->execute();
		$comentarii = $comms->rowCount();
		/******************/
		$categoryes = $db->prepare("SELECT * FROM `tutorial_category` WHERE `notation` = '$category'");
		$categoryes->execute();
		$row2 = $categoryes->fetch();
		$category_name = $row2['category']; 
		/******************/
		$time = $row['data'];
		$aprobat = $row['approved'];
		
		if($aprobat == 0)
		{
			if(isset($_SESSION['user']) && $_SESSION['admin'] != 0)
			{
				echo'<div class="well">
						<div class="row">
						  <div class="col-md-4">
							<div class="image"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'"><img src="'.URL.'uploads/tutorials/'.$photo.'" class="img-fluid"></a></div>
						  </div>
						  <div class="col-md-8">
							<h2 class="h3 mt-0"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'">'.$title.' <h4 style="color:red;">NEAPROBAT</h4></a></h2>
							<div class="d-flex flex-wrap justify-content-between text-xs">
							  <p class="author-category">Postat de <a href="'.URL.'profile/'.$author_id.'-'.$author.'">'.$author.'</a> in <a href="'.URL.'tutoriale/search/'.$category.'">'.$category_name.'</a></p>
							  <p class="date-comments"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'"><i class="fa fa-calendar-o"></i> '.$time.'</a><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'"><i class="fa fa-comment-o"></i> '.$comentarii.' Comentarii</a></p>
							</div>
							<p class="intro">'.$shortdesc.' ...</p>
							<p class="read-more text-right"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'" class="btn btn-template-outlined">Citeste tot</a>
							<a href="'.URL.'tutoriale/admin/approve-'.$id.'-'.$author_id.'" class="btn btn-success">Aproba</a>
							<a href="'.URL.'tutoriale/admin/delete-'.$id.'-'.$author_id.'" class="btn btn btn-danger">Sterge</a></p>
						  </div>
						</div></div>';
			}
		}
		else
		{
			echo'<div class="well">
						<div class="row">
						  <div class="col-md-4">
							<div class="image"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'"><img src="'.URL.'uploads/tutorials/'.$photo.'" class="img-fluid"></a></div>
						  </div>
						  <div class="col-md-8">
							<h2 class="h3 mt-0"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'">'.$title.'</a></h2>
							<div class="d-flex flex-wrap justify-content-between text-xs">
							  <p class="author-category">Postat de  <a href="'.URL.'profile/'.$author_id.'-'.$author.'">'.$author.'</a> in <a href="'.URL.'tutoriale/search/'.$category.'">'.$category_name.'</a></p>
							  <p class="date-comments"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'"><i class="fa fa-calendar-o"></i> '.$time.'</a><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'"><i class="fa fa-comment-o"></i> '.$comentarii.' Comentarii</a></p>
							</div>
							<p class="intro">'.$shortdesc.' ...</p>
							<p class="read-more text-right"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'" class="btn btn-template-outlined">Citeste tot</a></p>
						  </div>
						</div></div>';
		}
	 }
}
/***************************************/
elseif($ctg == 'search')
{
	if(isset($_GET['item']))
	{
		$item = $_GET['item'];
		if(isset($_POST['sssssdas']))
		{
			$keyw = htmlspecialchars($_POST['keyw'], ENT_QUOTES, "UTF-8");
			$searchitem = $db->prepare("SELECT * FROM `tutorials` WHERE `approved` = 1 AND (title LIKE '%{$keyw}%' OR tutorial LIKE '%{$keyw}%' OR tags LIKE '%{$keyw}%')");
			$searchitem->execute();
		}
		else
		{
			$searchitem = $db->prepare("SELECT * FROM `tutorials` WHERE `categ` = '$item' AND `approved` = 1");
			$searchitem->execute();
		}
		echo'<title>Cautare '.$item.' - '.WEBNAME.'</title>';
		foreach($searchitem as $row)
		{
			$id = $row['id'];
		$photo = $row['photo'];
		$title = $row['title'];
		$titlu = curata_url($title);
		$author = $row['user'];
		$author_id = $row['userid'];
		$category = $row['categ'];
		$news_stripped = strip_tags($row['tutorial']);
		$shortdesc = substr($news_stripped,0,170);
		/******************/
		$comms = $db->prepare("SELECT * FROM `comments` WHERE `sectionid` = $id");
		$comms->execute();
		$comentarii = $comms->rowCount();
		/******************/
		$categoryes = $db->prepare("SELECT * FROM `tutorial_category` WHERE `notation` = '$category'");
		$categoryes->execute();
		$row2 = $categoryes->fetch();
		$category_name = $row2['category']; 
		/******************/
		$time = $row['data'];
		
		
		echo'<div class="well">
                <div class="row">
                  <div class="col-md-4">
                    <div class="image"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'"><img src="'.URL.'uploads/tutorials/'.$photo.'" class="img-fluid"></a></div>
                  </div>
                  <div class="col-md-8">
                    <h2 class="h3 mt-0"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'">'.$title.'</a></h2>
                    <div class="d-flex flex-wrap justify-content-between text-xs">
                      <p class="author-category">Postat de <a href="#">'.$author.'</a> in <a href="'.URL.'tutoriale/search/'.$category.'">'.$category_name.'</a></p>
                      <p class="date-comments"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'"><i class="fa fa-calendar-o"></i> '.$time.'</a><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'"><i class="fa fa-comment-o"></i> '.$comentarii.' Comentarii</a></p>
                    </div>
                    <p class="intro">'.$shortdesc.'</p>
                    <p class="read-more text-right"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'" class="btn btn-template-outlined">Citeste tot</a></p>
                  </div>
                </div></div>';
		}
	}
}
/***************************************/
elseif($ctg == 'approve')
{
	if($_SESSION['admin']<1) { echo("<script>location.href = '".URL."';</script>"); }
	/***************************************/
	if(isset($_GET['pendid']) && isset($_GET['readmore']))
	{	
		$id = $_GET['pendid'];
		/***************************************/
		$readmore = $db->prepare("SELECT * FROM `tutorials` WHERE `id` = $id");
		$readmore->execute();
		/******************************/
		foreach($readmore as $row)
		{
			$id = $row['id'];
			$title = $row['title'];
			$user = $row['user'];
			$userid = $row['userid'];
			$time = date('Y/m/d H:i:s' ,$row['data']);
			$views = $row['views'];
			$tutorial = $row['tutorial'];
			$ctg = $row['categ'];
			if($ctg == 1) $categname = 'C++';
			else if($ctg == 2) $categname = 'HTML';
			else if($ctg == 3) $categname = 'PHP';
			else if($ctg == 4) $categname = 'JavaScript';
			else if($ctg == 5) $categname = 'jQuery';
			$name = curata_url($row['user']);
			echo'<ul class="breadcrumb">
			<li><i class="icon-home"></i> <a href="'.URL.'home">Home</a> <span class="divider">/</span></li>
			<li ><a href="'.URL.'tutoriale">Tutoriale</a> <span class="divider">/</span> </li>
			<li ><a href="'.URL.'tutoriale/1">'.$categname.'</a> <span class="divider">/</span> </li>
			<li class="active">'.$title.'</li>'; //AICI AM RAMAS
			echo'<div style="float:right;"><a href="'.URL.'tutoriale/admin/delete-'.$id.'"><img src="'.URL.'images/newdel.gif"></img></a> <a href="'.URL.'tutoriale/admin/approve-i'.$id.'-u'.$userid.'"><img src="'.URL.'images/newconfirm.gif"></img></a></div>';
			echo'</ul>';
			/*****************************/
			echo"<center><h2>$title</h2></center><hr>";
			echo"$tutorial";
			echo"<hr>";
			echo"<div style='float:left;'>$time</div>";
			echo"<div style='float:right;'>Postat de <a href='".URL."profile/$userid-$name'>$user</a></div><br>";
		}
	}
}
/***************************************/
elseif($ctg == 'edit')
{
}
/***************************************/
elseif($ctg == 'viewtut')
{
	if(isset($_GET['rid']) && isset($_GET['delete']) && isset($_GET['userid']))
	{
		if($_SESSION['admin'] != 0) 
		{
			$escaped_id = mysql_real_Escape_String($_GET['rid']);
			$userid = mysql_real_Escape_String($_GET['userid']);
			
			$update = $db->prepare("DELETE FROM tutorials where id='$escaped_id'");
			$update->execute();
			$update2 = $db->prepare("DELETE FROM comments where sectionid='$escaped_id'");
			$update2->execute();
			
			$extract = $db->prepare("SELECT * FROM members WHERE `id` = $userid");
			$extract->execute();
			
			foreach($extract as $row)
			{
				$tuts = $row['tutorials'];
			}
			if($update) echo("<script>location.href = '".URL."tutoriale';</script>");
		}
	}
	elseif(isset($_GET['editid']) && isset($_GET['edit']))
	{
		if($_SESSION['admin'] != 0) 
		{
			$escaped_id = mysql_real_Escape_String($_GET['editid']);
			$search = $db->prepare("SELECT * FROM tutorials WHERE `id`=$escaped_id");
			$search->execute();
			foreach($search as $row)
			{
				$text = $row['tutorial'];
				$titlu = $row['title'];
			}
			/**********************************************/
			if (isset($_POST['edittutorial']))
			{
				$newtitle = trim($_POST['newtitle']);
				$newtut = trim($_POST['newtut']);
						
				$update = $db->prepare("UPDATE tutorials SET `title` = '$newtitle', `tutorial` = '$newtut' WHERE `id` = $escaped_id");
				$update->execute();
						
				if($update) echo'<div class="alert alert-success">Tutorial editat!</div>';
			}
			/**********************************************/
			echo"<form action='' method='post'>
			<label>Titlu:<br />
			<input class='form-control'  type='text' name='newtitle' value='$titlu'></input>
			</label> <br /><br />
			
			<label>Tutorial:<br />
			<input id='textarea2' name='newtut' value='$text'></input>
			</label> <br /><br />
			<button class='btn btn-success' type='submit' name='edittutorial'>Edit</button>
			</form>";
		}
	}
	else if(isset($_GET['accid']) && isset($_GET['confirm']) && isset($_GET['userid']))
	{
		$escaped_id = mysql_real_Escape_String($_GET['accid']);
		$userid = mysql_real_Escape_String($_GET['userid']);
		/******************************/
		$update = $db->prepare("UPDATE `tutorials` SET `approved`=1 WHERE `id` = $escaped_id");
		$update->execute();
		/******************************/
		$extract = $db->prepare("select * from members where `id` = $userid");
		$extract->execute();
		foreach($extract as $row)
		{
			$tuts = $row['tutorials'];
		}
		$update2 = $db->prepare("update members set `tutorials` = $tuts + 1 where `id` = $userid");
		$update2->execute();
		/******************************/
		echo '<div role="alert" class="alert alert-success">Tutorial acceptat. Acum va aparea pe lista de tutoriale si poate fi vizualizat de orice utilizator.</div>';
	}
	else if(isset($_GET['disableid']) && isset($_GET['confirm']) && isset($_GET['userid']))
	{
		$escaped_id = mysql_real_Escape_String($_GET['disableid']);
		$userid = mysql_real_Escape_String($_GET['userid']);
		/******************************/
		$update = $db->prepare("UPDATE `tutorials` SET `approved`=0 WHERE `id` = $escaped_id");
		$update->execute();
		/******************************/
		$extract = $db->prepare("select * from members where `id` = $userid");
		$extract->execute();
		foreach($extract as $row)
		{
			$tuts = $row['tutorials'];
		}
		$update2 = $db->prepare("update members set `tutorials` = $tuts - 1 where `id` = $userid");
		$update2->execute();
		/******************************/
		echo '<div role="alert" class="alert alert-success">Tutorialul a fost dezactivat si poate fi vazut doar de echipa administrativa.</div>';
	}
	else if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		/***************************************/
		$fromc = $db->prepare("SELECT * FROM `tutorials` WHERE `id` = $id");
		$fromc->execute();
		/***************************************/
		foreach($fromc as $row)
		{
			if($row['approved']==0)
			{
				if((isset($_SESSION['user']) && $_SESSION['admin'] == 0) || !isset($_SESSION['user']))
				{
					echo("<script>location.href = '".URL."tutoriale';</script>");
				}
			}
			echo"<div class='well'>";
			$id = $row['id'];
			$title = $row['title'];
			$user = $row['user'];
			$userid = $row['userid'];
			$time = $row['data'];
			$views = $row['views'];
			$tutorial = $row['tutorial'];
			$name = curata_url($row['user']);
			$categ = $row['categ'];
			/******************************/
			$categoryes = $db->prepare("SELECT * FROM `tutorial_category` WHERE `notation` = '$categ'");
			$categoryes->execute();
			$row2 = $categoryes->fetch();
			$category_name = $row2['category']; 
			$aprobat = $row['approved'];
			/******************************/
			echo"<title>$title - ".WEBNAME."</title>";
			/******************************/
			if($_SESSION['admin'] != 0) 
			{ 
				echo'<div style="float:right;">';
				if($aprobat == 0)
				echo'<a href="'.URL.'tutoriale/admin/approve-'.$id.'-'.$userid.'" class="btn btn-sm btn-success"  style="margin-right:2px;">Activeaza</a>';
				else echo'<a href="'.URL.'tutoriale/admin/disable-'.$id.'-'.$userid.'" class="btn btn-sm btn-danger" style="margin-right:2px;">Dezactiveaza</a>';
				echo'<a href="'.URL.'tutoriale/admin/delete-'.$id.'-'.$userid.'"  class="btn btn-sm btn-default" style="margin-right:2px;"><i class="fa fa-remove"></i> Sterge</a>';
				echo'<a href="'.URL.'tutoriale/admin/edit-'.$id.'"  class="btn btn-sm btn-info">Editeaza</a></div>';
			}
			/*****************************/
			echo"<center><h2>$title</h2></center><hr>";
			echo"$tutorial";
			echo"<hr>";
			echo"<div style='float:right;'>$time</div>";
			echo"<div style='float:left;'>Postat de <a href='".URL."profile/$userid-$name'>$user</a></div><br>";
			echo"</div>";
			if(isset($_SESSION['user'])) 
			{
				echo "<hr>
				<form action='".URL."comentator.php?page=comment&category=$categ&id=$id&sectionname=tutorials' method='post'>";
					include("modules/comments/comment_form.php");
				echo "</form>";
			}
			else 
			{
				echo'<hr><center><div class="alert alert-danger">Trebuie sa fii <b>autentificat</b> pentru a putea posta un comentariu!</div></center>';
			}
			display_comments($categ,$id);
		}
	}
}
/***************************************/
elseif($ctg == 'addtutorials')
{
	if(!isset($_SESSION['user'])) { echo("<script>location.href = '".URL."autentificare';</script>"); } 
	echo"<div class='well'><strong>Informatii</strong><hr>";
	echo "<div class='alert alert-info'><b>Pentru a posta un tutorial, urmeaz&#259; pa&#351;ii:</b><br><div style='font-size:15px;'>";
	echo '<u>1.</u> Scrie un titlu coerent care s&#259; reflecte con&#355;inutul tutorialului.<br>';
	echo '<u>2.</u> Creeaz&#259; tutorialul astfel &#238;ncat s&#259; fie pe &#238;n&#355;elesul tuturor (f&#259;r&#259; gre&#351;eli gramaticale, prescurt&#259;ri, etc.). Utilizeaz&#259; instrumentele puse la dispozi&#355;ie pentru un aspect pl&#259;cut.<br>';
	echo "<u>3.</u> Selecteaz&#259; sec&#355;iunea corespunz&#259;toare, apas&#259; butonul <i>'Adaug&#259; tutorial'</i> &#351;i a&#351;teapt&#259; aprobarea unui administrator.</br></div>";
	echo"</div><hr></div>";
	/******************************/
	echo"<title>Adauga un tutorial - ".WEBNAME."</title>";
	/*********************************/
	if (isset($_POST['add_tutorialbyuser']))
	{
		$username = $_SESSION['user'];
		$titlu = $_POST['titlu'];
		$tutorial = $_POST['tutorial'];
		$option = $_POST['categ_option'];
		$tags = $_POST['tags'];
		$ziua = date("d");
		$anul = date("Y");
		$luna = date("M");
		$nume_luna = array(
		"Jan" => "Ianuarie",
		"Feb" => "Februarie",
		"Mar" => "Martie",
		"Apr" => "Aprilie",
		"May" => "Mai",
		"Iun" => "Junie",
		"Jul" => "Julie",
		"Aug" => "August",
		"Sep" => "Septembrie",
		"Oct" => "Octombrie",
		"Nov" => "Noiembrie",
		"Dec" => "Decembrie");
		$luna = $nume_luna[$luna]; 
		$azi = "$ziua $luna $anul";
		/********************************************/
		$finduserid = $db->prepare("select * from members WHERE `name` = :name");
		$finduserid->execute(array('name'=>$username));
		foreach($finduserid as $torow)
		{
			$userid = $torow['id'];
			$tuts = $torow['tutorials'];
		}
		/********************************************/
		$sql = $db->prepare("insert into tutorials (title, categ, user, userid, data, tutorial,approved,tags) values (:titlu,:option,:username,:userid,:azi,:tutorial,0,:tags)");
		$sql->execute(array(':titlu'=>$titlu, ':option'=>$option, ':username'=>$username, ':userid'=>$userid, ':azi'=>$azi, ':tutorial'=>$tutorial, ':tags'=>$tags));
		if($sql)
		{
			echo'<div class="alert alert-success">
				Tutorialul t&#259;u a fost ad&#259;ugat &#238;n baza noastr&#259; de date, &#238;ns&#259; acesta necesit&#259; aprobarea unui <b>administrator</b>.
			</div>';
		}
	}
	echo"<br />";
	/*********************************/
	echo"<form action='' method='post' enctype='multipart/form-data'><div class='well'><strong>Adauga un tutorial</strong><hr>
			<div class='row'><label class='col-sm-2'>Titlul tutorialului</label>
			<div class='col-sm-10'><input class='form-control' name='titlu' placeholder='Titlul tutorialului'></input>
			</div>
			</div><br>
			
			<div class='row'><label class='col-sm-2'>Tutorial</label>
			<div class='col-sm-10'><textarea id='edit'  name='tutorial' rows='3' placeholder='Tutorial..'></textarea>
			</div>
			</div><br>
			
			<div class='row'><label class='col-sm-2'>Categorie:</label>
			<div class='col-sm-10'><select class='form-control' name='categ_option'>";
			/*****************************************************************************/
			$categ = $db->prepare("SELECT * FROM `tutorial_category` ORDER BY `id`");
			$categ->execute();
			/*****************************************************************************/
			foreach($categ as $row)
			{
				$notation = $row['notation'];
				$category = $row['category'];
				echo"<option value='$notation'>$category</option>";
			}
			echo"</select></div><br /><br /><br>	
			</div>
			
			<div class='row'><label class='col-sm-2'>Tags</label>
			<div class='col-sm-10'><input class='form-control' name='tags' placeholder='Cuvinte cheie'></input>
			</div>
			</div><br>
	<center><button class='btn btn-template-outlined' type='submit' name='add_tutorialbyuser'><i class='fa fa-upload'></i> Adaug&#259; tutorial</button></center>";
	echo"</div></form>";
}
echo"</div>";
echo'<div class="col-md-3"><a class="btn btn-danger btn-block" href="'.URL.'adauga-un-tutorial">Adauga un tutorial</a><br><div class="well">';
	echo'<div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Categorii</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">';
					/*echo'<li class="nav-item"><a href="'.URL.'tutoriale" class="nav-link"><i class="fa fa-chevron-right" style="color: #58666e;"></i> Toate resursele </a></li>';*/
					/*************************************************************************/
					$maincateg = $db->prepare("SELECT * FROM `tutorial_maincateg` ORDER BY `id`");
					$maincateg->execute();
					foreach($maincateg as $row2)
					{
						echo'<strong>'.$row2['name'].'</strong>';
						$mainid = $row2['id'];
						$allcateg = $db->prepare("SELECT * FROM `tutorial_category` WHERE `maincateg` = '$mainid'");
						$allcateg->execute();
						/*************************************************************************/
						foreach($allcateg as $row)
						{
							$id = $row['id'];
							$category = $row['category'];
							$notation = $row['notation'];
							$txt = $db->prepare("SELECT * FROM `tutorials` WHERE `categ` = '$notation' AND `approved` = '1'");
							$txt->execute();
							echo'<li class="nav-item"><a href="'.URL.'tutoriale/search/'.$notation.'" class="nav-link"><i class="fa fa-chevron-right" style="color: #58666e;"></i>  '.$category.' ('.$txt->rowCount().') </a></li>';
						}
					}
                  echo'</ul>
                </div>
              </div>';
	echo'</div>
	
	<div class="well">';
	echo'<div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Ultimele tutoriale</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">';
					/*************************************************************************/
					$allcateg = $db->prepare("SELECT * FROM `tutorials` WHERE `approved` = 1 ORDER BY `id` DESC LIMIT 5");
					$allcateg->execute();
					/*************************************************************************/
					foreach($allcateg as $row)
					{
						$id = $row['id'];
						$title = $row['title'];
						$titlu = curata_url($title);
						echo'<li class="nav-item"><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'" class="nav-link"><i class="fa fa-chevron-right" style="color: #58666e;"></i> '.$title.'</a></li>';
					}
                  echo'</ul>
                </div>
              </div>';
	echo'</div>';
	
	/*echo'<div class="well">';
	echo'<div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Top 5 utilizatori</h3>
                </div>
                <div class="panel-body">';
                  echo'<ul class="nav nav-pills flex-column text-sm">';
				  $allcateg = $db->prepare("SELECT COUNT(*) as id FROM `tutorials` GROUP by `user`");
					$allcateg->execute();
					echo"<div style='font-size:15px;'>";
					$count = 1;
					foreach($allcateg as $row)
					{
						$id = $row['id'];
						$user = $row['user'];
						$userid = $row['userid'];
						if($count == 1) $countm = "<strong><font color=#B81414>$count</font></strong>";
						else if($count == 2) $countm = "<strong><font color=#30BA09>$count</font></strong>";
						else if($count == 3) $countm = "<strong><font color=#8DC402>$count</font></strong>";
						else $countm = $count;
						echo"<div style='border-bottom:1px solid #E0E0E0; padding-top:5px;'>$countm. <strong><a href='".URL."profile/$userid-$user'>$user</a></strong>  tutoriale postate.</div>";
						$count++;
					}
					echo"</div>";
                  echo'</ul>
                </div>
              </div>';
	echo'</div>';	*/	
	echo'</div>';
/***************************************/
echo'</div></div></div>';
/*********************************/
Footer();
echo"</div></div></div></div>";
AnotherScripts();

if($ctg == "viewtut" && isset($_GET['editid']) && isset($_GET['edit']))
{
 echo'<script type="text/javascript" src="'.URL.'js/wysiwyg/scripts/wysiwyg.js"></script>
	<script type="text/javascript" src="'.URL.'wysiwyg/scripts/wysiwyg-settings.js"></script>';
echo "<script> WYSIWYG.attach('textarea2'); </script>";
}
if($ctg == "addtutorials")
{/*echo'<script type="text/javascript" src="'.URL.'js/wysiwyg/scripts/wysiwyg.js"></script>
	<script type="text/javascript" src="'.URL.'wysiwyg/scripts/wysiwyg-settings.js"></script>';
echo "<script> WYSIWYG.attach('textarea1'); </script>";*/
echo'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

  <script type="text/javascript" src="'.URL.'texteditor/js/froala_editor.min.js" ></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/align.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/char_counter.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/code_beautifier.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/code_view.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/colors.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/draggable.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/emoticons.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/entities.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/file.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/font_size.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/font_family.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/fullscreen.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/image.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/image_manager.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/line_breaker.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/inline_style.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/link.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/lists.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/paragraph_format.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/paragraph_style.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/quick_insert.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/quote.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/table.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/save.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/url.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/video.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/help.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/print.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/third_party/spell_checker.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/special_characters.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/word_paste.min.js"></script>';?>

  <script>
    $(function(){
      $('#edit').froalaEditor()
    });
  </script>
<?php
}
echo"</body>";