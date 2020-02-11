<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/***************************************/
if(isset($_SESSION['user'])) $username = $_SESSION['user'];
if(isset($_GET['page'])) $page=$_GET["page"];
else $_GET['page'] = NULL;
if(isset($_GET['active'])) $active=$_GET["active"];
else $active = $_GET['active'] = NULL;
/***************************************/
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
/*********************************/
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar">';	  
echo'<div class="col-md-3 mt-4 mt-md-0">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Comenzi</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">';
				  if(isset($_GET['userid']))
				  {
					$userid = $_GET['userid'];
					$userid = $db->prepare("SELECT * FROM `members` WHERE `id` = '$userid'");
					$userid->execute();
					$row = $userid->fetch();
					$user_id = $row['id']; 
					$user = $row['name']; 
					
					if($active == 0) echo'<li class="nav-item"><a href="'.URL.'profile/'.$user_id.'-'.$user.'" class="nav-link active"><i class="fa fa-user"></i> Profil</a></li>';
					else echo'<li class="nav-item"><a href="'.URL.'profile/'.$user_id.'-'.$user.'" class="nav-link"><i class="fa fa-user"></i> Profil</a></li>';
						
					if($active == 1) echo'<li class="nav-item"><a href="'.URL.'profile/tutorials-'.$user_id.'/1" class="nav-link active"><i class="fa fa-copy"></i> Tutoriale postate</a></li>';
					else echo'<li class="nav-item"><a href="'.URL.'profile/tutorials-'.$user_id.'/1" class="nav-link"><i class="fa fa-copy"></i> Tutoriale postate</a></li>';
					
					if($active == 2) echo'<li class="nav-item"><a href="'.URL.'profile/resurse-'.$user_id.'/2" class="nav-link active"><i class="fa fa-list"></i> Resurse postate</a></li>';
					else echo'<li class="nav-item"><a href="'.URL.'profile/resurse-'.$user_id.'/2" class="nav-link"><i class="fa fa-list"></i> Resurse postate</a></li>';
						
					if($active == 3) echo'<li class="nav-item"><a href="'.URL.'profile/comments-'.$user_id.'/3" class="nav-link active"><i class="fa fa-commenting"></i> Comentarii recente</a></li>';
					else echo'<li class="nav-item"><a href="'.URL.'profile/comments-'.$user_id.'/3" class="nav-link"><i class="fa fa-commenting"></i> Comentarii recente</a></li>';
					
					if(isset($_SESSION['user']))
					{
						if($_SESSION['userid'] == $_GET['userid'])
						{
							if($active == 5)  echo'<li class="nav-item"><a href="'.URL.'profile/setari-'.$_SESSION['userid'].'/5" class="nav-link active"><i class="fa fa-gear"></i> Setari cont</a></li>';
							else echo'<li class="nav-item"><a href="'.URL.'profile/setari-'.$_SESSION['userid'].'/5" class="nav-link"><i class="fa fa-gear"></i> Setari cont</a></li>';
							if($active == 4) echo'<li class="nav-item"><a href="'.URL.'profile/favorites-'.$user_id.'/4" class="nav-link active"><i class="fa fa-heart"></i> Resurse favorite</a></li>';
							else echo'<li class="nav-item"><a href="'.URL.'profile/favorites-'.$user_id.'/4" class="nav-link"><i class="fa fa-heart"></i> Resurse favorite</a></li>';
							echo'<li class="nav-item"><a href="'.URL.'logout.php" class="nav-link"><i class="fa fa-sign-out"></i> Deconectare cont</a></li>';
						}
					}
				  }
				  else
				  {
					  if(isset($_SESSION['user']))
					  {
						$userid = $_SESSION['userid'];
						$userid = $db->prepare("SELECT * FROM `members` WHERE `id` = '$userid'");
						$userid->execute();
						$row = $userid->fetch();
						$user_id = $row['id']; 
						$user = $row['name']; 
						
						if($active == 0) echo'<li class="nav-item"><a href="'.URL.'profile/'.$user_id.'-'.$user.'" class="nav-link active"><i class="fa fa-user"></i> Profil</a></li>';
						else echo'<li class="nav-item"><a href="'.URL.'profile/'.$user_id.'-'.$user.'" class="nav-link"><i class="fa fa-user"></i> Profil</a></li>';
							
						if($active == 1) echo'<li class="nav-item"><a href="'.URL.'profile/tutorials-'.$user_id.'/1" class="nav-link active"><i class="fa fa-copy"></i> Tutoriale postate</a></li>';
						else echo'<li class="nav-item"><a href="'.URL.'profile/tutorials-'.$user_id.'/1" class="nav-link"><i class="fa fa-copy"></i> Tutoriale postate</a></li>';
						
						if($active == 2) echo'<li class="nav-item"><a href="'.URL.'profile/resurse-'.$user_id.'/2" class="nav-link active"><i class="fa fa-list"></i> Resurse postate</a></li>';
						else echo'<li class="nav-item"><a href="'.URL.'profile/resurse-'.$user_id.'/2" class="nav-link"><i class="fa fa-list"></i> Resurse postate</a></li>';
							
						if($active == 3) echo'<li class="nav-item"><a href="'.URL.'profile/comments-'.$user_id.'/3" class="nav-link active"><i class="fa fa-commenting"></i> Comentarii recente</a></li>';
						else echo'<li class="nav-item"><a href="'.URL.'profile/comments-'.$user_id.'/3" class="nav-link"><i class="fa fa-commenting"></i> Comentarii recente</a></li>';
						
						if($active == 5)  echo'<li class="nav-item"><a href="'.URL.'profile/setari-'.$_SESSION['userid'].'/5" class="nav-link active"><i class="fa fa-gear"></i> Setari cont</a></li>';
						else echo'<li class="nav-item"><a href="'.URL.'profile/setari-'.$_SESSION['userid'].'/5" class="nav-link"><i class="fa fa-gear"></i> Setari cont</a></li>';
						
						if($active == 4) echo'<li class="nav-item"><a href="'.URL.'profile/favorites-'.$user_id.'/4" class="nav-link active"><i class="fa fa-heart"></i> Resurse favorite</a></li>';
						else echo'<li class="nav-item"><a href="'.URL.'profile/favorites-'.$user_id.'/4" class="nav-link"><i class="fa fa-heart"></i> Resurse favorite</a></li>';
						
						echo'<li class="nav-item"><a href="'.URL.'logout.php" class="nav-link"><i class="fa fa-sign-out"></i> Deconectare cont</a></li>';
					  }
					 }
                 echo' </ul>
                </div>
              </div>
            </div>';
