<?php

	$conn = new mysqli('localhost', 'coachmic_sfi_dev', 'tLAnU+%2+jSqIUb[', 'coachmic_sfi');
	
	$search = $_GET['term'];
	
	$query = $conn->query("SELECT * FROM `member` WHERE `LastName` LIKE '%$search%' ORDER BY `LastName` ASC") or die(mysqli_connect_errno());
	
	$list = array();
	$rows = $query->num_rows;
	
	if($rows > 0){
		while($fetch = $query->fetch_assoc()){
			$data['value'] = $fetch['LastName'].' '.' '.$fetch['FirstName']; 
			array_push($list, $data);
		}
	}
	
	echo json_encode($list);
?>