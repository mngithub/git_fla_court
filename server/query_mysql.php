<?php

session_start(); 
require_once("init.php");
require_once("utils.php");
require_once("db_mysql/db_service.php");

$result_unformat = selectRecentDocuments();
$result_formated = array();

foreach ($result_unformat as $k => $v) {

	$tmp['id'] 			= $v['d_id'];
	$tmp['title'] 		= $v['d_title'];
	$tmp['desc'] 		= $v['d_desc'];
	$tmp['user'] 		= $v['u_name'];
	// yyyy-mm-dd hh:mm:ss
	$tmp['create_time'] = $v['d_create_time'];
	
	$tmp['field_1'] 	= $v['d_id'];
	$tmp['field_2'] 	= $v['d_title'];
	$tmp['field_3'] 	= $v['u_name'];

	array_push($result_formated, $tmp);
}

echo json_encode($result_formated);
?>