<?php
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/***************************************/
$promoted23 = $db->prepare('SELECT * FROM `shops` WHERE `promoted` = 1 AND `activation` = 1 ORDER BY id DESC');
$promoted23->execute();
/***************************************/
$shops = $db->prepare('SELECT * FROM `shops` WHERE `promoted` = 0 AND `activation` = 1 ORDER BY id DESC');
$shops->execute();
/***************************************/
$unconfirmed = $db->prepare('SELECT * FROM `shops` WHERE `activation` = 0 ORDER BY id DESC');
$unconfirmed->execute();
/***************************************/
$all = $db->prepare("SELECT * FROM `shops` WHERE `activation` = '1'");
$all->execute();
/***************************************/
if(isset($_GET["page"])) $page=$_GET["page"];
else {$_GET["page"] = NULL; $page=$_GET["page"];}
Website_Header();
echo'<body><div id="all">';
include('modules/menu.php');
echo '<div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Dezvolta-te cu ajutorul resurselor noastre</h1>
			  <p style="font-size:16px;margin-bottom: 10px;font-weight: 300;color:#DBDBDB;">Descopera template-uri web, site-uri & diverse coduri. Aceste coduri te pot ajuta in dezvoltarea unui proiect personal.<br>
			  De asemenea, poti ajuta si tu ceilalti useri in dezvoltarea unui proiect, postand propriile creatii contra-cost sau gratuit!</p>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
				<form role="form" method="post" action="'.URL.'shop/search/cauta-dupa" autocomplete="off">
                <div class="input-group">
                  <input type="text" name="keyw" placeholder="Cauta.." class="form-control">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-info" name="searchresurse"><i class="fa fa-search"></i> Cauta</button>
                  </div>
                </div>
              </form>
			  </ul>
			 </div>
          </div>
        </div>
      </div>';
/*********************************/
echo'<div class="background-content"><div id="content"><div class="container"><div class="row bar">';

