<?php

session_start(); 
require_once("init.php");
require_once("utils.php");
//require_once("db_microsoft_sql/db_service.php");

$result_formated = array();
echo json_encode($result_formated);

/*
$result_unformat = selectRecentDocuments();
$result_formated = array();

foreach ($result_unformat as $k => $v) {

	$tmp['id'] 			= $v['d_id'];
	$tmp['title'] 		= $v['d_title'];
	$tmp['desc'] 		= $v['d_desc'];
	$tmp['user'] 		= $v['u_name'];
	// yyyy-mm-dd hh:mm:ss
	$tmp['create_time'] = $v['d_create_time'];

	array_push($result_formated, $tmp);
}

echo json_encode($result_formated);
*/
?>