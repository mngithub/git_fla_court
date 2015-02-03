<?php

$db_host	=$conf["server_hostname"]; 
$db_user	=$conf["server_username"]; 
$db_pass	=$conf["server_password"];
$db_name	=$conf["server_database"];

$db_conn 	= new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($db_conn->connect_error) {
	trigger_error('Database connection failed: '  . $db_conn->connect_error, E_USER_ERROR);
}
$db_conn->set_charset("utf8");

// count total database execution
$db_execute_count = 0;

// -----------------------------------------------------------
// -----------------------------------------------------------
// mysqli

// [insert]
// return inserted id
// can determine result by if($id > 0)

// [update, delete]
// return affected rows
// can determine result by 
// if($result > 0) 	: success with required affected row.
// if($result >= 0) : success with does not required affected row.

// [select]
// return the first single row
// return null if not found
// can determine result by if($result)
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
// -----------------------------------------------------------
// -----------------------------------------------------------
?>