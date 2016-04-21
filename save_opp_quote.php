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
		
		$errors="";
		
		if($errors!=""){

			Alert("Please fill in the following fields: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_quotes.php");
			}
			else{
				redirect("frm_quotes.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['opp_quote'])){
				//Insert
				$inputs=$_POST;
				
				//$inputs['name']=$_POST['name'];
				
				$inputs['opp_id']=$inputs['quote_id'];
				unset($inputs['quote_id']);
				unset($inputs['opp_quote']);
				// unset($inputs['opportunity_name']);
				$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['user']=$userid;
				// var_dump($inputs);
				// die;
				// var_dump($inputs);
				// die;

				if(0 == filesize($_FILES['file']['tmp_name'])){
					$name="Default.jpg";
				}
				else
				{

					$allowed =  array('doc', 'docx', 'xls', 'xlsx', 'xlsx');
					$filename = $_FILES['file']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(!in_array($ext,$allowed) ) 
					{
    					Alert("Invalid file type.","danger");
    					redirect("frm_quotes.php");
    					die();
					}

					$file_id=$_POST['opportunity_name']. "_" . "Quotation" . "_" . (new \DateTime())->format('Y-m-d-H-i-s');

					$name=$file_id.getFileExtension($_FILES['file']['name']);
					//$tmp_name = $_FILES['file']['tmp_name'];
				
					move_uploaded_file($_FILES['file']['tmp_name'],"uploads/Documents/".$name);
				}
				unset($inputs['opportunity_name']);
				// var_dump($inputs);
				// die;
				
				$con->myQuery("INSERT INTO quotes(title,opportunity_name, user_name, description, document) VALUES (:title,:opp_id,:user,:description, :file)", $inputs);	

				

				$inputs['notes']="Added a quote (".$inputs['title'].")";
				unset($inputs['file']);
				unset($inputs['title']);
				unset($inputs['description']);
				unset($inputs['opportunity_name']);

				// var_dump($inputs['notes']);
				// die;

				$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, action_date) VALUES (:opp_id, :user, :notes, NOW())", $inputs);
								
				$testing = error_reporting(E_ALL);
				Alert("Save successful","success");
				

			}
			else{
				
				$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['user']=$userid;

				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs['opp_id']=$inputs['quote_id'];
				unset($inputs['quote_id']);
				unset($inputs['opportunity_name']);

				
				
				if($inputs['file']==""){
				//var_dump("Update fields only");				
				unset($inputs['file']);
				// var_dump($inputs);
				// die;
				
				$con->myQuery("UPDATE quotes SET title=:title, opportunity_name=:opp_id, description=:description, user_name=:user, date_modified=:date_modified WHERE id=:opp_quote",$inputs);

					//$con->myQuery("UPDATE quotes SET opportunity_name=:opportunity_name, description=:description, date_modified=NOW()WHERE id=:opp_quote",$inputs);
				
				$inputs['notes']="Modified quote (".$inputs['title'].")";
					unset($inputs['opp_quote']);
					unset($inputs['date_modified']);
					unset($inputs['description']);					
					unset($inputs['title']);
					
				// var_dump($inputs);
				// die;				

				$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, action_date) VALUES (:opp_id, :user, :notes, NOW())", $inputs);
					Alert("Update successful","success");
				}
				else{

				// var_dump("Update file only");
				// die;

				// 	$allowed =  array('doc', 'docx', 'xls', 'xlsx', 'xlsx');
				// 	$filename = $_FILES['file']['name'];
				// 	$ext = pathinfo($filename, PATHINFO_EXTENSION);
				// 	if(!in_array($ext,$allowed) ) 
				// 	{
    // 					Alert("Invalid file type.","danger");
    // 					redirect("opp_quotes.php"."?id={$inputs['opp_id']}");
    // 					die();
				// 	}

				// $file_id=$_POST['opportunity_name']. "_" . "Quotation" . "_" . (new \DateTime())->format('Y-m-d-H-i-s');

				// $name=$file_id.getFileExtension($_FILES['file']['name']);
				//$tmp_name = $_FILES['file']['tmp_name'];
				
				//move_uploaded_file($_FILES['file']['tmp_name'],"uploads/Documents/".$name);
				// var_dump($inputs);
				// die;
				$con->myQuery("UPDATE quotes SET title=:title, opportunity_name=:opp_id, description=:description, date_modified=:date_modified,user_name=:user, document=:file WHERE id=:opp_quote",$inputs);
				//$con->myQuery("UPDATE quotes SET title=:title, opportunity_name=:opportunity_name, description=:description, user_name=:user, date_modified=:date_modified WHERE id=:opp_quote",$inputs);

				Alert("Update successful","success");
				}
					
				
			}
			
			redirect("opp_quotes.php"."?id={$inputs['opp_id']}");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>