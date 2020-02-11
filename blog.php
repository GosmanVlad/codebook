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
              <h1 class="h2">Citeste noutatile comunitatii</h1>
			  <p style="font-size:16px;margin-bottom: 10px;font-weight: 300;color:#DBDBDB;">Te ajutam sa te imprietenesti atat cu domeniul IT cat si cu aceasta comunitate.<br>
			  Postam noutatile si update-urile facute comunitatii, tutoriale pentru o desfasurare mai buna pe '.WEBNAME.' si diverse lucruri educationale.</p>
          </div>
        </div>
      </div>';
/******************************/
echo"<meta name='keywords' content='blog it, articole it, it, coduri, noutati'>
		<meta name='description' content='Vezi noutatile din domeniul IT, despre coduri si noutatile comunitatii.'>";
		
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar"><div id="blog-listing-big" class="col-md-9">';
/******************************/
if(!$ctg)
{
echo"<title>Articole din industria IT, noutati despre comunitate si alte discutii - ".WEBNAME."</title>";
/*****************************/
$search = $db->prepare('SELECT * FROM `stiri` WHERE `visible` = 1 ORDER BY `id` DESC');
$search->execute();
foreach($search as $row)
	 {
		$id = $row['id'];
		$stire = $row['stire'];
		$titlu = $row['titlu'];
		$newtitle = curata_url($row['titlu']);
		$user = $row['user'];
		$userid = $row['userid'];
		$data = $row['data'];
		$image = $row['image'];
		$comms = $db->prepare("SELECT * FROM `comments` WHERE `sectionid` = $id");
		$comms->execute();
		$comentarii = $comms->rowCount();
		/***************************************************************************/
		$news_stripped = strip_tags($stire);
		$offset = strpos($news_stripped, '^');
		$short_desc = substr($news_stripped,0,$offset);
		/***************************************************************************/
		echo'<div class="well">';
		echo'
				<h2><a href="'.URL.'blog/'.$id.'-'.$newtitle.'">'.$titlu.'</a></h2>
               <div class="row">
                  <div class="col-sm-6">
                    <p class="author-category">By <a href="'.URL.'profile/'.$userid.'-'.$user.'">'.$user.'</a></p>
                  </div>
                  <div class="col-sm-6 text-right">
                    <p class="date-comments"><a href="'.URL.'blog/'.$id.'-'.$newtitle.'"><i class="fa fa-calendar-o"></i>'.$data.'</a><a href="'.URL.'blog/'.$id.'-'.$newtitle.'"><i class="fa fa-comment-o"></i> '.$comentarii.' comentarii</a></p>
                  </div>
                </div>
                <div class="image"><a href="'.URL.'blog/'.$id.'-'.$newtitle.'"><img src="'.URL.'uploads/blog/'.$image.'" alt="'.$titlu.'" class="img-fluid"></a></div>
                <p class="intro" style="font-size: 1.0em;">'.$short_desc.'</p>
                <p class="read-more text-right"><a href="'.URL.'blog/'.$id.'-'.$newtitle.'" class="btn btn-template-outlined">Citeste mai mult</a>';
				if(isset($_SESSION['user']) && $_SESSION['admin'] != 0)
				{
					echo'<a href="'.URL.'blog/delete/'.$id.'" class="btn btn-danger" style="margin-left:4px;margin-right:4px;">Sterge articolul</a>';
					echo'<a href="'.URL.'blog/edit/'.$id.'" class="btn btn-info">Editeaza articolul</a>';
				}
              echo'</p></div>';
	}
}
elseif ($ctg == 'edit')
{
	if($_SESSION['admin']<1) { echo("<script>location.href = '".URL."';</script>"); }
	else  if(isset($_GET['id']))
	{
		if($_SESSION['admin'] != 0) 
		{
			$escaped_id = mysql_real_Escape_String($_GET['id']);
			$search = $db->prepare("SELECT * FROM stiri WHERE `id`=$escaped_id");
			$search->execute();
			foreach($search as $row)
			{
				$text = $row['stire'];
				$titlu = $row['titlu'];
				$tags = $row['tags'];
			}
			/**********************************************/
			if (isset($_POST['editblog']))
			{
				$newtitle = trim($_POST['newtitle']);
				$newtut = trim($_POST['newtut']);
				$newtags = trim($_POST['newtags']);
				$vizibil = $_POST['vizibil'];
						
				$update = $db->prepare("UPDATE stiri SET `titlu` = :newtitle, `stire` = :newtut, `tags` = :newtags, `visible` = :vizibil WHERE `id` = :escaped_id");
				$update->execute(array(':newtitle'=>$newtitle, ':newtut'=>$newtut, ':newtags'=>$newtags, ':vizibil'=>$vizibil, ':escaped_id'=>$escaped_id));
						
				if($update) echo'<div class="alert alert-success">Articol editat!</div>';
			}
			/**********************************************/
			echo'<div class="well">';
			echo"<form action='' method='post'>
			<label>Titlu:<br />
			<input class='form-control'  type='text' name='newtitle' value='$titlu'></input>
			</label> <br /><br />
			
			<label>Articol:<br />
			<input id='textarea1' name='newtut' value='$text'></input>
			</label> <br /><br />
			
			<label>Tags:<br />
			<input class='form-control'  type='text' name='newtags' value='$tags'></input>
			</label> <br /><br />
			
			<label>Vizibil:<br />
			<select class='form-control' name='vizibil'>
					<option value='1'>Da</option>
					<option value='0'>Nu</option>
				</select><div class='helpline'>Articolul poate fi vazut de oricine, dar nu va aparea pe pagina 'Blog'.</div><br>
				
			</label><br>
			<button class='btn btn-success' type='submit' name='editblog'>Edit</button>
			</form>";
			echo'</div>';
		}
	}
}
elseif ($ctg == 'delete')
{
	if($_SESSION['admin']<1) { echo("<script>location.href = '".URL."';</script>"); }
	else  if(isset($_GET['id']))
	{
		//$id = $_GET['id'];
		$id = mysql_real_Escape_String($_GET['id']);
		$update = $db->prepare("DELETE FROM stiri where id='$id'");
		$update->execute();
		echo("<script>location.href = '".URL."blog';</script>");
	}
}
elseif ($ctg == 'commsettings')
{
	if($_SESSION['admin']<1) { echo("<script>location.href = '".URL."';</script>"); }
	else  if(isset($_GET['id']) && isset($_GET['set']))
	{
		$set = $_GET['set'];
		$id = mysql_real_Escape_String($_GET['id']);
		$update = $db->prepare("UPDATE stiri SET `commsettings` = '$set' WHERE `id` = '$id'");
		$update->execute();
		echo("<script>location.href = '".URL."blog';</script>");
	}
}
elseif ($ctg == 'view')
{
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		$searchitem = $db->prepare("SELECT * FROM `stiri` WHERE `id` = '$id'");
		$searchitem->execute();
		foreach($searchitem as $row)
		{
			$id = $row['id'];
			$stire = $row['stire'];
			$titlu = $row['titlu'];
			$user = $row['user'];
			$userid = $row['userid'];
			$data = $row['data'];
			$tags = $row['tags'];
			$image = $row['image'];
			$commSettings = $row['commSettings'];
			echo'<meta name="keywords" content="'.$tags.' '.$titlu.'">';
			$news_stire = str_replace('^','',ucfirst($row['stire']));
			/********************************/
			echo'<title>'.$titlu.' - '.WEBNAME.'</title>';
			/********************************/
			echo '<div class="well">';
			echo'<p class="text-muted text-uppercase mb-small text-right text-sm">By <a href="'.URL.'profile/'.$userid.'-'.$user.'">'.$user.'</a> | '.$data.'</p>';
			echo'<h2>'.$titlu.'</h2>';
			if($image != 'no-image.png')
			echo '<div class="image"><img src="'.URL.'uploads/blog/'.$image.'" alt="'.$titlu.'" class="img-fluid"></div>';
			echo''.$news_stire.'<hr>';
			if(isset($_SESSION['user']) && $_SESSION['admin'] != 0)
				{
					echo'<a href="'.URL.'blog/delete/'.$id.'" class="btn btn-sm btn-danger" style="margin-left:4px;margin-right:4px;">Sterge articolul</a>';
					echo'<a href="'.URL.'blog/edit/'.$id.'" class="btn btn-sm btn-info" style="margin-left:4px;margin-right:4px;">Editeaza articolul</a>';
					if($commSettings == 0)
						echo'<a href="'.URL.'blog/commsettings/1/'.$id.'" class="btn btn-sm btn-success" style="margin-left:4px;margin-right:4px;">Activeaza comentariile</a>';
					else
						echo'<a href="'.URL.'blog/commsettings/0/'.$id.'" class="btn btn-sm btn-danger" style="margin-left:4px;margin-right:4px;">Dezactiveaza comentariile</a>';
				}
			echo'</div>';
			if(isset($_SESSION['user'])) 
			{
				if($commSettings == 1)
				{
					echo "
					<form action='".URL."comentator.php?page=comment&category=blog&id=$id&sectionname=blog' method='post'>";
						include("modules/comments/comment_form.php");
					echo "</form>";
				}
				else echo'<hr><center><div class="alert alert-danger">Comentariile sunt dezactivate!</div></center>';
			}
			else 
			{
				echo'<hr><center><div class="alert alert-danger">Trebuie sa fii <b>autentificat</b> pentru a putea posta un comentariu!</div></center>';
			}
			display_comments('blog',$id);	 
		}
	}
}
elseif ($ctg == 'new')
{
	if(isset($_SESSION['user']) && $_SESSION['admin'] != 0)	
	{
		echo"<title>Adauga un articol - ".WEBNAME."</title>";
		/*********************************/
		if (isset($_POST['addblog']))
		{
			$username = $_SESSION['user'];
			$titlu = $_POST['titlu'];
			$articol = $_POST['articol'];
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
			$imaglink = 'no-image.png';
			if($_FILES['imag1']['tmp_name'] != "")
			{
				$enter_link = NULL;
				$av_path = "uploads/blog/";
				
				$temp_fisier = $_FILES["imag1"]['tmp_name']; //temp
				$nume_fisier = $_FILES["imag1"]['name']; // nume
				$marime_fisier = $_FILES["imag1"]['size']; // marime
				$tip_fisier = $_FILES["imag1"]['type']; // tip
				
				$nume_random = rand(0,999999999);
				if (($tip_fisier == "image/gif") || ($tip_fisier == "image/jpeg") || ($tip_fisier == "image/pjpeg") || ($tip_fisier == "image/png") || ($tip_fisier == "image/x-png"))
				{
					list($width, $height) = getimagesize($temp_fisier);
					if($width<825 || $height<340)
					{
						echo "<tr><td colspan='2'><p style='color: red'>Dimensiunile imaginii sunt prea mici! Min: (825px x 342px)</p></td></tr>";
						global $badsize;
						$badsize = "1";
					}
					else
					{ 
						$_SESSION['badsize'] = false;
						echo "<br><p>";
						echo "</p>";
						if (file_exists("$av_path$nume_fisier")) echo "$nume_fisier <p style='color: red'>exista deja. </font>";
							
						$ext = strrchr($nume_fisier,'.');
						$ext = strtolower($ext);

						if($tip_fisier == "image/pjpeg" || $tip_fisier == "image/jpeg"){
						$imagine_noua = imagecreatefromjpeg($_FILES["imag1"]['tmp_name']);
						}elseif($tip_fisier == "image/x-png" || $tip_fisier == "image/png"){
						$imagine_noua = imagecreatefrompng($_FILES["imag1"]['tmp_name']);
						}elseif($tip_fisier == "image/gif"){
						$imagine_noua = imagecreatefromgif($_FILES["imag1"]['tmp_name']);
						}
						if ($imagine_noua === false) { die ('Nu pot deschide imaginea'); }
						
						list($width, $height) = getimagesize($temp_fisier);

						$new_width1 = "825"; 	$new_height1 = "342";
						$new_width2 = "825"; 	$new_height2 = "342";
						
						if($width > 825 or $height > 400) //DE MODIFICAT
						{
							$imagine_mod1 = imagecreatetruecolor($new_width1, $new_height1); 
							imagecopyresampled($imagine_mod1, $imagine_noua, 0, 0, 0, 0, $new_width1, $new_height1, $width, $height);
							Imagejpeg($imagine_mod1, "$av_path$nume_random$ext");

						}
						else{move_uploaded_file($temp_fisier, "uploads/blog/$nume_random$ext");}
						$imagine_mod2 = imagecreatetruecolor($new_width2, $new_height2); // Imaginea modificata2

						//imagecopyresampled($imagine_mod2, $imagine_noua, 0, 0, 0, 0, $new_width2, $new_height2, $width, $height);
						//Imagejpeg($imagine_mod2, "$av_path2$nume_random$ext");

						$link_image = "$nume_random$ext";
						if(isset($imagine_mod1)){ImageDestroy ($imagine_mod1);}
						//ImageDestroy ($imagine_mod2);

						$enter_link = "$nume_random$ext";
					
						global $imaginee1;
						$imaglink = $enter_link;
						/***************************************/

					}
				}
			}
			/********************************************/
			$finduserid = $db->prepare("select * from members WHERE `name` = :name");
			$finduserid->execute(array('name'=>$username));
			foreach($finduserid as $torow)
			{
				$userid = $torow['id'];
			}
			$vizibil = $_POST['vizibil'];
			/********************************************/
			$sql = $db->prepare("insert into stiri (titlu, stire, image, user, userid, data,tags,visible) values (:titlu,:articol,:imaglink,:username,:userid,:azi,:tags,:vizibil)");
			$sql->execute(array(':titlu'=>$titlu,':articol'=>$articol,'imaglink'=>$imaglink,':username'=>$username,':userid'=>$userid,':azi'=>$azi,':tags'=>$tags,':vizibil'=>$vizibil));

			if($sql)
			{
				echo'<div class="alert alert-success">
					Articolul t&#259;u a fost publicat.</b>.
				</div>';
			}
		}
		echo"<br />";
		/*********************************/
		echo"<form action='' method='post' enctype='multipart/form-data'><div class='well'><strong>Adauga un articol</strong><hr>
				<div role='alert' class='alert alert-info alert-dismissible'>
                <button type='button' data-dismiss='alert' class='close'><span aria-hidden='true'>×</span><span class='sr-only'>Close</span></button><strong>Info:</strong> Foloseste caracterul <strong>'^'</strong> pentru a incheia descrierea scurta.
              </div>
				<div class='row'><label class='col-sm-2'>Titlul articolului</label>
				<div class='col-sm-10'><input class='form-control' name='titlu' placeholder='Titlul'></input>
				</div>
				</div><br>
				
				<div class='row'><label class='col-sm-2'>Articol</label>
				<div class='col-sm-10'><textarea id='edit'  name='articol' rows='3' placeholder='Articol..'></textarea>
				</div>
				</div><br>
				
				<div class='row'><label class='col-sm-2'>Imagine (Recomandat: 825x342)</label>
				<div class='col-sm-10'><input type='file' name='imag1' id='file' style='border:solid #afafaf 1px;background:#fff;padding:3px;'>
				</div>
				</div><br>
				
				<div class='row'><label class='col-sm-2'>Tags</label>
				<div class='col-sm-10'><input class='form-control' name='tags' placeholder='Tags'></input>
				</div>
				</div><br>
				
				<div class='row'><label class='col-sm-2'>Vizibil</label>
				<div class='col-sm-10'><select class='form-control' name='vizibil'>
					<option value='1'>Da</option>
					<option value='0'>Nu</option>
				</select>
				<div class='helpline'>Articolul poate fi vazut de oricine, dar nu va aparea pe pagina 'Blog'.</div>
				</div>
				</div><br>
		<center><button class='btn btn-template-outlined' type='submit' name='addblog'><i class='fa fa-upload'></i> Adaug&#259;</button></center>";
		echo"</div></form>";
	}
}
	echo'</div>';
