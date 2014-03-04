<?php
// Start config PowerDNS database
$mysqlhost = ""; //mysql server
$mysqluser = ""; // mysql user
$mysqlpass = ""; // mysql pass
$mysqldaba = ""; //mysql database
// End config PowerDNS database

// Start config WHMCS database
$mysqlhost3 = ""; //mysql server
$mysqluser3 = ""; // mysql user
$mysqlpass3 = ""; // mysql pass
$mysqldaba3 = ""; //mysql database
// End config WHMCS database

// Start config DNScheck
$mysqlhost2 = ""; //mysql server
$mysqluser2 = ""; // mysql user
$mysqlpass2 = ""; // mysql pass
$mysqldaba2 = ""; //mysql database
// End config DNScheck

$mysqli = mysqli_init();
if(!$mysqli){
	die('FATAL ERROR: mysqli_init failed');
}
if(!$mysqli->real_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldaba)){
	die('FATAL ERROR: mysqli mysqli->real_connect failed');
}
$mysqli2 = mysqli_init();
if(!$mysqli2){
	die('FATAL ERROR: mysqli2 mysqli_init failed');
}
if(!$mysqli2->real_connect($mysqlhost2, $mysqluser2, $mysqlpass2, $mysqldaba2)){
	die('FATAL ERROR: mysqli2->real_connect failed');
}
$mysqli3 = mysqli_init();
if(!$mysqli3){
	die('FATAL ERROR: mysqli3 mysqli_init failed');
}
if(!$mysqli3->real_connect($mysqlhost3, $mysqluser3, $mysqlpass3, $mysqldaba3)){
	die('FATAL ERROR: mysqli3->real_connect failed');
}

$query = $mysqli->query('SELECT `name`,`account` FROM `domains`') or die($mysqli->error);
if($query->num_rows == "0"){
}else{
	while($row = $query->fetch_array(MYSQLI_ASSOC)){
		$mysqli2->query('INSERT INTO `queue` (`domain`,`source_id`,`source_data`) VALUES ("'.$row['name'].'","2","'.$row['account'].'")');
	}
}

$query3 = $mysqli3->query('SELECT `domain` FROM `tbldomains`') or die($mysqli->error);
if($query3->num_rows == "0"){
}else{
	while($row3 = $query3->fetch_array(MYSQLI_ASSOC)){
		$mysqli2->query('INSERT INTO `queue` (`domain`,`source_id`,`source_data`) VALUES ("'.$row3['domain'].'","2","999")');
	}
}
?>