if($page != "view" && $page != "informations")
{
			  
	echo'<div class="col-md-3"><a class="btn btn-danger btn-block" href="'.URL.'adauga-anunt">Start your business!</a><br><div class="well">';
	echo'<div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Categorii disponibile</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">
					<li class="nav-item"><a href="'.URL.'shop" class="nav-link"><i class="fa fa-list" style="color: #58666e;"></i> Toate resursele ('.$all->rowCount().') </a></li>';
					/*************************************************************************/
					$allcateg = $db->prepare("SELECT * FROM `shop_category` ORDER BY `id`");
					$allcateg->execute();
					/*************************************************************************/
					foreach($allcateg as $row)
					{
						$id = $row['id'];
						$category = $row['category'];
						$notation = $row['notation'];
						$icon = $row['icon'];
						$txt = $db->prepare("SELECT * FROM `shops` WHERE `category` = '$notation' AND `activation` = '1'");
						$txt->execute();
						
						echo'<li class="nav-item"><a href="'.URL.'shop/search/'.$notation.'" class="nav-link"><i class="'.$icon.'" style="color: #58666e;"></i> '.$category.' ('.$txt->rowCount().') </a></li>';
					}
						/*<li class="nav-item"><a href="'.URL.'shop/search/websites" class="nav-link"><i class="fa fa-dashboard" style="color: #58666e;"></i> Websites ('.$web->rowCount().')</a></li>
                        <li class="nav-item"><a href="'.URL.'shop/search/themes" class="nav-link"><i class="fa fa-desktop" style="color: #58666e;"></i> Teme/Template-uri ('.$theme->rowCount().')</a></li>
                        <li class="nav-item"><a href="'.URL.'shop/search/graph" class="nav-link"><i class="fa fa-paint-brush" style="color: #58666e;"></i> Design ('.$graph->rowCount().')</a></li>
						<li class="nav-item"><a href="'.URL.'shop/search/phpjscss" class="nav-link"><i class="fa fa-cog" style="color: #58666e;"></i> PHP/JS/CSS('.$php->rowCount().')</a></li>
						<li class="nav-item"><a href="'.URL.'shop/search/cpyrby" class="nav-link"><i class="fa fa-code" style="color: #58666e;"></i> C++/C#/Py/Rby ('.$cpp->rowCount().')</a></li>*/
                    
                  echo'</ul>
                </div>
              </div>';
	echo'</div></div>';
}
else
{
	if(isset($_GET['id']))
	{
		$id =$_GET['id'];
		/*******************************************/
		$sql = $db->prepare("SELECT * FROM shops WHERE id = $id");
		$sql->execute();
		/*******************************************/
		echo'<div class="col-lg-3"><div class="well"><div class="panel panel-default sidebar-menu">
		<div class="panel-body text-widget">';
		foreach($sql as $row)
		{
			$name = $row['name'];
			$userid = $row['userid'];
			/*******************************************/
			$sideprofile = $db->prepare('SELECT * FROM `members` WHERE `name` = :name');
			$sideprofile->execute(array('name' => $name));
			foreach($sideprofile as $infouser)
			{
				$avatar = $infouser['avatar']; 
				$lastlogin = $infouser['lastlogin'];
			}
			/*******************************************/
			echo '<center><div class="user-image" style="margin-bottom:5px;"><img src="'.URL.'uploads/avatars/'.$avatar.'" alt=""></img></div><b><a href="'.URL.'profile/'.$userid.'-'.$name.'">'.$name.'</a></b></center><hr style="margin-top:5px;">';

			echo '<center><div class="helpline"><strong>Ultima autentificare:</strong> <br>'.$lastlogin.'</div></center>';
			
			echo"<div style='margin-top:10px;'><a href='".URL."newpm/$name' class='btn btn-danger btn-block'>Trimite mesaj privat</a></div>";
			echo'<div style="margin-top:5px"><a href="'.URL.'profile/'.$userid.'-'.$name.'" class="btn btn-info btn-block">Vezi profilul lui '.$name.'</a></div></center>';
		}
		/************************************************************/
		echo'</div></div></div>';
		
		echo'<div class="well">';
		$sql2 = $db->prepare("SELECT * FROM shops WHERE id = $id");
		$sql2->execute();
		echo'<div class="heading">
                  <h4>Informatii</h4>
                </div>';
		foreach($sql2 as $row)
		{
			$priceoption = $row['priceoption'];
			$currency = $row['currency'];
			$network = $row['network'];
			$verified = $row['verified'];
			$price = $row['price'];
			$tags = $row['tags'];
			$data = $row['data'];
			/*************************/
			echo"<div style='font-size:15px;'>";
			if($price !=0) echo"<div style='border-bottom:1px solid #E0E0E0;'><strong>Metoda de plata:</strong> $priceoption</div>";
			if($priceoption == 'SMS') echo "<div style='border-bottom:1px solid #E0E0E0;padding-top:5px;'><strong>Retea:</strong> $network</div>";
			if($price != 0) echo"<div style='border-bottom:1px solid #E0E0E0;padding-top:5px;'><strong>Pret:</strong> $price $currency</div>";
			else echo"<div style='border-bottom:1px solid #E0E0E0;padding-top:5px;'><strong>Produsul este <span class='badge badge-success'>GRATUIT</span></strong></div>";
			echo"<div style='border-bottom:1px solid #E0E0E0;padding-top:5px;'><strong>Adaugat la:</strong> $data</div>";
			echo"<div style='border-bottom:1px solid #E0E0E0;padding-top:5px;'><strong>Tags:</strong> $tags</div>";
			if($verified != 0) echo"<div style='border-bottom:1px solid #E0E0E0;padding-top:5px;'><strong style='color:#097BE8;'>Anunt verificat</strong> <i class='fa fa-check-circle' style='padding-left:4px;color:#097BE8;'></i></div>";
			echo"</div>";
		}
		echo'</div>';
		echo'</div>';
	}
}
if(!$page)
{
	echo"<title>Cumpara coduri, scripturi, teme sau descarca-le pe cele gratuite - ".WEBNAME."</title>
		<meta name='keywords' content='cumpara coduri, coduri, descarcare coduri, coduri gratis, teme wordpress, scripturi, afacere online'>
		<meta name='description' content='Cumpara coduri, scripturi, teme sau descarca fisierele gratuite pentru dezvoltarea ta pe aceasta nisa.'>";
		
	echo'<div class="col-md-9">
        <div class="row products products-big">';
		if(isset($_SESSION['user']))
		{
			foreach ($unconfirmed as $row) 
			{
				$id = $row['id'];
				$title = $row['title'];
				$name = $row['name'];
				$userid = $row['userid'];
				$description = $row['description'];
				$price = $row['price'];
				$photo = $row['photo'];
				$option = $row['priceoption'];
				$promoted = $row['promoted'];
				$verified = $row['verified'];
				$titlu = curata_url($row['title']);
				$category = $row['category'];
				$short_desc = $row['short_desc'];
				$video = $row['video'];
				$demo = $row['demo_link'];
				$currency = $row['currency'];
				/***************************/
				if($currency = 'Euro') $currency_type = '&euro;';
				else if($currency = 'RON') $currency_type = 'RON';
				/***************************/
				if($_SESSION['admin'] != 0 || $_SESSION['user'] == $name)
				{
					echo"<div class='col-lg-4 col-md-6'><div class='product-shop-box'><div class='product'>";
					echo"<div class='image'><a href='".URL."shop/$id-$titlu'><img src='".URL."uploads/shops/mici/$photo' alt=''></img></a></div>";
					echo"<div class='text'><h2 style='font-size: 14px;font-weight: 700;'><a href='".URL."shop/$id-$titlu'>$title";
					if($verified != 0) echo"<i class='fa fa-check-circle' style='padding-left:4px;color:#097BE8;'></i>";
					echo"</a></h2>";
					echo"<img src='".URL."images/unconfirmed.png' class='unconfirmed'></img>";
					/***************************/
					echo"<div style='float:left;margin-left:10px;'>";
					if($video != NULL) echo"<span data-toggle='tooltip' data-placement='top' title='Video' style='padding:5px;'><a href='' data-toggle='modal' data-target='#video-modal' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-video-camera'></i></a></span>";
					if($demo != NULL) echo"<a href='$demo' target='_blank' data-toggle='tooltip' data-placement='top' title='Demo' style='padding:5px;border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-desktop'></i></a>";
					echo"<span style='padding:5px;'><a href='".URL."shop/addfav/$id' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em' data-toggle='tooltip' data-placement='top' title='Adauga la favorite' style='padding:5px;'><i class='fa fa-heart'></i></a></span>";
					echo"</div>";
					/**************************/
					if($price == 0) echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong><span class='badge badge-success'>Gratis</span></strong></div>";
					else echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong>$price$currency_type</strong></div>";
					echo"</div>";
					echo"</div></div></div>";
					/**************************/
					echo'<div id="video-modal" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg">
					<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Vizualizare video</h4>
					<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
					</div>
					<div class="modal-body">
					<iframe frameborder="0" src="'.$row['video'].'" style="margin: 0px; padding: 0px; width: 770px; height: 480px;"></iframe>
					</div>
					</div>
					</div>
					</div>';
				}
			}
		}
		foreach ($promoted23 as $row2) 
		{
			$id = $row2['id'];
			$title = $row2['title'];
			$name = $row2['name'];
			$userid = $row2['userid'];
			$description = $row2['description'];
			$price = $row2['price'];
			$photo = $row2['photo'];
			$option = $row2['priceoption'];
			$promoteds = $row2['promoted'];
			$verified = $row2['verified'];
			$titlu = curata_url($row2['title']);
			$category = $row2['category'];
			$short_desc = $row2['short_desc'];
			$video2 = $row2['video'];
			$demo = $row2['demo_link'];
			$currency = $row2['currency'];
			/***************************/
			if($currency = 'Euro') $currency_type = '&euro;';
			else if($currency = 'RON') $currency_type = 'RON';
			/***************************/
			echo"<div class='col-lg-4 col-md-6'><div class='product-shop-box'><div class='product'>";
			echo"<div class='image'><a href='".URL."shop/$id-$titlu'><img src='".URL."uploads/shops/mici/$photo' alt=''></img></a></div>";
			echo"<img src='".URL."images/promoted.png' class='promoted'></img>";
			echo"<div class='text'><h2 style='font-size: 14px;font-weight: 700;'><a href='".URL."shop/$id-$titlu'>$title";
			if($verified != 0) echo"<i class='fa fa-check-circle' style='padding-left:4px;color:#097BE8;'></i>";
			echo"</a></h2>";
			/***************************/
			echo"<div style='float:left;margin-left:10px;'>";
			if($video2 != NULL) echo"<span data-toggle='tooltip' data-placement='top' title='Video' style='padding:5px;'><a href='' data-toggle='modal' data-target='#video-modal' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-video-camera'></i></a></span>";
			if($demo != NULL) echo"<a href='$demo' target='_blank' data-toggle='tooltip' data-placement='top' title='Demo' style='padding:5px;border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-desktop'></i></a>";
			echo"<span style='padding:5px;'><a href='".URL."shop/addfav/$id' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em' data-toggle='tooltip' data-placement='top' title='Adauga la favorite' style='padding:5px;'><i class='fa fa-heart'></i></a></span>";
			echo"</div>";
			/**************************/
			if($price == 0) echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong><span class='badge badge-success'>Gratis</span></strong></div>";
			else echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong>$price$currency_type</strong></div>";
			echo"</div>";
			echo"</div></div></div>";
			/**************************/
			echo'<div id="video-modal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title">Vizualizare video</h4>
			<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
			<iframe frameborder="0" src="'.$row2['video'].'" style="margin: 0px; padding: 0px; width: 770px; height: 480px;"></iframe>
			</div>
			</div>
			</div>
			</div>';
		}
		foreach ($shops as $row3) 
		{
			$id = $row3['id'];
			$title = $row3['title'];
			$name = $row3['name'];
			$userid = $row3['userid'];
			$description = $row3['description'];
			$price = $row3['price'];
			$photo = $row3['photo'];
			$option = $row3['priceoption'];
			$promoted = $row3['promoted'];
			$verified = $row3['verified'];
			$titlu = curata_url($row3['title']);
			$category = $row3['category'];
			$short_desc = $row3['short_desc'];
			$video3 = $row3['video'];
			$demo = $row3['demo_link'];
			$currency = $row3['currency'];
			/***************************/
			if($currency = 'Euro') $currency_type = '&euro;';
			else if($currency = 'RON') $currency_type = 'RON';
			/***************************/
			echo"<div class='col-lg-4 col-md-6'><div class='product-shop-box'><div class='product'>";
			echo"<div class='image'><a href='".URL."shop/$id-$titlu'><img src='".URL."uploads/shops/mici/$photo' alt=''></img></a></div>";
			echo"<div class='text'><h2 style='font-size: 14px;font-weight: 700;'><a href='".URL."shop/$id-$titlu'>$title";
			if($verified != 0) echo"<i class='fa fa-check-circle' style='padding-left:4px;color:#097BE8;'></i>";
			echo"</a></h2>";
			/***************************/
			echo"<div style='float:left;margin-left:10px;'>";
			if($video3 != NULL) echo"<span data-toggle='tooltip' data-placement='top' title='Video' style='padding:5px;'><a href='' data-toggle='modal' data-target='#video-modal' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-video-camera'></i></a></span>";
			if($demo != NULL) echo"<a href='$demo' target='_blank' data-toggle='tooltip' data-placement='top' title='Demo' style='padding:5px;border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-desktop'></i></a>";
			echo"<span style='padding:5px;'><a href='".URL."shop/addfav/$id' target='_blank' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em' data-toggle='tooltip' data-placement='top' title='Adauga la favorite' style='padding:5px;'><i class='fa fa-heart'></i></a></span>";
			echo"</div>";
			/**************************/
			if($price == 0) echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong><span class='badge badge-success'>Gratis</span></strong></div>";
			else echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong>$price$currency_type</strong></div>";
			echo"</div>";
			echo"</div></div></div>";
			/**************************/
			echo'<div id="video-modal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title">Vizualizare video</h4>
			<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
			<iframe frameborder="0" src="'.$row3['video'].'" style="margin: 0px; padding: 0px; width: 770px; height: 480px;"></iframe>
			</div>
			</div>
			</div>
		    </div>';
		}
					?>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
<?php
echo'</div></div>';
}
else if($page == 'addfav')
{
	if(!isset($_SESSION['user'])) echo("<script>location.href = '".URL."autentificare'; alert('Trebuie sa fii autentificat pentru a putea adauga un produs la favorite!');</script>");
	else if(isset($_GET['itemid']))
	{
		/********************************************************************/
		$itemid = $_GET['itemid'];
		$user = $_SESSION['user'];
		/********************************************************************/
		$userid = $db->prepare("SELECT `id` FROM members WHERE name = '$user'");
		$userid->execute();
		$row = $userid->fetch();
		$user_id = $row['id']; 
		/********************************************************************/
		$sql = $db->prepare("SELECT * FROM shop_favorites WHERE userid = '$user_id' AND item = '$itemid'");
		$sql->execute();
		if($sql->rowCount() != 0) 
		{
				echo'<div class="col-md-9"><div id="text-page"><div role="alert" class="alert alert-danger">Acest produs este deja adaugat la favoritele tale!<br>
							Pentru a-l sterge, intra in contul tau la sectiunea \'Lista produse favorite\' -> Sterge item-ul.</div></div></div>';
		}
		else
		{
			$sql = $db->prepare("insert into shop_favorites (user, userid, item) values ('$user','$user_id','$itemid')");
			$sql->execute();
			/********************************************************************/
			if($sql)
			{
				echo'<div class="col-md-9"><div id="text-page"><div role="alert" class="alert alert-success">Produsul a fost adaugat la favoritele tale!<br>
					Pentru a-l accesa mai usor, intra in contul tau la sectiunea \'Lista produse favorite\'!</div>';
				/********************************************************************/	
				$name = $_SESSION['user'];
				$sql2 = $db->prepare("SELECT * FROM shops WHERE id = '$itemid' AND `activation` = 1");
				$sql2->execute();
				/********************************************************************/	
				echo'<hr><div class="heading">
                    <h3>Produsul:</h3>
                    </div>
					<div id="customer-orders">
					<div class="table-responsive">
					<table class="table table-hover">
                    <thead>
                    <tr>
                    <th>ID</th>
                    <th>Titlu</th>
                    <th>Postat de</th>
                    <th>Status</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';
				foreach($sql2 as $row)
				{
					$id = $row['id'];
						$title = $row['title'];
						$name = $row['name'];
						$userid = $row['userid'];
						$description = $row['description'];
						$price = $row['price'];
						$photo = $row['photo'];
						$option = $row['priceoption'];
						$promoted = $row['promoted'];
						$verified = $row['verified'];
						$titlu = curata_url($row['title']);
						$category = $row['category'];
						$short_desc = $row['short_desc'];
						$video = $row['video'];
						$demo = $row['demo_link'];
						echo'<tr>
                        <th># '.$id.'</th>
                        <td><a href="'.URL.'shop/'.$id.'-'.$titlu.'">'.$title.'</a></td>
                        <td><a href="'.URL.'profile/'.$userid.'-'.$name.'">'.$name.'</a></td>';
						if($promoted == 1) echo'<td><span class="badge badge-danger">Promovat</span></td>';
						else if($verified == 1) echo'<td><span class="badge badge-info">Verificat</span></td>';
						else echo'<td><span class="badge badge-success">Normal</span></td>';
                       echo'<td><a href="'.URL.'shop/'.$id.'-'.$titlu.'" class="btn btn-template-outlined btn-sm">Vizualizare</a></td>
                      </tr>';
				}
				echo"</tbody>
                  </table>
                </div></div></div></div>";
			}
		}
	}
}
else if($page == 'search')
{
	if(isset($_GET['item']))
	{
		$item = $_GET['item'];
		if(isset($_POST['searchresurse']))
		{
			$keyw = htmlspecialchars($_POST['keyw'], ENT_QUOTES, "UTF-8");
			echo'<title>Cauta "'.$keyw.'" - '.WEBNAME.'</title>';
			$sql = $db->prepare("SELECT * FROM `shops` WHERE 
							(`promoted` = '0' AND `activation` = '1') AND 
							((`category` LIKE '%{$keyw}%') OR 
							(`title` LIKE '%{$keyw}%') OR 
							(`description` LIKE '%{$keyw}%') OR 
							(`tags` LIKE '%{$keyw}%'))");
			$sql->execute();
			$sql2 = $db->prepare("SELECT * FROM `shops` WHERE 
							(`promoted` = '1' AND `activation` = '1') AND 
							((`category` LIKE '%{$keyw}%') OR 
							(`title` LIKE '%{$keyw}%') OR 
							(`description` LIKE '%{$keyw}%') OR 
							(`tags` LIKE '%{$keyw}%'))");
			$sql2->execute();
		}
		else
		{
			echo'<title>Cauta "'.$item.'" - '.WEBNAME.'</title>';
			$sql = $db->prepare("SELECT * FROM shops WHERE category = '$item' AND promoted = 0 AND activation = 1");
			$sql->execute();
			/*******************************************/
			$sql2 = $db->prepare("SELECT * FROM shops WHERE category = '$item' AND promoted = 1 AND activation = 1");
			$sql2->execute();
		}
		echo'<div class="col-md-9">
        <div class="row products products-big">';
		/******************************************/
		foreach ($sql2 as $row2) 
		{
			$id = $row2['id'];
			$title = $row2['title'];
			$name = $row2['name'];
			$userid = $row2['userid'];
			$description = $row2['description'];
			$price = $row2['price'];
			$photo = $row2['photo'];
			$option = $row2['priceoption'];
			$promoteds = $row2['promoted'];
			$verified = $row2['verified'];
			$titlu = curata_url($row2['title']);
			$category = $row2['category'];
			$short_desc = $row2['short_desc'];
			$video = $row2['video'];
			$demo = $row2['demo_link'];
			$currency = $row2['currency'];
			/***************************/
			if($currency = 'Euro') $currency_type = '&euro;';
			else if($currency = 'RON') $currency_type = 'RON';
			/***************************/
			echo"<div class='col-lg-4 col-md-6'><div class='product-shop-box'><div class='product'>";
			echo"<div class='image'><a href='".URL."shop/$id-$titlu'><img src='".URL."uploads/shops/mici/$photo' alt=''></img></a></div>";
			echo"<img src='".URL."images/promoted.png' class='promoted'></img>";
			echo"<div class='text'><h2 style='font-size: 14px;font-weight: 700;'><a href='".URL."shop/$id-$titlu'>$title";
			if($verified != 0) echo"<i class='fa fa-check-circle' style='padding-left:4px;color:#097BE8;'></i>";
			echo"</a></h2>";
			/***************************/
			echo"<div style='float:left;margin-left:10px;'>";
			if($video != NULL) echo"<span data-toggle='tooltip' data-placement='top' title='Video' style='padding:5px;'><a href='' data-toggle='modal' data-target='#video-modal' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-video-camera'></i></a></span>";
			if($demo != NULL) echo"<a href='$demo' target='_blank' data-toggle='tooltip' data-placement='top' title='Demo' style='padding:5px;border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-desktop'></i></a>";
			echo"<span style='padding:5px;'><a href='".URL."shop/addfav/$id' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em' data-toggle='tooltip' data-placement='top' title='Adauga la favorite' style='padding:5px;'><i class='fa fa-heart'></i></a></span>";
			echo"</div>";
			/**************************/
			if($price == 0) echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong><span class='badge badge-success'>Gratis</span></strong></div>";
			else echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong>$price$currency_type</strong></div>";
			echo"</div>";
			echo"</div></div></div>";
			/**************************/
			echo'<div id="video-modal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title">Vizualizare video</h4>
			<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
			<iframe frameborder="0" src="'.$video.'" style="margin: 0px; padding: 0px; width: 770px; height: 480px;"></iframe>
			</div>
			</div>
			</div>
			</div>';
		}
		foreach ($sql as $row) 
		{
			$id = $row['id'];
			$title = $row['title'];
			$name = $row['name'];
			$userid = $row['userid'];
			$description = $row['description'];
			$price = $row['price'];
			$photo = $row['photo'];
			$option = $row['priceoption'];
			$promoted = $row['promoted'];
			$verified = $row['verified'];
			$titlu = curata_url($row['title']);
			$category = $row['category'];
			$short_desc = $row['short_desc'];
			$video = $row['video'];
			$demo = $row['demo_link'];
			$currency = $row['currency'];
			/***************************/
			if($currency = 'Euro') $currency_type = '&euro;';
			else if($currency = 'RON') $currency_type = 'RON';
			/***************************/
			echo"<div class='col-lg-4 col-md-6'><div class='product-shop-box'><div class='product'>";
			echo"<div class='image'><a href='".URL."shop/$id-$titlu'><img src='".URL."uploads/shops/mici/$photo' alt=''></img></a></div>";
			echo"<div class='text'><h2 style='font-size: 14px;font-weight: 700;'><a href='".URL."shop/$id-$titlu'>$title";
			if($verified != 0) echo"<i class='fa fa-check-circle' style='padding-left:4px;color:#097BE8;'></i>";
			echo"</a></h2>";
			/***************************/
			echo"<div style='float:left;margin-left:10px;'>";
			if($video != NULL) echo"<span data-toggle='tooltip' data-placement='top' title='Video' style='padding:5px;'><a href='' data-toggle='modal' data-target='#video-modal' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-video-camera'></i></a></span>";
			if($demo != NULL) echo"<a href='$demo' target='_blank' data-toggle='tooltip' data-placement='top' title='Demo' style='padding:5px;border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em'><i class='fa fa-desktop'></i></a>";
			echo"<span style='padding:5px;'><a href='".URL."shop/addfav/$id' target='_blank' style='border:solid #afafaf 1px;background:#fff;padding:5px;font-size:0.8em' data-toggle='tooltip' data-placement='top' title='Adauga la favorite' style='padding:5px;'><i class='fa fa-heart'></i></a></span>";
			echo"</div>";
			/**************************/
			if($price == 0) echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong><span class='badge badge-success'>Gratis</span></strong></div>";
			else echo"<hr><div style='float:right;margin-right:10px;font-size:13px;'><strong>$price$currency_type</strong></div>";
			echo"</div>";
			echo"</div></div></div>";
			/**************************/
			echo'<div id="video-modal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title">Vizualizare video</h4>
			<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
			<iframe frameborder="0" src="'.$video.'" style="margin: 0px; padding: 0px; width: 770px; height: 480px;"></iframe>
			</div>
			</div>
			</div>
		    </div>';
		}
		echo"</div></div>";
	}
}
else if($page == 'addshop')
{	
	if(!isset($_SESSION['user'])) echo("<script>location.href = '".URL."autentificare'; alert('Trebuie sa fii autentificat pentru a putea adauga un anunt!');</script>");
	?>
	 <script language=javascript>
	function admSelectCheck(nameSelect)
	{
		console.log(nameSelect);
		if(nameSelect)
		{
			admOptionValue = document.getElementById("Altele").value;
			if(admOptionValue == nameSelect.value){
				document.getElementById("admDivCheck").style.display = "block";
			}
			else{
				document.getElementById("admDivCheck").style.display = "none";
			}
			
			admOptionValue = document.getElementById("SMS").value;
			if(admOptionValue == nameSelect.value){
				document.getElementById("admDivCheck2").style.display = "block";
			}
			else{
				document.getElementById("admDivCheck2").style.display = "none";
			}
		}
	}	
	</script><?php
	/****************************************/
	echo'<title>Adauga o resursa - '.WEBNAME.'</title>';
	echo'<div class="col-md-9">
        <div class="row products products-big">';
	echo"<form action='' method='post' enctype='multipart/form-data'>
	<div class='well'><strong>Nume si descriere</strong><hr>
			<div class='row'><label class='col-sm-2'>Nume</label>
			<div class='col-sm-10'><input class='form-control' name='titlu' placeholder='Nume'></input>
			<div class='helpline'>Numele creatiei, maxim 50 caractere, explicit si specific.</div></div>
			</div><br>
			
			<div class='row'><label class='col-sm-2'>Descriere pe scurt </label>
			<div class='col-sm-10'><textarea class='form-control'  name='short_desc' rows='3' placeholder='Descriere pe scurt'></textarea>
			<div class='helpline'>Maxim 150 caractere</div></div>
			</div> <br /><div class='row'><label class='col-sm-2'>Descriere </label>
			<div class='col-sm-10'><textarea id='textarea1' name='description' rows='5' placeholder='Descriere detaliata a produsului.'></textarea>
			<div class='helpline'>Camp nelimitat, produsul trebuie descris cat mai detaliat. Totodata, in acest camp se pot introduce imagini.</div></div>
			</div> <br />
			
			<div class='row'><label class='col-sm-2'>Tags (etichete):</label>
			<div class='col-sm-10'><textarea class='form-control' name='tags' rows='2' cols='55' placeholder='Etichete, cuvinte cheie'></textarea>
			<div class='helpline'>Maxim 15 cuvinte care acopera caracteristicile, stilul, optiunile produsului. Toate cuvintele cheie vor fi scrise cu litera mica, separate prin virgula.<br>
			Exemplu: template web, galerie foto, simplu, inchirieri, mini game, etc.</div></div>
			</div><br /><br />	
	</div>
	<div class='well'><strong>Previzualizare</strong><hr>
		<div class='row'><label class='col-sm-2'>Demo Link:</label>
		<div class='col-sm-10'><input class='form-control' name='demo' placeholder='Live demo'></input>
		<div class='helpline'>* (Optional) Link catre prezentarea live a proiectului.</div></div>
		</div><br />
		<div class='row'><label class='col-sm-2'>Imagine 1</label>
		<div class='col-sm-6'><input type='file' name='imag1' id='file' style='border:solid #afafaf 1px;background:#fff;padding:3px;'></div>
		</div> <br />
		<div class='row'><label class='col-sm-2'>Imagine 2</label>
		<div class='col-sm-6'><input type='file' name='imag2' id='file' style='border:solid #afafaf 1px;background:#fff;padding:3px;'></div>
		</div> <br />
		<div class='row'><label class='col-sm-2'>Imagine 3</label>
		<div class='col-sm-6'><input type='file' name='imag3' id='file' style='border:solid #afafaf 1px;background:#fff;padding:3px;'></div>
		</div> <br />
		<div class='row'><label class='col-sm-2'>Imagine 4</label>
		<div class='col-sm-6'><input type='file' name='imag4' id='file' style='border:solid #afafaf 1px;background:#fff;padding:3px;'><br>
		<div class='helpline'><b><font color=red>* Dimensiune trebuie sa fie cuprinse intre 200x200 - 748x454, in caz contrar, imaginile nu se vor inregistra!</b></font></div></div>
		</div> <br />
	</div>
	<div class='well'><strong>Plati</strong><hr>
		<nav id='myTab' role='tablist' class='nav nav-tabs'><a id='tab4-1-tab' data-toggle='tab' href='#tab4-1' role='tab' aria-controls='tab4-1' aria-selected='true' class='nav-item nav-link active show'> <i class='icon-star'></i>Contra cost</a>
		<a id='tab4-2-tab' data-toggle='tab' href='#tab4-2' role='tab' aria-controls='tab4-2' aria-selected='false' class='nav-item nav-link'>Gratuit</a></nav>
		<div id='nav-tabContent' class='tab-content'>
			<div id='tab4-1' role='tabpanel' aria-labelledby='tab4-1-tab' class='tab-pane fade active show'>
			<div class='row'><label class='col-sm-2'>Metoda de plata</label>
				<div class='col-sm-10'><select class='form-control' name='method' onchange='admSelectCheck(this);'>
					<option value='Paypal'>Paypal</option>
					<option value='Visa'>Visa</option>
					<option value='Mastercard'>Mastercard</option>
					<option id='SMS' value='SMS'>Mobilpay (SMS)</option>
					<option id='Altele' value='Altele'>Altele...</option>
				</select>
				<div class='helpline'>Selectati metoda de plata din lista pusa la dispozitie. In cazul selectiei 'Altele', specificati o metoda de plata diferita fata de cele listate.</div>
				<div id='admDivCheck' style='display:none;'>
				<textarea class='form-control' name='othermethod' rows='1' cols='25' placeholder='Alta metoda de plata'></textarea>
				</div>
				<br>
				<div id='admDivCheck2' style='display:none;'>
				<select class='form-control' name='network'>
					<option value='NULL' disabled selected='true'>Selecteaza o retea</option>
					<option value='Orange'>Orange</option>
					<option value='Vodafone'>Vodafone</option>
					<option value='Telekom'>Telekom</option>
					<option value='DigiMobil'>Digi Mobil</option>
				</select><br>
				</div></div>
				</div>
				<div class='row'><label class='col-sm-2'>Moneda:</label>
			<div class='col-sm-10'><select class='form-control' name='currency' onchange='admSelectCheck(this);'>
				<option value='Euro'>Euro</option>
				<option value='Visa'>RON</option>
			</select>
			<div class='helpline'>In cazul metodei de plata prin mobilpay (SMS), se va selecta obligatoriu moneda EURO.</div></div>
		</div><br>
		<div class='row'><label class='col-sm-2'>Pret:</label>
		<div class='col-sm-10'><input class='form-control' name='price' placeholder='Pretul produsului'></input>
		<div class='helpline'>Pretul produsului va fi stabilit in functie de tipul monezii selectate (Euro/Ron).</div></div>
		</div><br>
		</div>
		<div id='tab4-2' role='tabpanel' aria-labelledby='tab4-2-tab' class='tab-pane fade'>
			<div class='row'><label class='col-sm-2'>Download Link:</label>
			<div class='col-sm-10'><input class='form-control' name='download' placeholder='Link descarcare'></input>
			<div class='helpline'>Acest camp este obligatoriu.</div></div>
			</div>
		</div><br>
		
	</div></div>
	<div class='well'><strong>Categorie</strong><hr>
	<div class='row'><label class='col-sm-2'>Categoria</label>
		<div class='col-sm-10'><select class='form-control' name='category'>";
			$select = $db->prepare("SELECT * FROM `shop_category` ORDER BY `id`");
			$select->execute();
			foreach($select as $row)
			{
				$category = $row['category'];
				$notice = $row['notation'];
				echo"<option value='$notice'>$category</option>";
			}
		echo"</select></div></div>
	</div>
	<div class='well'><div class='row'><label class='col-sm-2'>Finalizare:</label>
			<div class='checkbox'>
				<label><input type='checkbox'> Accept regulamentul sectiunii 'Resurse'</label>
            </div><hr>
			<button class='btn btn-success' type='submit' name='add_products'>Adaug&#259;</button>
	</div></div><br>
		
	</form>";
	/***************************************************/
	if(isset($_POST['add_products']))
	{
		if(empty($_POST['titlu']) || empty($_POST['description']) || empty($_POST['short_desc'])) 
		{
			echo 'Toate campurile sunt obligatorii! Completeaza-le cu atentie pe toate!'; 
			$problem = 1;
		}
		/***********************************************/
		//if($_FILES['uploadedfile']['tmp_name'] != "")
		//{
		
			/*$title = curata_url($_POST['titlu']);
			$random = rand(0,999);
			$randd = "$title-$random";
			$ext1 = findexts($_FILES['uploadedfile']['name']); 
			
			$target_path = "up_files/$randd.$ext1";
			if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			}
			else echo "<small>A fost o eroare in uploadarea fisierului, va rugam reincercati!</small>";
			if(strlen($ext1) > 0){
			$ranext = "$randd".".$ext1";
			}
			else{
			$ranext = "";
			}
			$marime = number_format(((($_FILES['uploadedfile']['size'])/1024)/1024),2);*/
		//}
		/***********************************************/
		if($_FILES['imag1']['tmp_name'] != ""){
		shop_image("imag1","1");
		}else{ $imaginee1 = "no-image.png"; }
		if($_FILES['imag2']['tmp_name'] != ""){
		shop_image("imag2","2");
		}else{$imaginee2 = "no-image.png";}
		if($_FILES['imag3']['tmp_name'] != ""){
		shop_image("imag3","3");
		}else $imaginee3 = "no-image.png";
		if($_FILES['imag4']['tmp_name'] != ""){
		shop_image("imag4","4");
		}else $imaginee4 = "no-image.png";
		/***********************************************/
		$priceoption = ucfirst($_POST['method']);
		if($_POST['method'] == 'Altele') $priceoption = $_POST['othermethod'];
		
		$currency = ucfirst($_POST['currency']);
		$network = ucfirst($_POST['network']);
		$category = $_POST['category'];
		
		$titlu = $_POST['titlu'];
		$description = $_POST['description'];
		$short_desc = $_POST['short_desc'];
		$price = $_POST['price'];
		$demo = $_POST['demo'];
		$tags = $_POST['tags'];
		$name = $_SESSION['user'];
		$download = $_POST['download'];
		/***************************************/
		$sql = $db->prepare("SELECT * FROM members WHERE name = '$name'");
		$sql->execute();
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
		foreach($sql as $row) { $userid = $row['id'];}
		/***************************************/
		/*if(isset($_SESSION['gratis']))
		{
			$sql = $db->prepare("insert into shops (title,name,userid,description,short_desc,price,photo,priceoption,category,demo_link,currency,network,marime_fisier, link_fisier) values ('$titlu','$name','$userid','$description', '$short_desc', '$price', '$enter_link', '$priceoption', '$category', '$demo', '$currency', '$network','$marime','$randd')");
			$sql->execute();
		}
		else
		{*/
			$sql = $db->prepare("insert into shops (title,name,userid,description,short_desc,price,photo,photo2, photo3, photo4,priceoption,category,demo_link,currency,network,tags,data,link) values ('$titlu','$name','$userid','$description', '$short_desc', '$price', '$imaginee1', '$imaginee2', '$imaginee3', '$imaginee4', '$priceoption', '$category', '$demo', '$currency', '$network', '$tags', '$azi','$download')");
			$sql->execute();
		/*}*/
		//if($sql) echo("<script>location.href = '".URL."shop.php';</script>");
		//if($sql) echo'<center><b><font color=#04690B>Produsul a fost adaugat! Acesta necesita o aprobare din partea echipei administrative!</font></b></center>';
		if($sql) echo("<script>location.href = '".URL."shop'; alert('Produsul a fost adaugat! Acesta necesita o aprobare din partea echipei administrative!');</script>");
		/***************************************/
	}
	echo"</div></div>";
}
else if($page == 'view')
{
	if(isset($_GET['id']))
	{
		echo'<div class="col-md-9">';
		$id =$_GET['id'];
		/*******************************************/
		$sql = $db->prepare("SELECT * FROM shops WHERE id = $id");
		$sql->execute();
		/*******************************************/
		foreach($sql as $row)
		{
			$name = $row['name'];
			$userid = $row['userid'];
			$title = $row['title'];
			$description = $row['description'];
			$shortdesc = $row['short_desc'];
			$price = $row['price'];
			$photo = $row['photo'];
			$photo2 = $row['photo2'];
			$photo3 = $row['photo3'];
			$photo4 = $row['photo4'];
			$priceoption = $row['priceoption'];
			$promoted = $row['promoted'];
			$verified = $row['verified'];
			$activation = $row['activation'];
			$tags = $row['tags'];
			$email = $row['email'];
			$demo = $row['demo_link'];
			$video = $row['video'];
			$download = $row['link'];
			/**************/
			echo"<head><title>$title - ".WEBNAME."</title>
			<meta name='keywords' content='$tags $title'>
			<meta name='description' content='$shortdesc'></head>";
			/*echo '<div class="well"><div class="row">
              <div class="col-md-12">
                <div class="heading">
                  <h2>'.$title.'</h2>
                </div>
                <p class="lead no-mb">'.$shortdesc.'.</p>
              </div>
            </div></div>';*/
			echo'<div class="row portfolio-project">';
			echo'<div class="well" style="padding-bottom:30px;"> <div class="heading">
                  <center><h2>Imagini</h2></center>
                </div><div class="project owl-carousel mb-4">';
				  if($photo != "no-image.png" || $photo == "no-image.png") echo'<div class="item"><img src="'.URL.'uploads/shops/'.$photo.'" alt="" class="img-fluid"></div>';
				  if($photo2 != "no-image.png" && $photo2 != "") echo'<div class="item"><img src="'.URL.'uploads/shops/'.$photo2.'" alt="" class="img-fluid"></div>';
				  if($photo3 != "no-image.png" && $photo3 != "") echo'<div class="item"><img src="'.URL.'uploads/shops/'.$photo3.'" alt="" class="img-fluid"></div>';
				  if($photo4 != "no-image.png" && $photo4 != "") echo'<div class="item"><img src="'.URL.'uploads/shops/'.$photo4.'" alt="" class="img-fluid"></div>';
				echo'</div>';
				echo'<div style="margin-top:60px;"><center>';
				if($demo != NULL) echo'<a href="'.$demo.'" target="_blank" class="btn btn-outline-primary"><i class="fa fa-desktop"></i> Demo</a>';
				echo'<a href="'.URL.'shop/addfav/'.$id.'" class="btn btn-outline-primary" style="margin-left:5px;"><i class="fa fa-heart"></i> Adauga la favorite</a>';
				if($video != NULL) echo'<a href="" data-toggle="modal" data-target="#video-modal" class="btn btn-outline-primary" style="margin-left:5px;"><i class="fa fa-heart"></i> Video</a>';
				if($price == 0) echo'<a href="'.$download.'" class="btn btn-outline-primary" style="margin-left:5px;" target=_blank><i class="fa fa-cloud-download"></i> Download</a>';
				echo'</center></div>';
				/*************************************/
				echo'<div id="video-modal" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg">
					<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Vizualizare video</h4>
					<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
					</div>
					<div class="modal-body">
					<iframe frameborder="0" src="'.$video.'" style="margin: 0px; padding: 0px; width: 770px; height: 480px;"></iframe>
					</div>
					</div>
					</div>
					</div>';
				/**********************************/
				echo'</div>';
			 echo'<div class="well" style="padding-bottom:30px;overflow:hidden;position:relative;width:100%;">';
			 echo'<div class="heading">
                  <h3>Descrierea produsului</h3>
                </div>';
			echo'<p>'.$description.'</p>';
			 echo'</div>';
			echo "<hr>";
			if(isset($_SESSION['user'])) 
			{ 
				if($_SESSION['admin']!=0)
				{
					echo'<div class="well" style="padding-bottom:30px;overflow:hidden;position:relative;width:100%;"> <div class="heading"><h3>Comenzile administratorului</h3></div>';
			
					echo "<a href='".URL."shop/admin/delete-$id' class='btn btn-danger'>Sterge</a>";
					echo "<a href='".URL."shop/admin/verify-$id' class='btn btn-danger' style='margin-left:3px;'>Verificat</a> ";
					echo "<a href='".URL."shop/admin/promoted-$id' class='btn btn-danger' style='margin-left:3px;'>Promoveaza</a> ";
					echo "<a href='".URL."shop/admin/normal-$id' class='btn btn-danger' style='margin-left:3px;'>Normal</a> ";
					if($activation == 0) echo"<a href='".URL."shop/admin/activate-$id' class='btn btn-danger' style='margin-left:3px;'>Activeaza</a>";
					else echo"<a href='".URL."shop/admin/disable-$id' class='btn btn-danger' style='margin-left:3px;'>Dezactiveaza</a>";
					 echo'</div>';
				}
			}
			echo'</div>';
		}
echo "</div>";
	}
}
else if($page == 'edit')
{
	if($_SESSION['admin']==0) echo("<script>location.href = '".URL."';</script>");
	/***************************************/
	if(isset($_GET['id']) && isset($_GET['delete']) && !isset($_GET['verify']) && !isset($_GET['promoted']) && !isset($_GET['normal']) && !isset($_GET['activated']) && !isset($_GET['disabled']))
	{
		$id =$_GET['id'];
		$delete = $db->prepare("DELETE FROM shops where id='$id'");
		$delete->execute();
		/*******************************************************/
		if($delete) echo "Anunt sters";
	}
	if(isset($_GET['id']) && !isset($_GET['delete']) && isset($_GET['verify']) && !isset($_GET['promoted']) && !isset($_GET['normal'])&& !isset($_GET['activated'])&& !isset($_GET['disabled']))
	{
		$id =$_GET['id'];
		$update = $db->prepare("UPDATE shops SET verified = 1 WHERE id = $id");
		$update->execute();
		/*******************************************************/
		if($update) echo("<script>location.href = '".URL."shop/$id';</script>");
	}
	if(isset($_GET['id']) && !isset($_GET['delete']) && !isset($_GET['verify']) && isset($_GET['promoted']) && !isset($_GET['normal'])&& !isset($_GET['activated'])&& !isset($_GET['disabled']))
	{
		$id =$_GET['id'];
		$update = $db->prepare("UPDATE shops SET promoted = 1 WHERE id = $id");
		$update->execute();
		/*******************************************************/
		if($update) echo("<script>location.href = '".URL."shop/$id';</script>");
	}
	if(isset($_GET['id']) && !isset($_GET['delete']) && !isset($_GET['verify']) && !isset($_GET['promoted']) && isset($_GET['normal'])&& !isset($_GET['activated'])&& !isset($_GET['disabled']))
	{
		$id =$_GET['id'];
		$update = $db->prepare("UPDATE shops SET verified = 0, promoted = 0 WHERE id = $id");
		$update->execute();
		/*******************************************************/
		if($update) echo("<script>location.href = '".URL."shop/$id';</script>");
	}
	if(isset($_GET['id']) && !isset($_GET['delete']) && !isset($_GET['verify']) && !isset($_GET['promoted']) && !isset($_GET['normal'])&& isset($_GET['activated'])&& !isset($_GET['disabled']))
	{
		$id =$_GET['id'];
		$update = $db->prepare("UPDATE shops SET activation = 1 WHERE id = $id");
		$update->execute();
		/*******************************************************/
		if($update) echo("<script>location.href = '".URL."shop/$id';</script>");
	}
	if(isset($_GET['id']) && !isset($_GET['delete']) && !isset($_GET['verify']) && !isset($_GET['promoted']) && !isset($_GET['normal'])&& !isset($_GET['activated'])&& isset($_GET['disabled']))
	{
		$id =$_GET['id'];
		$update = $db->prepare("UPDATE shops SET activation = 0 WHERE id = $id");
		$update->execute();
		/*******************************************************/
		if($update) echo("<script>location.href = '".URL."shop/$id';</script>");
	}
}
echo'</div></div></div>';
/*********************************/
Footer();
echo"</div></div></div></div>";
AnotherScripts();
if($page == "addshop")
{echo'<script type="text/javascript" src="'.URL.'js/wysiwyg/scripts/wysiwyg.js"></script>
	<script type="text/javascript" src="'.URL.'wysiwyg/scripts/wysiwyg-settings.js"></script>';
echo "<script> WYSIWYG.attach('textarea1'); </script>";
}
echo"</body>";