<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
include('includes/online_visitors.php');
/***************************************/
$resurse= $db->prepare('SELECT * FROM `shops` WHERE `activation` = 1');
$resurse->execute();
/***************************************/
$articole= $db->prepare('SELECT * FROM `stiri`');
$articole->execute();
/***************************************/
$conturi = $db->prepare('SELECT * FROM `members`');
$conturi->execute();
/***************************************/
$tutoriale = $db->prepare('SELECT * FROM `tutorials` WHERE `approved` = 1');
$tutoriale->execute();
/***************************************/
$randtut = $db->prepare('SELECT * FROM `tutorials` WHERE `approved` = 1 ORDER BY `id` DESC LIMIT 4');
$randtut->execute();
/***************************************/
?>
<script>
var auto_refresh2 = setInterval(
function()
{
$('#online_visitors').fadeOut('slow').load('includes/online_visitors.php?return=1').fadeIn("slow");
}, 15000);
/***************************/
</script>

<?php
/***************************************/
echo"<title>".WEBNAME." - ".WEBTITLE."</title>";
echo'<meta name="Description" content="'.WEBNAME.' - '.WEBTITLE.', Porneste o afacere online postand resurse contra-cost sau gratuite, invata lucruri noi din domeniul IT citind tutorialele noastre!">';
echo'<meta name="Keywords" content="tutoriale it, resurse, coduri, php, tutoriale php, html, css, programare, cum sa programezi, tehnologie, tehnologia informatiei, informatica">';

/***************************************/
Website_Header();
echo' <body><div id="all">';
include('modules/menu.php');
/***********************************************************/
echo' <section class="change bar background-white relative-positioned">
	   <div class="container">
          <div class="home-carousel">
            <div class="dark-mask mask-primary"></div>
            <div class="container">
              <div class="homepage owl-carousel">';
			  foreach($randtut as $row)
			  {
				$news_stripped = strip_tags($row['tutorial']);
			$short_desc = substr($news_stripped,0,200);
			$title = curata_url($row['title']);
                echo'<div class="item">
                  <div class="row">
                    <div class="col-sm-9">
                      <h1><a href="'.URL.'tutoriale/'.$row['id'].'/'.$title.'" style="text-decoration:none;color:white;">'.$row['title'].'</a></h1>
					  <p><strong><a href="'.URL.'profile/'.$row['userid'].'-'.$row['user'].'" style="text-decoration:none;color:white">'.$row['user'].'</a></strong> | '.$row['data'].'</p>
                      <p>'.$short_desc.' ...
					 </p>
                    </div>
                    
                  </div>
                </div>';
				}
              echo'</div>
            </div>
          </div>
        </div>
      </section>';
/***********************************************************/
/***********************************************************/	  
/*echo '<section class="bar background-pentagon no-mb">
        <div class="container">
          <div class="row showcase text-center">
		  <div class="col-md-3 col-sm-6">
              <div class="item">
                <div class="icon-outlined icon-sm icon-thin"><i class="fa fa-list"></i></div>
                <h4><span class="h1 counter">'.$resurse->rowCount().'</span><br>Resurse</h4>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="item">
                <div class="icon-outlined icon-sm icon-thin"><i class="fa  fa-download"></i></div>
                <h4><span class="h1 counter">'.$articole->rowCount().'</span><br>Articole</h4>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="item">
                <div class="icon-outlined icon-sm icon-thin"><i class="fa fa-copy"></i></div>
                <h4><span class="h1 counter">'.$tutoriale->rowCount().'</span><br>Tutoriale</h4>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="item">
                <div class="icon-outlined icon-sm icon-thin"><i class="fa fa-users"></i></div>
                <h4><span class="h1 counter">'.$conturi->rowCount().'</span><br>Membrii inregistrati</h4>
              </div>
            </div>
          </div>
        </div>
      </section>';*/
