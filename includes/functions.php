<?php
define("URL","http://localhost/codeb/");
define("WEBNAME","CodeBook");
define("WEBTITLE", "Comunitatea micilor programatori din Romania");
require_once('mysql.php');
/*****************************************/

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
/************************************************************/
function adminlog($username,$info)
{
/*$ip = $_SERVER['REMOTE_ADDR'];
$ins09 = "insert into adminlogs (username, ip, info, date) values ('$username','$ip','$info','$azi')";
$query09 = mysql_query($ins09) or die(mysql_error());*/

}
/************************************************************/
function lrandom($length = 16)
{     
    $chars = 'bcdfghjklmnprstvwxzaeiou1234567890';
	$result = "";
   
    for ($p = 0; $p < $length; $p++)
    {
        $result .= ($p%2) ? $chars[mt_rand(19, 23)] : $chars[mt_rand(0, 28)];
    }
   
    return $result;
}
/************************************************************/
function curata_url($string,$lower=true)
{
	setlocale(LC_ALL, 'ro_RO.UTF8');
	$string = iconv("utf-8", "us-ascii//TRANSLIT", $string);
    $string = preg_replace('~[^\\pL0-9_]+~u', '-', $string);
    $string = trim($string, "-");
    if($lower){$string = strtolower($string);}
    $string = preg_replace('~[^-a-zA-Z0-9_]+~', '', $string);
    return $string;
}

