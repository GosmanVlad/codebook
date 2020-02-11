<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
if(isset($_SESSION['user'])) { header('Location: '.URL.''); } 
/***************************************/
echo"<title>".WEBNAME." - ".WEBTITLE."</title>";
/***************************************/
Website_Header();

if (isset($_POST['login_username'])) {
    $login_username = $_POST['login_username'];
}

if (isset($_POST['login_password'])) {
    $login_password = $_POST['login_password'];
}

echo'<body><div id="all">';
include('modules/menu.php');
echo'<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Autentificare cont</h1>
			  <p style="font-size:16px;margin-bottom: 10px;font-weight: 300;color:#DBDBDB;">Inca nu ai un cont? <a href="'.URL.'inregistrare">Inregistreaza unul</a>.<br>
			  <a href="'.URL.'recuperare-cont">Ti-ai uitat parola?</a></p>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="'.URL.'">Home</a></li>
                <li class="breadcrumb-item active">Inregistrare cont nou</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
	  
	  <div class="background-content"><div id="content">
        <div class="container">
          <div class="row"><div class="col-md-12">
              <center><div class="box"><div class="well">
                <h2 class="text-uppercase">Autentificare</h2>
                <p class="text-muted">In cazul in care nu ai un cont, acceseaza pagina de <a href="'.URL.'inregistrare">inregistrare</a> pentru a-ti putea crea.</p>
                <hr>
                <form class="form-signin" role="form" method="post" action="" autocomplete="off"><center>
                  <div class="form-group">
                    <label for="login_username">Username</label>
                    <input name="login_username" type="text" class="form-control" style="width:35%;">
                  </div>
                  <div class="form-group">
                    <label for="login_password">Password</label>
                    <input name="login_password" type="password" class="form-control" style="width:35%;">
                  </div>
                  <div class="text-center">
                    <button type="submit" name="login_btn" class="btn btn-template-outlined"><i class="fa fa-sign-in"></i> Log in</button>
                  </div>
                </center></form>';
if(isset($_POST['login_btn'])){

	$name = htmlspecialchars($_POST['login_username'], ENT_QUOTES, "UTF-8");
	$password = md5($_POST['login_password']);


	$acc = $db->prepare('SELECT * FROM `members` WHERE name = :name AND password = :password');
	$acc->execute(array('name' => $name, 'password' => $password));
	if($acc->rowCount()){
		foreach($acc as $data)
		{
			$activated = $data['activated'];
			if($activated == 1)
			{
				$_SESSION["user"] = $name;
				$_SESSION["userid"] = $data['id'];
				$_SESSION["admin"] = $data['admin'];
				$_SESSION["editor"] = $data['editor'];
				$_SESSION["reply_msgto"] = "";
				$_SESSION["reply"] = 0;
				$_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];
				$ip = $_SERVER['REMOTE_ADDR'];
				
				$ziua = date("d");
				$anul = date("Y");
				//
				$luna = date("M");
				$ora = date("H:i");

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


				$azi = "$ziua $luna $anul - $ora";
				$nrlog = $data['nr_login'];
				$updatec = $db->prepare("UPDATE members SET `ip` = '$ip', `lastlogin` = '$azi', `nr_login` = '$nrlog'+1 WHERE `name` = '$name'");
				$updatec->execute();
				echo("<script>location.href = '".URL."';</script>");
			}
			else echo '<div role="alert" class="alert alert-danger"><strong>Eroare!</strong><br>Acest cont nu este activat! Acceseaza link-ul din email-ul personal pentru activarea acestuia.</div>';
		}
	} else {
		echo '<div role="alert" class="alert alert-danger"><strong>Eroare!</strong><br>Datele introduse nu se regasesc in baza noastra de date!</div>';

	}
}
echo'</div></center></div></div></div></div></div></div>';
Footer();
echo'</div>';
AnotherScripts();
echo '</body>';