/***********************************************************/
$randtut = $db->prepare('SELECT * FROM `tutorials` WHERE `approved` = 1 ORDER BY id DESC LIMIT 6');
$randtut->execute();
echo '<section class="bg-white bar">
        <div class="container">
          <div class="heading text-center">
            <h2>Ultimele tutoriale postate</h2>
          </div>
          <div id="blog-listing-small">
          <div class="row">';
		  foreach($randtut as $row)
		  {
			$id = $row['id'];
		$photo = $row['photo'];
		$title = $row['title'];
		$titlu = curata_url($title);
		$author = $row['user'];
		$author_id = $row['userid'];
		$category = $row['categ'];
		$shortdesc = $row['short_desc'];
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
		/******************/
		$news_stripped = strip_tags($row['tutorial']);
			$short_desc = substr($news_stripped,0,100);
           /* echo'<div class="well">
						<div class="row">
						  <div class="col-md-4">
							<div class="image"><a href="'.URL.'tutoriale/'.$id.'-'.$titlu.'"><img src="'.URL.'uploads/tutorials/'.$photo.'" class="img-fluid"></a></div>
						  </div>
						  <div class="col-md-8">
							<h2 class="h3 mt-0"><a href="'.URL.'tutoriale/'.$id.'-'.$titlu.'">'.$title.'</a></h2>
							<div class="d-flex flex-wrap justify-content-between text-xs">
							  <p class="author-category">Postat de  <a href="'.URL.'profile/'.$author_id.'-'.$author.'">'.$author.'</a> in <a href="'.URL.'tutoriale/search/'.$category.'">'.$category_name.'</a></p>
							  <p class="date-comments"><a href="'.URL.'tutoriale/1/'.$id.'-'.$titlu.'"><i class="fa fa-calendar-o"></i> '.$time.'</a><a href="'.URL.'tutoriale/'.$id.'-'.$titlu.'"><i class="fa fa-comment-o"></i> '.$comentarii.' Comentarii</a></p>
							</div>
							<p class="intro">'.$shortdesc.'</p>
							<p class="read-more text-right"><a href="'.URL.'tutoriale/'.$id.'-'.$titlu.'" class="btn btn-template-outlined">Citeste tot</a></p>
						  </div>
						</div></div>';*/
			echo'<div class="col-lg-4 col-md-6">
                  <div class="home-blog-post">
                    <div class="image"><img src="'.URL.'uploads/tutorials/'.$photo.'"class="img-fluid">
                      <div class="overlay d-flex align-items-center justify-content-center">
						<a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'" class="btn btn-template-outlined-white"><i class="fa fa-chain"> </i> Afla mai multe</a></div>
                    </div>
                    <div class="text">
                      <h4><a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'">'.$title.' </a></h4>
                      <p class="author-category"><a href="'.URL.'profile/'.$author_id.'-'.$author.'">'.$author.'</a> in <a href="'.URL.'tutoriale/search/'.$category.'">'.$category_name.'</a></p>
                      <p class="intro">'.$short_desc.' ...</p>
					  <a href="'.URL.'tutoriale/'.$id.'/'.$titlu.'" class="btn btn-template-outlined">Citeste mai departe</a>
                    </div>
                  </div>
                </div>';
		}
          echo'</div></div>';
        echo'</div>
      </section>';
	  /***********************************************************/
	  echo '<section class="bar no-mb color-white padding-big text-md-center business">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h2 class="text-uppercase">Porneste o afacere online</h2>
              <p class="lead mb-small">Vinde creatia ta in doar cativa pasi</p>
              <p class="mb-small"><strong>1.</strong> Inregistreaza-ti un cont pe website-ul nostru sau autentifica-te<br>
		  <strong>2.</strong> Incarca propriul produs.<br>
		  <strong>3.</strong> Un administrator va examina produsul tau.<br>
		  <strong>4.</strong> Daca obiectul va fi aprobat, acesta va aparea pe pagina resurse. In cazul in care va fi respins, veti primi un mesaj privat cu toate detaliile.</p>
              <p>
			  <a href="'.URL.'adauga-anunt" class="btn btn-lg btn-template-outlined-white">Incarca un produs</a></p>
            </div>
            <div class="col-md-6 text-center"><img src="'.URL.'img/template-easy-customize.png" alt="Posteaza resursa ta, porneste o afacere onlina" class="img-fluid"></div>
          </div>
        </div>
      </section>';
	  
