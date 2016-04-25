<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    
	if(!AllowUser(array(1,2))){
		redirect("index.php");
	}

	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		
				// var_dump($inputs);
				// die;
		$errors="";
		
		if (empty($inputs['description'])){
			
		}


		if($errors!=""){

			Alert("Please fill in the following fields: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("opp_invoices.php"."?id={$inputs['opp_id']}");
			}
			else{
				redirect("opp_invoices.php"."?id={$inputs['opp_id']}");
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['opp_purchase'])){
				//Insert
				$inputs=$_POST;
				
				//$inputs['name']=$_POST['name'];
				$inputs['opp_id']=$inputs['po_id'];
				unset($inputs['po_id']);
				unset($inputs['opp_purchase']);
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs['date_uploaded']=$now->format('Y-m-d H:i:s a');
				$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['user']=$userid;
				$inputs['cat_id']=2;

				$item=$_POST['opp_purchase'];
				$page="purchase";

				var_dump($inputs);
				die;

				$inputs['file_name']=$_FILES['file']['name'];
				$filename=$file_id.getFileExtension($_FILES['file']['name']);
				$file_id=$inputs['opp_id']. "_" . "Purchase" . "_" . (new \DateTime())->format('Y-m-d-H-i-s');
				$name=$file_id.getFileExtension($_FILES['file']['name']);
				move_uploaded_file($_FILES['file']['tmp_name'],"uploads/Files/".$inputs['file_name']);

				// var_dump($inputs['file_name'])
				// die;
				unset($inputs['file']);
				$con->myQuery("INSERT INTO files(title,description,document,user_id,date_uploaded,date_modified,opp_id,cat_id) VALUES (:title, :description,:file_name, :user, :date_uploaded, :date_modified, :opp_id, :cat_id)", $inputs);	
				$notes="Uploaded purchase order file."; 
				$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, page, item, action_date) VALUES (:opp_id, '$userid', '$notes', '$page', '$item', NOW())", $inputs);
				
				$testing = error_reporting(E_ALL);
				Alert("Save succesful","success");
				

			}
			else{
				// var_dump("update");
				// die;
				//Update
				$inputs=$_POST;
				$inputs['opp_id']=$inputs['po_id'];
				$item=$inputs['po_id'];
				$page="purchases";
				unset($inputs['po_id']);
				
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['user']=$userid;

				var_dump($inputs['file']);
				// var_dump("update details");
				die;

				
				if(0 == filesize($_FILES['file']['tmp_name'])){
					// var_dump("update file details");
					// die;
					unset($inputs['file']);
					$con->myQuery("UPDATE files SET title=:title,description=:description,user_id=:user,date_modified=:date_modified,opp_id=:opp_id WHERE id=:opp_purchase",$inputs);
					$notes="Updated purchase order details."; 
					$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, page, item, action_date) VALUES (:opp_id, '$userid', '$notes', '$page', '$item', NOW())", $inputs);
					
					Alert("Update successful","success");
				}
				else{
					var_dump("update file only");
					die;
					$inputs['file_name']=$_FILES['file']['name'];
					// var_dump($inputs['file_name']);
					// die;
					$filename=$file_id.getFileExtension($_FILES['file']['name']);
					$file_id=$inputs['opp_id']. "_" . "Purchase" . "_" . (new \DateTime())->format('Y-m-d-H-i-s');

					// $name=$file_id.getFileExtension($_FILES['file']['name']);
					move_uploaded_file($_FILES['file']['tmp_name'],"uploads/Files/".$inputs['file_name']);
					
					// var_dump($inputs);
					// die;

					$con->myQuery("UPDATE files SET title=:title,description=:description,document=:file_name,user_id=:user,date_modified=:date_modified,opp_id=:opp_id WHERE id=:opp_quote",$inputs);
					$notes="Updated purchase order file."; 
					$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, action_date) VALUES ('$opp_id', '$userid', '$notes', NOW())", $inputs);
					Alert("Update successful","success");
				}
					
				
			}

			
			redirect("opp_purchases.php"."?id={$inputs['opp_id']}");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>