/***************************************/
echo'<div class="col-md-3">';
if(isset($_SESSION['user']) && $_SESSION['admin'] != 0)
{
	echo'<div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="h4 panel-title">Admin Panel</h3>
             </div>
             <div class="panel-body">
                 <ul class="nav nav-pills flex-column text-sm">
                   <li class="nav-item"><a href="'.URL.'blog/new"><i class="fa fa-chevron-right" style="color: #58666e;"></i> Adauga un nou articol</a></li>
                 </ul>
           </div>
        </div>';
	echo'<div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="h4 panel-title">Articole nevizibile</h3>
             </div>
             <div class="panel-body">
                 <ul class="nav nav-pills flex-column text-sm">';
					$blogs = $db->prepare("SELECT * FROM `stiri` WHERE `visible` = 0 ORDER BY `id` DESC");
					$blogs->execute();
					foreach($blogs as $row)
					{
						$title = $row['titlu'];
						$titlu = curata_url($title);
						$id = $row['id'];
						echo'<li class="nav-item"><a href="'.URL.'blog/'.$id.'-'.$titlu.'"><i class="fa fa-chevron-right" style="color: #58666e;"></i> '.$title.'</a></li>';
					}
                 echo'</ul>
           </div>
        </div>';
}
	echo'<div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Ultimele articole</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">';
					/*************************************************************************/
					$allcateg = $db->prepare("SELECT * FROM `stiri` ORDER BY `id` DESC LIMIT 5");
					$allcateg->execute();
					/*************************************************************************/
					foreach($allcateg as $row)
					{
						$id = $row['id'];
						$title = $row['titlu'];
						$titlu = curata_url($title);
						echo'<li class="nav-item"><a href="'.URL.'blog/'.$id.'-'.$titlu.'" class="nav-link"><i class="fa fa-chevron-right" style="color: #58666e;"></i> '.$title.'</a></li>';
					}
                  echo'</ul>
                </div>
              </div>
            </div>';
