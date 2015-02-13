<?php

session_start(); 
require_once("init.php");
require_once("utils.php");
require_once("db_mysql/db_service.php");

$result_unformat = selectRecentDocuments();
$result_formated = array();

foreach ($result_unformat as $k => $v) {

	// yyyy-mm-dd hh:mm:ss
	
	$tmp['id'] 		= $v['d_id'];
	$tmp['create_time'] = $v['d_create_time'];

	$tmp['field_1'] 	= $v['field_1'];
	$tmp['field_2'] 	= $v['field_2'];
	$tmp['field_3'] 	= $v['field_3'];
	$tmp['field_4'] 	= $v['field_4'];
	$tmp['field_5'] 	= $v['field_5'];
	$tmp['field_6'] 	= $v['field_6'];
	$tmp['field_7'] 	= $v['field_7'];
	$tmp['field_8'] 	= $v['field_8'];

	array_push($result_formated, $tmp);
}

echo json_encode($result_formated);
?>