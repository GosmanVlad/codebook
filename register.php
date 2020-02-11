<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/************************************************/
if(isset($_SESSION['user']) && $_SESSION['admin']<1) { header('Location: '.URL.''); } 
/****************************************/
echo"<title>Inregistreaza un cont nou - ".WEBNAME."</title>";
/*****************************************/
if(isset($_GET["page"])) $page=$_GET["page"];
else $page = $_GET["page"] = NULL;

if (isset($_POST['register_user'])) {
    $register_user = $_POST['register_user'];
}

if (isset($_POST['register_password'])) {
    $register_password = $_POST['register_password'];
}

if (isset($_POST['register_email'])) {
    $register_email = $_POST['register_email'];
}
Website_Header();
echo'<body><div id="all">';
include('modules/menu.php');
echo '<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Inregistreaza-ti un cont pentru a beneficia de mai multe facilitati!</h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="'.URL.'">Home</a></li>
                <li class="breadcrumb-item active">Resurse</li>
              </ul>
            </div>
          </div>
        </div>
      </div>';
	  
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar">';

/**********************************/
echo'<div class="col-md-3">';
	echo'<div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Cateva avantaje</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">
					<li class="nav-item" style="padding-bottom:10px;"><i class="fa fa-check" style="color: #0E8703;"></i> Poti fi ajutat mai repede, lasand un comentariu la un anumit tutorial.</li>
					<li class="nav-item" style="padding-bottom:10px;"><i class="fa fa-check" style="color: #0E8703;"></i> Poti propune imbunatatiri noi asupra comunitatii.</li>
					<li class="nav-item" style="padding-bottom:10px;"><i class="fa fa-check" style="color: #0E8703;"></i> Poti comunica mai usor cu staff-ul si ceilalti useri.</li>
					<li class="nav-item" style="padding-bottom:10px;"><i class="fa fa-check" style="color: #0E8703;"></i> Ai sansa sa faci parte din staff-ul comunitatii.</li>
					<li class="nav-item" style="padding-bottom:10px;"><i class="fa fa-check" style="color: #0E8703;"></i> + alte facilitati.</li>
				</ul>
                </div>
				<hr>
				<div class="panel-heading">
                  <h3 class="h4 panel-title">Sfaturi</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">
					<li class="nav-item" style="padding-bottom:10px;"><i class="fa fa-angle-double-right" style="color: #0E8703;"></i> Alege o parola formata din mai multe caractere, simboluri, numere.</li>
					<li class="nav-item" style="padding-bottom:10px;"><i class="fa fa-angle-double-right" style="color: #0E8703;"></i> Foloseste o adresa de email corecta pentru recuperarea contului, in cazul uitarii parolei.</li>
					<li class="nav-item" style="padding-bottom:10px;"><i class="fa fa-angle-double-right" style="color: #0E8703;"></i> Foloseste un nume sau o porecla reala, fara subintelesuri sau limbaj ofensator/agresiv.</li>
				</ul>
                </div>
              </div>';
	echo'</div>';
	/**********************************/
