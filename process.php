<?php
include('support/config.php');

$type = $_POST['type'];


if($type == 'fetch')
{
	$events = array();
	$query = $con->query("SELECT id,subject,start_date,end_date,allDay FROM events");
	while($fetch = $query->fetch(PDO::FETCH_ASSOC))
	{
	$e = array();
    $e['id'] = $fetch['id'];
    $e['title'] = $fetch['subject'];
    $e['start'] = $fetch['start_date'];
    $e['end'] = $fetch['end_date'];

    $allday = ($fetch['allDay'] == "true") ? true : false;
    $e['allDay'] = $allday;

    array_push($events, $e);
	}
	echo json_encode($events);
}

?>