<?php
// Script created by Mark Scholten (www.mscholten.eu) for SinnerG BV (www.sinnerg.nl)
// Distribution is not allowed
// Changing this code is allowed if you don't change this copyright notice (at least for the parts created by Mark Scholten)

// Start config
$mysqlhost = "localhost"; //mysql server
$mysqluser = ""; // mysql user
$mysqlpass = ""; // mysql pass
$mysqldaba = ""; //mysql database
$pdnssec = "/usr/bin/pdnssec"; // pdnssec
$dig = "/usr/bin/dig"; // dig
$connect = 'connect1234'; // connect variable name
$epphost = 'drs.domain-registry.nl';
$eppuser = '';
$epppass = '';
$eppport = 700;
// End config



function sidn_connect($url,$port,$connect){
	global $$connect;
	$timeout = 30;
	putenv('SURPRESS_ERROR_HANDLER=1');
	$$connect = fsockopen('ssl://'.$url, $port, $errno, $errstr, $timeout);
	putenv('SURPRESS_ERROR_HANDLER=0');
	if (is_resource($$connect))
	{
		stream_set_blocking($$connect, false);
		if ($errno == 0)
		{
			$read = sidn_read($$connect);
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function sidn_addInteger($content){
		$len  = strlen($content)+4;
		$int3 = chr($len%256);
		$len  = $len/256;
		$int2 = chr($len%256);
		$len  = $len/256;
		$int1 = chr($len%256);
		$len  = $len/256;
		$int0 = chr($len%256);
		$int  = array ($int0, $int1, $int2, $int3);
		$int  = implode('', $int);
		return $int.$content;
}

function sidn_write($connect,$content){
	//var_dump($content);
	//var_dump($connect);
	if (!is_resource($connect)){
		//var_dump($content);
		die('Writing while no connection is made is not supported.');
	}
	putenv('SURPRESS_ERROR_HANDLER=1');
	$content = sidn_addInteger($content);
	if (fputs($connect, $content, strlen($content))){
		putenv('SURPRESS_ERROR_HANDLER=0');
		return true;
	}
	putenv('SURPRESS_ERROR_HANDLER=0');
	return false;
}

function sidn_readInteger($content){
	$int = substr($content, 0, 4);
	$int = ord($int[3])+256*(ord($int[2])+256*(ord($int[1])+256*(ord($int[0]))));
	return $int;
}

function sidn_read($connect){
	putenv('SURPRESS_ERROR_HANDLER=1');
	$content = '';
	$time = time()+30;
	$connections[] = $connect;
	while (! isset ($length) || $length > 0)
	{
		if (feof($connect))
		{
			putenv('SURPRESS_ERROR_HANDLER=0');
			die('Unexpected closed connection by remote host...');
		}
		if (time() >= $time)
		{
			putenv('SURPRESS_ERROR_HANDLER=0');
			return false;
		}
		if ( !isset($length) || $length == 0)
		{
			if ($read = fread($connect, 4))
			{
				$length = sidn_readInteger($read)-4;
			}
		}
		if ( isset($length) && $length > 0)
		{
			$time = time()+5;
			if ($read = fread($connect, 1024))
			{
				//var_dump($read);
				$length = $length-strlen($read);
				$content .= $read;
			}
		}
	}
	//var_dump($content);
	putenv('SURPRESS_ERROR_HANDLER=0');
	return $content;
}

function sidn_xml2array($xml, $get_attributes = 1, $priority = 'tag'){
    if (!function_exists('xml_parser_create'))
    {
        return array ();
    }
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($xml), $xml_values);
    xml_parser_free($parser);
    if (!$xml_values)
        return; //Hmm...
    $xml_array = array ();
    $parents = array ();
    $opened_tags = array ();
    $arr = array ();
    $current = & $xml_array;
    $repeated_tag_index = array ();
    foreach ($xml_values as $data)
    {
        unset ($attributes, $value);
        extract($data);
        $result = array ();
        $attributes_data = array ();
        if (isset ($value))
        {
            if ($priority == 'tag')
                $result = $value;
            else
                $result['value'] = $value;
        }
        if (isset ($attributes) and $get_attributes)
        {
            foreach ($attributes as $attr => $val)
            {
                if ($priority == 'tag')
                    $attributes_data[$attr] = $val;
                else
                    $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }
        if ($type == "open")
        {
            $parent[$level -1] = & $current;
            if (!is_array($current) or (!in_array($tag, array_keys($current))))
            {
                $current[$tag] = $result;
                if ($attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                $current = & $current[$tag];
            }
            else
            {
                if (isset ($current[$tag][0]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset ($current[$tag . '_attr']))
                    {
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset ($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = & $current[$tag][$last_item_index];
            }
        }
        elseif ($type == "complete")
        {
            if (!isset ($current[$tag]))
            {
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
            }
            else
            {
                if (isset ($current[$tag][0]) and is_array($current[$tag]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    if ($priority == 'tag' and $get_attributes and $attributes_data)
                    {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes)
                    {
                        if (isset ($current[$tag . '_attr']))
                        {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset ($current[$tag . '_attr']);
                        }
                        if ($attributes_data)
                        {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                }
            }
        }
        elseif ($type == 'close')
        {
            $current = & $parent[$level -1];
        }
    }
    return ($xml_array);
}

function sidn_get_domain_info($domain,$connect){
	global $$connect;
	$content = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
<command>
<info>
<domain:info
xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
<domain:name>'.$domain.'</domain:name>
</domain:info>
</info>
';
$content .= '<clTRID>'.microtime().'</clTRID>
</command>
</epp>';
	sidn_write($$connect,$content);
	$read = sidn_read($$connect);
	//var_dump($read);
	$status = sidn_succes($read,1000);
	if($status === TRUE){
		return $read;
	}elseif($status === FALSE){
		return $status;
	}else{
		return 'error';
	}
}

function sidn_succes($xml,$code){
	$read2 = explode('<result code="',$xml,2);
	$read3 = explode('">',$read2[1],2);
	if($read3[0] == $code){
		$return = TRUE;
	}else{
		$return = FALSE;
	}
	return $return;
}

function sidn_login($user,$pass,$url,$port,$connect){
	global $$connect;
	sidn_connect($url,$port,$connect);
	//sleep(1);
	$content = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0
epp-1.0.xsd">
<command>
<login>
<clID>'.$user.'</clID>
<pw>'.$pass.'</pw>
<options>
<version>1.0</version>
<lang>en</lang>
</options>
<svcs>
<objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
<objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
<objURI>urn:ietf:params:xml:ns:host-1.0</objURI>
<objURI>urn:ietf:params:xml:ns:secDNS-1.1</objURI>';
	$content .= '</svcs>
</login>
<clTRID>'.microtime().'</clTRID>
</command>
</epp>';
	sidn_write($$connect,$content);
	$read = sidn_read($$connect);
	$return = sidn_succes($read,1000);
	//var_dump($return);
	return $return;
}

function sidn_logout($connect){
	global $$connect;
	$content = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0
epp-1.0.xsd">
<command>
<logout/>
<clTRID>'.microtime().'</clTRID>
</command>
</epp>';
	sidn_write($$connect,$content);
	$read = sidn_read($$connect);
	$return = sidn_succes($read,1500);
	fclose($$connect);
	return $return;
}

function sidn_insertdnssec($domain,$dnskeys,$connect){
	global $$connect;
	$content = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
	<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
	<command>
	<update>
	<domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
	<domain:name>'.$domain.'</domain:name>
	</domain:update>
	</update>
	<extension>
	<secDNS:update xmlns:secDNS="urn:ietf:params:xml:ns:secDNS-1.1">
	';
	$content .= '<secDNS:add>
		';
		foreach($dnskeys as $dnskey){
			if(isset($dnskey) && $dnskey !== ''){
				$valuetmp = explode(' ',$dnskey);
				$content .= '<secDNS:keyData>
				<secDNS:flags>'.$valuetmp[0].'</secDNS:flags>
				<secDNS:protocol>'.$valuetmp[1].'</secDNS:protocol>
				<secDNS:alg>'.$valuetmp[2].'</secDNS:alg>
				<secDNS:pubKey>'.$valuetmp[3].'</secDNS:pubKey>
				</secDNS:keyData>
				';
				unset($valuetmp);
			}
		}
		$content .= '
		</secDNS:add>
		';
	$content .= '</secDNS:update>
	</extension>
	<clTRID>'.microtime().'</clTRID>
	</command>
	</epp>';

	sidn_write($$connect,$content);
	//var_dump($content);
	$read = sidn_read($$connect);
	//var_dump($read);
	$status = sidn_succes($read,1000);
	//var_dump($status);
	return($status);
}



$mysqli = mysqli_init();
if(!$mysqli){
	die('FATAL ERROR: mysqli_init failed');
}
if(!$mysqli->real_connect($mysqlhost, $mysqluser, $mysqlpass, $mysqldaba)){
	die('FATAL ERROR: mysqli->real_connect failed');
}

$eppdone = 0;

$query = $mysqli->query('SELECT * FROM `domains` WHERE `pushdnssec` LIKE "1" LIMIT 1') or die($mysqli->error);
if($query->num_rows == "0"){
}else{
	while($row = $query->fetch_array(MYSQLI_ASSOC)){
		exec($pdnssec." show-zone ".$row['name']." 2>&1", &$output, $retval);
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
					$ds[] = trim($expl2[2]);
					unset($expl2);
				}elseif($expl[0] === "KSK"){
					$expl2 = explode('DNSKEY',$line,3);
					$dnskey[] = trim($expl2[2]);
					unset($expl2);
				}
				unset($expl);
			}
		}
		unset($output);
		if($dnssec !== 3){
			exec($pdnssec." secure-zone ".$row['name']." 2>&1", &$output, $retval);
			$secure = 0;
			foreach($output as $line){
				if($line == "Zone ".$row['name']." secured"){
					$secure++;
				}
			}
			unset($output);
			if($secure !== 1){
				exec($pdnssec." secure-zone ".$row['name']." 2>&1", &$output, $retval);
				$secure = 0;
				foreach($output as $line){
					if($line == "Zone ".$row['name']." secured"){
						$secure++;
					}
				}
				unset($output);
				if($secure !== 1){
					exec($pdnssec." secure-zone ".$row['name']." 2>&1", &$output, $retval);
					$secure = 0;
					foreach($output as $line){
						if($line == "Zone ".$row['name']." secured"){
							$secure++;
						}
					}
					unset($output);
					if($secure !== 1){
						exec($pdnssec." secure-zone ".$row['name']." 2>&1", &$output, $retval);
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
			exec($pdnssec." show-zone ".$row['name']." 2>&1", &$output, $retval);
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
						$ds[] = trim($expl2[2]);
						unset($expl2);
					}elseif($expl[0] === "KSK"){
						$expl2 = explode('DNSKEY',$line,3);
						$dnskey[] = trim($expl2[2]);
						unset($expl2);
					}
					unset($expl);
				}
			}
			unset($output);
		}
		if(is_array($dnskey)){
			if($eppdone === 10){
				sidn_logout($connect);
				$eppdone = 0;
			}
			if($eppdone === 0){
				sidn_login($eppuser,$epppass,$epphost,$eppport,$connect);
			}
			$xmlinfo = sidn_get_domain_info($row['name'],$connect);
			$arrayinfo = sidn_xml2array($xmlinfo);
			$do_dnssec = 0;
			$ns_dnssec = 0;
			if(isset($arrayinfo['epp']['response']['resData']['domain:infData']['domain:ns']['domain:hostObj'])){
				$ns_count = count($arrayinfo['epp']['response']['resData']['domain:infData']['domain:ns']['domain:hostObj']);
				foreach($arrayinfo['epp']['response']['resData']['domain:infData']['domain:ns']['domain:hostObj'] as $ns){
					exec($dig." DNSKEY +short ".$row['name']." @".$ns." 2>&1", &$output1, $retval1);
					foreach($output1 as $line1){
						$expl = explode(' ',$line1,4);
						$expl[3] = str_replace(' ','',$expl[3]);
						if($expl[0] == 257){
							foreach($dnskey as $dnskey1){
								$expl2 = explode(' ',$dnskey1);
								if($expl[3] === $expl2[3] && $expl[0] === $expl2[0] && $expl[1] === $expl2[1] && $expl[2] === $expl2[2]){
									$ns_dnssec++;
								}
							}
						}
					}
					unset($output1);
				}
				if($ns_dnssec === $ns_count){
					$do_dnssec = 1;
				}
			}
			if(!isset($arrayinfo['epp']['response']['extension']['secDNS:infData']['secDNS:keyData']) && $do_dnssec === 1){
				$status = sidn_insertdnssec($row['name'],$dnskey,$connect);
				$eppdone++;
				var_dump($row['name']);
			}
			
			if(isset($status) && $status !== FALSE){
				$mysqli->query('UPDATE `domains` SET `pushdnssec` = 2 WHERE `id` LIKE "'.$row['id'].'" LIMIT 1');
			}elseif($do_dnssec === 0){
				$mysqli->query('UPDATE `domains` SET `pushdnssec` = 3 WHERE `id` LIKE "'.$row['id'].'" LIMIT 1');
			}
			
				
			// pushdnssec = 2 staat voor de situatie waarbij de DNSSEC data reeds is overgezet naar SIDN, 3 staat voor de situatie dat het niet overgezet moet worden
		}
	}
	sidn_logout($connect);
}

// ALTER TABLE `domains` ADD `pushdnssec` INT( 5 ) NOT NULL;
// ALTER TABLE `domains` ALTER COLUMN `pushdnssec` SET DEFAULT 1;
// UPDATE `domains` SET `pushdnssec` = 1;
?>