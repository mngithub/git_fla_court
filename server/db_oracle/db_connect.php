<?php

$db_host	=$conf['oracle']['hostname']; 
$db_user	=$conf['oracle']['username']; 
$db_pass	=$conf['oracle']['password'];
$db_name	=$conf['oracle']['database'];

$conn_oracle = oci_connect($conf['oracle']['username'], $conf['oracle']['password'], $conf['oracle']['hostname'], 'utf8');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// -----------------------------------------------------------
// -----------------------------------------------------------
/*
function queryDB($sql){

	global $db_conn;
	global $db_execute_count;
	$db_execute_count++;
	
	try{
		$db_result 	= $db_conn->query($sql);
		
		if($db_result === false) {
			return null;
		}
		$db_result->data_seek(0);
		while($row = $db_result->fetch_assoc()){
			return $row;
		}
		
	}catch(Exception $e){}
	
	return null;
}

// return result in array
// determine by if(sizeof($result) > 0)
function queryArray($sql){

	global $db_conn;
	global $db_execute_count;
	$db_execute_count++;
	
	$temp_array = array();
	
	try{
		$db_result 	= $db_conn->query($sql);
		
		if($db_result === false) {
			return $temp_array;
		}
		$db_result->data_seek(0);
		while($row = $db_result->fetch_assoc()){
			array_push($temp_array,$row);
		}
		
	}catch(Exception $e){}
	
	return $temp_array;
}
*/
// -----------------------------------------------------------
// -----------------------------------------------------------
?>