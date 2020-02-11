<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/************************************************/
echo"<title>".WEBNAME." - ".WEBTITLE."</title>";
Website_Header();
echo'<body><div id="all">';
include('modules/menu.php');
if (isset($_POST['nickname'])) {
    $nickname = $_POST['nickname'];
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
}

if (isset($_POST['subject'])) {
    $subiect = $_POST['subject'];
}

if (isset($_POST['message'])) {
    $msg = $_POST['message'];
}

echo'<div id="heading-breadcrumbs" class="brder-top-0 border-bottom-0">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Contact</h1>
			  <p style="font-size:16px;margin-bottom: 10px;font-weight: 300;color:#DBDBDB;">Contacteaza-ne oricand ai nevoie de ajutor!<br>
			  Ne poti trimite orice idee/sugestie pentru imbunatatirea comunitatii.</p>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="'.URL.'">Home</a></li>
                <li class="breadcrumb-item active">Contact</li>
              </ul>
            </div>
          </div>
        </div>
      </div>';
echo'<div class="background-content"><div id="content">
        <div id="contact" class="container">
          <div class="row">';
	if(isset($_POST['sendbtn']))
	{
	$problem=0;
	$nickname = htmlspecialchars($_POST['nickname'], ENT_QUOTES, "UTF-8");
	$email = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");
	$subiect = htmlspecialchars($_POST['subject'], ENT_QUOTES, "UTF-8");
	$msg = htmlspecialchars($_POST['message'], ENT_QUOTES, "UTF-8");

	if((isset($nickname) && !empty($nickname)) || (isset($email) && !empty($email)) || (isset($subiect) && !empty($subiect)) || (isset($msg) && !empty($msg)))
	{	
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
		{
			echo'<div class="alert alert-danger">
				Email-ul introdus nu este corect! 
			</div>';
			$problem = 1;
		}
		if(empty($_POST['nickname']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message']))
		{
			echo'<div class="alert alert-danger">
				Toate campurile sunt obligatorii! 
			</div>';
			$problem = 1;
		}
		/*if(isset($_SESSION['limit_contact']) && $_SESSION['limit_contact']>(time()-300)) 
		{
			echo'<div class="alert alert-danger">
				Se poate trimite cel mult un mesaj la fiecare 5 minute.<br />Mai asteapta '.($_SESSION['limit_contact']-time()+300).' secunde.
			</div>';
			$problem = 1;
		}*/
	}
	if($problem==0)
	{
		$time = time();
		$_SESSION['limit_contact'] = time();
		$update = $db->prepare("INSERT into acp_email (nickname, email, subject, message, timestamp) VALUES ('$nickname', '$email', '$subiect','$msg','$time')");

		$update->execute(array('nickname' => $nickname, 'email' => $email, 'subject' => $subiect,'message'=>$msg,'timestamp'=>$time));
		
		echo'<div class="col-lg-8"> <section class="bar"><div class="alert alert-success">
				Mesajul t&#259;u a fost trimis!<br>Vei primi un raspuns cat de curand.<br>Mul&#355;umim!
			</div></section></div>';
	}
}
echo '<div class="col-lg-8">
              <section class="bar"><div class="well">
                <div class="heading">
                  <h2>Pagina de contact</h2>
                </div>
                <p class="text-sm">Completeaza campurile libere cu datele cerute.</p>
               <form class="form-signin" name="mailer" role="form" method="post" onsubmit="return Validate()" action="" autocomplete="off">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="firstname">Nume</label>
                        <input name="nickname" type="text" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="lastname">Email</label>
                        <input name="email" type="text" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="email">Subiect</label>
                        <input name="subject" type="text" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="message">Message</label>
                        <textarea name="message" class="form-control"></textarea>
                      </div>
                    </div>
					
					<div class="col-md-3">
						<input type="hidden" name="anti_spam" value="" />
						<hr><b>Scrie codul: </b> <font color="#FF0000"><b id="codas"> </b></font><br />
						<input class="form-control" type="text" name="anti_spam1" value="" maxlength="7" />
					</div>
					
					<div class="col-md-12 text-center">
                      <button type="submit" name="sendbtn" class="btn btn-template-outlined"><i class="fa fa-envelope-o"></i> Trimite mesajul</button>
                    </div>
					
                  </div>
                </form></div>
              </section>
            </div>';
?><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/ro_RO/sdk.js#xfbml=1&version=v3.2&appId=298847690718543&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script><?php
echo '<div class="col-lg-4">
              <section class="bar mb-0">
                <h3 class="text-uppercase">Social</h3>
                <p class="text-sm"><div class="fb-page" data-href="https://www.facebook.com/Codebook-Romania-151061585770628/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/Codebook-Romania-151061585770628/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/Codebook-Romania-151061585770628/">Codebook Romania</a></blockquote></div></p>
				
                
              </section>
            </div>';
echo'<script type="text/javascript" src="js/contact.js"> </script>';
echo'</div></div></div></div>';
Footer();
echo'</div>';
AnotherScripts();
echo'</body>';
?>