echo'<section class="bar background-white">
	  <center><div class="heading"><h3>Ce poti vinde pe '.WEBNAME.'</h3></div></center>
        <div class="container text-center">
          <div class="row">
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-desktop"></i></div>
                <h3 class="h4">Webdesign</h3>
                <p>Template-uri HTML, index-uri, pagini de prezentare, CSS-uri, etc.</p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-paint-brush"></i></div>
                <h3 class="h4">Elemente grafice</h3>
                <p>Elemente grafice (design) precum icon-uri, logo-uri, bannere personalizate, wallpapers, imagini reprezentative, etc.</p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-mobile"></i></div>
                <h3 class="h4">Aplicatii telefon</h3>
                <p>Aplicatii, jocuri, pentru telefon (android, ios, windows, etc.).</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-code"></i></div>
                <h3 class="h4">Scripturi</h3>
                <p>Sisteme, coduri in diverse limbaje de programare (atat programare web, cat si software - PHP/C++/Python/Ruby/C#/etc.).</p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-wordpress"></i></div>
                <h3 class="h4">Teme si plugin-uri</h3>
                <p>Teme, module si alte plugin-uri pentru Wordpress.</p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-plug"></i></div>
                <h3 class="h4">Plugin-uri</h3>
                <p>Plugin-uri pentru diverse programe.</p>
              </div>
            </div>
          </div>
        </div>
      </section>';
/***********************************************************/
	$aff = $db->prepare('SELECT * FROM `affiliates`');
	$aff->execute();
	if($aff->rowCount() > 0 || $_SESSION['admin'] > 0)
	{
		echo'<section id="partner">';
		  if(isset($_SESSION['user'])) 
			{ 
				if($_SESSION['admin']!=0)
				{
					echo'<div style="float:right;"><span data-toggle="tooltip" data-placement="top" title="Editeaza" style="padding:5px;"><a href="" data-toggle="modal" data-target="#edit-modal"><div style="color:white;"><i class="fa fa-pencil"></i></div></a></span></div>';
				}
			}
			echo'<div class="container">
					<h2>Partenerii nostrii</h2>
					<p>'.WEBNAME.' este o comunitate deschisa, astfel avem onoarea de a va prezenta partenerii nostrii.<br>Daca vrei sa devii unul dintre partenerii nostrii, intra pe pagina de <a href="'.URL.'contact">contact</a>.</p>  ';
		foreach($aff as $row)
		{   
				echo''.$row['code'].'';
		}
		echo'</div>';
			 echo'<div id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 id="edit-modalLabel" class="modal-title">Administrare afiliati</h4>
              <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
			  <form class="form-signin" role="form" method="post" action="'.URL.'admin.php?page=affiliates" autocomplete="off">
                <div class="form-group">
				  <textarea name="code" placeholder="Cod mini-banner." rows="5" cols="70"></textarea>
				 </div><div class="form-group">
				  <p>Codul implicit (standard): </p>
	<textarea rows="5" cols="70"><a href="http://thegtaplace.com" target="_blank"><img src="http://gta.jucatori.net/images/tgtapaff3.gif" width="88" height="31" border="0" style="margin:2px;" alt="" /></a>
	</textarea>
                </div>
				<div class="form-group">';
					$affiliates = $db->prepare('SELECT * FROM `affiliates`');
					$affiliates->execute();
					/***************************************/
					echo'<div id="customer-orders">
					<div class="table-responsive">
					<table class="table table-hover">';
					foreach ($affiliates as $row) 
					{
						echo"<tr><td>".$row['code']."</td><td><a href='".URL."admin/deleteaff/".$row['id']."'><font color=red>Delete</font></a></td></tr>";
					}
					echo'</table></div></div>';
				  
                echo'</div>
                <p class="text-center">
                  <button type="submit" name="affiliates_add" class="btn btn-template-outlined"><i class="fa fa-sign-in"></i> Adauga afiliat</button>
                </p>
              </form><hr>
            </div>
          </div>
        </div>
      </div>';
		echo'</section>';
	}
/***********************************************************/
$stiri = $db->prepare("SELECT * FROM `stiri` ORDER BY id DESC LIMIT 4");
$stiri->execute();
echo '<section class="bg-white bar">
        <div class="container">
          <div class="heading text-center">
            <h2>Articole recente</h2>
          </div>
          <center><p class="lead">In aceste articole veti gasi noutati despre comunitate, changelogs, informatii din aria IT-ului si multe altele.</p></center>
          <div class="row">';
		  foreach($stiri as $row)
		  {
			$id = $row['id']; 		  $titlu = $row['titlu'];
			$stire = $row['stire'];   $user = $row['user'];
			$userid = $row['userid']; $data = $row['data'];
			$image = $row['image'];	  $title = curata_url($titlu);
            echo'<div class="col-lg-3">
              <div class="home-blog-post">
                <div class="image"><img src="'.URL.'uploads/blog/'.$image.'" class="img-fluid">
                  <div class="overlay d-flex align-items-center justify-content-center"><a href="'.URL.'blog/'.$id.'-'.$title.'" class="btn btn-template-outlined-white"><i class="fa fa-chain"> </i> Afla mai multe</a></div>
                </div>
                <div class="text">
                  <h4><a href="'.URL.'blog/'.$id.'-'.$title.'">'.$titlu.'</a></h4>
                  <p class="author-category">Postat de <a href="'.URL.'profile/'.$userid.'-'.$user.'">'.$user.'</a> | '.$data.'</p>
                  <p class="intro">'.$descrierescurta.'</p>
				  <a href="'.URL.'blog/'.$id.'-'.$title.'" class="btn btn-template-outlined">Afla mai multe</a>
                </div>
              </div>
            </div>';
		}
          echo'</div>
        </div>
      </section>';
/****************************************************************/
Footer();
echo'</div>';
AnotherScripts();
echo'</body>';
?>