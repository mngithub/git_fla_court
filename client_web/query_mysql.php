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

$result_unformat = queryDisplay();
$result_formated = array();

foreach ($result_unformat as $k => $v) {

	// yyyy-mm-dd hh:mm:ss
	$tmp['id'] 				= ($conf['display']['field_id'] == '')? '' : $v[$conf['display']['field_id']];
	$tmp['create_time'] 	= ($conf['display']['field_create_time'] == '')? '' : $v[$conf['display']['field_create_time']];
	
	// กำหนด field ที่ใช้ เปรียบเทียบเวลา hh:mm or hh.mm
	$tmp['compare_time'] 	= ($conf['display']['field_compare_time'] == '')? '00.00' : convertToCompareTime($v[$conf['display']['field_compare_time']]);

	$tmp['field_1'] 		= ($conf['display']['field_1'] == '')? '' : $v[$conf['display']['field_1']];
	$tmp['field_2'] 		= ($conf['display']['field_2'] == '')? '' : $v[$conf['display']['field_2']];
	$tmp['field_3'] 		= ($conf['display']['field_3'] == '')? '' : $v[$conf['display']['field_3']];
	$tmp['field_4'] 		= ($conf['display']['field_4'] == '')? '' : $v[$conf['display']['field_4']];
	$tmp['field_5'] 		= ($conf['display']['field_5'] == '')? '' : $v[$conf['display']['field_5']];
	$tmp['field_6'] 		= ($conf['display']['field_6'] == '')? '' : $v[$conf['display']['field_6']];
	$tmp['field_7'] 		= ($conf['display']['field_7'] == '')? '' : $v[$conf['display']['field_7']];
	$tmp['field_8'] 		= ($conf['display']['field_8'] == '')? '' : $v[$conf['display']['field_8']];

	array_push($result_formated, $tmp);
}
// ------------------------------------------------
// ------------------------------------------------
echo json_encode($result_formated);
// ------------------------------------------------
// ------------------------------------------------
?>