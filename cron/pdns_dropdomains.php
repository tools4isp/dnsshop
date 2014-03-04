<?php
$dropdomains = array(); # array with domains to drop

mysql_connect(  '', // host
                                '', //username
                                ''); //pass
mysql_select_db('pdns');
$test = 0; // change to 0 to delete records/domains after $checks tests that failed, if set to something else it will not delete anything
$verbose = 1; // set to 1 to be verbose (output domainnames that are deleted/are to be deleted (depending on the $test setting)

$timestamp = time()-(24*3600); // get the timestamp for 24 hours ago

mysql_query("UPDATE domains SET `failed`=0 WHERE `type`='SLAVE' AND `last_check` > ".$timestamp." AND `failed` NOT LIKE '0'");

$sql3 = "SELECT * FROM domains";
$query = mysql_query($sql3) or die(mysql_error());
$dump = '';
if(mysql_num_rows($query) == FALSE){
}else{
	while($record = mysql_fetch_object($query)){
		if(in_array($record->name,$dropdomains)){
			if($test === 0){
				mysql_query("DELETE FROM records WHERE domain_id='".$record->id."'");
				mysql_query("DELETE FROM cryptokeys WHERE domain_id='".$record->id."'");
				mysql_query("DELETE FROM domains WHERE id='".$record->id."'");
				mysql_query("DELETE FROM domainmetadata WHERE domain_id='".$record->id."'");
				mysql_query("DELETE FROM dnssec WHERE domain_id='".$record->id."'"); // a table we use internally for the dnssec data (with some scripts it can be requested remote (read only) by our domain management UI)
			}
			if($verbose === 1){
				echo $record->name."
";
			}
			$dump .= $record->name." - ".$record->master." - ".$record->account." - ".$record->failed."\r\n";
		}elseif($record->failed != 0){
			mysql_query("UPDATE domains SET `failed`=0 WHERE id='".$record->id."'") or die(mysql_error());
		}

	}
	if($verbose === 1){
		echo "Done
";
	}
	if($dump != ''){
		mail("mark@streamservice.nl","The following domains are deleted",$dump);
	}
}
?>