<?php
// Script created by Mark Scholten (www.mscholten.eu) for SinnerG BV (www.sinnerg.nl)
// Distribution is allowed if you don't change this copyright notice
// Changing this code is allowed if you don't change this copyright notice (at least for the parts created by Mark Scholten)
// Asking money for this script is allowed, however if you didn't change it don't say you created it (if you want to donate money, please donate it to PowerDNS)
// Mark Scholten and SinnerG BV provide this script "as is" and without any warranties, it is possible that there are errors in this script

// This script assumes that there is an additional column in the domains table that contains the number of changes since the last time this script did run, we run this script every minute so the number is low (normally 0)
// If this isn't done there might be problems with domains not resolving, a default value for this column of 1 is probably the easiest option so you only need to update it (+1) when you update/remove/add a record

// Start config
$mysqlhost = "127.0.0.1"; //mysql server
$mysqluser = "user"; // mysql user
$mysqlpass = "pass"; // mysql pass
$mysqldaba = "database"; //mysql database
$pdnssec = "/usr/bin/pdnssec"; // pdnssec
// End config

$mysqli = mysqli_init();
if(!$mysqli){
	die('FATAL ERROR: mysqli_init failed');
}
if(!$mysqli->real_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldaba)){
	die('FATAL ERROR: mysqli->real_connect failed');
}
$query = $mysqli->query('SELECT id,name,changed FROM `domains` WHERE `changed` NOT LIKE "0"') or die($mysqli->error);
if($query->num_rows == "0"){
}else{
	while($row = $query->fetch_array(MYSQLI_ASSOC)){
		$output = passthru($pdnssec." rectify-zone ".$row['name'], $retval);
		$mysqli->query("UPDATE `domains` SET `changed` = `changed`-".$row['changed']." WHERE `id` = ".$row['id']." LIMIT 1");
	}
}

// To start ordering rectify-zone to all domain names without another system being the master: UPDATE `domains` SET `changed` = +1 WHERE `master` IS NULL;
// ALTER TABLE `domains` ADD `changed` INT( 5 ) NOT NULL;
?>
