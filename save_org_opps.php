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

		date_default_timezone_set('Asia/Manila');
		$now = new DateTime();

		$errors="";
		//var_dump($error);
		//die;

		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_opportunities.php");
			}
			else{
				redirect("frm_opportunities.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);				
				
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs['date_created']=$now->format('Y-m-d H:i:s a');
				//$inputs['org_id']=$inputs['org_id'];
				// var_dump($inputs);
				// die;
				$con->myQuery("INSERT INTO opportunities (opp_name,org_id,contact_id,opp_type,assigned_to,sales_stage,forecast_amount,description,expected_close_date,date_created,date_modified) VALUES(:opp_name,:org_id,:contact_id,:opp_type,:assigned_to,:sales_stage,:forecast_amount,:description,:expected_close_date,:date_created,:date_modified)",$inputs);				
				
				$inputs['id'] = $con->lastInsertId();
				//echo $lastId;
				Alert("Save successful","success");
				//redirect("opp_details.php"."?id={$inputs['id']}");

				$con->myQuery("INSERT INTO events (subject,contact_id,assigned_to,sales_stage,forecast_amount,description,expected_close_date,date_created,date_modified) VALUES(:opp_name,:contact_id,:assigned_to,:sales_stage,:forecast_amount,:description,:expected_close_date,:date_created,:date_modified)",$inputs);				
				
				//redirect("opportunities.php");
				redirect("org_opps.php"."?id={$inputs['org_id']}");
			}
			else{
				//Update
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				//var_dump($inputs);
				//die;
				$con->myQuery("UPDATE opportunities SET opp_name=:opp_name,org_id=:org_id,contact_id=:contact_id,opp_type=:opp_type,assigned_to=:assigned_to,sales_stage=:sales_stage,forecast_amount=:forecast_amount,description=:description,expected_close_date=:expected_close_date,date_modified=:date_modified WHERE id=:id",$inputs);
				
				//Alert("Update successful","success");
				redirect("org_opps.php"."?id={$inputs['org_id']}");
			}
			
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>	