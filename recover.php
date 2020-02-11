<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
if(isset($_SESSION['user'])) { echo("<script>location.href = '".URL."';</script>"); } 
Website_Header();
/****************************************/
echo"<title>Recuperare cont - ".WEBNAME."</title>";
/*****************************************/
if(isset($_GET["page"])) $page=$_GET["page"];
else $page = $_GET["page"] = NULL;
if (isset($_POST['recover_email'])) {
    $recover_email = $_POST['recover_email'];
}
echo'<body><div id="all">';
include('modules/menu.php');
echo'<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Recuperare cont</h1>
			  <p style="font-size:16px;margin-bottom: 10px;font-weight: 300;color:#DBDBDB;">Introdu adresa de email a contului tau pentru trimiterea datelor.<br>
			  In cazul in care intampinati probleme, accesati pagina de <a href="'.URL.'contact">Contact</a></p>
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
        <div class="container">';
if(!$page)
{	
          echo'<div class="row"><div class="col-md-12"><div class="box"><div class="well">
		<center><h3>Completeaza datele de mai jos pentru recuperarea contului!</h3></center><hr>
		<form class="form-signin" role="form" method="post" action="" autocomplete="off">
	
	<center><p><b>User:</b><br>
 <input type="text" name="recover_user" style="width:35%;" class="form-control">

	<p><b>Email:</b><br>
 <input type="text" name="recover_email" style="width:35%;" class="form-control">
 <p>
 <button class="btn btn-template-outlined" type="submit" name="recover_btn">Recupereaz&#259; parola</button>
   </center>
</form>';
if(isset($_POST['recover_btn']))
{
	/**********/
	$email = $_POST['recover_email'];
	$user = $_POST['recover_user'];
	$Subject = "Mail de la ".WEBNAME."";

	$headers = 'From: ' .WEBNAME. "\r\n" .
    'Reply-To: ' .WEBNAME. "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	$acc = $db->prepare("SELECT * FROM `members` WHERE email = '$email'");
	$acc->execute();
	if($acc->rowCount() != 0)
	{
		foreach($acc as $row)
		{
			if($user == $row['name'])
			{
				if($row['activated'] == 1)
				{
					$password = $row['password'];
					$activation_hash = $row['activation_hash'];
					$Body = ""; 
					$Body .= "Subiect: "; 
					$Body .= ''.WEBNAME.''; 
					$Body .= "\n"; 
					$Body .= "Email: "; 
					$Body .= ''.WEBNAME.''; 
					$Body .= "\n"; 
					$Body .= "Mesaj: "; 
					$Body .= "Ai solicitat recuperarea contului tau\nSalut, $user!\n\nApasa butonul pentru recuperarea parolei:<br /><a href='".URL."recuperare-cont/$activation_hash' class='btn btn-success'>Recupereaza parola</a>"; 
					$Body .= "\n"; 
					$success = mail($email, $Subject, $Body, $headers); 
					if ($success){   print "<hr><center><div role='alert' class='alert alert-success'>Datele au fost trimise pe email-ul introdus.</div></center>"; } 
					else{   print "Eroare! Mesajul nu a fost trimis!"; }
				}
				else print "<hr><center><div role='alert' class='alert alert-danger'>Acest cont nu este activat.</div></center>";
			}
			else print "<hr><center><div role='alert' class='alert alert-danger'>Userul nu corespunde cu email-ul introdus.</div></center>";
		}
	}else print "<hr><center><div role='alert' class='alert alert-danger'>Nici un cont nu corespunde email-ului introdus!</div></center>";
}
echo'</div></center></div></div></div>';
}
elseif($page == 'recover_hash')
{
	if(isset($_GET['activationhash']))
	{
		$activation_hash = $_GET['activationhash'];
		echo'<div class="row"><div class="col-md-12"><div class="box"><div class="well">
			<center><h3>Noua ta parola:</h3></center><hr>';
		$acc2 = $db->prepare("SELECT * FROM `members` WHERE activation_hash = '$activation_hash'");
		$acc2->execute();	
		foreach($acc2 as $row)
		{
			$newpassword = lrandom(5);
			$newpassword2 = md5($newpassword);
			$acc3 = $db->prepare("UPDATE `members` SET `password` = '$newpassword2' WHERE `activation_hash` = '$activation_hash'");
			$acc3->execute();	
			echo'<center><div class="col-md-4">
                  <div class="box-simple box-white same-height">
                    <h1>'.$newpassword.'</h1>
					<p>Pentru a schimba parola, acceseaza contul tau -> Setari cont -> Parola.</p>
                  </div>
                </div></center>';
		}
		echo'</div></center></div></div></div>';
	}
}
echo'</div></div></div>';
Footer();
echo'</div>';
AnotherScripts();
echo '</body>';