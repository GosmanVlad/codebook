<?php
session_start();
$to = 'vlad_freeboy@yahoo.com';
if(isset($_SESSION['limit_contact']) && $_SESSION['limit_contact']>(time()-300)) {
  echo 'Se poate trimite cel mult un mesaj la fiecare 5 minute.<br />Mai asteapta '.($_SESSION['limit_contact']-time()+300).' secunde.';
  exit;
}

if(isset($_POST['anti_spam']) && isset($_POST['anti_spam1']) && $_POST['anti_spam']==$_POST['anti_spam1']) {
  if (isset($_POST['nume']) && isset($_POST['email']) && isset($_POST['mesaj'])) {
    $_POST = array_map("trim", $_POST);
	$_POST = array_map("strip_tags", $_POST);

    $nume = $_POST['nume'];
    $from = 'From: '. $_POST['email'];
	$subiect = $_POST['subiect'];
    $mesaj = $_POST['mesaj'];
    $body = 'Email de catre '.$nume. "\n-----\n\n"
		  .''.$mesaj;

    if (mail($to, $subiect, $body, $from)) {
	  $_SESSION['limit_contact'] = time();

      $re = '<p><b>Mesajul t&#259;u a fost trimis!<br>Vei primi un raspuns cat de curand.<br>Mul&#355;umim!</b></p>';
    }
    else $re = '<p><b>Serverul nu a putut expedia mesajul prin e-mail.';
  }
  else $re = 'Campuri de formular netransmise.';
}
else $re = 'Cod de verificare incorect.';

echo $re;
?>