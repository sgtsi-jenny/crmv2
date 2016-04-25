<?php
	require_once("support/config.php");
		if(!isLoggedIn()){
			toLogin();
			die();
		}

	if(!empty($_GET['id'] && !empty($_GET['type']))){
		if(is_numeric($_GET['id'])){
			switch ($_GET['type']) {
				
				case 'q':
					# code...
					$sql="SELECT document,file_location FROM quotes WHERE id=? AND is_deleted=0";
					$file=$con->myQuery($sql,array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
					if(empty($file)){
						die;
					}
					$location="comp_files/";
					//die;
					//$fp = fopen($location.$file['file_location'], 'rb');
					 header("Content-Type: application/octet-stream");
					header("Content-Disposition: attachment; filename=".$file['document']);
					header("Content-Length: " . filesize($location.$file['file_location']));
					readfile($location.$file['file_location']);
					break;
				default:
					# code...
					break;
			}
		}
	}
?>