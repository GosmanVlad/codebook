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
              <h1 class="h2">Ai o problema? O Eroare?</h1>
			  <p style="font-size:16px;margin-bottom: 10px;font-weight: 300;color:#DBDBDB;">Posteaza aici problema pe care ai intalnit-o, alaturi de solutiile incercate si modul tau de gandire.<br>
			  Aici poti discuta orice, poti adresa intrebari legate de comunitate sau alte nedumeriri.</p>
            </div>
          </div>
        </div>
      </div>';
/******************************/
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar"><div id="blog-listing-medium" class="col-lg-9">';
if(!$ctg)
{	
	echo"<title>Q&A si alte discutii - ".WEBNAME."</title>";
	/******************************/
	echo'<div class="well"><div class="row"><div class="col-md-7" style="padding-bottom:4px;"><a href="'.URL.'discutii/adauga-post" class="btn btn-success">Adauga un post</a></div>
		 <div class="col-md-5">';
		 echo'<form role="form" method="post" action="" autocomplete="off">
                <div class="input-group">
                  <input type="text" name="searchtags" class="form-control" placeholder="Cauta un subiect..">
                  <div class="input-group-append">
                    <button type="submit" name="searchts" class="btn btn-info"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>';
			  /*echo'<form role="form" method="post" action="" autocomplete="off">
                <div class="input-group">
                  <input name="searchtags" type="text" class="form-control">
                  <div class="input-group-append">
                    <button type="submit" name="searchts" class="btn btn-secondary"><i class="fa fa-send"></i></button>
                  </div>
                </div>
              </form>';*/
			  echo'</div></div></div>';
			  if(isset($_POST['searchts']))
{
	$searchtags = htmlspecialchars($_POST['searchtags'], ENT_QUOTES, "UTF-8");
	$acc = $db->prepare("SELECT * FROM `forum` WHERE tags LIKE '%{$searchtags}%' OR title LIKE '%{$searchtags}%' OR text LIKE '%{$searchtags}%'");
	$acc->execute();
echo'<div class="well">
	';
	if($acc->rowCount() != 0)
	{
		echo "<center><h5>Rezultate gasite (".$acc->rowCount().")</h5></center>";
		 foreach($acc as $row)
	 {
		$id = $row['id'];
		$titlu = $row['title'];
		$title = curata_url($titlu);
		$text = $row['text'];
		$userid = $row['userid'];
		$username = $row['username'];
		$solved = $row['solved'];
		$data = $row['data'];
		$tags = $row['tags'];
		$icon = NULL;
		/*****************************************************/
		$answers = $db->prepare("SELECT * FROM `comments` WHERE `sectionid` = '$id' AND `sectionname` = 'forum'");
		$answers->execute();
		$raspunsuri = $answers->rowCount();
		if($raspunsuri == 0) $icon = '<i class="fa fa-comment-o"></i>';
		else if($raspunsuri == 1) $icon = '<i class="fa fa-comment""></i>';
		else $icon = '<i class="fa fa-comments"></i>';
		if($solved == 1) $icon = '<i class="fa fa-check" style="color:#085001"></i>';
		/*****************************************************/
		echo'<div class="row">';
		echo'<div class="col-1"><div class="forumicon">'.$icon.'</div></div>';
		echo'<div class="col-md-6"><a href="'.URL.'discutii/'.$id.'/'.$title.'" style="font-weight: 700;font-size: 14px;text-decoration: none;color: #000;">'.$titlu.'</a><br><div class="helpline">Postat de <a href="'.URL.'profile/'.$userid.'-'.$username.'"><strong>'.$username.'</strong></a> <br>'.$data.'</div></div>';
		echo'<div class="col-md-4"><center><strong>'.$raspunsuri.'<br>Raspunsuri</strong></center></div>';
		echo'</div><br>';
		/*****************************************************/
	 }
	}
	else echo '<div role="alert" class="alert alert-danger">Nu s-a gasit utilizatorul cautat.</div>';
	echo'</div>';
}
	echo'<div class="well">';
	echo'<nav id="myTab" role="tablist" class="nav nav-tabs">
		<a id="tab4-1-tab" data-toggle="tab" href="#tab4-1" role="tab" aria-controls="tab4-1" aria-selected="true" class="nav-item nav-link active"> <i class="icon-star"></i>Cele mai recente</a>
		<a id="tab4-2-tab" data-toggle="tab" href="#tab4-2" role="tab" aria-controls="tab4-2" aria-selected="false" class="nav-item nav-link">Fara rezolvare</a>
		<a id="tab4-3-tab" data-toggle="tab" href="#tab4-3" role="tab" aria-controls="tab4-3" aria-selected="false" class="nav-item nav-link">Intrebarile mele</a>
		<a id="tab4-4-tab" data-toggle="tab" href="#tab4-4" role="tab" aria-controls="tab4-4" aria-selected="false" class="nav-item nav-link">Rezolvate/Inchise</a>
		</nav>';
	echo'<div id="nav-tabContent" class="tab-content">';
	/*****************************/
	$MostRecent = $db->prepare('SELECT * FROM `forum` ORDER BY id DESC');
	$MostRecent->execute();
	/*****************************************************************************/
	echo'<div id="tab4-1" role="tabpanel" aria-labelledby="tab4-1-tab" class="tab-pane fade show active">';
	 foreach($MostRecent as $row)
	 {
		$id = $row['id'];
		$titlu = $row['title'];
		$title = curata_url($titlu);
		$text = $row['text'];
		$userid = $row['userid'];
		$username = $row['username'];
		$solved = $row['solved'];
		$data = $row['data'];
		$tags = $row['tags'];
		$icon = NULL;
		/*****************************************************/
		$answers = $db->prepare("SELECT * FROM `comments` WHERE `sectionid` = '$id' AND `sectionname` = 'forum'");
		$answers->execute();
		$raspunsuri = $answers->rowCount();
		if($raspunsuri == 0) $icon = '<i class="fa fa-comment-o"></i>';
		else if($raspunsuri == 1) $icon = '<i class="fa fa-comment""></i>';
		else $icon = '<i class="fa fa-comments"></i>';
		if($solved == 1) $icon = '<i class="fa fa-check" style="color:#085001"></i>';
		/*****************************************************/
		echo'<div class="row">';
		echo'<div class="col-1"><div class="forumicon">'.$icon.'</div></div>';
		echo'<div class="col-md-6"><a href="'.URL.'discutii/'.$id.'/'.$title.'" style="font-weight: 700;font-size: 14px;text-decoration: none;color: #000;">'.$titlu.'</a><br><div class="helpline">Postat de <a href="'.URL.'profile/'.$userid.'-'.$username.'"><strong>'.$username.'</strong></a> <br>'.$data.'</div></div>';
		echo'<div class="col-md-4"><center><strong>'.$raspunsuri.'<br>Raspunsuri</strong></center></div>';
		echo'</div><br>';
		/*****************************************************/
	 }
	 echo'</div>';
	  /*****************************************************************************/
	echo'<div id="tab4-2" role="tabpanel" aria-labelledby="tab4-2-tab" class="tab-pane fade">';
	$nosolved = $db->prepare('SELECT * FROM `forum` WHERE `solved` = 0 ORDER BY id DESC');
	$nosolved->execute();
	 foreach($nosolved as $row)
	 {
		$id2 = $row['id'];
		$titlu2 = $row['title'];
		$title2 = curata_url($titlu);
		$text2 = $row['text'];
		$userid2 = $row['userid'];
		$username2 = $row['username'];
		$solved2 = $row['solved'];
		$data2 = $row['data'];
		$tags2 = $row['tags'];
		$icon2 = NULL;
		/*****************************************************/
		$answers2 = $db->prepare("SELECT * FROM `comments` WHERE `sectionid` = $id2 AND `sectionname` = 'forum'");
		$answers2->execute();
		$raspunsuri2 = $answers2->rowCount();
		if($raspunsuri2 == 0) $icon2 = '<i class="fa fa-comment-o"></i>';
		else if($raspunsuri2 == 1) $icon2 = '<i class="fa fa-comment""></i>';
		else $icon2 = '<i class="fa fa-comments"></i>';
		if($solved2 == 1) $icon2 = '<i class="fa fa-check" style="color:#085001"></i>';
		/*****************************************************/
		echo'<div class="row">';
		echo'<div class="col-1"><div class="forumicon">'.$icon2.'</div></div>';
		echo'<div class="col-md-6"><a href="'.URL.'discutii/'.$id2.'/'.$title2.'" style="font-weight: 700;font-size: 14px;text-decoration: none;color: #000;">'.$titlu2.'</a><br><div class="helpline">Postat de <a href="'.URL.'profile/'.$userid2.'-'.$username2.'"><strong>'.$username2.'</strong></a> <br>'.$data2.'</div></div>';
		echo'<div class="col-md-4"><center><strong>'.$raspunsuri2.'<br>Raspunsuri</strong></center></div>';
		echo'</div><br>';
		/*****************************************************/
	 }
	 echo'</div>';
	 /*****************************************************************************/
	echo'<div id="tab4-3" role="tabpanel" aria-labelledby="tab4-3-tab" class="tab-pane fade">';
	if(isset($_SESSION['user']))
	{
		$username = $_SESSION['user'];
		$myquestions = $db->prepare("SELECT * FROM `forum` WHERE `username` = '$username' ORDER BY id DESC");
		$myquestions->execute();
		if($myquestions->rowCount() > 0)
		{
			 foreach($myquestions as $row)
			 {
				$id2 = $row['id'];
				$titlu2 = $row['title'];
				$title2 = curata_url($titlu);
				$text2 = $row['text'];
				$userid2 = $row['userid'];
				$username2 = $row['username'];
				$solved2 = $row['solved'];
				$data2 = $row['data'];
				$tags2 = $row['tags'];
				$icon2 = NULL;
				/*****************************************************/
				$answers2 = $db->prepare("SELECT * FROM `comments` WHERE `sectionid` = $id2 AND `sectionname` = 'forum'");
				$answers2->execute();
				$raspunsuri2 = $answers2->rowCount();
				if($raspunsuri2 == 0) $icon2 = '<i class="fa fa-comment-o"></i>';
				else if($raspunsuri2 == 1) $icon2 = '<i class="fa fa-comment""></i>';
				else $icon2 = '<i class="fa fa-comments"></i>';
				if($solved2 == 1) $icon2 = '<i class="fa fa-check" style="color:#085001"></i>';
				/*****************************************************/
				echo'<div class="row">';
				echo'<div class="col-1"><div class="forumicon">'.$icon2.'</div></div>';
				echo'<div class="col-md-6"><a href="'.URL.'discutii/'.$id2.'/'.$title2.'" style="font-weight: 700;font-size: 14px;text-decoration: none;color: #000;">'.$titlu2.'</a><br><div class="helpline">Postat de <a href="'.URL.'profile/'.$userid2.'-'.$username2.'"><strong>'.$username2.'</strong></a> <br>'.$data2.'</div></div>';
				echo'<div class="col-md-4"><center><strong>'.$raspunsuri2.'<br>Raspunsuri</strong></center></div>';
				echo'</div><br>';
				/*****************************************************/
			 }
		}
		else echo'<div role="alert" class="alert alert-info">Nu ai nici un post.</div>';
	}
	else echo'<div role="alert" class="alert alert-danger">Trebuie sa fii autentificat pentru a-ti afisa intrebarile.</div>';
	echo'</div>';
	 /*****************************************************************************/
	echo'<div id="tab4-4" role="tabpanel" aria-labelledby="tab4-4-tab" class="tab-pane fade">';
	$solved = $db->prepare('SELECT * FROM `forum` WHERE `solved` = 1 ORDER BY id DESC');
	$solved->execute();
	 foreach($solved as $row)
	 {
		$id2 = $row['id'];
		$titlu2 = $row['title'];
		$title2 = curata_url($titlu);
		$text2 = $row['text'];
		$userid2 = $row['userid'];
		$username2 = $row['username'];
		$solved2 = $row['solved'];
		$data2 = $row['data'];
		$tags2 = $row['tags'];
		$icon2 = NULL;
		/*****************************************************/
		$answers2 = $db->prepare("SELECT * FROM `comments` WHERE `sectionid` = $id2 AND `sectionname` = 'forum'");
		$answers2->execute();
		$raspunsuri2 = $answers2->rowCount();
		if($raspunsuri2 == 0) $icon2 = '<i class="fa fa-comment-o"></i>';
		else if($raspunsuri2 == 1) $icon2 = '<i class="fa fa-comment""></i>';
		else $icon2 = '<i class="fa fa-comments"></i>';
		if($solved2 == 1) $icon2 = '<i class="fa fa-check" style="color:#085001"></i>';
		/*****************************************************/
		echo'<div class="row">';
		echo'<div class="col-1"><div class="forumicon">'.$icon2.'</div></div>';
		echo'<div class="col-md-6"><a href="'.URL.'discutii/'.$id2.'/'.$title2.'" style="font-weight: 700;font-size: 14px;text-decoration: none;color: #000;">'.$titlu2.'</a><br><div class="helpline">Postat de <a href="'.URL.'profile/'.$userid2.'-'.$username2.'"><strong>'.$username2.'</strong></a> <br>'.$data2.'</div></div>';
		echo'<div class="col-md-4"><center><strong>'.$raspunsuri2.'<br>Raspunsuri</strong></center></div>';
		echo'</div><br>';
		/*****************************************************/
	 }
	 echo'</div>';
	 /*****************************************************************************/
	echo'</div></div>';
}
elseif($ctg=="newpost")
{
	if(isset($_SESSION['user']))
	{
		echo"<title>Adauga o noua intrebare - ".WEBNAME."</title>";
		echo'<meta name="Keywords" content="problema it, eroare cod, problema info">';
		/*****************************************************************************/
		if (isset($_POST['addpost']))
		{
			$username = $_SESSION['user'];
			$titlu = $_POST['titlu'];
			$post = $_POST['message'];
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
			/***************************/
			$finduserid = $db->prepare("select * from members WHERE `name` = :name");
			$finduserid->execute(array('name'=>$username));
			$row = $finduserid->fetch();
			$userid = $row['id'];
			$sql = $db->prepare("insert into forum (title, text, userid, username, data, tags) values ('$titlu','$post','$userid','$username','$azi','$tags')");
			$sql->execute();
			/***************************/
			if($sql)
			{
				echo'<div class="alert alert-success">
					Post-ul t&#259;u a fost ad&#259;ugat.
				</div>';
			}
		}
		echo'<form action="" method="post" enctype="multipart/form-data"><div class="well"><strong>Adauga un post</strong><hr>
				Titlu:<br>
				<input class="form-control" name="titlu" placeholder="Titlul postului"><br>
				Mesaj:<br>
				<textarea name="message" class="form-control"></textarea><br>
				Tags:<br>
				<input class="form-control" name="tags" placeholder="Cuvinte cheie"><hr>
				
		<center><button class="btn btn-template-outlined" type="submit" name="addpost"><i class="fa fa-upload"></i> Adauga</button></center></div></form>';
	}
	else echo'<div role="alert" class="alert alert-danger">Trebuie sa fii autentificat!</div>';
}
elseif($ctg=="view")
{
	if(isset($_GET['deleteid']))
	{
		if($_SESSION['admin'] != 0) 
		{
			$escaped_id = $_GET['deleteid'];
			$update = $db->prepare("DELETE FROM forum where id='$escaped_id'");
			$update->execute();
			
			if($update) echo("<script>location.href = '".URL."discutii';</script>");
		}
	}
	elseif(isset($_GET['solvid']) && isset($_GET['solved']))
	{
		$escaped_id = $_GET['solvid'];
		$update = $db->prepare("UPDATE `forum` SET `solved`=1 WHERE `id` = $escaped_id");
		$update->execute();
		
		if($update) echo("<script>location.href = '".URL."discutii';</script>");
	}
	elseif(isset($_GET['unsolvid']) && isset($_GET['unsolved']))
	{
		if($_SESSION['admin'] != 0) 
		{
			$escaped_id = $_GET['unsolvid'];
			$update = $db->prepare("UPDATE `forum` SET `solved`=0 WHERE `id` = $escaped_id");
			$update->execute();
			
			if($update)  echo("<script>location.href = '".URL."discutii';</script>");
		}
	}
	elseif(isset($_GET['unsolvid']) && isset($_GET['unsolved']))
	{
		if($_SESSION['admin'] != 0) 
		{
			$escaped_id = $_GET['unsolvid'];
			$update = $db->prepare("UPDATE `forum` SET `solved`=0 WHERE `id` = $escaped_id");
			$update->execute();
			
			if($update)  echo("<script>location.href = '".URL."discutii';</script>");
		}
	}
	elseif(isset($_GET['editid']))
	{
		$escaped_id = $_GET['editid'];
		$username = $_GET['postby'];
		if($_SESSION['user'] == $username || $_SESSION['admin'] > 0) 
		{
			echo"<title>Editeaza post-ul - ".WEBNAME."</title>";
			$search = $db->prepare("SELECT * FROM forum WHERE `id`=$escaped_id");
			$search->execute();
			foreach($search as $row)
			{
				$text = $row['text'];
				$titlu = $row['title'];
				$tags = $row['tags'];
			}
			/**********************************************/
			if (isset($_POST['editpost']))
			{
				$newtitle = trim($_POST['newtitle']);
				$newpost = trim($_POST['newpost']);
				$newtags = trim($_POST['newtags']);
						
				$update = $db->prepare("UPDATE forum SET `title` = '$newtitle', `text` = '$newpost', `tags` = '$newtags' WHERE `id` = $escaped_id");
				$update->execute();
						
				if($update) echo'<div class="alert alert-success">Post editat!</div>';
			}
			/**********************************************/
			echo"<div class='well'><form action='' method='post'>
			<label>Titlu:<br />
			<input class='form-control'  type='text' name='newtitle' value='$titlu'></input>
			</label> <br /><br />
			
			<label>Post:<br />
			<input id='textarea1' type='text' name='newpost' value='$text'></input>
			</label> <br /><br />
			
			<label>Tags:<br />
			<input class='form-control'  type='text' name='newtags' value='$tags'></input>
			</label> <br /><br />
			<button class='btn btn-success' type='submit' name='editpost'>Edit</button>
			</form></div>";
		}
	}
	else if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		/***************************************/
		$fromc = $db->prepare("SELECT * FROM `forum` WHERE `id` = $id");
		$fromc->execute();
		/***************************************/
		foreach($fromc as $row)
		{
			$title = $row['title'];
			$titlu = curata_url($title);
			$text = $row['text'];
			$userid = $row['userid'];
			$username = $row['username'];
			$solved = $row['solved'];
			$data = $row['data'];
			$tags = $row['tags'];
			echo"<title>$title - ".WEBNAME."</title>";
			echo'<meta name="Keywords" content="$tags">';
			echo"<div class='well'>";
			/*****************************/
			echo"<center><h2>$title</h2></center><hr>";
			echo"$text";
			echo"<hr>";
			echo"<div style='float:right;'>$data</div>";
			echo"<div style='float:left;'>Postat de <a href='".URL."profile/$userid-$username'>$username</a></div><br>";
			/******************************/
			if(isset($_SESSION['user'])) 
			{ 
				if($_SESSION['user'] == $username || $_SESSION['admin'] > 0)
				{
					echo'<hr><div style="float:right;">';
					if($_SESSION['admin'] > 0)
					{
						echo'<a href="'.URL.'discutii/admin/delete-'.$id.'"  class="btn btn-sm btn-default" style="margin-right:2px;"><i class="fa fa-remove"></i> Sterge</a>';
						if($solved == 1)
						echo'<a href="'.URL.'discutii/unsolved/'.$id.'" class="btn btn-sm btn-success" style="margin-right:2px;">Redeschide post-ul</a>';
					}
					if($solved == 0)
					{
						echo'<a href="'.URL.'discutii/solved/'.$id.'" class="btn btn-sm btn-success" style="margin-right:2px;">Inchide/Marcheaza ca rezolvat</a>';
						echo'<a href="'.URL.'discutii/edit/'.$id.'-'.$username.'"  class="btn btn-sm btn-info">Editeaza</a>';
					}
					echo'</div>';
				}
			}
			echo"</div>";
			if(isset($_SESSION['user'])) 
			{
				if($solved == 0)
				{
					echo "<hr>
					<form action='".URL."comentator.php?page=comment&category=discutii&id=$id&sectionname=forum' method='post'>";
						include("modules/comments/comment_form.php");
					echo "</form>";
				}
				else echo'<div role="alert" class="alert alert-info">Acest subiect este rezolvat/inchis intrucat nu se mai pot posta raspunsuri.</div>';
			}
			else 
			{
				echo'<hr><center><div class="alert alert-danger">Trebuie sa fii <b>autentificat</b> pentru a putea posta un comentariu!</div></center>';
			}
			display_comments('discutii',$id);
			
		}
	}
}
echo"</div>";
echo'<div class="col-md-3">
	<div class="well">';
	echo'<div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Ultimele discutii</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">';
					/*************************************************************************/
					$allcateg = $db->prepare("SELECT * FROM `forum` WHERE `solved` = 0 ORDER BY `id` DESC LIMIT 5");
					$allcateg->execute();
					/*************************************************************************/
					foreach($allcateg as $row)
					{
						$id = $row['id'];
						$title = $row['title'];
						$titlu = curata_url($title);
						echo'<li class="nav-item"><a href="'.URL.'discutii/'.$id.'/'.$titlu.'" class="nav-link"><i class="fa fa-chevron-right" style="color: #58666e;"></i> '.$title.'</a></li>';
					}
                  echo'</ul>
                </div>
              </div>';
	echo'</div>
	
	
	</div>';
/***************************************/
echo'</div></div></div>';
/*********************************/
Footer();
echo"</div></div></div></div>";
AnotherScripts();
if($ctg == "view" && isset($_GET['editid']) && isset($_GET['postby']))
{echo'<script type="text/javascript" src="'.URL.'js/wysiwyg/scripts/wysiwyg.js"></script>
	<script type="text/javascript" src="'.URL.'wysiwyg/scripts/wysiwyg-settings.js"></script>';
echo "<script> WYSIWYG.attach('textarea1'); </script>";
}
echo"</body>";