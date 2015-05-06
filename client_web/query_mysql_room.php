<?php

require_once("init.php");

// ------------------------------------------------
// ------------------------------------------------
// CONNECT
// ------------------------------------------------
// ------------------------------------------------

$db_conn 	= new mysqli($conf['mysql']['hostname'], 
							$conf['mysql']['username'], 
							$conf['mysql']['password'], 
							$conf['mysql']['database']
						);
						
if ($db_conn->connect_error) {
	trigger_error('Database connection failed: '  . $db_conn->connect_error, E_USER_ERROR);
}
$db_conn->set_charset("utf8");

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

// ------------------------------------------------
// ------------------------------------------------
// QUERY
// ------------------------------------------------
// ------------------------------------------------

// ดึงข้อมูลเฉพาะวันที่ปัจจุบัน
function selectNextDocuments($room){
	
	global $conf;
	global $db_conn;
	
	$date_start = date("Y-m-d") . ' 00:00:00';
	$date_end = date("Y-m-d") . ' 23:59:59';
	
	$sql = "select * from documents d where d.d_create_time >= '$date_start' and d.d_create_time <= '$date_end' and d.d_create_time >= now() and field_5='$room' order by d.d_create_time asc limit 0,2";
	$result = queryArray($sql);
	return $result;
}
$room = '';
if(isset($_REQUEST['r'])) $room = $_REQUEST['r'];

$result_unformat = selectNextDocuments($room);
$result_formated = array();

foreach ($result_unformat as $k => $v) {
	
	// yyyy-mm-dd hh:mm:ss
	
	$tmp['id'] 				= $v['d_id'];
	$tmp['create_time'] 	= $v['d_create_time'];
	
	$tmp['field_1'] 		= $v['field_1'];
	$tmp['field_2'] 		= $v['field_2'];
	$tmp['field_3'] 		= $v['field_3'];
	$tmp['field_4'] 		= $v['field_4'];
	$tmp['field_5'] 		= $v['field_5'];

	array_push($result_formated, $tmp);
}
// ------------------------------------------------
// ------------------------------------------------
echo json_encode($result_formated);
// ------------------------------------------------
// ------------------------------------------------
?>