/*******************************************/
echo'</div></div></div>';
/*********************************/
Footer();
echo"</div></div></div></div>";
AnotherScripts();
if($ctg == "edit")
{echo'<script type="text/javascript" src="'.URL.'js/wysiwyg/scripts/wysiwyg.js"></script>
	<script type="text/javascript" src="'.URL.'wysiwyg/scripts/wysiwyg-settings.js"></script>';
echo "<script> WYSIWYG.attach('textarea1'); </script>";
}
if($ctg == "new")
{echo'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

  <script type="text/javascript" src="'.URL.'texteditor/js/froala_editor.min.js" ></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/align.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/char_counter.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/code_beautifier.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/code_view.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/colors.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/draggable.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/emoticons.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/entities.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/file.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/font_size.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/font_family.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/fullscreen.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/image.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/image_manager.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/line_breaker.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/inline_style.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/link.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/lists.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/paragraph_format.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/paragraph_style.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/quick_insert.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/quote.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/table.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/save.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/url.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/video.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/help.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/print.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/third_party/spell_checker.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/special_characters.min.js"></script>
  <script type="text/javascript" src="'.URL.'texteditor/js/plugins/word_paste.min.js"></script>';?>

  <script>
    $(function(){
      $('#edit').froalaEditor()
    });
  </script>
<?php
}
echo"</body>";