<?php
require_once('includes/functions.php');
if(isset($_SESSION['user'])) 
{ 
	$username = $_SESSION['user']; 
	$unreadpm = $db->prepare('SELECT * FROM `pm` WHERE `name2` = :name2 AND `readmessage` = 0');
	$unreadpm->execute(array('name2' => $username));
	$how = $unreadpm->rowCount();

	$checkban = $db->prepare("select * from banlist WHERE `name` = :name");
	$checkban->execute(array('name'=>$username));
	if($checkban->rowCount())
	{
		echo("<script>location.href = '".URL."banspage.php';</script>");
	}
}
echo'<div class="top-bar">
        <div class="container">
          <div class="row d-flex align-items-center">
            <div class="col-md-6 d-md-block d-none">
				
            </div>
            <div class="col-md-6">
              <div class="d-flex justify-content-md-end justify-content-between">
                <ul class="list-inline contact-info d-block d-md-none">';
                 ////FOR PHONE - left
                echo'</ul>';
				if(!isset($_SESSION['user']))
					{
						echo'<div class="login"><a href="#" data-toggle="modal" data-target="#login-modal" class="login-btn">
						<i class="fa fa-user"></i> <span class="d-none d-md-inline-block">Autentificare</span></a>
						<a href="'.URL.'inregistrare" class="signup-btn"><i class="fa fa-user"></i><span class="d-none d-md-inline-block">Inregistrare</span></a></div>';
					}
                echo'<ul class="social-custom list-inline">
                  <li class="list-inline-item"><a href="#"><i class="fa fa-facebook"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-twitter"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-envelope"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>';
	  
      echo'<div id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalLabel" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 id="login-modalLabel" class="modal-title">Autentificare cont</h4>
              <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
			  <form class="form-signin" role="form" method="post" action="'.URL.'login.php" autocomplete="off">
                <div class="form-group">
                  <input name="login_username" type="text" placeholder="User" class="form-control">
                </div>
                <div class="form-group">
                  <input name="login_password" type="password" placeholder="password" class="form-control">
                </div>
                <p class="text-center">
                  <button type="submit" name="login_btn" class="btn btn-template-outlined"><i class="fa fa-sign-in"></i> Log in</button>
                </p>
              </form>
			  <p class="text-center text-muted"><a href="'.URL.'recuperare-cont">Ti-ai uitat parola?</a></p>
              <p class="text-center text-muted">Inca nu ai un cont?<br>
              <a href="'.URL.'inregistrare"><strong>Inregistreaza-ti unul acum</strong></a>! Este usor de facut, dureaza 1 minut si beneficiati de mai multe facilitati!</p>
            </div>
          </div>
        </div>
      </div>';
	  
	  echo'<header class="nav-holder make-sticky">
        <div id="navbar" role="navigation" class="navbar navbar-expand-lg">
          <div class="container"><a href="'.URL.'" class="navbar-brand home"><img src="'.URL.'img/minilogo2.png" alt="'.WEBNAME.'" class="d-none d-md-inline-block"><img src="'.URL.'img/logo-small.png" alt="'.WEBNAME.' logo" class="d-inline-block d-md-none"><span class="sr-only">'.WEBNAME.' - Pagina principala</span></a>
            <button type="button" data-toggle="collapse" data-target="#navigation" class="navbar-toggler btn-template-outlined"><span class="sr-only">Toggle navigation</span><i class="fa fa-align-justify"></i></button>
            <div id="navigation" class="navbar-collapse collapse">
              <ul class="nav navbar-nav ml-auto">
			 <li class="nav-item"> <a href="'.URL.'shop">Resurse <b class="caret"></b></a></li>
                <li class="nav-item"><a href="'.URL.'contact">Contact <b class="caret"></b></a></li>
				<li class="nav-item"><a href="'.URL.'tutoriale">Tutoriale <b class="caret"></b></a></li>
				<li class="nav-item"><a href="'.URL.'blog">Blog <b class="caret"></b></a></li>
				<li class="nav-item"><a href="'.URL.'discutii">Discutii <b class="caret"></b></a></li>';
				if(isset($_SESSION['user']))
				{
					echo'<li class="nav-item dropdown"><a href="javascript: void(0)" data-toggle="dropdown" class="dropdown-toggle"><b>'.$_SESSION['user'].'</b> <b class="caret"></b></a>
					  <ul class="dropdown-menu">
						<li class="dropdown-item"><a href="'.URL.'profile/'.$_SESSION['userid'].'-'.$_SESSION['user'].'" class="nav-link"><i class="fa fa-user" style="color:#000;"></i> Profil</a></li>
						<li class="dropdown-item"><a href="'.URL.'profile/setari-'.$_SESSION['userid'].'/5" class="nav-link"><i class="fa fa-cog" style="color:#000;"></i> Setari</a></li>
						<li class="dropdown-item"><a href="'.URL.'memberlist" class="nav-link"><i class="fa fa-list" style="color:#000;"></i> Lista membri</a></li>
						<li class="dropdown-item"><a href="'.URL.'medalii" class="nav-link"><i class="fa fa-certificate" style="color:#000;"></i> Medalii</a></li>
						<li class="dropdown-item"><a href="'.URL.'logout.php" class="nav-link"><i class="fa fa-sign-out" style="color:#000;"></i> Deconectare</a></li>
					  </ul>
					</li>';
					
					if($unreadpm->rowCount() == 0)echo'<li class="nav-item dropdown"><a href="javascript: void(0)" data-toggle="dropdown" class="dropdown-toggle"><b>Mailbox</b> <b class="caret"></b></a>';
					else echo'<li class="nav-item dropdown"><a href="javascript: void(0)" data-toggle="dropdown" class="dropdown-toggle"><b>Mailbox ('.$how.')</b> <b class="caret"></b></a>';
					  echo'<ul class="dropdown-menu">
						<li class="dropdown-item"><a href="'.URL.'inbox" class="nav-link"><i class="fa fa-envelope" style="color:#000;"></i> Inbox ('.$how.')</a></li>
						<li class="dropdown-item"><a href="'.URL.'newpm" class="nav-link"><i class="fa fa-mail-forward" style="color:#000;"></i> Trimite un mesaj</a></li>
					  </ul>
					</li>';
				}
              echo'</ul>
            </div>
            <div id="search" class="collapse clearfix">
              <form role="search" class="navbar-form">
                <div class="input-group">
                  <input type="text" placeholder="Search" class="form-control"><span class="input-group-btn">
                    <button type="submit" class="btn btn-template-main"><i class="fa fa-search"></i></button></span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </header>';
	  