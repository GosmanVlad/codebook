<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
include('includes/online_visitors.php');
/***************************************/
if(!isset($_SESSION['user'])) { header('Location: '.URL.''); }
if($_SESSION['admin']<1) { header('Location: '.URL.''); }
$username = $_SESSION['user'];
if(isset($_GET["page"])) $page=$_GET["page"];
else $page=$_GET["page"] = NULL;
/***************************************/
echo "<title>Admin Control Panel - ".WEBNAME."</title>";
Website_Header();
echo'<body><div id="all">';
include('modules/menu.php');
echo '<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Salut, '.$username.'</h1>
			  <p style="font-size:16px;margin-bottom: 10px;font-weight: 300;color:#DBDBDB;">Acesta este panoul tau de administrare. De aici, tu poti edita setarile comunitatii,
			  poti manageria userii, medalii, tutoriale si multe alte lucruri.</p>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="'.URL.'">Home</a></li>
                <li class="breadcrumb-item active">ACP</li>
              </ul>
            </div>
          </div>
        </div>
      </div>';
/*********************************/
$mailbox = $db->prepare('SELECT * FROM `acp_email` WHERE `readbyadmin` = 0');
$mailbox->execute();
if($mailbox->rowCount() != 0) $mail = '<b><font color=red>'.$mailbox->rowCount().'</font></b>';
else $mail = "".$mailbox->rowCount()."";
/*********************************/
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar">';		  
	echo'<div class="col-md-3"><div class="well">';
	echo'<div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Navigare</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">
					<li class="nav-item"><a href="'.URL.'admin" class="nav-link"><i class="fa fa-angle-double-right" style="color: #58666e;"></i> Admin Control Panel</a></li>';
					echo'<li class="nav-item"><a href="'.URL.'admin/members" class="nav-link"><i class="fa fa-angle-double-right" style="color: #58666e;"></i> Members </a></li>';
					echo'<li class="nav-item"><a href="'.URL.'admin/medals" class="nav-link"><i class="fa fa-angle-double-right" style="color: #58666e;"></i> Medals </a></li>';
					echo'<li class="nav-item"><a href="'.URL.'admin/mailbox" class="nav-link"><i class="fa fa-angle-double-right" style="color: #58666e;"></i> Mailbox ('.$mail.')</a></li>';
					echo'<li class="nav-item"><a href="'.URL.'admin/tutoriale" class="nav-link"><i class="fa fa-angle-double-right" style="color: #58666e;"></i> Administrare tutoriale </a></li>';
					echo'<li class="nav-item"><a href="'.URL.'admin/resurse" class="nav-link"><i class="fa fa-angle-double-right" style="color: #58666e;"></i> Administrare resurse </a></li>';
                  echo'</ul>
                </div>
              </div>';
	echo'</div></div>';
