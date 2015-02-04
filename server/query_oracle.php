<?php

session_start(); 
require_once("init.php");
require_once("utils.php");
require_once("db_oracle/db_service.php");

$stmt = $pdo->prepare("select * from documents");
$stmt->execute();

$result_formated = array();

while($v = $stmt->fetch(PDO::FETCH_ASSOC)) {

	$tmp['field_1'] 	= $v['DOC_ID'];
	$tmp['field_2'] 	= $v['DOC_TITLE'];
	$tmp['field_3'] 	= $v['DOC_USER'];

	array_push($result_formated, $tmp);
}


echo json_encode($result_formated);
?>