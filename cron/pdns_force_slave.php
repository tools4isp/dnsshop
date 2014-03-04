<?php
// Start config
$mysqlhost = ""; //mysql server
$mysqluser = ""; // mysql user
$mysqlpass = ""; // mysql pass
$mysqldaba = ""; //mysql database
$pdns_control = "/usr/bin/pdns_control"; // pdnssec
$slave = '';
// End config

$mysqli = mysqli_init();
if(!$mysqli){
        die('FATAL ERROR: mysqli_init failed');
}
if(!$mysqli->real_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldaba)){
        die('FATAL ERROR: mysqli->real_connect failed');
}
$i = 0;
$mysqli->query('UPDATE `domains` SET `notified_serial` = NULL') or die($mysqli->error);
$query = $mysqli->query('SELECT id,name FROM `domains` WHERE `type` NOT LIKE "SLAVE"') or die($mysqli->error);
if($query->num_rows == "0"){
}else{
	while($row = $query->fetch_array(MYSQLI_ASSOC)){
		exec($pdns_control." notify-host ".$row['name']." ".$slave." 2>&1", $output, $retval);
		echo $pdns_control." notify-host ".$row['name']." ".$slave." 2>&1\n";
		var_dump($output);
		echo "\h\h";
		unset($output);
	}
}
?>
