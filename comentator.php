<?php
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/****************************************/
$user = $_SESSION['user'];
/***************************************/
$select = $db->prepare("SELECT * FROM `members` WHERE `name` = '$user'");
$select->execute();
/***************************************/
if($_GET['page'] == "comment")
{
	if(isset($_POST['addcomm']))
	{
		if(isset($_GET['category']) && isset($_GET['id']) && isset($_GET['sectionname']))
		{
			$sectionname = $_GET['sectionname'];
			/***************************************/
			$select = $db->prepare("SELECT * FROM `members` WHERE `name` = '$user'");
			$select->execute();
			/***************************************/
			foreach($select as $row)
			{
				$rang = $row['rang'];
				$nickname = $row['name'];
				$userid = $row['id'];
				$comments = $row['comments'];
			}
			$type = $_GET['category'];
			$backid = $_GET['id'];
			
			$com_postat = $_POST['comentariu'];
		
			$categorie = $_GET['category'];;
			$id_selectare = $backid;
			
			$dataa = date("y-m-d H:i:s");
			$ip = $_SERVER['REMOTE_ADDR'];
			
			if($sectionname == 'tutorials')
			{
				$select = $db->prepare("SELECT * FROM `tutorials` WHERE `id` = '$backid'");
				$select->execute();
				$row2 = $select->fetch();
				$titlu = curata_url($row2['title']); 
				$url = "tutoriale/$backid/$titlu";
			}
			else if($sectionname == 'blog')
			{
				$select = $db->prepare("SELECT * FROM `stiri` WHERE `id` = '$backid'");
				$select->execute();
				$row2 = $select->fetch();
				$titlu = curata_url($row2['titlu']); 
				$url = "blog/$backid-$titlu";
			}
			else if($sectionname == 'forum')
			{
				$select = $db->prepare("SELECT * FROM `forum` WHERE `id` = '$backid'");
				$select->execute();
				$row2 = $select->fetch();
				$titlu = curata_url($row2['title']); 
				$url = "discutii/$backid/$titlu";
			}
			
			$sql = $db->prepare("INSERT INTO comments (comment,sectionid,categ,user,userid,data,ip,sectionname) VALUES ('$com_postat', '$id_selectare', '$categorie', '$user', '$userid', '$dataa', '$ip', '$sectionname')");
			$sql->execute();
			echo("<script>location.href = '$url';</script>");
		}
	}
}
/***************************************/
if($_GET['page'] == "delete")
{
	if(isset($_GET['delete']) && isset($_GET['rid']) && isset($_GET['userid']) && isset($_GET['type']))
	{
		if($_SESSION['admin'] == 0 && $_SESSION['editor'] == 0) 
echo("<script>location.href = '".URL."';</script>");

		$userid = mysql_real_Escape_String($_GET['userid']);
		$category = $_GET['type'];
		/***************************************/
		$select = $db->prepare("SELECT * FROM `members` WHERE `id` = '$userid'");
		$select->execute();
		/***************************************/
		foreach($select as $row)
		{
			$id = $row['id'];
			$comments = $row['comments'];
		}
		$escaped_id = mysql_real_Escape_String($_GET['rid']);
		$escaped_id_selectare = mysql_real_Escape_String($_GET['sectionid']);
		$update = $db->prepare("DELETE FROM comments where id='$escaped_id'");
		$update->execute();
		$updateuser = $db->prepare("UPDATE members SET `comments` = $comments-1 WHERE `id`=$userid");
		$updateuser->execute();
		
		if($category == 'blog')
			echo("<script>location.href = 'blog.php?category=view&id=$escaped_id_selectare';</script>");
		else if($category == 'discutii')
			echo("<script>location.href = 'discutii.php?category=view&id=$escaped_id_selectare';</script>");
		else
			echo("<script>location.href = 'tutorials.php?category=viewtut&type=$category&id=$escaped_id_selectare';</script>");
	}
}
?>