echo'<div class="col-md-9"><div class="well">
                <div id="text-page">';
if($page=="profile")
{
	/***********************************/
	if(isset($_GET['userid']) && isset($_GET['delete']))
	{
		if($_SESSION['admin']!=0)
		{
			$escaped_id = $_GET['userid'];
			$update = $db->prepare("DELETE FROM members where id='$escaped_id'");
			$update->execute();
			
			echo ' Utilizator sters';
		}
	}
	if(isset($_GET['userid']) && !isset($_GET['delete']))
	{
		$user = $_GET['userid'];
		
		$profile = $db->prepare('SELECT * FROM `members` WHERE `id` = :id');
		$profile->execute(array('id' => $user));
		
		foreach ($profile as $row) 
		{
			$nickname = $row['name'];
			$email = $row['email'];
			$avatar = $row['avatar'];
			$rang = $row['rang'];
			/****************************************/	
			$com = $db->prepare("SELECT * FROM `comments` WHERE `user` = '$nickname'");
			$com->execute();
			$comments = $com->rowCount();
			/****************************************/
			$tut = $db->prepare("SELECT * FROM `tutorials` WHERE `user` = '$nickname' AND `approved` = 1");
			$tut->execute();
			$tutorials = $tut->rowCount();
			/****************************************/	
			$location = $row['location'];
			$web = $row['website'];
			$sex = $row['sex'];
			if($sex == 1) $sex_name = 'Masculin';
			else if($sex == 2) $sex_name = 'Feminin';
			$occupation = $row['occupation'];
			$skills = $row['skills'];
			/****************************************/	
			/*$feed = $db->prepare('SELECT * FROM `feed` WHERE `nickname` = :nickname ORDER BY `id` DESC LIMIT 20');
			$feed->execute(array('nickname' => $nickname));*/
			/****************************************/	
			$medals = $db->prepare("SELECT * FROM `playermedals` WHERE `name` = '$nickname' ORDER BY `id` DESC LIMIT 20");
			$medals->execute();
			/****************************************/	
			if($rang == 0) { $rangname = '<b><span class="label label-red">Fondator</span></b>';  }
			else if($rang == 1) { $rangname = '<b><span class="label label-orange">Administrator</span></b>'; }
			else if($rang == 2) { $rangname = '<b><span class="label label-blue">Editor</span></b>';}
			else if($rang == 3) { $rangname = 'Utilizator'; }
			else if($rang == 4) { $rangname = '<b><span class="label label-black">Banat</span></b>';}
			/****************************************/
			echo"<title>Profil $nickname - ".WEBNAME."</title>";
			/*****************************************/
			if(isset($_SESSION['user']))
				if($_SESSION['admin'] != 0)
				echo"<div style='float:right;'><a href='".URL."admin/deleteuser/$user'><img src='".URL."images/delete.gif'> Remove user</a></div>";
			echo'<div class="row"><div class="col-sm-2"><img src="'.URL.'uploads/avatars/'.$avatar.'" style="border:solid #ccc 1px;"></div>';
			echo'<div class="col-sm-10"><b>Email: </b>'.$email.'<br>
			<b>Nume: </b>'.$nickname.'<br>
			<b>Rang: </b>'.$rangname.'<br>
			<b>Comentarii: </b>'.$comments.'<br>
			<b>Tutoriale: </b>'.$tutorials.'<br>';
			if($web != NULL)
				if(strchr($web, 'http://'))
					echo'<b>Website: </b><a href="'.$web.'" target="_blank">'.$web.'</a>';
				else
					echo'<b>Website: </b><a href="http://'.$web.'" target="_blank">'.$web.'</a>';
			echo'</div>';
			echo'</div><hr>';
			echo'<strong>Medalii: '.$medals->rowCount().'<br>';
			if($medals->rowCount() != 0)
			{
				foreach ($medals as $row) 
				{
					$id = $row['id'];
					$name = $row['name'];
					$userid = $row['userid'];
					$medalid = $row['medalid'];
					/*********************************/
					$searchmedals = $db->prepare("SELECT * FROM `medals` WHERE `id` = '$medalid'");
					$searchmedals->execute();
					/*********************************/
					foreach($searchmedals as $mrow)
					{
						$medal_id = $mrow['id'];
						$medal_name = $mrow['medalname'];
						$photo = $mrow['photo'];
					}
					echo"<a href='".URL."medals'><img src='".URL."uploads/medals/$photo'></a><div style='line-height:40%;'><br></div>";
					
				}
			}
			echo'</strong>';
			
		}
	}
	else if(!isset($_GET['userid']) && !isset($_GET['delete']) && isset($_SESSION['user']))
	{
		$user = $_SESSION['userid'];
		
		$profile = $db->prepare('SELECT * FROM `members` WHERE `id` = :id');
		$profile->execute(array('id' => $user));
		
		foreach ($profile as $row) 
		{
			$nickname = $row['name'];
			$email = $row['email'];
			$avatar = $row['avatar'];
			$rang = $row['rang'];
			$comments = $row['comments'];
			$tutorials = $row['tutorials'];
			$location = $row['location'];
			$web = $row['website'];
			$sex = $row['sex'];
			if($sex == 1) $sex_name = 'Masculin';
			else if($sex == 2) $sex_name = 'Feminin';
			$occupation = $row['occupation'];
			$skills = $row['skills'];
			/****************************************/	
			/*$feed = $db->prepare('SELECT * FROM `feed` WHERE `nickname` = :nickname ORDER BY `id` DESC LIMIT 20');
			$feed->execute(array('nickname' => $nickname));*/
			/****************************************/	
			$medals = $db->prepare("SELECT * FROM `playermedals` WHERE `name` = '$nickname' ORDER BY `id`");
			$medals->execute();
			/****************************************/	
			if($rang == 0) { $rangname = '<b><span class="label label-red">Fondator</span></b>';  }
			else if($rang == 1) { $rangname = '<b><span class="label label-orange">Administrator</span></b>'; }
			else if($rang == 2) { $rangname = '<b><span class="label label-blue">Editor</span></b>';}
			else if($rang == 3) { $rangname = 'Utilizator'; }
			else if($rang == 4) { $rangname = '<b><span class="label label-black">Banat</span></b>';}
			/****************************************/
			echo"<title>Profil $nickname - ".WEBNAME."</title>";
			/*****************************************/
			if($_SESSION['admin'] != 0)
				echo"<div style='float:right;'><a href='profile.php?page=profile&userid=$user&delete=true'><img src='".URL."images/delete.gif'> Remove user</a></div>";
			echo'<div class="row"><div class="col-sm-2"><img src="'.URL.'uploads/avatars/'.$avatar.'" style="border:solid #ccc 1px;"></div>';
			echo'<div class="col-sm-10"><b>Email: </b>'.$email.'<br>
			<b>Nume: </b>'.$nickname.'<br>
			<b>Rang: </b>'.$rangname.'<br>
			<b>Comentarii: </b>'.$comments.'<br>
			<b>Tutoriale: </b>'.$tutorials.'</div></div>';
		}
	}
	
}
elseif($page=="tutorials")
{
	if(isset($_GET['userid']))
	{
		$id = $_GET['userid'];
		$tutorials = $db->prepare("SELECT * FROM `tutorials` WHERE `userid` = '$id' AND `approved` = 1");
		$tutorials->execute();
		$name = $db->prepare("SELECT * FROM `members` WHERE `id` = '$id'");
		$name->execute();
		$row = $name->fetch();
		$name = $row['name']; 
		echo"<title>Tutorialele lui $name - ".WEBNAME."</title>";
		echo'<h3 class="h4 panel-title">Tutorialele lui '.$name.'</h3>';
		if($tutorials->rowCount() != 0)
		{
			echo'<div id="customer-orders">
					<div class="table-responsive">
					<table class="table table-hover">
                    <thead>
                    <tr>
                    <th>ID</th>
                    <th>Titlu</th>
                    <th>Categorie</th>
                    <th>Data</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';
			foreach($tutorials as $row)
			{
				$id = $row['id'];
				$titlu = $row['title'];
				$categ = $row['categ'];
				$data = $row['data'];
				$title = curata_url($titlu);
				$tutorialscateg = $db->prepare("SELECT * FROM `tutorial_category` WHERE `notation` = '$categ'");
				$tutorialscateg->execute();
				$row = $tutorialscateg->fetch();
				$categorie = $row['category']; 
				echo'<tr>
							<th># '.$id.'</th>
							<td><a href="'.URL.'tutoriale/'.$id.'/'.$title.'">'.$titlu.'</a></td>
							<td>'.$categorie.'</td>
							<td>'.$data.'</td>';
						   echo'<td><a href="'.URL.'tutoriale/'.$id.'/'.$title.'" class="btn btn-template-outlined btn-sm">Vizualizare</a></td>
						  </tr>';
			}
			echo"</tbody>
                  </table>
                </div></div></div></div>";
		}
		else echo'<div role="alert" class="alert alert-info">Acest user nu a postat nici un tutorial!</div>';
	}
}
elseif($page=="favorites")
{
	if(isset($_GET['deletefav']) && !isset($_GET['userid']))
	{
		$escaped_id = $_GET['deletefav'];
		$update = $db->prepare("DELETE FROM shop_favorites where id='$escaped_id'");
		$update->execute();
		echo("<script>location.href = '".URL."profile';</script>");
	}
	if(isset($_GET['userid']) && !isset($_GET['deletefav']))
	{
		$id = $_GET['userid'];
		$favs = $db->prepare("SELECT * FROM `shop_favorites` WHERE `userid` = '$id'");
		$favs->execute();
		$name = $db->prepare("SELECT * FROM `members` WHERE `id` = '$id'");
		$name->execute();
		$row = $name->fetch();
		$name = $row['name']; 
		echo'<h3 class="h4 panel-title">Lista produselor favorite</h3>';
		echo"<title>Lista favorite $name - ".WEBNAME."</title>";
		if($favs->rowCount() != 0)
		{
			echo'<div id="customer-orders">
					<div class="table-responsive">
					<table class="table table-hover">
                    <thead>
                     <tr>
                    <th>ID</th>
                    <th>Titlu</th>
                    <th>Postat de</th>
                    <th>Status</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';
			foreach($favs as $row)
			{
				$id_fav = $row['id'];
				$item = $row['item'];
				$sql2 = $db->prepare("SELECT * FROM shops WHERE id = '$item' AND `activation` = 1");
				$sql2->execute();
				foreach($sql2 as $row)
				{
					$id = $row['id'];
						$title = $row['title'];
						$name = $row['name'];
						$userid = $row['userid'];
						$description = $row['description'];
						$price = $row['price'];
						$photo = $row['photo'];
						$option = $row['priceoption'];
						$promoted = $row['promoted'];
						$verified = $row['verified'];
						$titlu = curata_url($row['title']);
						$category = $row['category'];
						$short_desc = $row['short_desc'];
						$video = $row['video'];
						$demo = $row['demo_link'];
						echo'<tr>
                        <th># '.$id.'</th>
                        <td><a href="'.URL.'shop/'.$id.'-'.$titlu.'">'.$title.'</a></td>
                        <td><a href="'.URL.'profile/'.$userid.'-'.$name.'">'.$name.'</a></td>';
						if($promoted == 1) echo'<td><span class="badge badge-danger">Promovat</span></td>';
						else if($verified == 1) echo'<td><span class="badge badge-info">Verificat</span></td>';
						else echo'<td><span class="badge badge-success">Normal</span></td>';
                       echo'<td><a href="'.URL.'shop/'.$id.'-'.$titlu.'" class="btn btn-template-outlined btn-sm">Vizualizare</a> <a href="'.URL.'profile/deletefav-'.$id_fav.'" class="btn btn-sm btn-danger">Sterge</a></td>
                      </tr>';
				}
			}
			echo"</tbody>
                  </table>
                </div></div></div></div>";
		}
		else echo'<div role="alert" class="alert alert-info">Acest user nu are produse favorite!</div>';
	}
}
elseif($page=="comments")
{
	if(isset($_GET['userid']))
	{
		$id = $_GET['userid'];
		$comments = $db->prepare("SELECT * FROM `comments` WHERE `userid` = '$id' ORDER BY `id` DESC");
		$comments->execute();
		$name = $db->prepare("SELECT * FROM `members` WHERE `id` = '$id'");
		$name->execute();
		$row = $name->fetch();
		$name = $row['name']; 
		$avatar1 = $row['avatar'];
		echo"<title>Comentariile lui $name - ".WEBNAME."</title>";
		if($comments->rowCount() != 0)
		{
			foreach($comments as $row)
			{
				$ids = $row['id'];
				$comment = $row['comment'];
				$sectionid = $row['sectionid'];
				$categ = $row['categ'];
				$data = $row['data'];
				$sectionname = $row['sectionname'];
				
				echo'<div class="row comment">';
				echo'<div class="col-sm-3 col-md-2 text-center-xs">';
				if($avatar1 == "default.png" or $avatar1 == "") echo "<p><img src='".URL."uploads/images/default_avatar.png' class='img-fluid rounded-circle' alt='' /></p>";
				else echo "<p><img src='".URL."uploads/avatars/mici/$avatar1' class='img-fluid rounded-circle' alt='' /></p>";
				
				echo'</div>';
				
				echo'<div class="col-sm-9 col-md-10">';
echo'<div class="text-uppercase" style="font-size: 16px;font-weight: bold;">'.$name.'</div>';	
				if($sectionname == 'blog')
				{$searchcomm = $db->prepare("SELECT * FROM `stiri` WHERE `id` = '$sectionid'");
				$searchcomm->execute();}
				else
				{
					$searchcomm = $db->prepare("SELECT * FROM `$sectionname` WHERE `id` = '$sectionid'");
					$searchcomm->execute();
				}
				$row = $searchcomm->fetch();
				if($sectionname == 'blog') $titlu = curata_url($row['titlu']); 
				else $titlu = curata_url($row['title']); 
				if($sectionname == 'forum') $sectionname = 'discutii';
				else if($sectionname == 'tutorials') $sectionname = 'tutoriale';
				echo'<div style="float:right;"><a href="'.URL.''.$sectionname.'/'.$row['id'].'/'.$titlu.'" class="btn btn-sm btn-info">Vezi pagina</a></div>';
                    echo'<p class="posted" style="font-size: 13px;"><i class="fa fa-clock-o"></i> '.$data.'</p>';
                    echo'<p style="font-size: 14px;">'.$comment.'</p>';
					if(isset($_SESSION['user'])) 
				{ 
					if($_SESSION['admin'] > 0 || $_SESSION['editor'] > 0)
					{
					echo'<div style="float:right;"><a href="'.URL.'comentator.php?page=delete&rid='.$ids.'&sectionid='.$sectionid.'&type='.$categ.'&userid='.$id.'&delete=true"><img src="'.URL.'images/newdel.gif"></img></a></div>';
					}
				}
echo'</div></div><hr>';

			}

		}
		else echo'<div role="alert" class="alert alert-info">Acest user nu a postat nici o resursa!</div>';
	}
}
elseif($page=="resurse")
{
	if(isset($_GET['userid']))
	{
		$id = $_GET['userid'];
		$resurse = $db->prepare("SELECT * FROM `shops` WHERE `userid` = '$id' AND `activation` = 1");
		$resurse->execute();
		$name = $db->prepare("SELECT * FROM `members` WHERE `id` = '$id'");
		$name->execute();
		$row = $name->fetch();
		$name = $row['name']; 
		echo"<title>Resursele lui $name - ".WEBNAME."</title>";
		echo'<h3 class="h4 panel-title">Resursele lui '.$name.'</h3>';
		if($resurse->rowCount() != 0)
		{
			echo'<div id="customer-orders">
					<div class="table-responsive">
					<table class="table table-hover">
                    <thead>
                    <tr>
                    <th>ID</th>
                    <th>Titlu</th>
                    <th>Categorie</th>
                    <th>Data</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';
			foreach($resurse as $row)
			{
				$id = $row['id'];
				$titlu = $row['title'];
				$categ = $row['category'];
				$data = $row['data'];
				$title = curata_url($titlu);
				$rescateg = $db->prepare("SELECT * FROM `shop_category` WHERE `notation` = '$categ'");
				$rescateg->execute();
				$row = $rescateg->fetch();
				$categorie = $row['category']; 
				echo'<tr>
							<th># '.$id.'</th>
							<td><a href="'.URL.'shop/'.$id.'-'.$title.'">'.$titlu.'</a></td>
							<td>'.$categorie.'</td>
							<td>'.$data.'</td>';
						   echo'<td><a href="'.URL.'shop/'.$id.'-'.$title.'" class="btn btn-template-outlined btn-sm">Vizualizare</a></td>
						  </tr>';
			}
		}
		else echo'<div role="alert" class="alert alert-info">Acest user nu a postat nici un tutorial!</div>';
		echo"</tbody>
                  </table>
                </div></div></div></div>";
	}
}
elseif($page=="setari")
{
	if(isset($_GET['userid']))
	{
		if($_GET['userid'] == $_SESSION['userid'])
		{
			$id = $_GET['userid'];
			$username = $_SESSION['user'];
			$name = $db->prepare("SELECT * FROM `members` WHERE `id` = '$id'");
			$name->execute();
			echo"<title>Setari cont - ".WEBNAME."</title>";
			foreach($name as $row)
			{
				/****************************************/
				/* 				PAROLA                  */
				/****************************************/
				if (isset($_POST['changepass']))
				{
					$changepass = $db->prepare("SELECT * FROM `members` WHERE `name` = '$username'");
					$changepass->execute();
					/****************************************************/
					foreach($changepass as $row)
					{
						$oldpass = md5($row['password']);
						$userid = $row['id'];
						$parola_noua = md5($_POST['new1']);
						/*************************************/
						if($_POST['old'] == "" or $_POST['new1'] == "" or $_POST['new2'] == "")
							echo "<center>Completeaz&#259; spa&#355;iile de mai sus!</center>";
						elseif($_POST['old'] != $oldpass)
							echo "<center>Parola veche nu corespunde cu cea scris&#259;!</center>";
						elseif($_POST['new1'] != $_POST['new2'])
							echo "<center>Parola nou&#259; nu este aceea&#351;i cu cea de a doua!</center>";
						else
						{
							$changepass = $db->prepare("UPDATE `members` SET `password` = '$parola_noua' WHERE `id` = $id");
							$changepass->execute();
							echo'<div role="alert" class="alert alert-success alert-dismissible">
							<button type="button" data-dismiss="alert" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Parola schimbata cu succes
							</div>';
						}
					}
				}
				/****************************************/
				/* 				EMAIL                  */
				/****************************************/
				if (isset($_POST['changeemail']))
				{
					$problem = 0;
					$email_nou = htmlspecialchars($_POST['newemail'], ENT_QUOTES, "UTF-8");
					/*********************/
					$sql = $db->prepare("SELECT * FROM members WHERE email = :email");
					$sql->execute(array('email' => $email_nou));
				
					if($sql->rowCount() != 0) 
					{
						$problem=1;
						echo'<div role="alert" class="alert alert-danger">Exista deja un utilizator cu acest nume sau cu aceasta adresa de email! </div>';
					}
					/*********************/
					if (!filter_var($email_nou, FILTER_VALIDATE_EMAIL)) 
					{
						echo'<div role="alert" class="alert alert-danger">Email-ul introdus nu este corect! </div>';
						$problem = 1;
					}
					/*********************/
					if($problem == 0)
					{
						$changeemail = $db->prepare("UPDATE `members` SET `email` = '$email_nou' WHERE `id` = $id");
						$changeemail->execute();
						echo'<div role="alert" class="alert alert-success alert-dismissible">
						<button type="button" data-dismiss="alert" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Email schimbat cu succes!
					    </div>';
					}
				}
				/****************************************/
				/* 				OCUPATII                */
				/****************************************/
				if (isset($_POST['setocc']))
				{
					$new_occ = $_POST['new_occ'];
					$newocc = $db->prepare("UPDATE `members` SET `occupation` = '$new_occ' WHERE `id` = $id");
					$newocc->execute();
					if($newocc){
					echo'<div role="alert" class="alert alert-success alert-dismissible">
						<button type="button" data-dismiss="alert" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Ocupa&#355;ie schimbat&#259;!
					    </div>';}
				}
				/****************************************/
				/* 				APTITUDINI              */
				/****************************************/
				if (isset($_POST['setskill']))
				{
					$new_skills = $_POST['new_skills'];
					$newocc = $db->prepare("UPDATE `members` SET `skills` = '$new_skills' WHERE `id` = $id");
					$newocc->execute();
					if($newocc){
					echo'<div role="alert" class="alert alert-success alert-dismissible">
						<button type="button" data-dismiss="alert" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Aptitudine schimbat&#259;!
					    </div>';}
				}
				/****************************************/
				/* 				WEBSITE              	*/
				/****************************************/
				if (isset($_POST['setweb']))
				{
					$newsite = $_POST['newsite'];
					$newocc = $db->prepare("UPDATE `members` SET `website` = '$newsite' WHERE `id` = $id");
					$newocc->execute();
					if($newocc)
					{
						echo'<div role="alert" class="alert alert-success alert-dismissible">
						<button type="button" data-dismiss="alert" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Website schimbat!
					</div>';
					}
				}
				/****************************************/
				/* 				WEBSITE              	*/
				/****************************************/
				if (isset($_POST['avatarch']))
				{
					$av_path = "uploads/avatars/";
					$av_path2 = "uploads/avatars/mici/";
					/***********************************************/
					$temp_fisier = $_FILES['file']['tmp_name']; //temp
					$nume_fisier = $_FILES['file']['name']; // nume
					$marime_fisier = $_FILES['file']['size']; // marime
					$tip_fisier = $_FILES['file']['type']; // tip
					/***********************************************/
					$nume_random = rand(0,999999999); // nume random
					/***********************************************/
					if (($tip_fisier == "image/gif") || ($tip_fisier == "image/jpeg") ||  ($tip_fisier == "image/jpg") || ($tip_fisier == "image/pjpeg") || ($tip_fisier == "image/png") || ($tip_fisier == "image/x-png") && ($marime_fisier < 1))
					{
						list($width, $height) = getimagesize($temp_fisier);
						if($width>200 || $height>200)
						{
							echo'<div role="alert" class="alert alert-danger">Dimensiunile imaginii sunt prea mari! Max: (200px x 200px)</div>';
							global $badsize;
							$badsize = "1";
						}
						elseif($width<= 200 || $height<=200)
						{ 
							$_SESSION['badsize'] = false;
							echo "<br><p>";
							echo "</p>";
							if (file_exists("$av_path$nume_fisier")) echo "$nume_fisier <p style='color: red'>already exists. </font>";
								
							$ext = strrchr($nume_fisier,'.');
							$ext = strtolower($ext);

							if($tip_fisier == "image/pjpeg" || $tip_fisier == "image/jpeg"){
							$imagine_noua = imagecreatefromjpeg($_FILES['file']['tmp_name']);
							}elseif($tip_fisier == "image/x-png" || $tip_fisier == "image/png"){
							$imagine_noua = imagecreatefrompng($_FILES['file']['tmp_name']);
							}elseif($tip_fisier == "image/gif"){
							$imagine_noua = imagecreatefromgif($_FILES['file']['tmp_name']);
							}
							if ($imagine_noua === false) { die ('Nu pot deschide imaginea'); }
							
							list($width, $height) = getimagesize($temp_fisier);

							$new_width1 = "100"; 	$new_height1 = "100";
							$new_width2 = "80"; 	$new_height2 = "80";
							
							if($width > 100 && $width <= 200 or $height > 100 and $height <= 200)
							{
								$imagine_mod1 = imagecreatetruecolor($new_width1, $new_height1); 
								imagecopyresampled($imagine_mod1, $imagine_noua, 0, 0, 0, 0, $new_width1, $new_height1, $width, $height);
								Imagejpeg($imagine_mod1, "$av_path$nume_random$ext");

							}
							else{move_uploaded_file($temp_fisier, "uploads/avatars/$nume_random$ext");}
							$imagine_mod2 = imagecreatetruecolor($new_width2, $new_height2); // Imaginea modificata2

							imagecopyresampled($imagine_mod2, $imagine_noua, 0, 0, 0, 0, $new_width2, $new_height2, $width, $height);
							Imagejpeg($imagine_mod2, "$av_path2$nume_random$ext");

							$link_image = "$nume_random$ext";
							if(isset($imagine_mod1)){ImageDestroy ($imagine_mod1);}
							ImageDestroy ($imagine_mod2);

							$enter_link = "$nume_random$ext";
							/***************************************/
							$avatar = $db->prepare('update members set `avatar` = :avatar WHERE `name` = :name');
							$avatar->execute(array('avatar' => $enter_link,'name' => $username));
							/***************************************/

						}
					}
				}
				$name = $row['name'];
				echo'<h3 class="h4 panel-title">Setarile contului '.$name.'</h3>';
					echo'<div class="row">
					<div class="col-md-6">
					  <nav id="myTab" role="tablist" class="nav nav-tabs"><a id="tab4-1-tab" data-toggle="tab" href="#tab4-1" role="tab" aria-controls="tab4-1" aria-selected="true" class="nav-item nav-link active show"> <i class="icon-star"></i>Parola</a><a id="tab4-2-tab" data-toggle="tab" href="#tab4-2" role="tab" aria-controls="tab4-2" aria-selected="false" class="nav-item nav-link">Avatar</a><a id="tab4-3-tab" data-toggle="tab" href="#tab4-3" role="tab" aria-controls="tab4-3" aria-selected="false" class="nav-item nav-link">Email</a><a id="tab4-4-tab" data-toggle="tab" href="#tab4-4" role="tab" aria-controls="tab4-4" aria-selected="false" class="nav-item nav-link">Ocupatii</a></nav>
					  <div id="nav-tabContent" class="tab-content">
						<div id="tab4-1" role="tabpanel" aria-labelledby="tab4-1-tab" class="tab-pane fade active show">
							<form action="" method="post"><center>
							<strong>Parola veche:</strong><br>
							<input class="form-control" type="password" name="old"><br>
							<strong>Parola Nou&#259;:</strong><br>
							<input class="form-control" type="password" name="new1"><br>
							<strong>Repetare parola nou&#259;:</strong><br>
							<input class="form-control" type="password" name="new2"><br><br>
							<button class="btn btn-sm btn-danger" type="submit" name="changepass">Schimb&#259;</button></center>
							</form>
						</div>
						<div id="tab4-2" role="tabpanel" aria-labelledby="tab4-2-tab" class="tab-pane fade">
							<form action="" method="post" enctype="multipart/form-data">
							<strong>Selecteaza o imagine</strong>
							<input class="form-control" type="file" name="file" id="file"><hr>
							<center><button class="btn btn-sm btn-danger" type="submit" name="avatarch">Incarc&#259; imaginea</button></center>
							</form>
						</div>
						<div id="tab4-3" role="tabpanel" aria-labelledby="tab4-3-tab" class="tab-pane fade">
							<center><form action="" method="post">
							<strong>Email:</strong>
							<input class="form-control" value="'.$row['email'].'" type="inputtext" name="newemail"><br>
							<br><button class="btn btn-sm btn-danger" type="submit" name="changeemail">Schimb&#259;</button>
							</form></center>
						</div>
						<div id="tab4-4" role="tabpanel" aria-labelledby="tab4-4-tab" class="tab-pane fade">
							<center>
							<form action="" method="post">
							<strong>Ocupa&#355;ii:</strong>
							<input class="form-control" value="'.$row['occupation'].'" type="inputtext" name="new_occ"><br>
							<br><button class="btn btn-sm btn-danger" type="submit" name="setocc">Schimb&#259;</button>
							</form></center>
						</div>
					  </div>
					</div>
					<div class="col-md-6">
					  <nav id="myTab" role="tablist" class="nav nav-tabs"><a id="tab4-5-tab" data-toggle="tab" href="#tab4-5" role="tab" aria-controls="tab4-5" aria-selected="false" class="nav-item nav-link active show"> <i class="icon-star"></i>Aptitudini</a><a id="tab4-6-tab" data-toggle="tab" href="#tab4-6" role="tab" aria-controls="tab4-6" aria-selected="false" class="nav-item nav-link">Website personal</a><a id="tab4-7-tab" data-toggle="tab" href="#tab4-7" role="tab" aria-controls="tab4-7" aria-selected="true" class="nav-item nav-link">Locatia</a></nav>
					  <div id="nav-tabContent" class="tab-content">
						<div id="tab4-5" role="tabpanel" aria-labelledby="tab4-5-tab" class="tab-pane fade active show">
							<center>
							<form action="" method="post">
							<strong>Aptitudini:</strong>
							<input class="form-control" value="'.$row['skills'].'" type="inputtext" name="new_skills"><br>
							<br><button class="btn btn-sm btn-danger" type="submit" name="setskill">Schimb&#259;</button>
							</form></center>
						</div>
						<div id="tab4-6" role="tabpanel" aria-labelledby="tab4-6-tab" class="tab-pane fade">
							<center>
							<form action="" method="post">
							<striong>Website:</strong>
							<input class="form-control" value="'.$row['website'].'" type="inputtext" name="newsite"><br>
							<br><button class="btn btn-sm btn-danger" type="submit" name="setweb">Schimb&#259;</button>
							</form></center>
						</div>
						<div id="tab4-7" role="tabpanel" aria-labelledby="tab4-7-tab" class="tab-pane fade">
							<center>
							<form action="" method="post">
							<strong>Loca&#355;ie nou&#259;:</strong>
							<input class="form-control" value="'.$row['location'].'" type="inputtext" name="new_loc"><br>
							<br><button class="btn btn-sm btn-danger" type="submit" name="newlocation">Schimb&#259;</button>
							</form></center>
						</div>
					  </div>
					</div>
				  </div>';
			}
		}
	}
}
echo'</div></div></div></div></div>';
/*********************************/
Footer();
echo"</div></div></div>";
AnotherScripts();
echo"</body>";