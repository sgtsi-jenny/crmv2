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
		if (empty($inputs['subject'])){
			$errors.="Enter opportunity name. <br/>";
		}


		//var_dump($inputs);
		//die;

		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_to_do");
			}
			else{
				redirect("frm_to_do.php?id=".urlencode($inputs['id']));
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

				//$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs['date_created']=$now->format('Y-m-d H:i:s a');
				$inputs['sdate']=$inputs['start_date'].' '.$inputs['start_time'].':00';
				$inputs['assigned_to']=$_SESSION[WEBAPP]['user']['id'];
				//$inputs['edate']=$inputs['end_date'].' '.$inputs['end_time'].':00';
				
				unset($inputs['start_date']);
				unset($inputs['start_time']);
				$inputs['activity_type']=1;
				$inputs['allDay']='false';
				// unset($inputs['end_date']);
				// // unset($inputs['end_time']);
				// var_dump($inputs);
				// die;
				$con->myQuery("INSERT INTO events(subject,assigned_to,start_date,due_date,event_stat,activity_type,event_place,priority,description,date_created,allDay) VALUES(:subject,:assigned_to,:sdate,:due_date,:status,:activity_type,:event_place,:priority,:description,:date_created,:allDay)",$inputs);								

				Alert("Save successful","success");
				redirect("calendar_list.php");
			}
			else{
				//Update

				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs['sdate']=$inputs['start_date'].' '.$inputs['start_time'].':00';
				unset($inputs['start_date']);
				unset($inputs['start_time']);
				$inputs['activity_type']=1;
				 $inputs['allDay']='false';
				 $inputs['assigned_to']=$_SESSION[WEBAPP]['user']['id'];
				// var_dump($inputs);
				// die;

				$con->myQuery("UPDATE events SET subject=:subject,assigned_to=:assigned_to,start_date=:sdate,due_date=:due_date,event_stat=:status,activity_type=:activity_type,event_place=:event_place,priority=:priority,description=:description,date_modified=:date_modified,allDay=:allDay WHERE id=:id",$inputs);
				Alert("Update successful","success");
				redirect("calendar_list.php");
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