<?php

session_start(); 
require_once("init.php");
require_once("utils.php");

$tns = "  
(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521))
    )
    (CONNECT_DATA =
      (SERVICE_NAME = XE)
    )
  )
       ";

$pdo = new PDO('oci:dbname='.$tns, 'git_court', 'git_court');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->prepare("select * from documents");
$stmt->execute();
$arr = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
 $arr[] = $row;
}
$json_response = json_encode($arr);
echo $json_response;

?>