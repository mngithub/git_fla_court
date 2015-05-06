<?php
// ------------------------------------------------
// ------------------------------------------------
// INIT
// ------------------------------------------------
// ------------------------------------------------
$conf['mysql']['hostname'] = '10.32.84.1'; 
$conf['mysql']['username'] = 'appoint'; 
$conf['mysql']['password'] = 'appoint';
$conf['mysql']['database'] = 'ptymc';

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
function selectTodayDocuments(){
	
	global $conf;
	global $db_conn;
	
	$date_start = date("Y-m-d");
	
	$sql = "select * from v_appointment d where d.date_appoint = '$date_start' order by d.time_appoint asc";
	$result = queryArray($sql);
	return $result;
}

$result_unformat = selectTodayDocuments();
$result_formated = array();

foreach ($result_unformat as $k => $v) {

	// yyyy-mm-dd hh:mm:ss
	
	$tmp['id'] 				= $v['run_id'];
	$tmp['create_time'] 	= $v['date_appoint'];
	
	// กำหนด field ที่ใช้ เปรียบเทียบเวลา hh:mm or hh.mm
	$tmp['compare_time'] 	= $v['time_appoint'];

	$tmp['field_1'] 		= $v['case_no'];
	$tmp['field_2'] 		= $v['pros_desc'];
	$tmp['field_3'] 		= $v['accu_desc'];
	$tmp['field_4'] 		= $v['time_appoint'];
	$tmp['field_5'] 		= $v['room_id'];
	$tmp['field_6'] 		= $v['app_name'];
	$tmp['field_7'] 		= $v['status_name'];
	$tmp['field_8'] 		= $v['status_name'];

	array_push($result_formated, $tmp);
}
// ------------------------------------------------
// ------------------------------------------------
echo json_encode($result_formated);
// ------------------------------------------------
// ------------------------------------------------
?>