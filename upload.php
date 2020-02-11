<?php// variabile
$temp_fisier = $_FILES['file']['tmp_name']; //temp
$nume_fisier = $_FILES['file']['name']; // nume
$marime_fisier = $_FILES['file']['size']; // marime
$tip_fisier = $_FILES['file']['type']; // tip


//echo $temp_fisier." - ".$nume_fisier." - ".$marime_fisier." - ".$tip_fisier;

$nume_random = rand(0,999999999); // nume random

if (($tip_fisier == "image/gif") || ($tip_fisier == "image/jpeg") || ($tip_fisier == "image/pjpeg") || ($tip_fisier == "image/png") || ($tip_fisier == "image/x-png") && ($marime_fisier < 1))
{// Daca fisierul corespunde restrictiilor de mai sus


list($width, $height) = getimagesize($temp_fisier); // list inaltime si latime

// Daca imaginea este mai mare decat restrictiile impuse pt width si height
if($width>200 || $height>200){
echo "<tr><td colspan='2'><p style='color: red'>Dimensiunile imaginii sunt prea mari! Max: (200px x 200px)</p></td></tr>";
global $badsize;
$badsize = "1";
}

// Daca imaginea este mai mare decat restrictiile impuse pt width si height END

elseif($width<= 200 || $height<=200){ // Daca imaginea corespunde restricitiilor impuse pt width si height
$_SESSION['badsize'] = false;
echo "<br><p>";


echo "</p>";

if (file_exists("$av_path$nume_fisier"))
{
echo "$nume_fisier <p style='color: red'>already exists. </font>";
}

// Extensie fisier
$ext = strrchr($nume_fisier,'.');
$ext = strtolower($ext);

// Extensie fisier END

if($tip_fisier == "image/pjpeg" || $tip_fisier == "image/jpeg"){
$imagine_noua = imagecreatefromjpeg($_FILES['file']['tmp_name']);
}elseif($tip_fisier == "image/x-png" || $tip_fisier == "image/png"){
$imagine_noua = imagecreatefrompng($_FILES['file']['tmp_name']);
}elseif($tip_fisier == "image/gif"){
$imagine_noua = imagecreatefromgif($_FILES['file']['tmp_name']);
}


if ($imagine_noua === false) { die ('Nu pot deschide imaginea'); }

// Get original width and height
list($width, $height) = getimagesize($temp_fisier);


// New width and height
$new_width1 = "100";
$new_height1 = "100";

// New width and height2
$new_width2 = "50";
$new_height2 = "50";


// Resample
if($width > 100 && $width <= 200 or $height > 100 and $height <= 200){
$imagine_mod1 = imagecreatetruecolor($new_width1, $new_height1); // Imaginea modificata
imagecopyresampled($imagine_mod1, $imagine_noua, 0, 0, 0, 0, $new_width1, $new_height1, $width, $height);
Imagejpeg($imagine_mod1, "$av_path$nume_random$ext");

}
else{
move_uploaded_file($temp_fisier, "up_images/avatare/$nume_random$ext"); // Muta fisierul upload
}
$imagine_mod2 = imagecreatetruecolor($new_width2, $new_height2); // Imaginea modificata2

imagecopyresampled($imagine_mod2, $imagine_noua, 0, 0, 0, 0, $new_width2, $new_height2, $width, $height);
Imagejpeg($imagine_mod2, "$av_path2$nume_random$ext");

$link_image = "$nume_random$ext";
if(isset($imagine_mod1)){ImageDestroy ($imagine_mod1);}
ImageDestroy ($imagine_mod2);

}
}