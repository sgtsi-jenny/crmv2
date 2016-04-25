<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

		//Validate form inputs
		//$inputs=$_POST;
		// echo "<pre>";
		// print_r($inputs);
		// print_r($_FILES);
		// echo "</pre>";
		//die;
		// if(empty($_FILES['file']['name'])){
		// 	Alert("No file selected.","danger");
		// 	redirect("oquotes.php"."?id={$inputs['opp_id']}");
		// 	die();
		// }
		// elseif($_FILES['file']['error']<>0){
		// 	Alert("Invalid file selected.","danger");
		// 	redirect("oquotes.php"."?id={$inputs['opp_id']}");
		// 	die;
		// }
		

		try {  

		$con->beginTransaction();
				$inputs=$_POST;
     			$inputs['opp_id']=$inputs['quote_id'];
				unset($inputs['quote_id']);
				unset($inputs['opp_quote']);
				$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['user']=$userid;

			     // var_dump($inputs);
			     // die;
				//$inputs['file_name']=$_FILES['file']['name'];
				//unset($inputs['file']);
		// var_dump($inputs['file']);
		// die;
		$con->myQuery("INSERT INTO quotes(title,opportunity_name, user_name, description,document,date_uploaded) VALUES(:title,:opp_id,:user,:description,:file,NOW())",$inputs);
		$file_id=$con->lastInsertId();

		$filename=$file_id.getFileExtension($_FILES['file']['name']);
		move_uploaded_file($_FILES['file']['tmp_name'],"opp_files/".$filename);
		$con->myQuery("UPDATE quotes SET file_location=? WHERE id=?",array($filename,$file_id));
		Alert("File Added","success");
				
		//insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Uploaded ({$inputs['file_name']}) to company files.");

		$con->commit();
		 redirect("oquotes.php"."?id={$inputs['opp_id']}");
		die;
		} catch (Exception $e) {
		  $con->rollBack();
//		  echo "Failed: " . $e->getMessage();
		  Alert("Upload failed. Please try again.","danger");
		  redirect("oquotes.php"."?id={$inputs['opp_id']}");
		  die;
		}
	
	// redirect('index.php');
?>