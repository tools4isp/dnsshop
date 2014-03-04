<?php
// Start config
$mysqlhost = ""; //mysql server
$mysqluser = ""; // mysql user
$mysqlpass = ""; // mysql pass
$mysqldaba = ""; //mysql database
$pdnssec = "/usr/bin/pdnssec"; // pdnssec
// End config

$mysqli = mysqli_init();
if(!$mysqli){
        die('FATAL ERROR: mysqli_init failed');
}
if(!$mysqli->real_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldaba)){
        die('FATAL ERROR: mysqli->real_connect failed');
}
$i = 0;
$query = $mysqli->query('SELECT id,name,changed FROM `domains` WHERE `type` NOT LIKE "SLAVE"') or die($mysqli->error);
if($query->num_rows == "0"){
}else{
	while($row = $query->fetch_array(MYSQLI_ASSOC)){
		exec($pdnssec." show-zone ".$row['name']." 2>&1", $output, $retval);
		$dnssec = 0;
		$ds = 1;
		$dnskey = 1;
		unset($ds);
		unset($dnskey);
		foreach($output as $line){
			if($line == "Zone has NSEC semantics"){
				$dnssec++;
			}elseif($line == "Zone is not presigned"){
				$dnssec++;
			}elseif($line == "keys:"){
				$dnssec++;
			}else{
				$expl = explode(' ',$line);
				if($expl[0] === "DS"){
					$expl2 = explode('DS',$line,3);
					$ds[$i]['record'] = trim($expl2[2]);
					$ds[$i]['add'] = 1;
					$i++;
					unset($expl2);
				}elseif($expl[0] === "KSK"){
					$expl2 = explode('DNSKEY',$line,3);
					$dnskey[$i]['record'] = trim($expl2[2]);
					$dnskey[$i]['add'] = 1;
					$i++;
					unset($expl2);
				}
				unset($expl);
			}
		}
		unset($output);
		if($dnssec !== 3){
			exec($pdnssec." secure-zone ".$row['name']." 2>&1", $output, $retval);
			$secure = 0;
			foreach($output as $line){
				if($line == "Zone ".$row['name']." secured"){
					$secure++;
				}
			}
			unset($output);
			if($secure !== 1){
				exec($pdnssec." secure-zone ".$row['name']." 2>&1", $output, $retval);
				$secure = 0;
				foreach($output as $line){
					if($line == "Zone ".$row['name']." secured"){
						$secure++;
					}
				}
				unset($output);
				if($secure !== 1){
					exec($pdnssec." secure-zone ".$row['name']." 2>&1", $output, $retval);
					$secure = 0;
					foreach($output as $line){
						if($line == "Zone ".$row['name']." secured"){
							$secure++;
						}
					}
					unset($output);
					if($secure !== 1){
						exec($pdnssec." secure-zone ".$row['name']." 2>&1", $output, $retval);
						$secure = 0;
						foreach($output as $line){
							if($line == "Zone ".$row['name']." secured"){
								$secure++;
							}
						}
						unset($output);
					}
				}
			}
			exec($pdnssec." show-zone ".$row['name']." 2>&1", $output, $retval);
			$dnssec = 0;
			foreach($output as $line){
				if($line == "Zone has NSEC semantics"){
					$dnssec++;
				}elseif($line == "Zone is not presigned"){
					$dnssec++;
				}elseif($line == "keys:"){
					$dnssec++;
				}else{
					$expl = explode(' ',$line);
					if($expl[0] === "DS"){
						$expl2 = explode('DS',$line,3);
						$ds[$i]['record'] = trim($expl2[2]);
						$ds[$i]['add'] = 1;
						$i++;
						unset($expl2);
					}elseif($expl[0] === "KSK"){
						$expl2 = explode('DNSKEY',$line,3);
						$dnskey[$i]['record'] = trim($expl2[2]);
						$dnskey[$i]['add'] = 1;
						$i++;
						unset($expl2);
					}
					unset($expl);
				}
			}
			unset($output);
		}
		if(is_array($ds) && is_array($dnskey)){
			$query2 = $mysqli->query('SELECT * FROM `dnssec` WHERE `domainid` LIKE "'.$row['id'].'"') or die($mysqli->error);
			if($query2->num_rows == "0"){
				foreach($ds as $record){
					$mysqli->query('INSERT INTO `dnssec` (`domainid`,`type`,`record`) VALUES ("'.$row['id'].'","DS","'.$record['record'].'")');
				}
				foreach($dnskey as $record){
					$mysqli->query('INSERT INTO `dnssec` (`domainid`,`type`,`record`) VALUES ("'.$row['id'].'","DNSKEY","'.$record['record'].'")');
				}
			}else{
				while($row2 = $query2->fetch_array(MYSQLI_ASSOC)){
					$del = 1;
					if($row2['type'] == "DS"){
						foreach($ds as $key => $record){
							if($record['record'] == $row2['record']){
								$del = 0;
								$ds[$key]['add'] = 0;
							}
						}
					}
					if($row2['type'] == "DNSKEY"){
						foreach($dnskey as $key => $record){
							if($record['record'] == $row2['record']){
								$del = 0;
								$dnskey[$key]['add'] = 0;
							}
						}
					}
					if($del === 1){
						$mysqli->query('DELETE FROM `dnssec` WHERE `id` LIKE '.$row2['id'].'');
					}
				}
				foreach($ds as $record){
					if($record['add'] === 1){
						$mysqli->query('INSERT INTO `dnssec` (`domainid`,`type`,`record`) VALUES ("'.$row['id'].'","DS","'.$record.'")');
					}
				}
				foreach($dnskey as $record){
					if($record['add'] === 1){
						$mysqli->query('INSERT INTO `dnssec` (`domainid`,`type`,`record`) VALUES ("'.$row['id'].'","DNSKEY","'.$record.'")');
					}
				}
			}
			//echo $row['name']." secured and key stored.\r\n";
		}
	}
}
?>