function relativedate($data) 
{
	$secs = time() - strtotime($data);
	/**********************************/
	$second = 1;
	$minute = 60;
	$hour = 60*60;
	$day = 60*60*24;
	$week = 60*60*24*7;
	$month = 60*60*24*7*30;
	$year = 60*60*24*7*30*365;
	/**********************************/
	if ($secs <= 0) { $output = "chiar acum";
	}elseif ($secs > $second && $secs < $minute) { $output = round($secs/$second)." secunde";
	}elseif ($secs >= $minute && $secs < $hour) { $output = round($secs/$minute); if($output > 1){$output = $output." minute";}else{$output = " un minut";}
	}elseif ($secs >= $hour && $secs < $day) { $output = round($secs/$hour); if($output > 1){$output = $output." ore";}else{$output = " o ora";}
	}elseif ($secs >= $day && $secs < $week) { $output = round($secs/$day); if($output > 1){$output = $output." zile";}else{$output =" o zi";}
	}elseif ($secs >= $week && $secs < $month) { $output = round($secs/$week); if($output > 1){$output = $output." saptamani";}else{$output = " o saptamana";}
	}elseif ($secs >= $month && $secs < $year) { $output = round($secs/$month); if($output > 1){$output = $output." luni";}else{$output = " o luna";}
	}elseif ($secs >= $year && $secs < $year*10) { $output = round($secs/$year); if($output > 1){$output = $output." ani";}else{$output = " un an";}
	}else{ $output = " more than a decade ago"; }
	/**********************************/
	if ($output <> "now"){
	$output = (substr($output,0,2)<>"1 ") ? $output."" : $output;
	}
	return "acum ".$output;
}
/************************************************************/
function findexts ($filename)
{
$filename = strtolower($filename) ;
$exts = preg_split("[/\\.]", $filename) ;
$n = count($exts)-1;
$exts = $exts[$n];
return $exts;
}
/************************************************************/
function shop_image($forumname, $number)
{
		$enter_link = NULL;
		$av_path = "uploads/shops/";
		$av_path2 = "uploads/shops/mici/";
	
		$temp_fisier = $_FILES["$forumname"]['tmp_name']; //temp
		$nume_fisier = $_FILES["$forumname"]['name']; // nume
		$marime_fisier = $_FILES["$forumname"]['size']; // marime
		$tip_fisier = $_FILES["$forumname"]['type']; // tip
		/***********************************************/
		$nume_random = rand(0,999999999); // nume random
		/***********************************************/
		if (($tip_fisier == "image/gif") || ($tip_fisier == "image/jpeg") || ($tip_fisier == "image/pjpeg") || ($tip_fisier == "image/png") || ($tip_fisier == "image/x-png"))
		{
			list($width, $height) = getimagesize($temp_fisier);
			if($width<200 || $height<200)
			{
				echo "<tr><td colspan='2'><p style='color: red'>Dimensiunile imaginii sunt prea mici! Min: (200px x 200px)</p></td></tr>";
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
				$imagine_noua = imagecreatefromjpeg($_FILES["$forumname"]['tmp_name']);
				}elseif($tip_fisier == "image/x-png" || $tip_fisier == "image/png"){
				$imagine_noua = imagecreatefrompng($_FILES["$forumname"]['tmp_name']);
				}elseif($tip_fisier == "image/gif"){
				$imagine_noua = imagecreatefromgif($_FILES["$forumname"]['tmp_name']);
				}
				if ($imagine_noua === false) { die ('Nu pot deschide imaginea'); }
				
				list($width, $height) = getimagesize($temp_fisier);

				$new_width1 = "748"; 	$new_height1 = "454";
				$new_width2 = "253"; 	$new_height2 = "200";
				
				if($width > 748 or $height > 454) //DE MODIFICAT
				{
					$imagine_mod1 = imagecreatetruecolor($new_width1, $new_height1); 
					imagecopyresampled($imagine_mod1, $imagine_noua, 0, 0, 0, 0, $new_width1, $new_height1, $width, $height);
					Imagejpeg($imagine_mod1, "$av_path$nume_random$ext");

				}
				else{move_uploaded_file($temp_fisier, "uploads/shops/$nume_random$ext");}
				$imagine_mod2 = imagecreatetruecolor($new_width2, $new_height2); // Imaginea modificata2

				imagecopyresampled($imagine_mod2, $imagine_noua, 0, 0, 0, 0, $new_width2, $new_height2, $width, $height);
				Imagejpeg($imagine_mod2, "$av_path2$nume_random$ext");

				$link_image = "$nume_random$ext";
				if(isset($imagine_mod1)){ImageDestroy ($imagine_mod1);}
				ImageDestroy ($imagine_mod2);

				$enter_link = "$nume_random$ext";
				
				if($number == "1"){
				$enter_link = "$nume_random$ext";
				global $imaginee1;
				$imaginee1 = $enter_link;
				}
				elseif($number == "2"){
				$enter_link = "$nume_random$ext";
				global $imaginee2;
				$imaginee2 = $enter_link;
				}
				elseif($number == "3"){
				$enter_link = "$nume_random$ext";
				global $imaginee3;
				$imaginee3 = $enter_link;
				}
				elseif($number == "4"){
				$enter_link = "$nume_random$ext";
				global $imaginee4;
				$imaginee4 = $enter_link;
				}
				/***************************************/

			}
		}
}
/************************************************************/
function Website_Header() 
{
	echo'
	<head>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>

<script>
window.addEventListener("load", function(){
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#034787"
    },
    "button": {
      "background": "#0857a1"
    }
  },
  "theme": "classic",
  "position": "bottom-right",
  "content": {
    "message": "Acest website foloseste cookies pentru a imbunatati experienta ta pe comunitate.",
    "dismiss": "Am inteles.",
    "link": "Afla mai multe"
  }
})});
</script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="'.URL.'vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="'.URL.'vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,700">
    <link rel="stylesheet" href="'.URL.'vendor/bootstrap-select/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="'.URL.'vendor/owl.carousel/assets/owl.carousel.css">
    <link rel="stylesheet" href="'.URL.'vendor/owl.carousel/assets/owl.theme.default.css">
    <link rel="stylesheet" href="'.URL.'css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="'.URL.'css/custom.css">
   
    <link rel="shortcut icon" href="'.URL.'img/icon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="'.URL.'img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="'.URL.'img/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="'.URL.'img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="'.URL.'img/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="'.URL.'img/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="'.URL.'img/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="'.URL.'img/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="'.URL.'img/apple-touch-icon-152x152.png">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/froala_editor.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/froala_style.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/code_view.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/draggable.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/colors.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/emoticons.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/image_manager.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/image.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/line_breaker.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/table.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/char_counter.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/video.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/fullscreen.css">
  <link rel="stylesheet" href="'.URL.'texteditor/ss/plugins/file.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/quick_insert.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/help.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/third_party/spell_checker.css">
  <link rel="stylesheet" href="'.URL.'texteditor/css/plugins/special_characters.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">

        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  </head>';
}
function getimgurl($html){
$find_start = 'src="';
$find_end = '"';
$getpos1 = strpos($html,$find_start)+5;
$getpos2 = strpos($html,$find_end,$getpos1);

if($getpos1 != 5){
$length = $getpos2-$getpos1;
$extract = substr($html,$getpos1,$length);
$img_url = $extract;
return $img_url;
}
else{
return false;
}

}