if(!$page)
{
	echo'<div class="col-lg-9">';
		if(isset($_SESSION['user']) && $_SESSION['admin'] > 0)
		{
			$users = $db->prepare("SELECT * FROM members");
			$users->execute();
			/***********************************************/
			$tuts = $db->prepare("SELECT * FROM tutorials WHERE approved = 1");
			$tuts->execute();
			/***********************************************/
			$tuts2 = $db->prepare("SELECT * FROM tutorials WHERE approved = 0");
			$tuts2->execute();
			/***********************************************/
			$comms = $db->prepare("SELECT * FROM comments");
			$comms->execute();
			/***********************************************/
			$files = $db->prepare("SELECT * FROM shops WHERE activation = 1");
			$files->execute();
			/***********************************************/
			$files2 = $db->prepare("SELECT * FROM shops WHERE activation = 0");
			$files2->execute();
			/***********************************************/
			$articole = $db->prepare("SELECT * FROM stiri");
			$articole->execute();
			echo'<div class="row d-flex align-items-stretch">
                <div class="col-md-4">
                  <div class="box-simple box-white same-height">
                    <div class="icon"><i class="fa fa-user-circle-o" style="color:#6242b8;"></i></div>
                    <h4>'.$users->rowCount().' useri inregistrati</h4>
                  </div>
                </div>
				<div class="col-md-4">
                  <div class="box-simple box-white same-height">
                    <div class="icon"><i class="fa fa-users" style="color:#6242b8;"></i></div>
                    <h4>'.$ONLINE_VISITORS.'</h4>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="box-simple box-white same-height">
                    <div class="icon"><i class="fa fa-wechat" style="color:#6242b8;"></i></div>
                    <h4>'.$comms->rowCount().' comentarii</h4>
                  </div>
                </div>
              </div><hr>';
			  echo'<div class="row d-flex align-items-stretch">
                <div class="col-md-4">
                  <div class="box-simple box-white same-height">
                    <div class="icon"><i class="fa fa-list" style="color:#6242b8;"></i></div>
                    <h4>'.$files->rowCount().' resurse activate<br>
					'.$files2->rowCount().' resurse dezactivate</h4>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="box-simple box-white same-height">
                    <div class="icon"><i class="fa fa-copy" style="color:#6242b8;"></i></div>
                    <h4>'.$tuts->rowCount().' tutoriale activate<br>
					'.$tuts2->rowCount().' tutoriale dezactivate</h4>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="box-simple box-white same-height">
                    <div class="icon"><i class="fa fa-newspaper-o" style="color:#6242b8;"></i></div>
                    <h4>'.$articole->rowCount().' articole </h4>
                  </div>
                </div>
              </div><hr>';
			  echo'<div class="row">
                <div class="col-lg-6">
                  <div id="accordionTwo" role="tablist">
                    <div class="card">
                      <div id="headingFour" role="tab" class="card-header">
                        <h5 class="mb-0"><a data-toggle="collapse" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour" class="">Ultimele resurse</a></h5>
                      </div>
                      <div id="collapseFour" role="tabpanel" aria-labelledby="headingFour" data-parent="#accordionTwo" class="collapse show" style="">
                        <div class="card-body">';
						$files = $db->prepare("SELECT * FROM shops ORDER BY id DESC LIMIT 5");
							$files->execute();
							foreach($files as $row)
							{	
								$id = $row['id'];
								$name = $row['name'];
								$userid = $row['userid'];
								$title = $row['title'];
								$titlu = curata_url($title);
								echo'<div class="custombox">
								<div style="margin-left:3px;"><a href="'.URL.'shop/'.$id.'-'.$titlu.'" class="newz">'.$title.'</a><br>
								<div style="font-size:11px;margin-top:2px;color:gray;">În 
								Postat de <a href="'.URL.'profile/'.$userid.'-'.$name.'">'.$name.'</a> 
								</div></div>
								</div>';
							}
						echo'</div>
                      </div>
                    </div>
                    <div class="card">
                      <div id="headingFive" role="tab" class="card-header">
                        <h5 class="mb-0"><a data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive" class="collapsed">Ultimii useri inregistrati</a></h5>
                      </div>
                      <div id="collapseFive" role="tabpanel" aria-labelledby="headingFive" data-parent="#accordionTwo" class="collapse show">
                        <div class="card-body">';
							$lastusers = $db->prepare("SELECT * FROM members ORDER BY id DESC LIMIT 5");
							$lastusers->execute();
							foreach($lastusers as $row)
							{	
								$id = $row['id'];
								$name = $row['name'];
								$registerdate = $row['joindate'];
								echo'<div class="custombox">
								<div style="margin-left:3px;"><a href="'.URL.'profile/'.$id.'-'.$name.'">'.$id.'. '.$name.'</a><br>
								<div style="font-size:11px;margin-top:2px;color:gray;">
								Inregistrat la data de <strong>'.$registerdate.'</strong> 
								</div></div>
								</div>';
							}
						echo'</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div id="accordionThree" role="tablist">
                    <div class="card">
                      <div id="headingSeven" role="tab" class="card-header">
                        <h5 class="mb-0"><a data-toggle="collapse" href="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">Ultimele tutoriale</a></h5>
                      </div>
                      <div id="collapseSeven" role="tabpanel" aria-labelledby="headingSeven" data-parent="#accordionThree" class="collapse show">
                        <div class="card-body">';
							$tuts = $db->prepare("SELECT * FROM tutorials ORDER BY id DESC LIMIT 5");
							$tuts->execute();
							foreach($tuts as $row)
							{	
								$id = $row['id'];
								$title = $row['title'];
								$titlu = curata_url($title);
								$categ = $row['categ'];
								$user = $row['user'];
								$userid = $row['userid'];
								$categoryes = $db->prepare("SELECT * FROM `tutorial_category` WHERE `notation` = '$categ'");
								$categoryes->execute();
								$row2 = $categoryes->fetch();
								$category_name = $row2['category']; 
								echo'<div class="custombox">
								<div style="margin-left:3px;"><a href="'.URL.'tutorials/'.$id.'-'.$titlu.'" class="newz">'.$title.'</a><br>
								<div style="font-size:11px;margin-top:2px;color:gray;">În 
								<a href="'.URL.'tutorials/search/'.$categ.'" class="newzin">'.$category_name.' ('.$categ.')</a><br>
								Postat de <a href="'.URL.'profile/'.$userid.'-'.$user.'">'.$user.'</a> 
								</div></div>
								</div>';
							}
						echo'</div>
                      </div>
                    </div>
                    <div class="card">
                      <div id="headingEight" role="tab" class="card-header">
                        <h5 class="mb-0"><a data-toggle="collapse" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight" class="collapsed">Ultimele comentarii</a></h5>
                      </div>
                      <div id="collapseEight" role="tabpanel" aria-labelledby="headingEight" data-parent="#accordionThree" class="collapse show">
                        <div class="card-body">';
							$comms = $db->prepare("SELECT * FROM comments ORDER BY id DESC LIMIT 5");
							$comms->execute();
							foreach($comms as $row)
							{	
								$id = $row['id'];
								$comment = $row['comment'];
								$sectionid = $row['sectionid'];
								$sectionname = $row['sectionname'];
								$user = $row['user'];
								$data = $row['data'];
								$ip = $row['ip'];
								$userid = $row['userid'];
								$type = $row['categ'];
								if($sectionname == 'tutoriale')
								{
									$searchitem = $db->prepare("SELECT * FROM `tutorials` WHERE `id` = '$sectionid'");
									$searchitem->execute();
									$row2 = $searchitem->fetch();
									$titlu = $row2['titlu']; 
									$title = curata_url($titlu);
									$categ = 'tutorials';
								}
								else if($sectionname == 'blog')
								{
									$searchitem = $db->prepare("SELECT * FROM `stiri` WHERE `id` = '$sectionid'");
									$searchitem->execute();
									$row2 = $searchitem->fetch();
									$titlu = $row2['titlu']; 
									$title = curata_url($titlu);
									$categ = 'blog';
								}
								echo'<div class="custombox">
								<div style="margin-left:3px;"><div style="float:right;"><a href="'.URL.'comentator.php?page=delete&rid='.$id.'&sectionid='.$sectionid.'&type='.$type.'&userid='.$userid.'&delete=true">Sterge</a></div>
								<a href="'.URL.''.$categ.'/'.$sectionid.'-'.$title.'" class="newz">'.$title.'</a><br>
								<div style="font-size:11px;margin-top:2px;color:gray;">În 
								<a href="'.URL.''.$categ.'" class="newzin">'.$categ.'</a><br>
								Postat de <a href="'.URL.'profile/'.$userid.'-'.$user.'">'.$user.'</a> 
								</div>
								</div>
								</div>';
							}
						echo'</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
		}
echo'</div>';
}
elseif($page=="search2")
{
	if(!isset($_POST['updateuser']) && isset($_GET['id']) && isset($_GET['delete']))
	{
		$id = $_GET['id'];
		
		$delete = $db->prepare("DELETE FROM playermedals where id='$id'");
		$delete->execute();
			
		if($delete) echo 'Medalie stearsa.';
	}
	if(isset($_POST['updateuser']) && !isset($_POST['newuser']))
		{
			$sqlid = $_POST['id'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$email = $_POST['email'];
			$tutorials = $_POST['tutorials'];
			$comments = $_POST['comments'];
			$rang = $_POST['rang'];
			$admin = $_POST['admin'];
			$editor = $_POST['editor'];
			$banned = $_POST['banned'];
			$avatar = $_POST['avatar'];
			$activated = $_POST['activated'];
			$nrlogins = $_POST['nrlogin'];

			$medalid = ucfirst($_POST['medalid']);
			/********************************************/
			$update = $db->prepare("UPDATE members SET `name` = '$username', `password` = '$password', `email` = '$email', `tutorials` = $tutorials, `comments` = $comments, `rang` = $rang, `admin` = $admin,
								   `editor` = $editor, `banned` = $banned, `avatar` = '$avatar', `activated` = '$activated', `nr_login` = '$nrlogins' WHERE `id` = $sqlid");
			$update->execute();
			/*******************************************/
			if($medalid != 0)
			{
				$medals = $db->prepare("insert into playermedals (name, userid, medalid) values ('$username','$sqlid','$medalid')");
				$medals->execute();
			}
			if($update) echo'Utilizator editat';
			/********************************************/
		}
	if(isset($_POST['newuser']) && !isset($_POST['uptadeuser']))
	{
		$name = htmlspecialchars($_POST['username'], ENT_QUOTES, "UTF-8");
		$email = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");
		if(isset($name) && !empty($name))
		{	
			if(empty($_POST['email'])) 
			{
				echo'Toate campurile sunt obligatorii! Completeaza-le cu atentie pe toate!';
				$problem = 1;
			}
			$password = $_POST['password'];
		
			$sql = $db->prepare("SELECT * FROM members WHERE name = '$name' OR email = '$email'");
			$sql->execute();
		
			if($sql->rowCount() != 0) 
			{
				$problem=1;
				echo'Exista deja un utilizator cu acest nume sau cu aceasta adresa de email!';
			}
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
				echo'Email incorect.';
				$problem = 1;
			}
			if($problem == 0)
			{
				$sex = $_POST['register_sex'];
				$time = time();
				
				$update = $db->prepare("INSERT into members (name, email, password, sex) VALUES ('$name', '$email', '$password', '$sex')");
				$update->execute();
					
				$sendpm = $db->prepare("INSERT into pm (name1,name2, timestamp, title, message) VALUES ('".WEBNAME." Team', '$name', '$time', 'Bine ai venit!', 'Salutare <b>$name</b>!<br>Echipa ".WEBNAME." Romania iti ureaza bun venit pe comunitate!<br>In cazul in care ai nevoie de ajutor, nu ezita sa folosesti pagina noastra de contact.')");
				$sendpm->execute();
				
				if($update && $sendpm)
					echo'Inregistrare efectuata.';
				else
					echo'A aparut o eroare! Incearca din nou.';
			}
		}
	}
	if(isset($_GET['id']) && isset($_GET['userid']) && isset($_GET['unban']))
	{
		$userid = $_GET['userid'];
		$escaped_id = $_GET['id'];
		$update = $db->prepare("DELETE FROM banlist where id='$escaped_id'");
		$update->execute();
						
		$update2 = $db->prepare("UPDATE members SET `banned` = 0 WHERE `id` = $userid");
		$update2->execute();
						
		if($update){echo"Utilizator debanat.";}
	}
	if (isset($_POST['add_ban']))
	{
		$username = $_SESSION['user'];
		$name = $_POST['name'];
		$reason = $_POST['reason'];
		$data = time();
		$time = date('Y/m/d H:i:s' ,$data);
		/********************************************/
		$finduserid = $db->prepare("select * from members WHERE `name` = :name");
		$finduserid->execute(array('name'=>$name));
		foreach($finduserid as $torow)
		{
			$userid = $torow['id'];
			$ip = $torow['ip'];
		}
		/********************************************/
		$adminfind = $db->prepare("select * from members WHERE `name` = :name");
		$adminfind->execute(array('name'=>$username));
		foreach($adminfind as $torow)
		{
			$adminid = $torow['id'];
		}
		/********************************************/
		$sql = $db->prepare("insert into banlist (name, userid, bannedby, adminid, reason, data,ip) values ('$name','$userid','$username','$adminid','$reason','$data','$ip')");
		$sql->execute();
		/********************************************/
		$sq2l = $db->prepare("UPDATE members SET `banned` = 1 WHERE `name` = '$name'");
		$sq2l->execute();

		if($sql && $sq2l)
		{
			echo "<h4>Utilizator banat ($name - $time).</h4><hr>";
		}
	}
}
else if($page == 'members')
{
	if(!isset($_SESSION['user'])) echo("<script>location.href = '".URL."autentificare'; alert('Trebuie sa fii autentificat pentru a putea adauga un produs la favorite!');</script>");
	echo'<div class="col-lg-9">';
	echo'<ul id="pills-tab" role="tablist" class="nav nav-pills nav-justified">
                <li class="nav-item"><a id="pills-search-tab" data-toggle="pill" href="#pills-search" role="tab" aria-controls="pills-search" aria-selected="true" class="nav-link">Cauta un user</a></li>
                <li class="nav-item"><a id="pills-new-tab" data-toggle="pill" href="#pills-new" role="tab" aria-controls="pills-new" aria-selected="false" class="nav-link">Creeaza un user</a></li>
                <li class="nav-item"><a id="pills-banlist-tab" data-toggle="pill" href="#pills-banlist" role="tab" aria-controls="pills-banlist" aria-selected="false" class="nav-link">Banlist</a></li>
				<li class="nav-item"><a id="pills-unactive-tab" data-toggle="pill" href="#pills-unactive" role="tab" aria-controls="pills-unactive" aria-selected="false" class="nav-link">Conturi neactivate</a></li>
              </ul>
              <div id="pills-tabContent" class="tab-content">
                <div id="pills-search" role="tabpanel" aria-labelledby="pills-home-tab" class="tab-pane fade show active">';
				echo "<form action='' method='post'>
				<p>Cauta: <input class='form-control' type='text' name='username'></p>
				<input class='btn btn-template-outlined' type='submit' name='sendbtn' value=' Caut&#259; ' style='padding:3px;'><br>
				</form>
				<br><br>
				";
				if(isset($_POST['sendbtn']))
				{	
					$username = $_POST['username'];
					$search = $db->prepare("SELECT * FROM members WHERE `name` = '$username'");
					$search->execute();
					$medals = $db->prepare("SELECT * FROM medals");
					$medals->execute();
					$whatmedals = $db->prepare("SELECT * FROM playermedals WHERE `name` = '$username'");
					$whatmedals->execute();
					/**********************************/
					if($search->rowCount() == 1)
					{
						foreach($search as $row)
						{
							$id = $row['id'];
							$username = $row['name'];
							$password = $row['password'];
							$email = $row['email'];
							$editor = $row['editor'];
							$admin = $row['admin'];
							$rang = $row['rang'];
							$avatar = $row['avatar'];
							$banned = $row['banned'];
							$tutorials = $row['tutorials'];
							$comments = $row['comments'];
							$ip = $row['ip'];
							$activated = $row['activated'];
							$nrlogin = $row['nr_login'];
						}
					}else {echo'Cont inexistent'; return 0;}
					echo'<form method="post" action="'.URL.'admin/search2" autocomplete="off">';
					echo'<div id="customer-orders">
					<div class="table-responsive">
					<table class="table table-hover"><tbody><tr>
					<td colspan="2"><b>SQLID:</b></td>  <td>'.$id.'</td> <input type="hidden" name="id" value="'.$id.'"></input>
					</tr>
					<tr>
					<td colspan="2">Username</td> <td><input type="text" name="username" value="'.$username.'"></input><br></td>
					</tr>
					<tr>
					<td colspan="2">Parola</td> <td><input type="password" name="password" value="'.$password.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Email</td> <td><input type="text" name="email" value="'.$email.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Tutoriale</td> <td><input type="text" name="tutorials" value="'.$tutorials.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Comentarii</td> <td><input type="text" name="comments" value="'.$comments.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Rang</td> <td><input type="text" name="rang" value="'.$rang.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Admin</td> <td><input type="text" name="admin" value="'.$admin.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Editor</td> <td><input type="text" name="editor" value="'.$editor.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Banned</td> <td><input type="text" name="banned" value="'.$banned.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Avatar</td> <td><input type="text" name="avatar" value="'.$avatar.'"></input><a href="'.URL.'uploads/avatars/'.$avatar.'" target="_app"> <font size=2>Show image</font></a></td>
					</tr>
					<tr>
					<td colspan="2">IP</td> <td>'.$ip.'</td>
					</tr>
					<tr>
					<td colspan="2">Activated</td> <td><input type="text" name="activated" value="'.$activated.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Numar de autentificari</td> <td><input type="text" name="nrlogin" value="'.$nrlogin.'"></input></td>
					</tr>
					<tr>
					<td colspan="2">Medalii:</td>
					<td>';
					if($whatmedals->rowCount() != 0)
					{
						foreach($whatmedals as $row)
						{
							$id = $row['id'];
							$name = $row['name'];
							$medalid = $row['medalid'];
							/***************************/
							$extractmedal = $db->prepare("SELECT * FROM medals WHERE `id` = $medalid");
							$extractmedal->execute();
							/***************************/
							foreach($extractmedal as $trow)
							{
								$photo = $trow['photo'];
								echo"<a href='".URL."admin.php?page=search2&delete=true&id=$id'><img src='".URL."uploads/medals/$photo'></img></a>";
							}
						}
					}
					else echo'Acest user nu are nici o medalie!';
					echo'</td>
					</tr>
					<tr>
					<td colspan="2">Atribuie medalie</td>  
					<td><select name="medalid"><option value=0>Alege o medalie</option>';
						foreach($medals as $row)
						{
							$id = $row['id'];
							$medal_name = $row['medalname'];
							echo"<option value='$id'>$medal_name</option>";
						}
					echo'</select></td>
					</tr>
					<tr><td colspan="2">
					</td></tr>
					</tbody></table></div></div><center><input class="btn btn-template-outlined" type="submit" name="updateuser" value=" Submit " style="padding:3px;"></center></form>';
					
					echo'<hr><b><u>Legenda</u></b><br>';
					echo'<div id="customer-orders">
					<div class="table-responsive">
					<table class="table table-hover"><tbody>';
					echo'<tr><td>Rang 0: </td><td><b><span class="label label-red">Fondator</span></b></td></tr>
					<tr><td>Rang 1:</td> <td><b><span class="label label-orange">Administrator</span></b></td></tr>
					<tr><td>Rang 2: </td> <td><b><span class="label label-blue">Editor</span></b></td></tr>
					<tr><td>Rang 3:</td> <td> Utilizator</td></tr>
					<tr><td>Rang 4: </td> <td><b><span class="label label-black">Banat</span></b></td></tr>';
					echo'</tbody></table></div></div>';
					
				}
				echo'</div>';
				/*********************************************/
				/* New user
				/********************************************/
                echo'<div id="pills-new" role="tabpanel" aria-labelledby="pills-profile-tab" class="tab-pane fade">';
					echo'<form method="post" action="'.URL.'admin/search2" autocomplete="off">';
					echo'<div id="customer-orders">
					<div class="table-responsive">
					<table class="table table-hover"><tbody>
					<tr>
					<td colspan="2">Username</td> <td><input type="text" name="username"></input><br></td>
					</tr>
					<tr>
					<td colspan="2">Parola</td> <td><input type="password" name="password"></input></td>
					</tr>
					<tr>
					<td colspan="2">Email</td> <td><input type="text" name="email"></input></td>
					</tr>
					<tr>
					<td colspan="2">Sex</td> <td><select name="register_sex"><option value="1">Masculin</option><option value="2">Feminin</option></select></td>
					</tr>
					
					</tbody></table></div></div><center><input class="btn btn-template-outlined" type="submit" name="newuser" value=" Submit " style="padding:3px;"></center></form>';
				echo'</div>';
                /*********************************************/
				/* Banlist
				/********************************************/
				echo'<div id="pills-banlist" role="tabpanel" aria-labelledby="pills-contact-tab" class="tab-pane fade">';
					/***************************************/
						/***************************************/
						$bans = $db->prepare('SELECT * FROM `banlist` ORDER by `id` DESC');
						$bans->execute();
						/****************************************/
						echo'<form method="post" action="'.URL.'admin/search2" autocomplete="off"><div id="customer-orders">
						<div class="table-responsive">
						<table class="table table-hover">
							<tr>
							<th></th>
							<th>Nume</th>
							<th>Banat de</th>
							<th>Motiv</th>
							<th>Data</th>
							<th>IP</th>
							<th>Action</th></tr>';
						foreach($bans as $row)
						{
							$id = $row['id'];
							$name = $row['name'];
							$bannedby = $row['bannedby'];
							$reason = $row['reason'];
							$time = date('Y/m/d H:i:s' ,$row['data']);
							$ip = $row['ip'];
							$userid = $row['userid'];
							$adminid = $row['adminid'];
							
							echo'<tr style="border-bottom: solid #ccc 1px;">
							<td>'.$id.'</td>';
							echo' <td> <a class="nounder" style="text-decoration: none;" href="'.URL.'profile/'.$userid.'-'.$name.'">'.$name.'</a></td>';
							echo'<td><a class="nounder" style="text-decoration: none;" href="'.URL.'profile/'.$adminid.'-'.$bannedby.'">'.$bannedby.'</a></td>
							<td>'.$reason.'</td>
							<td>'.$time.'</td>
							<td>'.$ip.'</td>
							<td><a href="'.URL.'admin.php?page=search2&id='.$id.'&userid='.$userid.'&unban=true">Debanare</a></td></tr>';
						}
						echo'</table></div></div></form>';
						echo'<div style="float:right;"><a href="" data-toggle="modal" data-target="#edit-modal">Baneaza un user [+]</a></div>';
						
						echo'<div id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
						<h4 id="edit-modalLabel" class="modal-title">Baneaza un user</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
						<form class="form-signin" role="form" method="post" action="'.URL.'admin/search2" autocomplete="off">
						<div class="form-group">
						Utilizator<br />
						<input type="text" class="form-control" name="name" ><br>
		
						Motivul ban&#259;rii <br />
						<input type="text" class="form-control" name="reason">
						
						</div>
						<p class="text-center">
						<button type="submit" name="add_ban" class="btn btn-template-outlined"><i class="fa fa-remove"></i> Baneaza utilizator</button>
						</p>
						</form><hr>
						</div>
					</div>
					</div>
					</div>';
				echo'</div>';
				 /*********************************************/
				/* Conturi inactive
				/********************************************/
				echo'<div id="pills-unactive" role="tabpanel" aria-labelledby="pills-unactive-tab" class="tab-pane fade">';
					/***************************************/
						/***************************************/
						$bans = $db->prepare('SELECT * FROM `members` WHERE `activated` = 0 ORDER by `id` DESC');
						$bans->execute();
						/****************************************/
						echo'<form method="post" action="'.URL.'admin/search2" autocomplete="off"><div id="customer-orders">
						<div class="table-responsive">
						<table class="table table-hover">
							<tr>
							<th></th>
							<th>Nume</th>
							<th>Email</th>
							<th>Data crearii</th>
							<th>Cod de activare</th>
							<th>IP</th>';
						foreach($bans as $row)
						{
							$id = $row['id'];
							$name = $row['name'];
							$joindate = $row['joindate'];
							$activation_hash = $row['activation_hash'];
							$ip = $row['ip'];
							$email = $row['email'];
							
							echo'<tr style="border-bottom: solid #ccc 1px;">
							<td>'.$id.'</td>';
							echo' <td> <a class="nounder" style="text-decoration: none;" href="'.URL.'profile/'.$id.'-'.$name.'">'.$name.'</a></td>';
							echo'<td>'.$email.'</td>';
							echo'<td>'.$joindate.'</td>
							<td><a href ="'.URL.'activare-cont/'.$id.'/'.$activation_hash.'">'.$activation_hash.'</a></td>
							<td>'.$ip.'</td></tr>';
						}
						echo'</table></div></div></form>';
				echo'</div>';
             echo'</div>';
	echo'</div>';
}
elseif($page=="affiliates")
{
	if($_SESSION['admin']<1) { header('Location: '.URL.''); }
	/****************************************/
	if(isset($_GET['id']) && isset($_GET['delete']))
	{
		$escaped_id = $_GET['id'];
		$update = $db->prepare("DELETE FROM affiliates where id='$escaped_id'");
		$update->execute();
		
		if($update){echo("<script>location.href = '".URL."';</script>");}
	}
	if (isset($_POST['affiliates_add']))
	{
		$code = $_POST['code'];
		
		$sql = $db->prepare("insert into affiliates (code) values ('$code')");
		$sql->execute();
		if($sql)
		{
			echo "<h3>Afiliat adaugat!</h3>";
			echo("<script>location.href = '".URL."';</script>");
		}
	}
	/******************************************/
}
elseif($page=="tutoriale")
{
	if($_SESSION['admin']<1) { header('Location: '.URL.''); }
	/****************************************/
	if (isset($_POST['add_categ']))
	{
		$name = $_POST['name'];
		$notation = $_POST['notation'];
		$problem = 0;
		/********************************************/
		$verify = $db->prepare("select * from tutorial_maincateg WHERE `name` = '$name' OR `notation` = '$notation'");
		$verify->execute();
		if($verify->rowCount() > 0)
		{
			echo("<script>location.href = '".URL."admin/tutoriale'; alert('Numele sau notatia introdusa exista deja in baza de date!');</script>");
			$problem = 1;
		}
		/********************************************/
		if($problem == 0)
		{
			$sql = $db->prepare("insert into tutorial_maincateg (name, notation) values ('$name','$notation')");
			$sql->execute();
			
			if($sql) echo("<script>location.href = '".URL."admin/tutoriale';</script>");
		}
	}
	/****************************************/
	if (isset($_GET['removeid']))
	{
		$id = $_GET['removeid'];
		
		$delete = $db->prepare("DELETE FROM tutorial_maincateg where id='$id'");
		$delete->execute();
			
		if($delete) echo("<script>location.href = '".URL."admin/tutoriale';</script>");
	}
	/****************************************/
	else if (isset($_POST['edit_categ']))
	{
		$newname = $_POST['newname'];
		$newnotation = $_POST['newnotation'];
		$sqlid = $_POST['sqlid'];
		
		$update = $db->prepare("UPDATE tutorial_maincateg SET `name` = '$newname', `notation` = '$newnotation' WHERE `id` = '$sqlid'");
		$update->execute();
		
	}
	/****************************************/
	if (isset($_GET['subcategremove']))
	{
		$id = $_GET['subcategremove'];
		
		$delete = $db->prepare("DELETE FROM tutorial_category where id='$id'");
		$delete->execute();
			
		if($delete) echo("<script>location.href = '".URL."admin/tutoriale';</script>");
	}
	/****************************************/
	else if (isset($_POST['edit_subcateg']))
	{
		$newname = $_POST['newname'];
		$newnotation = $_POST['newnotation'];
		$sqlid = $_POST['sqlid'];
		$option = $_POST['category'];
		
		$update = $db->prepare("UPDATE tutorial_category SET `category` = '$newname', `notation` = '$newnotation', `maincateg` = '$option' WHERE `id` = '$sqlid'");
		$update->execute();
		
	}
	/****************************************/
	if (isset($_POST['add_subcateg']))
	{
		$name = $_POST['name'];
		$notation = $_POST['notation'];
		$option = $_POST['category'];
		$problem = 0;
		/********************************************/
		$verify = $db->prepare("select * from tutorial_category WHERE `category` = '$name' OR `notation` = '$notation'");
		$verify->execute();
		if($verify->rowCount() > 0)
		{
			echo("<script>location.href = '".URL."admin/tutoriale'; alert('Numele sau notatia introdusa exista deja in baza de date!');</script>");
			$problem = 1;
		}
		/********************************************/
		if($problem == 0)
		{
			$sql = $db->prepare("insert into tutorial_category (category, notation, maincateg) values ('$name','$notation', '$option')");
			$sql->execute();
			
			if($sql) echo("<script>location.href = '".URL."admin/tutoriale';</script>");
		}
	}
	/****************************************/
	echo'<div class="col-lg-9"><div class="well">';
	echo"<nav id='myTab' role='tablist' class='nav nav-tabs'><a id='tab4-1-tab' data-toggle='tab' href='#tab4-1' role='tab' aria-controls='tab4-1' aria-selected='true' class='nav-item nav-link active show'> <i class='icon-star'></i>Categorii principale</a>
		<a id='tab4-2-tab' data-toggle='tab' href='#tab4-2' role='tab' aria-controls='tab4-2' aria-selected='false' class='nav-item nav-link'>Categorii secundare</a></nav>
		<div id='nav-tabContent' class='tab-content'>
			<div id='tab4-1' role='tabpanel' aria-labelledby='tab4-1-tab' class='tab-pane fade active show'>";
				$maincateg = $db->prepare('SELECT * FROM `tutorial_maincateg`');
				$maincateg->execute();
				echo'<div id="customer-orders">
					<div class="table-responsive"> 
					<table class="table table-hover"><thead>
                    <tr>
                    <th>ID</th>
                    <th>Nume</th>
                    <th>Notatie</th>
                    <th>Subcategorii</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';
				foreach($maincateg as $row)
				{
					$id = $row['id'];
					$name = $row['name'];
					$notation = $row['notation'];
					echo'<tr><td>'.$id.'</td>';
					echo'<td>'.$name.'</td>';
					echo'<td>'.$notation.'</td>';
					/************************************/
					$subcateg = $db->prepare("SELECT * FROM `tutorial_category` WHERE  `maincateg` = '$id'");
					$subcateg->execute();
					/************************************/
					echo'<td>';
						foreach($subcateg as $row2)
						{
							$notation2 = $row2['notation'];
							echo ''.$notation2.', ';
						}
					echo'</td>';
					echo'<td><a href="'.URL.'admin/deletecateg/'.$id.'" class="btn btn-sm btn-danger">Sterge</a> <a href="" data-toggle="modal" data-target="#'.$id.'-modal" class="btn btn-sm btn-info">Editeaza</a></td></tr>';
					echo'<div id="'.$id.'-modal" tabindex="-1" role="dialog" aria-labelledby="'.$id.'-modalLabel" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
						<h4 id="edit-modalLabel" class="modal-title">Editeaza categoria "'.$name.'"</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
						<form class="form-signin" role="form" method="POST" action="'.URL.'admin/tutoriale" autocomplete="off">
						<div class="form-group">
						SQLID<br />
						<input type="text" class="form-control" name="sqlid" value="'.$id.'" readonly=readonly><br>
						
						Nume nou<br />
						<input type="text" class="form-control" name="newname" value="'.$name.'"><br>
		
						Notatia <br />
						<input type="text" class="form-control" name="newnotation" value="'.$notation.'">
						
						</div>
						<p class="text-center">
						<button type="submit" name="edit_categ" class="btn btn-template-outlined"><i class="fa fa-edit"></i> Editeaza</button>
						</p>
						</form><hr>
						</div>
					</div>
					</div>
					</div>';
				}
				echo'</tr></tbody></table></div></div>';
				echo'<div style="float:right;"><a href="" data-toggle="modal" data-target="#add-modal">Adauga o categorie [+]</a></div>';
				echo'<div id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-modalLabel" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
						<h4 id="edit-modalLabel" class="modal-title">Adauga o categorie principala</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
						<form class="form-signin" role="form" method="post" action="'.URL.'admin/tutoriale" autocomplete="off">
						<div class="form-group">
						Nume<br />
						<input type="text" class="form-control" name="name" ><br>
		
						Notatia <br />
						<input type="text" class="form-control" name="notation">
						
						</div>
						<p class="text-center">
						<button type="submit" name="add_categ" class="btn btn-template-outlined"><i class="fa fa-plus"></i> Adauga</button>
						</p>
						</form><hr>
						</div>
					</div>
					</div>
					</div>';
			echo"</div>";
				echo"<div id='tab4-2' role='tabpanel' aria-labelledby='tab4-2-tab' class='tab-pane fade'>";
				$secondcateg = $db->prepare('SELECT * FROM `tutorial_category`');
				$secondcateg->execute();
				echo'<div id="customer-orders">
					<div class="table-responsive"> 
					<table class="table table-hover"><thead>
                    <tr>
                    <th>ID</th>
                    <th>Nume</th>
                    <th>Notatie</th>
                    <th>Categorie</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';
				foreach($secondcateg as $row)
				{
					$id = $row['id'];
					$name = $row['category'];
					$notation = $row['notation'];
					$category = $row['maincateg'];
					echo'<tr><td>'.$id.'</td>';
					echo'<td>'.$name.'</td>';
					echo'<td>'.$notation.'</td>';
					/************************************/
					$maincateg = $db->prepare("SELECT * FROM `tutorial_maincateg` WHERE  `id` = '$category'");
					$maincateg->execute();
					$row2 = $maincateg->fetch();
					$name_main = $row2['name']; 
					/************************************/
					echo'<td>'.$name_main.'</td>';
					echo'<td><a href="'.URL.'admin/deletesubcateg/'.$id.'" class="btn btn-sm btn-danger">Sterge</a> <a href="" data-toggle="modal" data-target="#'.$name.'-modal" class="btn btn-sm btn-info">Editeaza</a></td></tr>';
					echo'<div id="'.$name.'-modal" tabindex="-1" role="dialog" aria-labelledby="'.$name.'-modalLabel" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
						<h4 id="edit-modalLabel" class="modal-title">Editeaza categoria "'.$name.'"</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
						<form class="form-signin" role="form" method="POST" action="'.URL.'admin/tutoriale" autocomplete="off">
						<div class="form-group">
						SQLID<br />
						<input type="text" class="form-control" name="sqlid" value="'.$id.'" readonly=readonly><br>
						
						Nume nou<br />
						<input type="text" class="form-control" name="newname" value="'.$name.'"><br>
		
						Notatia <br />
						<input type="text" class="form-control" name="newnotation" value="'.$notation.'"><br>
						
						Categorie <br />';
						echo"<select name='category' class='form-control'>";
						$selectcategh = $db->prepare("SELECT * FROM `tutorial_maincateg`");
						$selectcategh->execute();
						foreach($selectcategh as $row3)
						{
							$id = $row3['id'];
							$name = $row3['name'];
							echo"<option value='$id'>$name</option>";
						}
						echo"</select>";
						
						echo'</div>
						<p class="text-center">
						<button type="submit" name="edit_subcateg" class="btn btn-template-outlined"><i class="fa fa-edit"></i> Editeaza</button>
						</p>
						</form><hr>
						</div>
					</div>
					</div>
					</div>';
				}
				echo'</tr></tbody></table></div></div>';
				echo'<div style="float:right;"><a href="" data-toggle="modal" data-target="#sub-modal">Adauga o subcategorie [+]</a></div>';
				echo'<div id="sub-modal" tabindex="-1" role="dialog" aria-labelledby="sub-modalLabel" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
						<h4 id="edit-modalLabel" class="modal-title">Adauga o categorie secundara</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
						<form class="form-signin" role="form" method="post" action="'.URL.'admin/tutoriale" autocomplete="off">
						<div class="form-group">
						Nume<br />
						<input type="text" class="form-control" name="name" ><br>
		
						Notatia <br />
						<input type="text" class="form-control" name="notation"><br>
						
						Categorie <br />';
						echo"<select name='category' class='form-control'>";
						$selectcategh = $db->prepare("SELECT * FROM `tutorial_maincateg`");
						$selectcategh->execute();
						foreach($selectcategh as $row3)
						{
							$id = $row3['id'];
							$name = $row3['name'];
							echo"<option value='$id'>$name</option>";
						}
						echo"</select>";
						
						echo'</div>
						<p class="text-center">
						<button type="submit" name="add_subcateg" class="btn btn-template-outlined"><i class="fa fa-plus"></i> Adauga</button>
						</p>
						</form><hr>
						</div>
					</div>
					</div>
					</div>';
		echo"</div><br>";
	echo '</div></div>';
}
elseif($page=="resurse")
{
	if($_SESSION['admin']<1) { header('Location: '.URL.''); }
	/****************************************/
	if (isset($_POST['add_categ']))
	{
		$name = $_POST['name'];
		$notation = $_POST['notation'];
		$icon = $_POST['icon'];
		$problem = 0;
		/********************************************/
		$verify = $db->prepare("select * from shop_category WHERE `category` = '$name' OR `notation` = '$notation'");
		$verify->execute();
		if($verify->rowCount() > 0)
		{
			echo("<script>location.href = '".URL."admin/resurse'; alert('Numele sau notatia introdusa exista deja in baza de date!');</script>");
			$problem = 1;
		}
		/********************************************/
		if($problem == 0)
		{
			$sql = $db->prepare("insert into shop_category (category,notation,icon) values ('$name','$notation','$icon')");
			$sql->execute();
			
			if($sql) echo("<script>location.href = '".URL."admin/resurse';</script>");
		}
	}
	/****************************************/
	if (isset($_GET['removeid']))
	{
		$id = $_GET['removeid'];
		
		$delete = $db->prepare("DELETE FROM shop_category where id='$id'");
		$delete->execute();
			
		if($delete) echo("<script>location.href = '".URL."admin/resurse';</script>");
	}
	/****************************************/
	else if (isset($_POST['edit_categ']))
	{
		$newname = $_POST['newname'];
		$newnotation = $_POST['newnotation'];
		$newicon = $_POST['newicon'];
		$sqlid = $_POST['sqlid'];
		
		$update = $db->prepare("UPDATE shop_category SET `category` = '$newname', `notation` = '$newnotation', `icon` = '$newicon' WHERE `id` = '$sqlid'");
		$update->execute();
		
	}
	/****************************************/
	echo'<div class="col-lg-9"><div class="well">';
				$maincateg = $db->prepare('SELECT * FROM `shop_category`');
				$maincateg->execute();
				echo'<div id="customer-orders">
					<div class="table-responsive"> 
					<table class="table table-hover"><thead>
                    <tr>
                    <th>ID</th>
                    <th>Nume</th>
                    <th>Notatie</th>
                    <th>icon</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';
				foreach($maincateg as $row)
				{
					$id = $row['id'];
					$name = $row['category'];
					$notation = $row['notation'];
					$icon = $row['icon'];
					echo'<tr><td>'.$id.'</td>';
					echo'<td>'.$name.'</td>';
					echo'<td>'.$notation.'</td>';
					echo'<td><i class="'.$icon.'"></i> ('.$icon.')</td>';
					/************************************/
					echo'<td><a href="'.URL.'admin/deletecateg-stuff/'.$id.'" class="btn btn-sm btn-danger">Sterge</a> <a href="" data-toggle="modal" data-target="#'.$id.'-modal" class="btn btn-sm btn-info">Editeaza</a></td></tr>';
					echo'<div id="'.$id.'-modal" tabindex="-1" role="dialog" aria-labelledby="'.$id.'-modalLabel" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
						<h4 id="edit-modalLabel" class="modal-title">Editeaza categoria "'.$name.'"</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
						<form class="form-signin" role="form" method="POST" action="'.URL.'admin/resurse" autocomplete="off">
						<div class="form-group">
						SQLID<br />
						<input type="text" class="form-control" name="sqlid" value="'.$id.'" readonly=readonly><br>
						
						Nume nou<br />
						<input type="text" class="form-control" name="newname" value="'.$name.'"><br>
		
						Notatia <br />
						<input type="text" class="form-control" name="newnotation" value="'.$notation.'"><br>
						
						Icon (<i class="'.$icon.'"></i>)<br />
						<input type="text" class="form-control" name="newicon" value="'.$icon.'">
						
						</div>
						<p class="text-center">
						<button type="submit" name="edit_categ" class="btn btn-template-outlined"><i class="fa fa-edit"></i> Editeaza</button>
						</p>
						</form><hr>
						</div>
					</div>
					</div>
					</div>';
				}
				echo'</tr></tbody></table></div></div>';
				echo'<div style="float:right;"><a href="" data-toggle="modal" data-target="#add-modal">Adauga o categorie [+]</a></div>';
				echo'<div id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-modalLabel" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
						<h4 id="edit-modalLabel" class="modal-title">Adauga o categorie principala</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
						<form class="form-signin" role="form" method="post" action="'.URL.'admin/resurse" autocomplete="off">
						<div class="form-group">
						Nume<br />
						<input type="text" class="form-control" name="name" ><br>
		
						Notatia <br />
						<input type="text" class="form-control" name="notation"><br>
						
						Icon (<a href="https://www.w3schools.com/icons/icons_reference.asp" target=_blank>Click</a>) <br />
						<input type="text" class="form-control" name="icon">
						
						</div>
						<p class="text-center">
						<button type="submit" name="add_categ" class="btn btn-template-outlined"><i class="fa fa-plus"></i> Adauga</button>
						</p>
						</form><hr>
						</div>
					</div>
					</div>
					</div>';
			echo"</div>";
	echo '</div></div>';
}
elseif($page=="medals")
{
	if($_SESSION['admin']<1) { header('Location: '.URL.''); }
	/****************************************/
	if (isset($_POST['add_medal']))
	{
		$medalname = $_POST['name'];
		$medaldesc = $_POST['description'];
		$medalphoto = $_POST['photo'];
		$problem = 0;
		/********************************************/
		$verify = $db->prepare("select * from medals WHERE `medalname` = '$medalname'");
		$verify->execute();
		if($verify->rowCount() > 0)
		{
			echo("<script>location.href = '".URL."admin/medals'; alert('Numele medaliei exista deja in baza de date!');</script>");
			$problem = 1;
		}
		/********************************************/
		if($problem == 0)
		{
			$sql = $db->prepare("insert into medals (medalname,photo,description) values ('$medalname','$medalphoto','$medaldesc')");
			$sql->execute();
			if($sql) echo("<script>location.href = '".URL."admin/medals';</script>");
		}
	}
	/****************************************/
	if (isset($_GET['removeid']))
	{
		$id = $_GET['removeid'];
		
		$delete = $db->prepare("DELETE FROM medals where id='$id'");
		$delete->execute();
			
		if($delete) echo("<script>location.href = '".URL."admin/medals';</script>");
	}
	/****************************************/
	else if (isset($_POST['edit_medal']))
	{
		$newname = $_POST['newname'];
		$newphoto = $_POST['newphoto'];
		$newdesc = $_POST['newdesc'];
		$sqlid = $_POST['sqlid'];
		
		$update = $db->prepare("UPDATE medals SET `medalname` = '$newname', `photo` = '$newphoto', `description` = '$newdesc' WHERE `id` = '$sqlid'");
		$update->execute();
		
	}
	/****************************************/
	echo'<div class="col-lg-9"><div class="well">';
				$medals = $db->prepare('SELECT * FROM `medals`');
				$medals->execute();
				echo'<div id="customer-orders">
					<div class="table-responsive"> 
					<table class="table table-hover"><thead>
                    <tr>
                    <th>ID</th>
                    <th>Nume</th>
                    <th>Poza</th>
                    <th>Descriere</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';
				foreach($medals as $row)
				{
					$id = $row['id'];
					$medalname = $row['medalname'];
					$photo = $row['photo'];
					$description = $row['description'];
					echo'<tr><td>'.$id.'</td>';
					echo'<td>'.$medalname.'</td>';
					echo'<td><img src="'.URL.'uploads/medals/'.$photo.'"></img></td>';
					echo'<td>'.$description.'</td>';
					/************************************/
					echo'<td><a href="'.URL.'admin/deletemedal/'.$id.'" class="btn btn-sm btn-danger">Sterge</a> <a href="" data-toggle="modal" data-target="#'.$id.'-modal" class="btn btn-sm btn-info">Editeaza</a></td></tr>';
					echo'<div id="'.$id.'-modal" tabindex="-1" role="dialog" aria-labelledby="'.$id.'-modalLabel" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
						<h4 id="edit-modalLabel" class="modal-title">Editeaza categoria "'.$medalname.'"</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
						<form class="form-signin" role="form" method="POST" action="'.URL.'admin/medals" autocomplete="off">
						<div class="form-group">
						SQLID<br />
						<input type="text" class="form-control" name="sqlid" value="'.$id.'" readonly=readonly><br>
						
						Nume nou<br />
						<input type="text" class="form-control" name="newname" value="'.$medalname.'"><br>
		
						Poza <br />
						<input type="text" class="form-control" name="newphoto" value="'.$photo.'"><br>
						
						Descriere<br />
						<input type="text" class="form-control" name="newdesc" value="'.$description.'">
						
						</div>
						<p class="text-center">
						<button type="submit" name="edit_medal" class="btn btn-template-outlined"><i class="fa fa-edit"></i> Editeaza</button>
						</p>
						</form><hr>
						</div>
					</div>
					</div>
					</div>';
				}
				echo'</tr></tbody></table></div></div>';
				echo'<div style="float:right;"><a href="" data-toggle="modal" data-target="#add-modal">Adauga o medalie [+]</a></div>';
				echo'<div id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-modalLabel" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
						<h4 id="edit-modalLabel" class="modal-title">Adauga o categorie principala</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
						<form class="form-signin" role="form" method="post" action="'.URL.'admin/medals" autocomplete="off">
						<div class="form-group">
						Nume<br />
						<input type="text" class="form-control" name="name" ><br>
		
						Poza <br />
						<input type="text" class="form-control" name="photo">
						
						Descriere <br />
						<input type="text" class="form-control" name="description">
						
						</div>
						<p class="text-center">
						<button type="submit" name="add_medal" class="btn btn-template-outlined"><i class="fa fa-plus"></i> Adauga</button>
						</p>
						</form><hr>
						</div>
					</div>
					</div>
					</div>';
			echo"</div>";
	echo '</div></div>';
}
elseif($page=="mailbox")
{
	if($_SESSION['admin']<1 && $_SESSION['editor']<1) { echo("<script>location.href = '".URL."';</script>"); }
	/****************************************/
	echo '<div class="col-lg-9"><div class="well">';
	echo'<nav id="myTab" role="tablist" class="nav nav-tabs"><a id="tab4-1-tab" data-toggle="tab" href="#tab4-1" role="tab" aria-controls="tab4-1" aria-selected="true" class="nav-item nav-link active show"> <i class="icon-star"></i>Fara raspuns/necitite</a>
		<a id="tab4-2-tab" data-toggle="tab" href="#tab4-2" role="tab" aria-controls="tab4-2" aria-selected="false" class="nav-item nav-link">Cu raspuns</a></nav>';
	echo'<div id="nav-tabContent" class="tab-content">';
	/*****************************************************/
	echo'<div id="tab4-1" role="tabpanel" aria-labelledby="tab4-1-tab" class="tab-pane fade active show">';
	$mails = $db->prepare('SELECT * FROM `acp_email` WHERE `answer` = 0');
	$mails->execute();
	echo'<div id="customer-orders"><div class="table-responsive"><table class="table table-hover">';
	echo "<tr><th>#</th>";
	echo "<th>Trimis de</th>";
	echo "<th>Email</th>";
	echo "<th>Subiect</th>";
	echo "<th>Actiuni</th></tr>";

	foreach ($mails as $row) 
	{
		$id = $row['id'];
		$nickname = $row['nickname'];
		$email = $row['email'];
		$subject = $row['subject'];
		
		echo "<tr><td>$id</td>";
		echo "<td>$nickname</td>";
		echo "<td>$email</td>";
		echo "<td>$subject</td>";
		echo "<td><a href='".URL."admin/viewmail/".$id."'>Vizualizeaz&#259;</a></td></tr>";
	}
	echo "</table></div></div></div>";
	/*****************************************************/
	echo'<div id="tab4-2" role="tabpanel" aria-labelledby="tab4-2-tab" class="tab-pane fade show ">';
	$mails = $db->prepare('SELECT * FROM `acp_email` WHERE `answer` = 1');
	$mails->execute();
	echo'<div id="customer-orders"><div class="table-responsive"><table class="table table-hover">';
	echo "<tr><th>#</th>";
	echo "<th>Trimis de</th>";
	echo "<th>Email</th>";
	echo "<th>Subiect</th>";
	echo "<th>Actiuni</th></tr>";

	foreach ($mails as $row) 
	{
		$id = $row['id'];
		$nickname = $row['nickname'];
		$email = $row['email'];
		$subject = $row['subject'];
		echo "<tr><td>$id</td>";
		echo "<td>$nickname</td>";
		echo "<td>$email</td>";
		echo "<td>$subject</td>";
		echo "<td><a href='".URL."admin/viewmail/".$id."'>Vizualizeaz&#259;</a></td></tr>";
	}
	echo "</table></div></div></div>";
	/*****************************************************/
	echo"</div>";
	echo"</div></div><hr>";
}
/******************************************/
elseif($page=="respondmail")
{
	if (isset($_POST['sendmail']))
	{
		if(isset($_GET['mail']) && isset($_GET['id']))
		{
			$id = $_GET['id'];
			$EmailTo = Trim(stripslashes($_GET['mail'])); 
			$Subject = "Mail de la ".WEBNAME.""; 
			$Nume = Trim(stripslashes($_POST['admin-name'])); 
			$Email = Trim(stripslashes($_POST['admin-email'])); 
			$Mesaj = Trim(stripslashes($_POST['message'])); 
				
			$headers = 'From: ' .$Email. "\r\n" .
						'Reply-To: '.$Email . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
				
			$message = "Un administrator al website-ului ".WEBNAME." a raspuns la mesajul trimis de tine.\n\n
			$Mesaj

			$Nume,\n
			Echipa administrativa ".WEBNAME."
			";
				
			$success = mail($EmailTo, $Subject, $message, $headers); 
				
			if ($success){   print "<p>Mesajul t&#259;u a fost trimis catre <b>".$_GET['mail']."</b>.</p>"; } 
			else{   print "Eroare! Mesajul nu a fost trimis!"; } 
			/*******************/
			$answer = $db->prepare('UPDATE acp_email SET `answer`=1, `answerby`=:name, `answertxt` =:text WHERE `id` = :id');
			$answer->execute(array('name'=>$Nume, 'id' => $id, 'text'=>$Mesaj));
			/*******************/
			echo'<hr><div style="float:right;"><a href="acp.php?page=mailbox">&#171; Inapoi la lista de mesaje</a></div><br> ';
		}
	}
}
/******************************************/
elseif($page=="viewmail")
{
	if(isset($_GET['deleteid']))
	{
		$escaped_id = $_GET['deleteid'];
		$update = $db->prepare("DELETE FROM acp_email where id='$escaped_id'");
		$update->execute();
		
		if($update){echo("<script>location.href = '".URL."admin/mailbox';</script>");}
	}
	else if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		$acpemail = $db->prepare('SELECT * FROM `acp_email` WHERE `id` = :id');
		$acpemail->execute(array('id' => $id));
		$read = $db->prepare('UPDATE acp_email SET `readbyadmin`=1 WHERE `id` = :id');
		$read->execute(array('id' => $id));
		/***************************/
		echo'<div class="col-lg-9"><div class="well"><div style="float:right;"><a href="'.URL.'admin/deletemail/'.$id.'">Sterge mail [X]</a></div>';
		foreach ($acpemail as $row) 
		{
			$id = $row['id'];
			$nickname = $row['nickname'];
			$email = $row['email'];
			$subject = $row['subject'];
			$message = $row['message'];
			$AnswerByAdmin = $row['answertxt'];
			$answer = $row['answer'];
			$answerby = $row['answerby'];
			$time = date('Y/m/d H:i:s' ,$row['timestamp']);
			
			echo'<b>Subiect:</b> '.$subject.'<br>
			<b>De la:</b> '.$nickname.' ('.$email.')<br>
			<b>Data:</b> '.$time.'<br>
			<b>Mesaj:</b> '.$message.'<br>';
		}
		if($answer == 0)
		{
			echo"<hr><b>R&#259;spunde la mesaj:</b><br><br><form action='".URL."admin/reply/".$id."/".$email."' method='post'>
				<label>Nume administrator:<br />
				<input class='form-control' type='text' name='admin-name' rows='1' cols='50'></input>
				</label> <br /><br />
				
				<label>Email administrator:<br />
				<input class='form-control' type='text' name='admin-email' rows='1' cols='50'></input>
				</label> <br /><br />
				
				<label>Mesaj:<br />
				<textarea name='message' rows='15' cols='70'></textarea>
				</label> <br /><br />
				
				<input class='btn btn-outline-primary' type='submit' name='sendmail' value=' Trimite mesaj '>
			</form>";
		}
		else
		{
			if($_SESSION['admin'] > 0)
			{
				echo "<hr>";
				echo'<div id="accordion" role="tablist" class="mb-5">
                <div class="card">
                <div id="headingOne" role="tab" class="card-header">
                <h5 class="mb-0"><a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Raspuns administrator - '.$answerby.'</a></h5>
                </div>
                <div id="collapseOne" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" class="collapse show">
                <div class="card-body"><div class="row">'.$AnswerByAdmin.'
                </div></div></div></div></div>';
			}
		}
		
		echo'<hr><div style="float:left;"><a href="'.URL.'admin/mailbox">&#171; Inapoi la lista de mesaje</a></div></div></div>';
	}
}
echo'</div></div></div>';
/*********************************/
Footer();
echo"</div></div></div></div>";
AnotherScripts();
if($page == "addshop")
{?><script>
var auto_refresh = setInterval(
function()
{
$('#feeddiv').fadeOut('slow').load('feed.php').fadeIn("slow");
}, 35000);
var auto_refresh2 = setInterval(
function()
{
$('#online_visitors').fadeOut('slow').load('online_visitors.php?return=1').fadeIn("slow");
}, 15000);
</script>
<?php
}
echo"</body>";