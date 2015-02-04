<?php
function getCurrentDatetime(){

	return date("Y-m-d H:i:s");
}
function convertToArray($mysql_results){
	$temp_array=array();
	if(!$mysql_results) return $temp_array;
	
	while($temp=mysql_fetch_array($mysql_results,MYSQL_ASSOC)){
		array_push($temp_array,$temp);
	}
	return $temp_array;
}
function genRandomString($length) {

    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $string ="";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

// ------------------------------------------------------------
// text

function txtjs($str){
	return (json_encode($str));
	
}
function js($str){
	return addslashes($str);
}

function startsWith($haystack, $needle){
    return !strncmp($haystack, $needle, strlen($needle));
}

function endsWith($haystack, $needle){
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}
// if not contain the specific key in array return the key 
function notnullkey($array,$key){
	if(is_null($array[$key]) || $array[$key] == ''){
		return $key;
	}
	return $array[$key];
}
// ------------------------------------------------------------
// time

//บวกเวลาให้กับ givendate
function add_time($givendate, $hr=0, $min=0, $sec=0, $day=0, $mth=0, $yr=0) {
	$cd = strtotime($givendate);
	$newdate = date('Y-m-d H:i:s', mktime(date('H',$cd) + $hr,
	date('i',$cd) + $min, date('s',$cd) + $sec, date('m',$cd) + $mth,
	date('d',$cd) + $day, date('Y',$cd) + $yr));
	return $newdate;
}
//หาค่าต่างของวันที่และเวลา  start, end
// return end - start (second)
function diff_time($end_time, $start_time){
	return (strtotime($end_time) - strtotime($start_time));
}
//เปรียบเทียบวันที่และเวลา 
function compare_time($strDateTime1, $strDateTime2)
{
	$time1 = strtotime($strDateTime1);
	$time2 = strtotime($strDateTime2);
	if ($time1  - $time2 > 0)
		return 1;
	else if($time1  - $time2 < 0)
		return -1;
	else
		return 0;
}
// ------------------------------------------------------------
// check format

// yyyy-mm-dd HH:mm
function checkDateFormat($d){

	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[0-9]|[1][0-9]|2[0-3]):([0-6][0-9])$/",$d))
    {
        return 1;
    }else{
        return 0;
    }
}
// change dd/mm/yyyy to yyyy-mm-dd
function changeDateFormat($d){

	try{
		$dd = substr($d, 0, 2);
		$mm = substr($d, 3, 2);
		$yyyy = substr($d, 6, 4);

		return $yyyy . '-' . $mm . '-' . $dd;
	} catch (Exception $e) {}

	return '0000-00-00';
}

// ------------------------------------------------------------
// display

function getTextUserLevel($user){

	if($user['user_level'] == 2) return "ผู้ดูแลระบบ";
	return "ผู้ใช้ทั่วไป";
}

function getTextEnableFlag($r_enable_flag){

	if($r_enable_flag == 1) return '<i class="icon-check"></i>&nbsp;เปิด';
	return '<i class="icon-check-empty"></i>&nbsp;ปิด';
}

function getImagePath($image_path){
	
	if($image_path == null || $image_path == '') return "images/empty.png";
	if(file_exists($image_path)) return $image_path;
	return "images/empty.png";
}

// ------------------------------------------------------------
?>