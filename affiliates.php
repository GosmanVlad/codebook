<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/****************************************/
$affiliate = $db->prepare('SELECT * FROM `affiliates`');
$affiliate->execute();
/***************************************/
echo"<title>".WEBNAME." - ".WEBTITLE."</title>";
/***************************************/
Website_Header();
echo' <body bgcolor="#034990">';
include('modules/menu.php');
echo'<div class="content-well-heh"><div class="vladdist"></div>';
include('modules/sidebar.php');
echo'<div class="row-fluid">
<div class="well news">
		<h5>Partenerii comunit&#259;&#355;ii '.WEBNAME.'</h5><hr>';
		 foreach ($affiliate as $aff) {
	echo '
	  <tr>
	    <td>'.$aff['code'].'</td> 
	  </tr>
	';
}
echo'
<br><br>Dac&#259; vrei s&#259; fii unul dintre partenerii no&#351;trii, contacteaz&#259;-ne folosind pagina de <a href='.URL.'contact.php>Contact</a>.
</div></div></div></body>';
Footer();
?>