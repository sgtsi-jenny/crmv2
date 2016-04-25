<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	if(empty($_GET['id']) || empty($_GET['t'])){
		redirect('index.php');
		die;
	}
	else
	{

		$table="";
		switch ($_GET['t']) {
			case 'org':
				$table="organizations";
				$page="organizations.php";
				break;
			case 'opp':
				$table="opportunities";
				$page="opportunities.php";
				break;
			case 'org_opp':
				// var_dump($_GET['org_id']);
				// die;
				$table="opportunities";
				$page="org_opp.php?id={$_GET['org_id']}";
				break;
			case 'prod':
				$table="products";
				$page="products.php";
				break;
			case 'oprod':
				// var_dump($_GET['opp_id']);
				// var_dump($_GET['id']);
				// die;
				$table="opp_products";
				$page="opp_prods.php?id={$_GET['opp_id']}";
				break;
			case 'ocon':
				$table="opp_contacts";
				$page="opp_contact_persons.php?id={$_GET['opp_id']}";
				break;
			case 'odocs':
				$table="documents";				
				$page="opp_documents.php?id={$_GET['opp_id']}";
				break;
			case 'oquotes':
				// var_dump($_GET['opp_id']);
				// var_dump($_GET['id']);
				// die;
				$table="files";
				$page="opp_quotes.php?id={$_GET['opp_id']}";
				break;	
			case 'opurchase':
				$table="files";
				$page="opp_purchases.php?id={$_GET['opp_id']}";
				break;
			case 'oinvoice':
				$table="files";
				$page="opp_invoices.php?id={$_GET['opp_id']}";
				break;
			case 'other':
				$table="files";
				$page="opp_others.php?id={$_GET['opp_id']}";
				break;
			case 'eve':
				$table="events";
				$page="calendar_list.php";
				break;
			case 'opp_eve':
				// var_dump($_GET['org_id']);
				// var_dump($_GET['id']);
				// die;
				$table="events";
				$page="opp_events.php?id={$_GET['org_id']}";
				break;
			case 'l':
				$table="locations";
				$page="locations.php";
				break;
			case 'man':
				$table="manufacturers";
				$page="manufacturers.php";
				break;
			case 'co':
				$table="contacts";
				$page="contacts.php";
				break;
			case 'qo':
				$table="quotes";
				$page="quotes.php";
				break;
			case 'do':
				$table="documents";
				$page="documents.php";
				break;
			case 'ut':
				$table="user_types";
				$page="settings.php";
				break;
			case 'os':
				$table="opp_statuses";
				$page="settings.php";
				break;
			case 'lo':
				$table="locations";
				$page="settings.php";
				break;
			case 'ra':
				$table="org_ratings";
				$page="settings.php";
				break;
			case 'dpmt':
				$table="departments";
				$page="settings.php";
				break;
			case 'orgt':
				$table="org_types";
				$page="settings.php";
				break;
			case 'oppt':
				$table="opp_types";
				$page="settings.php";
				break;
			case 'fu':
				$table="files";
				$page="assets.php";
				if(!empty($_GET['a'])){
					#asset_id
					$page="view_asset.php?id={$_GET['a']}";
				}
				break;
			default:
				redirect("index.php");
				break;
		}
		$con->myQuery("UPDATE {$table} SET is_deleted=1 WHERE id=?",array($_GET['id']));
		Alert("Delete Successful.","success");
		redirect($page);

		die();

	}
?>