if(!$page)
{
	echo'<div class="col-md-9">
        <div class="row products products-big">';
	echo"<form name='mailer' role='form' method='post' onsubmit='return Validate()' action='' autocomplete='off'>
	
	<div class='well'><center><h4>Salutare si bine ai venit pe ".WEBNAME."!</h4>
	Ne bucuram pentru faptul ca te-ai hotarat sa intrii pe pagina de inregistrare a unui nou cont aici, pe comunitatea noastra. ".WEBNAME." a fost creata cu scopul de a ne 
	dezvolta cunostiintele in domeniul IT-ului, dar mai ales programarii. Astfel, impartasind din experientele si cunostiintele tale, poti ajuta alti useri in dezvoltarea lor, asa cum si tu,
	la randul tau vei primi informatii valoroase care te vor ajuta sa inveti mai departe ceea ce te intereseaza. Mult succes!
	</center></div>
	<div class='well'><strong>Inregistrare cont nou</strong><hr>";
			if(isset($_POST['register_btn']))
	{
		$problem=0;
		$name = htmlspecialchars($_POST['register_user'], ENT_QUOTES, "UTF-8");
		$email = htmlspecialchars($_POST['register_email'], ENT_QUOTES, "UTF-8");
		if(isset($name) && !empty($name))
		{	
			if(empty($_POST['register_email']) || empty($_POST['register_password']) || empty($_POST['register_occupation']) || empty($_POST['register_skills'])) 
			{
				echo'<div role="alert" class="alert alert-danger">Toate campurile sunt obligatorii! Completeaza-le cu atentie pe toate!</div>';
				$problem = 1;
			}
			$password = $_POST['register_password'];
		
			$sql = $db->prepare("SELECT * FROM members WHERE name = :name OR email = :email");
			$sql->execute(array('name' => $name, 'email' => $email));
		
			if($sql->rowCount() != 0) 
			{
				$problem=1;
				echo'<div role="alert" class="alert alert-danger">Exista deja un utilizator cu acest nume sau cu aceasta adresa de email!</div>';
			}
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
				echo'<div role="alert" class="alert alert-danger">Email incorect.</div>';
				$problem = 1;
			}
		}
		if($problem==0)
		{
			$name = htmlspecialchars($_POST['register_user'], ENT_QUOTES, "UTF-8");
			$email = htmlspecialchars($_POST['register_email'], ENT_QUOTES, "UTF-8");
			//$password = $_POST['register_password'];
			$password = md5($_POST['register_password']);
			$location = $_POST['register_location'];
			$occupation = $_POST['register_occupation'];
			$sex = ucfirst($_POST['register_sex']);
			$skills = $_POST['register_skills'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$time = time();
			/**************************************/
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
			/**************************************/
			$lrand = lrandom(16);
			$update = $db->prepare("INSERT into members (name, email, password, location, occupation, sex, skills, ip, joindate,lastlogin,activation_hash) VALUES ('$name', '$email', '$password', '$location', '$occupation', '$sex', '$skills', '$ip', '$azi', 'Never','$lrand')");
			$update->execute();
			
			$sendpm = $db->prepare("INSERT into pm (name1,name2, timestamp, title, message) VALUES ('".WEBNAME." Team', '$name', '$time', 'Bine ai venit!', 'Salutare <b>$name</b>!<br>Echipa ".WEBNAME." Romania iti ureaza bun venit pe comunitate!<br>In cazul in care ai nevoie de ajutor, nu ezita sa folosesti pagina noastra de contact.')");
			$sendpm->execute();
			
			if(isset($update))
			{
				$acc = $db->prepare("SELECT * FROM `members` WHERE name = '$name'");
				$acc->execute();
				$row2 = $acc->fetch();
				$userid = $row2['id']; 
				
				session_write_close();

				$EmailFrom = "no-reply@codebook.ro"; 
				$EmailTo = "$email"; 
				$Subject = "Activarea contului pe Codebook.ro"; 

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: Activare cont pe '.WEBNAME.' <codebook.ro>' . "\r\n";

				$mesaj = "Activarea contului tau pe site-ul ".WEBNAME."<br />";
				$mesaj .="\n \n";
				$mesaj .="<b>Pentru a-ti activa contul cu numele $name, acceseaza link-ul de mai jos</b><br />";
				$mesaj .="<a href='".URL."activare-cont/$userid/$lrand'>".URL."activare-cont/$userid/$lrand</a>";

				mail($EmailTo, $Subject, $mesaj, $headers); 
				echo'<div role="alert" class="alert alert-success">Contul a fost inregistrat cu succes! Link-ul pentru activarea contului a fost trimis pe email-ul tau.</div>';
			 }
		}
	}
			echo"<div class='row'><label class='col-sm-2'>User</label>
			<div class='col-sm-10'><input class='form-control' name='register_user' placeholder='Username'></input>
			<div class='helpline'>Cu acest username te vei autentifica pe site.</div></div>
			</div><br />
			
			<div class='row'><label class='col-sm-2'>Parola</label>
			<div class='col-sm-10'><input type='password' class='form-control' name='register_password' placeholder='Parola contului'></input>
			<div class='helpline'>Alegeti o parola complexa, formata din mai multe caractere, simboluri si numere.</div></div>
			</div><br />
			
			<div class='row'><label class='col-sm-2'>Email</label>
			<div class='col-sm-10'><input class='form-control' name='register_email' placeholder='Email'></input>
			<div class='helpline'>Pe adresa de email vei primi notificari atunci cand cineva va dori sa te contacteze sau cand vor aparea lucruri noi pe site.</div></div>
			</div><br />
			
			<div class='row'><label class='col-sm-2'>Locatie</label>
			<div class='col-sm-10'><input class='form-control' name='register_location' placeholder='Locatie'></input>
			<div class='helpline'><strong><font color=red>*</font></strong> Locatia este optionala.</div></div>
			</div><br />
			
			<div class='row'><label class='col-sm-2'>Sex</label>
			<div class='col-sm-10'><select name='register_sex' class='form-control'><option value='1'>Masculin</option><option value='2'>Feminin</option></select>
			</div>
			</div><br />
			
			<div class='row'><label class='col-sm-2'>Ocupatii</label>
			<div class='col-sm-10'><input class='form-control' name='register_occupation' placeholder='Ocupatii'></input>
			<div class='helpline'>Cu ce te ocupi, lucruri constructive, viata cotidiana, hobby-uri. Ne ajuta sa te cunoastem mai bine, atat noi, cat si ceilalti useri.</div></div>
			</div><br />
			
			<div class='row'><label class='col-sm-2'>Aptitudini</label>
			<div class='col-sm-10'><input class='form-control' name='register_skills' placeholder='Skills'></input>
			<div class='helpline'>Ce aptitudini ai? C++ ? Poate Javascript? Sau HTML? Arata-ne ce stii sa faci.</div></div>
			</div><br />
	</div>
	<div class='well'><div class='row'><label class='col-sm-2'>Finalizare:</label>
			<div class='checkbox'>
				Aici o sa vina capctha
            </div><hr>
			<button class='btn btn-success' type='submit' name='register_btn'>Inregistrare cont</button>
	</div></div><br>
		
	</form>";
	/***************************************************/
	echo"</div></div>";
}	  
else if($page == 'activate')
{
	$userid = $_GET['userid'];
	$code = $_GET['code'];
	$activate = $db->prepare("SELECT * FROM `members` WHERE activation_hash = '$code' AND id = '$userid' AND `activated` = 0");
	$activate->execute();
	if($activate->rowCount() == 1)
	{
		$activate = $db->prepare("UPDATE `members` SET `activated` = 1 WHERE `id` = '$userid'");
		$activate->execute();
		if(isset($activate))
		{
			echo'<div class="col-md-9">
			<div class="row products products-big"><div class="well">';
			echo '<div role="alert" class="alert alert-info alert-dismissible">
                <button type="button" data-dismiss="alert" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Contul tau a fost activat cu succes! Acum te poti autentifica cu datele introduse la etapa de inregistrare.
              </div>';
			echo'</div></div></div>';
		}
	}
	else{echo'<div class="col-md-9">
			<div class="row products products-big"><div class="well">'; echo '<div role="alert" class="alert alert-danger alert-dismissible">
                <button type="button" data-dismiss="alert" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Acest cont a fost deja activat sau nu exista in baza noastra de date!
              </div>';echo'</div></div></div>';}
}	  
echo'</div></div></div>';
/*********************************/
Footer();
echo"</div></div></div></div>";
AnotherScripts();
echo"</body>";