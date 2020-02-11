<?php
include('./includes/online_visitors.php');
/***************************************/
$affiliates = $db->prepare('SELECT * FROM `affiliates` LIMIT 10');
$affiliates->execute();
/***************************************/
$comments = $db->prepare('SELECT * FROM `comments` ORDER BY `id` DESC LIMIT 10');
$comments->execute();
/***************************************/
echo'<div class="row-fluid2"><div id="sidebar"><div class="new-right"><div style="float:left; margin-left:0px;"><div style="width:305px;overflow:hidden;">';
echo'
<div class="shop-menu-right">
                <div class="shop-top">Vlad</div>
                <a style="text-decoration: none;" href="#" class="menu_link_paidad" onclick="filterSelection("nature")"><b>User Control Panel</b><br>Controlarea propriului caracter</a>
                <a style="text-decoration: none;" href="#" class="menu_cgrid"><b>Factiuni oficiale</b><br>Factiunile oficiale de pe server</a>
                <a style="text-decoration: none;" href="#" class="menu_link_offers"><b>Profil public</b><br>Afisarea caracterului tau public</a>
				<a style="text-decoration: none;" href="#" class="menu_link_cashout"><b>Admin Record</b><br>Sanctiunile primite</a>
				<a style="text-decoration: none;" href="#" class="menu_link_offers"><b>Despre donatii</b><br>Toate informatiile necesare despre donatii</a>
                <a style="text-decoration: none;" href="#" class="menu_link_cashout"><b>Setari</b><br>Schimba parola contului tau..</a>
				<a style="text-decoration: none;" href="#" class="menu_link_cashout"><b>Iesire UCP</b><br>Paraseste sesiunea de autentificare</a>
            </div>
';
echo'</div></div></div></div></div>';
?>