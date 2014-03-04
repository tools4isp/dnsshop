<?php
// DNS mysql database
$config['db']['mysql']['dns']['host'] = ""; //mysql server
$config['db']['mysql']['dns']['user'] = ""; // mysql user
$config['db']['mysql']['dns']['pass'] = ""; // mysql pass
$config['db']['mysql']['dns']['database'] = ""; //mysql database


function GetDNSsecRecordsFromDnsshop($domain,$type){
	// DNS mysql database
	global $mysqli_dns;
	if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
	global $mysqli_dns;
	$sql = 'SELECT domains.id, domains.name, dnssec.type, dnssec.record FROM domains INNER JOIN dnssec ON (domains.id = dnssec.domainid) WHERE domains.name LIKE "'.$mysqli_dns->real_escape_string($domain).'" AND dnssec.type LIKE "'.$mysqli_dns->real_escape_string($type).'"';
	$query = $mysqli_dns->query($sql);
	if(!isset($query) || empty($query) || $query->num_rows == "0"){
		return FALSE;
	}else{
		$num = 0;
		while($row = $query->fetch_array(MYSQLI_ASSOC)){
			$array[$num] = $row['record'];
			$num++;
		}
		if($num === 0){
			return FALSE;
		}else{
			return $array;
		}
	}
}

function create_db_connection($conn_name,$config_array){
	global $config;
	global $$conn_name;
	$$conn_name = mysqli_init();
	if(!$$conn_name){
		die('FATAL ERROR: mysqli_init failed');
	}
	if(!$$conn_name->real_connect($config['db']['mysql'][$config_array]['host'], $config['db']['mysql'][$config_array]['user'], $config['db']['mysql'][$config_array]['pass'], $config['db']['mysql'][$config_array]['database'])){
		die('FATAL ERROR: '.$conn_name.'->real_connect failed');
	}
}
$echo = '';
if(isset($_GET['dom']) && !empty($_GET['dom']) && $_GET['dom'] != "" && stripos($_GET['dom'], " ") === FALSE && stripos($_GET['dom'], ';') === FALSE && stripos($_GET['dom'], '&') === FALSE && stripos($_GET['dom'], "%") === FALSE){
	if(isset($_GET['type']) && !empty($_GET['type']) && $_GET['type'] != "" && stripos($_GET['type'], " ") === FALSE && stripos($_GET['type'], ';') === FALSE && stripos($_GET['type'], '&') === FALSE && stripos($_GET['type'], "%") === FALSE){
		$records = GetDNSsecRecordsFromDnsshop($_GET['dom'],$_GET['type']);
		$i = 0;
		if($records === FALSE){
			$echo = '';
		}else{
			foreach($records as $record){
				if($i === 0){
					$echo .= $record;
				}else{
					$echo .= '-=-'.$record;
				}
				$i++;
			}
		}
	}
}
if($echo == ''){
	echo 'FALSE';
}else{
	echo $echo;
}
?>