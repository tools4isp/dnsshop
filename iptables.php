<?php
	session_start();
	ini_set("show_errors","on");
	error_reporting(E_ALL);
	ini_set('display_errors','1');
	$index = "1";
	
#	require_once("db.php");
        // mysql database met alle firewall rules
        $config['db']['mysql']['firewall']['host'] = "localhost"; //mysql server
        $config['db']['mysql']['firewall']['user'] = "firewall"; // mysql user
        $config['db']['mysql']['firewall']['pass'] = "d8DS32h@q245qa"; // mysql pass
        $config['db']['mysql']['firewall']['database'] = "firewall"; //mysql database


	$ip = $_SERVER["REMOTE_ADDR"];
	if(!isset($_GET['ipv']) || empty($_GET['ipv']) || $_GET['ipv'] == ""){
		header("Location: http://google.com/");
		exit();
	}
	
	$mysqli = mysqli_init();
	if(!$mysqli){
		die('FATAL ERROR: mysqli_init failed');
	}
	if(!$mysqli->real_connect($config['db']['mysql']['firewall']['host'], $config['db']['mysql']['firewall']['user'], $config['db']['mysql']['firewall']['pass'], $config['db']['mysql']['firewall']['database'])){
		die('FATAL ERROR: mysqli->real_connect failed');
	}
	$ipv = 0;
	if($_GET['ipv'] == 4){
		$sql = 'SELECT * FROM `iptables_rules` WHERE `ipv4_or_ipv6` LIKE "4" AND `prio` NOT LIKE "45" AND `status` LIKE "in_use" ORDER BY `prio`,`id` ASC';
		$ipv = 4;
	}elseif($_GET['ipv'] == 6){
		$sql = 'SELECT * FROM `iptables_rules` WHERE `ipv4_or_ipv6` LIKE "6" AND `prio` NOT LIKE "45" AND `status` LIKE "in_use" ORDER BY `prio`,`id` ASC';
		$ipv = 6;
	}
	$num = 0;
	if($ipv == 4 || $ipv == 6){
		$query = $mysqli->query($sql);
		if($query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$num++;
				if($num == 10){
					echo '/bin/sleep 0.5 && ';
					$num = 0;
				}
				if($ipv == 6){
					echo '/sbin/ip6tables';
				}else{
					echo '/sbin/iptables';
				}
				echo ' -A '.$row['chain'];
				if(isset($row['source_ip']) && !empty($row['source_ip']) && $row['source_ip'] != ""){
					echo ' -s '.$row['source_ip'];
				}
				if(isset($row['destination_ip']) && !empty($row['destination_ip']) && $row['destination_ip'] != ""){
					echo ' -d '.$row['destination_ip'];
				}
				if(isset($row['protocol']) && !empty($row['protocol']) && $row['protocol'] != ""){
					echo ' -p '.$row['protocol'];
				}else{
					echo ' -p all';
				}
				if(isset($row['source_ports']) && !empty($row['source_ports']) && $row['source_ports'] != ""){
					echo ' --sport '.$row['source_ports'];
				}
				if(isset($row['destination_ports']) && !empty($row['destination_ports']) && $row['destination_ports'] != ""){
					echo ' --dport '.$row['destination_ports'];
				}
				echo ' -j '.$row['action'];
				echo "\n";
			}
		}
	}
?>
