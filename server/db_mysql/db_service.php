<?php
include_once("db_connect.php");

// -------------------------------------------------
// -------------------------------------------------
function selectRecentDocuments(){
	
	global $conf;
	global $db_conn;
	
	$sql = "select * from documents d order by d.d_create_time desc limit 0, ".$conf['query_limit'];
	$result = queryArray($sql);
	//echo $sql;
	return $result;
}

/*
function selectSitTable($sit_id){
	
	global $db_conn;
	$sit_id = mysqli_real_escape_string($db_conn, $sit_id);
	
	$sql = "select * from sit_table where sit_id='$sit_id'";
	$result = queryDB($sql);
	return $result;
}
function insertSitTable($sit_int, $sit_text){
	
	global $db_conn;
	
	// -----------
	// 1: SQL
	// -----------
	$sit_datetime = getCurrentDatetime();
	$sql = 'insert into sit_table (sit_int, sit_text,sit_datetime) values (?,?,?)';
	// -----------
	
	$stmt = $db_conn->prepare($sql);
	if($stmt === false) {
		return $db_conn->error;
	}

	// -----------
	// 2: Bind parameters
	// Types: s = string, date, i = integer, d = double,  b = blob
	// -----------
	$stmt->bind_param('iss',$sit_int,$sit_text,$sit_datetime);
	// -----------
	
	$stmt->execute();
	$temp_id = $stmt->insert_id;
	$stmt->close();
	return $temp_id;
}
function updateSitTable($sit_id, $sit_int, $sit_text){
	
	global $db_conn;
	
	// -----------
	// 1: SQL
	// -----------
	$sql = 'update sit_table set sit_int=?, sit_text=? where sit_id=?';
	// -----------
	
	$stmt = $db_conn->prepare($sql);
	if($stmt === false) {
		return $db_conn->error;
	}

	// -----------
	// 2: Bind parameters
	// Types: s = string, date, i = integer, d = double,  b = blob
	// -----------
	$stmt->bind_param('isi', $sit_int, $sit_text, $sit_id);
	// -----------
	
	$stmt->execute();
	$temp_affected_rows = $stmt->affected_rows;
	$stmt->close();
	return $temp_affected_rows;
}
function deleteSitTable($sit_id){
	
	global $db_conn;
	
	// -----------
	// 1: SQL
	// -----------
	$sql = 'delete from sit_table where sit_id=?';
	// -----------
	
	$stmt = $db_conn->prepare($sql);
	if($stmt === false) {
		return $db_conn->error;
	}

	// -----------
	// 2: Bind parameters
	// Types: s = string, date, i = integer, d = double,  b = blob
	// -----------
	$stmt->bind_param('i',$sit_id);
	// -----------
	
	$stmt->execute();
	$temp_affected_rows = $stmt->affected_rows;
	$stmt->close();
	return $temp_affected_rows;
}
*/

// -------------------------------------------------
// -------------------------------------------------
?>