<?php
// ------------------------------------------------
// ------------------------------------------------
// INIT
// ------------------------------------------------
// ------------------------------------------------
// disable all error report
error_reporting(0);
//error_reporting(E_ALL ^ E_NOTICE);
// ------------------------------------------------
// ------------------------------------------------
// QUERY
// ------------------------------------------------
// ------------------------------------------------



$result_formated = array();

for($i=0;$i<5;$i++){

	// yyyy-mm-dd hh:mm:ss
	
	$tmp['id'] 				= $v['run_id'];
	$tmp['create_time'] 	= $v['date_appoint'];
	
	// กำหนด field ที่ใช้ เปรียบเทียบเวลา hh:mm or hh.mm
	$tmp['compare_time'] 	= $v['time_appoint'];

	$tmp['field_1'] 		= 'อย.4638/2558';
	$tmp['field_2'] 		= 'สำนักงานอัยการ สำนักอัยการสูงสุด (สำนักอัยการพิเศษฝ่ายคดียาเสพติด)';
	$tmp['field_3'] 		= 'สำนักงานอัยการ สำนักอัยการสูงสุด (สำนักอัยการพิเศษฝ่ายคดียาเสพติด) สำนักงานอัยการ สำนักอัยการสูงสุด (สำนักอัยการพิเศษฝ่ายคดียาเสพติด)';
	$tmp['field_4'] 		= '13.4'. $i;
	$tmp['field_5'] 		= $i.'';
	$tmp['field_6'] 		= 'สมานฉันท์';
	$tmp['field_7'] 		= 'รอพิจารณาคดี';
	$tmp['field_8'] 		= '';

	array_push($result_formated, $tmp);
}
// ------------------------------------------------
// ------------------------------------------------
echo json_encode($result_formated);
// ------------------------------------------------
// ------------------------------------------------
?>