if(!function_exists("create_square_image")){
	function create_square_image($original_file, $destination_file=NULL, $square_size = 96){
		
		if(isset($destination_file) and $destination_file!=NULL){
		}
		
		// get width and height of original image
		$imagedata = getimagesize($original_file);
		$original_width = $imagedata[0];	
		$original_height = $imagedata[1];
		$new_width = 824;
		$new_height = 342;
		if($original_height > 342)
			$new_height = 342;
			
		if(substr_count(strtolower($original_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			$original_image = imagecreatefromjpeg($original_file);
		}
		if(substr_count(strtolower($original_file), ".gif")){
			$original_image = imagecreatefromgif($original_file);
		}
		if(substr_count(strtolower($original_file), ".png")){
			$original_image = imagecreatefrompng($original_file);
		}
		
		$smaller_image = imagecreatetruecolor($new_width, $new_height);
		$square_image = imagecreatetruecolor($square_size, $new_height);
		
		imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
		
		if($new_width>$new_height){
			//$difference = $new_width-$new_height;
			//$half_difference =  round($difference/2);
			//imagecopyresampled($square_image, $smaller_image, 0-$half_difference+1, 0, 0, 0, $square_size+$difference, $square_size, $new_width, $new_height);
			imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $new_width+18, $new_height, $new_width, $new_height);
		}
		if($new_height>$new_width){
			//$difference = $new_height-$new_width;
			//$half_difference =  round($difference/2);
			//imagecopyresampled($square_image, $smaller_image, 0, 0-$half_difference+1, 0, 0, $square_size, $square_size+$difference, $new_width, $new_height);
			imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
		}
		if($new_height == $new_width){
			imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
		}
		

		// if no destination file was given then display a png		
		if(!$destination_file){
			imagepng($square_image,NULL,9);
		}
		
		// save the smaller image FILE if destination file given
		if(substr_count(strtolower($destination_file), ".jpg")){
			imagejpeg($square_image,$destination_file,100);
		}
		if(substr_count(strtolower($destination_file), ".gif")){
			imagegif($square_image,$destination_file);
		}
		if(substr_count(strtolower($destination_file), ".png")){
			imagepng($square_image,$destination_file,9);
		}

		imagedestroy($original_image);
		imagedestroy($smaller_image);
		imagedestroy($square_image);

	}
}
/************************************************************/
function AnotherScripts()
{
	echo'<script src="'.URL.'vendor/jquery/jquery.min.js"></script>
    <script src="'.URL.'vendor/popper.js/umd/popper.min.js"> </script>
    <script src="'.URL.'vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="'.URL.'vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="'.URL.'vendor/waypoints/lib/jquery.waypoints.min.js"> </script>
    <script src="'.URL.'vendor/jquery.counterup/jquery.counterup.min.js"> </script>
    <script src="'.URL.'vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="'.URL.'vendor/owl.carousel2.thumbs/owl.carousel2.thumbs.min.js"></script>
    <script src="'.URL.'js/jquery.parallax-1.1.3.js"></script>
    <script src="'.URL.'vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script src="'.URL.'vendor/jquery.scrollto/jquery.scrollTo.min.js"></script>
    <script src="'.URL.'js/front.js"></script>';
}
function Footer() 
{
	echo'<footer class="main-footer">
        <div class="copyrights">
          <div class="container">
            <div class="row">
              <div class="col-lg-4 text-center-md">
                <p>&copy; 2019. Toate drepturile sunt rezervate!</p>
              </div>
              <div class="col-lg-8 text-right text-center-md">';
				if(isset($_SESSION['user'])) { 
					if($_SESSION['admin'] > 0)
						echo'<a href="'.URL.'admin">Admin Control Panel</a>';
				} 
               echo' <p><a href="https://bootstrapious.com/free-templates" target="_blank">Bootstrapious Templates </a> - Design<br>
			   <a href="https://www.freepik.com/free-photos-vectors/background" target="_blank">Freepik</a> - Some pictures, photos and design elements.</br>
				Vlad - Modificari de design, codding.</p>
              </div>
            </div>
          </div>
        </div>
      </footer>';
}
/************************************************************/