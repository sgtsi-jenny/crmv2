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
		
		if (empty($inputs['description'])){
			
		}


		if($errors!=""){

			
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['opp_quote'])){
				// var_dump("add");
				// die;
				//Insert
				$inputs=$_POST;
				// var_dump($inputs['quote_id']);
				// 	die;
				//$inputs['name']=$_POST['name'];
				$inputs['opp_id']=$inputs['po_id'];
				unset($inputs['po_id']);
				unset($inputs['opp_quote']);
				$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['user']=$userid;
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs['date_uploaded']=$now->format('Y-m-d H:i:s a');
				$inputs['cat_id']=2;

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

					$file_id=$_POST['opp_id']. "_" . "Purchase Order" . "_" . (new \DateTime())->format('Y-m-d-H-i-s');

					$name=$file_id.getFileExtension($_FILES['file']['name']);
					//$tmp_name = $_FILES['file']['tmp_name'];
				
					move_uploaded_file($_FILES['file']['tmp_name'],"uploads/Documents/".$name);
				}
				//unset($inputs['opp_id']);
				// var_dump($inputs);
				// die;
				$con->myQuery("INSERT INTO files(title,description,user_id,date_uploaded,date_modified,document,opp_id,cat_id) VALUES (:title,:description,:user,:date_uploaded,:date_modified,:file,:opp_id,:cat_id)", $inputs);	

				

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
				// var_dump($inputs['opp_quote']);
				// die;
				
				$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['user']=$userid;

				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs['opp_id']=$inputs['po_id'];
				unset($inputs['po_id']);				
				//unset($inputs['file']);
				unset($inputs['opportunity_name']);

				// var_dump($inputs);
				// die;
				
				if($inputs['file']==""){
				//var_dump("Update fields only");				
				unset($inputs['file']);
				var_dump($inputs);
				die;
					
				
				$con->myQuery("UPDATE files SET title=:title,description=:description,user_id=:user,opp_id=:opp_id, date_modified=:date_modified WHERE id=:opp_quote",$inputs);

					//$con->myQuery("UPDATE quotes SET opportunity_name=:opportunity_name, description=:description, date_modified=NOW()WHERE id=:opp_quote",$inputs);
				//unset($inputs['quote_id']);
				
				$inputs['notes']="Modified quote (".$inputs['title'].")";
					unset($inputs['opp_quote']);
					unset($inputs['date_modified']);
					unset($inputs['description']);					
					unset($inputs['title']);
				// var_dump($inputs);
				// die;				

				//$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, action_date) VALUES (:opp_id, :user, :notes, NOW())", $inputs);
					Alert("Update successful","success");
				}
				else{
				//var_dump($inputs);
				// 	var_dump("Update file only");
				// die;

				// 	$allowed =  array('doc', 'docx', 'xls', 'xlsx', 'xlsx');
				// 	$filename = $_FILES['file']['name'];
				// 	$ext = pathinfo($filename, PATHINFO_EXTENSION);
				// 	if(!in_array($ext,$allowed) ) 
				// 	{
    // 					Alert("Invalid file type.","danger");
    // 					redirect("frm_purchases.php");
    // 					die();
				// 	}
				// $file_id=$_POST['opportunity_name']. "_" . "Quotation" . "_" . (new \DateTime())->format('Y-m-d-H-i-s');
				// $name=$file_id.getFileExtension($_FILES['file']['name']);
				// //$tmp_name = $_FILES['file']['tmp_name'];				
				// move_uploaded_file($_FILES['file']['tmp_name'],"uploads/Documents/".$name);

				$con->myQuery("UPDATE files SET title=:title,description=:description,user_id=:user,opp_id=:opp_id, date_modified=:date_modified,document=:file WHERE id=:opp_quote",$inputs);

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