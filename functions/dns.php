<?php
// Created by Mark Scholten
	function dns_get_number_domains($account,$type = 'NATIVE'){
		global $mysqli_dns;
		global $lang;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		$query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' AND `type` LIKE '".$mysqli_dns->real_escape_string($type)."'");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$array[$row['id']]['id'] = $row['id'];
				$array[$row['id']]['name'] = $row['name'];
				$array[$row['id']]['type'] = $row['type'];
			}
			return $array;
		}
	}
	function dns_get_number_templates($account,$type = 1){
		global $mysqli;
		global $lang;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if($type == 3){
			$query = $mysqli->query("SELECT * FROM `dns_templates` WHERE `account` LIKE '0' OR `account` LIKE '".$mysqli->real_escape_string($account)."'");
		}elseif($type == 2){
			$query = $mysqli->query("SELECT * FROM `dns_templates` WHERE `account` LIKE '0'");
		}else{
			$query = $mysqli->query("SELECT * FROM `dns_templates` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."'");
		}
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$array[$row['id']]['id'] = $row['id'];
				$array[$row['id']]['name'] = $row['name'];
				$array[$row['id']]['account'] = $row['account'];
			}
			return $array;
		}
	}
	function dns_get_value_overview($account,$type = 'domain',$admin = 2){
		if($type == 'domain'){
			global $mysqli_dns;
			if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
			global $mysqli_dns;
			
			if ( isset( $_POST['submit'] ) ) {
			  $query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' AND `name` LIKE '%$_POST[search]%'"); 
			}
			
		
				
		  elseif(get_value_get('sort') == "tld"){
				$query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY SUBSTRING_INDEX(`name`,'.',-1) ASC,`name` ASC");
			}	
			elseif(get_value_get('sort') == "tld2"){
				$query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY SUBSTRING_INDEX(`name`,'.',-1) DESC,`name` ASC");
			}elseif(get_value_get('sort') == "notld"){
				$query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY SUBSTRING_INDEX(`name`,'.',1) ASC,`name` ASC");
			}elseif(get_value_get('sort') == "notld2"){
				$query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY SUBSTRING_INDEX(`name`,'.',1) DESC,`name` ASC");
			}elseif(get_value_get('sort') == "name2"){
				$query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY `name` DESC");
			}else{
				$query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY `name` ASC");
			}
		}else{
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			$query = $mysqli->query("SELECT * FROM `dns_templates` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."' ORDER BY `name` ASC");
		}
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			$num = 0;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$temp = 0;
				if($admin === 1){
					$temp = 1;
				}elseif(pakketten_check_is_allowed($account,'dns',$admin) != FALSE){
					$temp = 1;
				}else{
					$temp = 0;
				}
				if($temp === 1){
					$num++;
					$return[$row['id']] = $row;
				}
			}
			if($num === 0){
				return FALSE;
			}else{
				return $return;
			}
		}
	}
	function dns_get_value_domain($id){
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		$query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `id` LIKE '".$mysqli_dns->real_escape_string($id)."' LIMIT 1");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				if(pakketten_check_is_allowed(get_value_get('id'),'dns',get_value_session('from_db','is_admin')) === FALSE){
					return FALSE;
				}else{
					return $row;
				}
			}
		}
	}
	function dns_get_value_super($id){
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		$query = $mysqli_dns->query("SELECT * FROM `supermasters` WHERE `id` LIKE '".$mysqli_dns->real_escape_string($id)."'");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				if(pakketten_check_is_allowed(get_value_get('id'),'dns',get_value_session('from_db','is_admin')) === FALSE){
					return FALSE;
				}else{
					return $row;
				}
			}
		}
	}
	function dns_get_value_template($id){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','dns'); }
		global $mysqli;
		$query = $mysqli->query("SELECT * FROM `dns_templates` WHERE `id` LIKE '".$mysqli->real_escape_string($id)."' LIMIT 1");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				if(pakketten_check_is_allowed(get_value_get('id'),'dns',get_value_session('from_db','is_admin')) === FALSE){
					return FALSE;
				}else{
					return $row;
				}
			}
		}
	}
	function dns_get_value_records($account,$id,$type = 'domain',$admin = 2){
		if($type == 'domain'){
			global $mysqli_dns;
			if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
			global $mysqli_dns;
			$query = $mysqli_dns->query("SELECT * FROM `records` WHERE `domain_id` LIKE '".$mysqli_dns->real_escape_string($id)."' ORDER BY `id` ASC");
		}else{
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			$query = $mysqli->query("SELECT * FROM `dns_templates_records` WHERE `template_id` LIKE '".$mysqli->real_escape_string($id)."' ORDER BY `id` ASC");
		}
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			$num = 0;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$temp = 0;
				if($admin === 1){
					$temp = 1;
				}elseif(pakketten_check_is_allowed($account,'dns',$admin) != FALSE){
					$temp = 1;
				}else{
					$temp = 0;
				}
				if($temp === 1 && isset($row['type']) && !empty($row['type']) && $row['type'] !== '' && $row['type'] !== NULL){
					$num++;
					$return[$row['id']] = $row;
				}
			}
			if($num === 0){
				return FALSE;
			}else{
				return $return;
			}
		}
	}
	function dns_get_value_dnssec($account,$id,$type = 'domain',$admin = 2){
		if($type == 'domain'){
			global $mysqli_dns;
			if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
			global $mysqli_dns;
			$sql = 'SELECT id, domainid, type, record FROM dnssec WHERE domainid LIKE "'.$mysqli_dns->real_escape_string($id).'"';
			$query = $mysqli_dns->query($sql);
		}else{
			return FALSE;
		}
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			$num = 0;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$temp = 0;
				if($admin === 1){
					$temp = 1;
				}elseif(pakketten_check_is_allowed($account,'dns',$admin) != FALSE){
					$temp = 1;
				}else{
					$temp = 0;
				}
				if($temp === 1){
					$num++;
					$return[$row['id']] = $row;
				}
			}
			if($num === 0){
				return FALSE;
			}else{
				return $return;
			}
		}
	}
	function dns_create_html_overview($account,$type = 'domain'){
		global $lang;
		global $template_dir;
		$overview = dns_get_value_overview(get_value_get('id'),$type,get_value_session('from_db','is_admin'));
		if($overview == FALSE){

		}else{
			if($type == 'domain'){ $typurl = 'dom'; }else{ $typurl = 'tem'; }
			$html = '';
			$html .= '<div class="tablestop2"><table>';
			if($type == 'domain'){ 
			  $html .= '<tr><td colspan="6"><div style="width:665px;">';
				$html .= '<div style="float: right;"> <form name="submit" method="post" action="">';
        $html .= '<input type="text" id="search" name="search" class="search"><input type="hidden" value="'.get_value_get('sort').'">';
        $html .= '<input type="submit" value="'.$lang->translate(533).'" id="submit" name="submit" class="searchbutton"></form></div>';		  
			  $html .= '</td></tr><tr><td>'.$lang->translate(702).'<div style="float: right;"><a href="?page=dns&type=domoverzicht&id=1&sort=name"><img src="'.$template_dir.'a.png" border="0"></a> <a href="?page=dns&type=domoverzicht&id=1&sort=name2"><img src="'.$template_dir.'z.png" border="0"></a>';
			
			 if(check_user_right(get_value_session('from_db','id'),'dnsdomtoevoegen',get_value_session('from_db','is_admin')) != FALSE){
			 $html .= '&nbsp;&nbsp;<a href="?page=dns&type=domtoevoegen&id='.get_value_get('id').'"><img src="'.$template_dir.'plus.png" border="0" valign="middle" title="'.$lang->translate(606).'"></a>';
			 }
			 $html .= '</div></td><td colspan="5">'.$lang->translate(709).'</td></tr>'; 
			  } else { 
			  $html .= '<tr><td colspan="7"><div style="width:665px;"><div style="float: left;"> ';			  
			  $html .= '</div><div style="float: right;"> <form name="submit" method="post" action="">';
        $html .= '<input type="text" id="search" name="search" class="search"><input type="hidden" value="'.get_value_get('sort').'">';
        $html .= '<input type="submit" value="'.$lang->translate(533).'" id="submit" name="submit" class="searchbutton"></form></div>';	
			  
			  $html .= '</td></tr><tr><td>'.$lang->translate(703).'';
			  
			  			  					if(check_user_right(get_value_session('from_db','id'),'dnstemtoevoegen',get_value_session('from_db','is_admin')) != FALSE){
				$html .= '<div style="float: right;"><a href="?page=dns&type=temtoevoegen&id='.get_value_get('id').'"><img src="'.$template_dir.'plus.png" border="0" valign="middle" title="'.$lang->translate(608).'"></a></div>';
			  }
			  
			  $html .= '</td><td colspan="5">Opties</td></tr>'; 
			   }
			foreach($overview as $domain){
				if($type == 'domain'){ $data = dns_get_value_domain($domain['id']); }else{ $data['type'] = 'NATIVE'; }
		    if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
					$html .= '<tr><td><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bekijken&id='.get_value_get('id').'&'.$typurl.'id='.$domain['id'].'">'.$domain['name'].'</a></td>';
				}else{
					$html .= '<tr><td>'.$domain['name'].'</td>';
				}
				$html .= '<td width="25px">';
		if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
				$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bekijken&id='.get_value_get('id').'&'.$typurl.'id='.$domain['id'].'"><img src="'.$template_dir.'info.png" border="0"  title="'.$lang->translate(1230).'"></a></center>';
		 }
		$html .= '</td>';
		$html .= '<td width="25px">';
				
				
				
				if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bewerken',get_value_session('from_db','is_admin')) != FALSE && $data['type'] != "SLAVE"){
					$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bewerken&id='.get_value_get('id').'&'.$typurl.'id='.$domain['id'].'"><img src="'.$template_dir.'wijzigen.png" border="0" title="'.$lang->translate(1231).'"></a></center>';
				}elseif(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
          $html .= '<center><img src="'.$template_dir.'wijzigen_off.png" border="0"></center>';
				}else{
					$html .= '<center><img src="'.$template_dir.'wijzigen_off.png" border="0"></center>';
				}
				
		
							
    $html .= '</td>';
		$html .= '<td width="25px">';		
				if(check_user_right(get_value_session('from_db','id'),'dnssmdomontkop',get_value_session('from_db','is_admin')) && $data['type'] == "SLAVE"){
					$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(796).'\', \'?lang='.lang_get_value_defaultlang().'&page=dns&type=domsuperontkoppelen&id='.get_value_get('id').'&'.$typurl.'id='.$domain['id'].'\')"><img src="'.$template_dir.'supermaster.png" border="0"  title="'.$lang->translate(1233).'"></a></center>';
				}				
    $html .= '</td>';
		$html .= '<td width="25px">';				
							if(check_user_right(get_value_session('from_db','id'),'dnstemkoppelen',get_value_session('from_db','is_admin') && $data['type'] != "SLAVE") != FALSE){
					if($type == 'domain'){
						if(isset($domain['template']) && $data['type'] != "SLAVE" && $domain['template'] == "0"){
							$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type=domkoppelen&id='.get_value_get('id').'&'.$typurl.'id='.$domain['id'].'"><img src="'.$template_dir.'koppellen.png" border="0" title="'.$lang->translate(1234).'"></a></center>';
						}else{
						}
					}
				}			
    $html .= '</td>';
		$html .= '<td width="25px">';	
				if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'verwijderen',get_value_session('from_db','is_admin')) != FALSE){
					if($type == 'domain'){ 
						$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(726).'\', \'?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'verwijderen&id='.get_value_get('id').'&'.$typurl.'id='.$domain['id'].'\')"><img src="'.$template_dir.'verwijderen.png" border="0" title="'.$lang->translate(1235).'"></a></center>';
					}else{
						$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(757).'\', \'?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'verwijderen&id='.get_value_get('id').'&'.$typurl.'id='.$domain['id'].'\')"><img src="'.$template_dir.'verwijderen.png" border="0" title="'.$lang->translate(1235).'"></a></center>';
					}
				}
				$html .= '</td></tr>';
			}
			$html .= '</table></div>';
		}
		return $html;
	}
	function dns_get_value_pakket($account,$type = 'domain'){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$query = $mysqli->query("SELECT * FROM `pakketten_dns` WHERE `id` LIKE '".$mysqli->real_escape_string($account)."' LIMIT 1");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				if($type == 'domain'){
					return $row['max_domain'];
				}elseif($type == 'template'){
					return $row['max_templates'];
				}else{
					return FALSE;
				}
			}
		}
	}
	function dns_get_value_current_usage($account,$type = 'domain'){
		if($type == 'domain'){
			global $mysqli_dns;
			if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
			global $mysqli_dns;
			$query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."'");
		}else{
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			$query = $mysqli->query("SELECT * FROM `dns_templates` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."'");
		}
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return '0';
		}else{
			return $query->num_rows;
		}
	}
	function dns_do_action_replace_records($account,$id,$type = 'domain',$admin = '2'){
		$array = dns_get_value_records($account,$id,$type,$admin);
		if($type == 'domain'){ $domain = dns_get_value_domain($id); }else{ $domain = 1; }
		if($array === FALSE){
			return FALSE;
		}elseif(get_value_post('ttl','0') === FALSE){
			return FALSE;
		}elseif($domain == FALSE){
			return FALSE;
		}else{
			$count = count($_POST['id']);
			$count = $count-1;
			$ns = 0;
			$temp['name'] = get_value_post('name');
			$temp['ttl'] = get_value_post('ttl');
			$temp['prio'] = get_value_post('prio');
			$temp['type'] = get_value_post('type');
			$temp['content'] = get_value_post('content');
			for($i = 0; $i <= $count; $i++){
				if($temp['name'][$i] != "" && $temp['content'][$i] != ""){
					$info[$i]['name'] = $temp['name'][$i];
					$info[$i]['ttl'] = $temp['ttl'][$i];
					$info[$i]['prio'] = $temp['prio'][$i];
					$info[$i]['type'] = strtoupper($temp['type'][$i]);
					$info[$i]['content'] = $temp['content'][$i];
					if($ns === 0){
						if($info[$i]['type'] == "NS"){ $ns = $temp['content'][$i]; }
					}
				}
			}
			if($ns === 0){ return FALSE;
			}else{
				if($type == 'domain'){
					global $mysqli_dns;
					if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
					global $mysqli_dns;
				}else{
					global $mysqli_dns;
					if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
					global $mysqli_dns;
					global $mysqli;
					if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
					global $mysqli;
				}
				if($type == 'template'){
					if(check_user_right(get_value_session('from_db','id'),'dnsdomglobbew',get_value_session('from_db','is_admin')) !== FALSE){
						$sql2 = 'SELECT * FROM `domains` WHERE `template` LIKE "'.$mysqli->real_escape_string($id).'"';
						$query = $mysqli_dns->query($sql2);
						if(!isset($query) || empty($query) || $query->num_rows == "0"){
						}else{
							while($row = $query->fetch_array(MYSQLI_ASSOC)){
								$mysqli_dns->query("DELETE FROM `records` WHERE `domain_id` LIKE '".$mysqli_dns->real_escape_string($row['id'])."'");
								foreach($info as $record){
									if($record['type'] == "MX"){
										$mysqli->query("INSERT INTO `dns_templates_records` (`domain_id`,`name`,`type`,`content`,`ttl`,`prio`) VALUES ('".$mysqli_dns->real_escape_string($row['id'])."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."','".$mysqli_dns->real_escape_string($record['prio'])."')");
									}else{
										$mysqli->query("INSERT INTO `dns_templates_records` (`domain_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($row['id'])."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."')");
									}
								}
							}
						}
					}
				}
				if($type == 'domain'){
					$mysqli_dns->query("DELETE FROM `records` WHERE `domain_id` LIKE '".$mysqli_dns->real_escape_string($id)."'");
					$soa = $ns.' postmaster.dnsshop.org '.date("YmdH").' 3600 600 86400 900';
					$mysqli_dns->query("INSERT INTO `records` (`domain_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($domain['name'])."','SOA','".$mysqli_dns->real_escape_string($soa)."','900')");
					$mysqli_dns->query("UPDATE `domains` SET `changed` = +1 WHERE `id` = ".$mysqli_dns->real_escape_string($id)." LIMIT 1");
				}else{
					$mysqli->query("DELETE FROM `dns_templates_records` WHERE `template_id` LIKE '".$mysqli->real_escape_string($id)."'");
				}
				foreach($info as $record){
					if($type == 'domain'){
						if($record['type'] == "MX"){
							$mysqli_dns->query("INSERT INTO `records` (`domain_id`,`name`,`type`,`content`,`ttl`,`prio`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."','".$mysqli_dns->real_escape_string($record['prio'])."')");
						}else{
							$mysqli_dns->query("INSERT INTO `records` (`domain_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."')");
						}
					}else{
						if($record['type'] == "MX"){
							$mysqli->query("INSERT INTO `dns_templates_records` (`template_id`,`name`,`type`,`content`,`ttl`,`prio`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."','".$mysqli_dns->real_escape_string($record['prio'])."')");
						}else{
							$mysqli->query("INSERT INTO `dns_templates_records` (`template_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."')");
						}
					}
				}
				return TRUE;
			}
		}
	}
	function dns_create_html_records($account,$id,$type = 'domain',$action = 'bewerk',$admin = '2',$target = FALSE){
		global $lang;
		$array = dns_get_value_records($account,$id,$type,$admin);
		if($array === FALSE){
			$html = '<br /><p>'.$lang->translate(710).'</p><br />';
		}else{
			if($action == 'bekijk'){
				$html = '<div class="tablestop2"><table>';
				$html .= '<tr><td colspan="4">'.$domains['name'].'</td></tr>';
				$html .= '<tr><td>'.$lang->translate(711).'</td><td>'.$lang->translate(712).'</td><td>'.$lang->translate(713).'</td><td>'.$lang->translate(714).'</td></tr>';
				foreach($array as $records){
					if($records['type'] == "SOA"){ }else{
						$html .= '<tr><td>'.$records['name'].'</td><td width="50px">'.$records['type'].'';
						if($records['type'] == "MX"){ $html .= ' '.$records['prio']; }
						$html .= '</td><td>'.$records['ttl'].'</td><td>'.$records['content'].'</td></tr>';
					}
				}
				$html .= '</table></div>';
				$dnssec = dns_get_value_dnssec($account,$id,$type,$admin);
				if($dnssec !== FALSE){
					$html .= '<br /><br /><b>'.$lang->translate(800).'</b>';
					$html .= '<table border="1">';
					$html .= '<tr><td>'.$lang->translate(712).'</td><td>'.$lang->translate(714).'</td></tr>';
					foreach($dnssec as $record){
						$html .= '<tr><td>'.strtoupper($record['type']).'</td><td>'.$record['record'].'</td></tr>';
					}
					$html .= '</table>';								
				}
			}elseif($action == 'bewerk'){
				global $dns_record_types;
				$array_types = $dns_record_types;
				sort($array_types);
				$i = 0;
				if($target === FALSE){
					$html = '<form method="POST" onSubmit="return validate();">';
				}else{
					$html = '<form method="POST" onSubmit="return validate();" action="'.$target.'">';
				}
				if($type == 'domain'){ $html .= '<br /><div class="content"><p>'.$lang->translate(799).'</p></div><br />'; }
				
				$html .= '<div class="tablednstop"><table><tr><td width=170px>'.$lang->translate(1100).'</td><td width=65px>'.$lang->translate(1101).'</td><td width=85px>'.$lang->translate(1102).'</td><td width=50px>'.$lang->translate(1103).'</td><td colspan="3">'.$lang->translate(1104).'</td></tr></table></div>';
	
				$html .= '<div class="tabledns"><table id="tableid">';
        foreach($array as $fields){
					$i++;
					if($fields['type'] == "SOA"){
						if(count($array) == $i){
							$html .= '<tr id="row_'.$i.'"><td></td><td></td><td></td><td></td><td></td><td></td><td id="addrow_'.$i.'"><input type="button" onclick="javascript:addnewrow('.$i.')" value="&nbsp;+&nbsp;" /></td></tr>';
						}
					}else{
						$html .= '
						<tr id="row_'.$i.'">
						<td>
						<input id="id_'.$i.'" name="id[]" type="hidden" value="'.$i.'"/>
						<input name="name[]" id="name_'.$i.'" value=\''.$fields['name'].'\'/></td>
						<td><input name="ttl[]" id="ttl_'.$i.'" value=\''.$fields['ttl'].'\' size=5/></td>
						<td>
						<select name="type[]"id="type_'.$i.'" onchange="onChangeType('.$i.')">';
						foreach($array_types as $types){
							if(strtoupper($types) == strtoupper($fields['type'])){
								$html .= '<option selected="selected" value="'.strtoupper($types).'">'.strtoupper($types).'</option>';
							}else{
								$html .= '<option value="'.strtoupper($types).'">'.strtoupper($types).'</option>';
							}
						}
						$html .= '</select>
						</td>';
						if(strtoupper($fields['type']) != 'MX'){
							$html .= '<td><input name="prio[]" id="prio_'.$i.'" style="display:none"/ size=2></td>';
						}else{
							$html .= '<td><input name="prio[]" id="prio_'.$i.'" style="display:show" value=\''.$fields['prio'].'\' size=2/></td>';
						}
						$html .= '<td><input name="content[]" id="content_'.$i.'" value=\''.$fields['content'].'\'></td>
						<td id="removerow_'.$i.'"><input type="button" onclick="javascript:removerow('.$i.')" value="&nbsp;-&nbsp;" /></td>';
						if(count($array) == $i){
						$html .= '<td id="addrow_'.$i.'"><input type="button" onclick="javascript:addnewrow('.$i.')" value="&nbsp;+&nbsp;" /></td>';
							
						}else{
							$html .= '<td id="addrow_'.$i.'"></td>';
						}
						$html .= '</tr>';
					}
				}
				$html .= '</table></div><div class="content"><p><input type="submit" value="'.$lang->translate(715).'" id="submit" name="submit" class="button"></p></div></form><br /><br />';
			}else{
				$html = '<br /><br />'.$lang->translate(710).'<br /><br />';
			}
		}
		return $html;
	}
	function dns_do_action_search_template($id,$search){
		global $lang;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$results = 0;
		$sql = 'SELECT id, name FROM dns_templates WHERE name LIKE "%'.$mysqli->real_escape_string($search).'%" AND account LIKE "'.$mysqli->real_escape_string($id).'"';
		$query = $mysqli->query($sql);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$results = 1;
				$array['template'][$row['id']]['name'] = $row['name'];
				$array['template'][$row['id']]['account'] = $row['account'];
				$array['template'][$row['id']]['id'] = $row['id'];
			}
		}
		$sql2 = 'SELECT dns_templates.id, dns_templates.name, dns_templates.account FROM dns_templates INNER JOIN dns_templates_records ON (dns_templates.id = dns_templates_records.template_id) WHERE dns_templates_records.name LIKE "%'.$mysqli->real_escape_string($search).'%" AND dns_templates_records.type NOT LIKE "SOA" AND dns_templates.account LIKE "'.$mysqli->real_escape_string($id).'"';
		$query = $mysqli->query($sql2);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$results = 1;
				$array['template'][$row['id']]['name'] = $row['name'];
				$array['template'][$row['id']]['account'] = $row['account'];
				$array['template'][$row['id']]['id'] = $row['id'];
			}
		}
		$sql3 = 'SELECT dns_templates.id, dns_templates.name, dns_templates.account FROM dns_templates INNER JOIN dns_templates_records ON (dns_templates.id = dns_templates_records.template_id) WHERE dns_templates_records.content LIKE "%'.$mysqli->real_escape_string($search).'%" AND dns_templates_records.type NOT LIKE "SOA" AND dns_templates.account LIKE "'.$mysqli->real_escape_string($id).'"';
		$query = $mysqli->query($sql3);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$results = 1;
				$array['template'][$row['id']]['name'] = $row['name'];
				$array['template'][$row['id']]['account'] = $row['account'];
				$array['template'][$row['id']]['id'] = $row['id'];
			}
		}
		if($results !== 1){
			return FALSE;
		}else{
			return $array;
		}
	}
	function dns_do_action_search_domain($id,$search){
		global $lang;
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		$results = 0;
		$sql = 'SELECT id, name, type,account FROM domains WHERE name LIKE "%'.$mysqli_dns->real_escape_string($search).'%" AND account LIKE "'.$mysqli_dns->real_escape_string($id).'"';
		$query = $mysqli_dns->query($sql);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$results = 1;
				$array['domain'][$row['id']]['name'] = $row['name'];
				$array['domain'][$row['id']]['account'] = $row['account'];
				$array['domain'][$row['id']]['type'] = $row['type'];
				$array['domain'][$row['id']]['id'] = $row['id'];
			}
		}
		$sql2 = 'SELECT domains.id, domains.name, domains.account, domains.type FROM domains INNER JOIN records ON (domains.id = records.domain_id) WHERE records.name LIKE "%'.$mysqli_dns->real_escape_string($search).'%" AND records.type NOT LIKE "SOA" AND domains.account LIKE "'.$mysqli_dns->real_escape_string($id).'"';
		$query = $mysqli_dns->query($sql2);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$results = 1;
				$array['domain'][$row['id']]['name'] = $row['name'];
				$array['domain'][$row['id']]['account'] = $row['account'];
				$array['domain'][$row['id']]['type'] = $row['type'];
				$array['domain'][$row['id']]['id'] = $row['id'];
			}
		}
		$sql3 = 'SELECT domains.id, domains.name, domains.account, domains.type FROM domains INNER JOIN records ON (domains.id = records.domain_id) WHERE records.content LIKE "%'.$mysqli_dns->real_escape_string($search).'%" AND records.type NOT LIKE "SOA" AND domains.account LIKE "'.$mysqli_dns->real_escape_string($id).'"';
		$query = $mysqli_dns->query($sql3);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$results = 1;
				$array['domain'][$row['id']]['name'] = $row['name'];
				$array['domain'][$row['id']]['account'] = $row['account'];
				$array['domain'][$row['id']]['type'] = $row['type'];
				$array['domain'][$row['id']]['id'] = $row['id'];
			}
		}
		if($results !== 1){
			return FALSE;
		}else{
			return $array;
		}
	}
	function dns_do_action_search_super($id,$search){
		global $lang;
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		$results = 0;
		$sql = 'SELECT * FROM supermasters WHERE `ip` LIKE "%'.$mysqli_dns->real_escape_string($search).'%" AND account LIKE "'.$mysqli_dns->real_escape_string($id).'"';
		$query = $mysqli_dns->query($sql);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$results = 1;
				$array['super'][$row['id']]['id'] = $row['id'];
				$array['super'][$row['id']]['account'] = $row['account'];
				$array['super'][$row['id']]['ip'] = $row['ip'];
				$array['super'][$row['id']]['nameserver'] = $row['nameserver'];
			}
		}
		$sql2 = 'SELECT * FROM supermasters WHERE `nameserver` LIKE "%'.$mysqli_dns->real_escape_string($search).'%" AND `ip` NOT LIKE "%'.$mysqli_dns->real_escape_string($search).'%" AND account LIKE "'.$mysqli_dns->real_escape_string($id).'"';
		$query2 = $mysqli_dns->query($sql2);
		if(!isset($query2) || empty($query2) || $query2->num_rows == "0"){
		}else{
			while($row2 = $query2->fetch_array(MYSQLI_ASSOC)){
				$results = 1;
				$array['super'][$row2['id']]['id'] = $row2['id'];
				$array['super'][$row2['id']]['account'] = $row2['account'];
				$array['super'][$row2['id']]['ip'] = $row2['ip'];
				$array['super'][$row2['id']]['nameserver'] = $row2['nameserver'];
			}
		}
		if($results !== 1){
			return FALSE;
		}else{
			return $array;
		}
	}
	function dns_do_action_search($id,$search,$type = 'domain',$admin = 2){
		if($type == 'domain'){
			return dns_do_action_search_domain($id,$search);
		}elseif($type == 'template'){
			return dns_do_action_search_template($id,$search);
		}elseif($type == 'super'){
			return dns_do_action_search_super($id,$search);
		}else{
			return FALSE;
		}
	}
	function dns_create_html_searchresults($array = FALSE){
		global $lang;
		global $template_dir;
		if($array === FALSE){
			return FALSE;
		}elseif(isset($array) && is_array($array)){
			$html = '';
			if(isset($array['domain'])){
				$typurl = 'dom';
				$html .= '<div class="tablestop2"><table>';
				$html .= '<tr><td colspan="6"><div style="width:665px;">';
				$html .= '<div style="float: right;"> <form name="submit" method="post" action=""><input type="hidden" name="category" value="'.get_value_post('category').'">';
				$html .= '<input type="text" id="search" name="search" class="search"><input type="hidden" name="sort" value="'.get_value_get('sort').'">';
				$html .= '<input type="submit" value="'.$lang->translate(533).'" id="submit" name="submit" class="searchbutton"></form></div>';		  
				$html .= '</td></tr><tr><td>'.$lang->translate(702);
				$html .= '</div></td><td colspan="5">'.$lang->translate(709).'</td></tr>';
				foreach($array['domain'] as $domain){
					$data = dns_get_value_domain($domain['id']);
					if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<tr><td><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bekijken&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'">'.$domain['name'].'</a></td>';
					}else{
						$html .= '<tr><td>'.$domain['name'].'</td>';
					}
					$html .= '<td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bekijken&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'"><img src="'.$template_dir.'info.png" border="0"  title="'.$lang->translate(1230).'"></a></center>';
					}
					$html .= '</td>';
					$html .= '<td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bewerken',get_value_session('from_db','is_admin')) != FALSE && $data['type'] != "SLAVE"){
						$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bewerken&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'"><img src="'.$template_dir.'wijzigen.png" border="0" title="'.$lang->translate(1231).'"></a></center>';
					}elseif(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<center><img src="'.$template_dir.'wijzigen_off.png" border="0"></center>';
					}else{
						$html .= '<center><img src="'.$template_dir.'wijzigen_off.png" border="0"></center>';
					}
					$html .= '</td>';
					$html .= '<td width="25px">';		
					if(check_user_right(get_value_session('from_db','id'),'dnssmdomontkop',get_value_session('from_db','is_admin')) && $data['type'] == "SLAVE"){
						$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(796).'\', \'?lang='.lang_get_value_defaultlang().'&page=dns&type=domsuperontkoppelen&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'\')"><img src="'.$template_dir.'supermaster.png" border="0"  title="'.$lang->translate(1233).'"></a></center>';
					}				
					$html .= '</td>';
					$html .= '<td width="25px">';				
					if(check_user_right(get_value_session('from_db','id'),'dnstemkoppelen',get_value_session('from_db','is_admin') && $data['type'] != "SLAVE") != FALSE){
						if(isset($domain['template']) && $data['type'] != "SLAVE" && $domain['template'] == "0"){
							$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type=domkoppelen&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'"><img src="'.$template_dir.'koppellen.png" border="0" title="'.$lang->translate(1234).'"></a></center>';
						}
					}			
					$html .= '</td>';
					$html .= '<td width="25px">';	
					if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'verwijderen',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(726).'\', \'?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'verwijderen&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'\')"><img src="'.$template_dir.'verwijderen.png" border="0" title="'.$lang->translate(1235).'"></a></center>';
					}
					$html .= '</td></tr>';
				}
				$html .= '</table></div>';
			}
			if(isset($array['template'])){
				$typurl = 'tem';
				$html .= '<div class="tablestop2"><table>';
				$html .= '<tr><td colspan="6"><div style="width:665px;">';
				$html .= '<div style="float: right;"> <form name="submit" method="post" action="">';
				$html .= '<input type="text" id="search" name="search" class="search"><input type="hidden" name="sort" value="'.get_value_get('sort').'">';
				$html .= '<input type="submit" value="'.$lang->translate(533).'" id="submit" name="submit" class="searchbutton"></form></div>';		  
				$html .= '</td></tr><tr><td>'.$lang->translate(703);
				$html .= '</div></td><td colspan="5">'.$lang->translate(709).'</td></tr>';
				foreach($array['template'] as $domain){
					$data['type'] = 'NATIVE';
					if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<tr><td><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bekijken&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'">'.$domain['name'].'</a></td>';
					}else{
						$html .= '<tr><td>'.$domain['name'].'</td>';
					}
					$html .= '<td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bekijken&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'"><img src="'.$template_dir.'info.png" border="0"  title="'.$lang->translate(1230).'"></a></center>';
					}
					$html .= '</td>';
					$html .= '<td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bewerken',get_value_session('from_db','is_admin')) != FALSE && $data['type'] != "SLAVE"){
						$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bewerken&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'"><img src="'.$template_dir.'wijzigen.png" border="0" title="'.$lang->translate(1231).'"></a></center>';
					}elseif(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<center><img src="'.$template_dir.'wijzigen_off.png" border="0"></center>';
					}else{
						$html .= '<center><img src="'.$template_dir.'wijzigen_off.png" border="0"></center>';
					}
					$html .= '</td>';
					$html .= '<td width="25px">';		
					if(check_user_right(get_value_session('from_db','id'),'dnssmdomontkop',get_value_session('from_db','is_admin')) && $data['type'] == "SLAVE"){
						$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(796).'\', \'?lang='.lang_get_value_defaultlang().'&page=dns&type=domsuperontkoppelen&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'\')"><img src="'.$template_dir.'supermaster.png" border="0"  title="'.$lang->translate(1233).'"></a></center>';
					}				
					$html .= '</td>';
					$html .= '<td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'verwijderen',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(757).'\', \'?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'verwijderen&id='.$domain['account'].'&'.$typurl.'id='.$domain['id'].'\')"><img src="'.$template_dir.'verwijderen.png" border="0" title="'.$lang->translate(1235).'"></a></center>';
					}
					$html .= '</td></tr>';
				}
				$html .= '</table></div>';
			}
			if(isset($array['super'])){
				$html .= '<div class=tablestop2><table>';
				$html .= '<tr><td colspan="5">';
				$html .= '<div style="float: left;"> ';
				$html .= '</td></tr>';
				$html .= '<tr><td>'.$lang->translate(784).'';
				$html .= '</td><td>'.$lang->translate(785).'';	
				$html .= '</td><td>'.$lang->translate(786).'</td><td colspan="2">'.$lang->translate(787).'</td></tr>';
				foreach($array['super'] as $supermaster){
					$domains = dns_get_value_supermaster($supermaster['ip'],$supermaster['account']);
					if($domains === FALSE){
						$domains['count'] = 0;
					}
					$html .= '<tr><td>';
					$html .= $supermaster['ip'];
					$html .= '</td><td>';
					$html .= $supermaster['nameserver'];
					$html .= '</td><td>';
					$html .= $domains['count'];
					$html .= '</td><td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'dnssmbewerken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type=superbewerken&id='.$supermaster['account'].'&superid='.$supermaster['id'].'"><img src="'.$template_dir.'wijzigen.png" border="0" title="'.$lang->translate(788).'"></a></center>';
						
					}
					$html .= '</td><td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'dnssmverwijderen',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(797).'\', \'?lang='.lang_get_value_defaultlang().'&page=dns&type=superverwijderen&id='.$supermaster['account'].'&superid='.$supermaster['id'].'\')"><img src="'.$template_dir.'verwijderen.png" border="0" title="'.$lang->translate(789).'"></a></center>';
					}
					$html .= '</td></tr>';
				}
				$html .= '</table></div>';
			}
			if($html === ''){
				return FALSE;
			}
			return $html;
		}else{
			return FALSE;
		}
		return $html;
	}
	function dns_create_html_search($type){
		global $lang;
		$html = '<br /><br /><form name="form1" method="post" action=""><p><table>';
		$html .= '<tr><td>'.$lang->translate(653).'</td><td><input type="text" id="search" name="search"></td></tr>';
		if($type == 'domain'){
			$html .= '<tr><td></td><td><p><input type="submit" value="'.$lang->translate(719).'" id="submit" name="submit" class="button"></p></td></tr></table></form>';
		}elseif($type == 'template'){
			$html .= '<tr><td></td><td><p><input type="submit" value="'.$lang->translate(720).'" id="submit" name="submit" class="button"></p></td></tr></table></form>';
		}elseif($type == 'super'){
			$html .= '<tr><td></td><td><p><input type="submit" value="'.$lang->translate(790).'" id="submit" name="submit" class="button"></p></td></tr></table></form>';
		}else{
			$html .= '<tr><td></td><td><p><input type="submit" value="'.$lang->translate(719).'" id="submit" name="submit" class="button"></p></td></tr></table></form>';
		}
		return $html;
	}
	function dns_do_action_delete($id,$account,$type = 'domain',$admin = 2){
		global $lang;
		if($type == 'domain'){
			$domain = dns_get_value_domain($id);
			$array = dns_get_value_records($account,$id,$type,$admin);
		}elseif($type == 'template'){
			$domain = dns_get_value_template($id);
			$array = dns_get_value_records($account,$id,$type,$admin);
		}else{
			$domain = dns_get_value_super($id);
			$array = '1';
		}
		if($array === FALSE){
			$return = '<br /><p>'.$lang->translate(723).'</p><br />';
		}elseif($domain == FALSE){
			$return = '<br /><p>'.$lang->translate(723).'</p><br />';
		}else{
			if($type == 'domain'){
				global $mysqli_dns;
				if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
				global $mysqli_dns;
			}elseif($type == 'template'){
				global $mysqli_dns;
				if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
				global $mysqli_dns;
				global $mysqli;
				if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
				global $mysqli;
			}else{
				global $mysqli_dns;
				if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
				global $mysqli_dns;
			}
			if($type == 'domain'){
				$mysqli_dns->query("DELETE FROM `records` WHERE `domain_id` LIKE '".$mysqli_dns->real_escape_string($id)."'");
				$mysqli_dns->query("DELETE FROM `domains` WHERE `id` LIKE '".$mysqli_dns->real_escape_string($id)."'");
				$return = '<br /><br /><div class=content><p>'.$lang->translate(724).'</p></div><br /><br />';
			}elseif($type == 'template'){
				$mysqli->query("DELETE FROM `dns_templates_records` WHERE `template_id` LIKE '".$mysqli->real_escape_string($id)."'");
				$mysqli->query("DELETE FROM `dns_templates` WHERE `id` LIKE '".$mysqli->real_escape_string($id)."'");
				$query = "UPDATE `domains` SET `template` = '0' WHERE `template` = '".$mysqli->real_escape_string($id)."'";
				$mysqli_dns->query($query);
				$return = '<br /><br /><div class=content"><p>'.$lang->translate(725).'</p></div><br /><br />';
			}else{
				$query = "DELETE FROM `supermasters` WHERE `id` = '".$mysqli_dns->real_escape_string($id)."' AND `account` = '".$mysqli_dns->real_escape_string($account)."'";
				$mysqli_dns->query($query);
				$return = '<br /><br /><div class=content"><p>'.$lang->translate(791).'</p></div><br /><br />';
			}
		}
		return $return;
	}
	function dns_get_value_available_templates($account,$typ = 1){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if($typ == 3){
			$sql = 'SELECT * FROM `dns_templates` WHERE `account` LIKE "'.$mysqli->real_escape_string($account).'" OR `account` LIKE "0"';
		}elseif($typ == 2){
			$sql = 'SELECT * FROM `dns_templates` WHERE `account` LIKE "0"';
		}else{
			$sql = 'SELECT * FROM `dns_templates` WHERE `account` LIKE "'.$mysqli->real_escape_string($account).'"';
		}
		$query = $mysqli->query($sql);
		$num = 0;
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$num++;
				$return[$row['id']]['name'] = $row['name'];
				$return[$row['id']]['id'] = $row['id'];
				$return[$row['id']]['account'] = $row['account'];
			}
		}
		if($num === 0){
			return FALSE;
		}else{
			return $return;
		}
	}
	function dns_create_html_met_template($account,$koppelen = 'nee',$admin = 2){
		global $lang;
		$templates = dns_get_value_available_templates($account,3);
		if($templates == FALSE){ $html = '<br /><br />'.$lang->translate(736).'<br /><br />';
		}else{
			$html = '<br /><div class="formtables"><form name="form1" method="post" action=""><input type="hidden" id="koppelen" name="koppelen" value="'.$koppelen.'">';
			$html .= '<p>'.$lang->translate(732).'<br /><input type="text" id="domein" name="domein"><br />';
			$html .= ''.$lang->translate(733).'<br /><input type="text" id="ipv4" name="ipv4"><br />';
			$html .= ''.$lang->translate(734).'<br /><input type="text" id="ipv6" name="ipv6"><br />';
			$html .= ''.$lang->translate(735).'<br /><select name="select_temp"></p>';
			foreach($templates as $template){
				$html .= '<option value="'.$template['id'].'">'.$template['name'].'</option>';
			}
			$html .= '</select><br /><br />';
			$html .= '<input type="submit" value="'.$lang->translate(742).'" id="submit" name="submit" class="button"></div>';
		}
		return $html;
	}
	function dns_create_html_selectie($account){
		global $lang;
		$templates = dns_get_value_available_templates($account,3);
		$html = '<br /><div class=content><p>'.$lang->translate(727).'</p></div><br /><br />';
		$html .= '<form name="select" method="post" action="">';
		if($templates == FALSE){
			$html .= '<div class=content><p><input type="radio" name="template" value="1" checked>'.$lang->translate(728).'</p></div><br />';
			$html .= '<div class=content><p><input type="radio" name="template" value="2" disabled>'.$lang->translate(729).'</p></div><br />';
			$html .= '<div class=content><p><input type="radio" name="template" value="3" disabled>'.$lang->translate(730).'</p></div><br />';
		}else{
			$html .= '<div class=content><p><input type="radio" name="template" value="1">'.$lang->translate(728).'</p></div><br />';
			$html .= '<div class=content><p><input type="radio" name="template" value="2" checked>'.$lang->translate(729).'</p></div><br />';
			$html .= '<div class=content><p><input type="radio" name="template" value="3">'.$lang->translate(730).'</p></div><br />';
		}
		$html .= '<br /><div class=content><p><input type="submit" value="'.$lang->translate(731).'" id="submit" name="submit" class="button">';
		$html .= '</p></div><br /><br />';
		return $html;
	}
	 function dns_do_action_fill_template($replace,$domnaam,$ip4 = FALSE,$ip6 = FALSE){
		if(isset($domnaam) && !empty($domnaam) && $domnaam !== FALSE && $domnaam !== "" && stripos($domnaam,'.') !== FALSE){
			$replace = str_ireplace("@",$domnaam,$replace);
		}
		if(isset($ip4) && !empty($ip4) && $ip4 !== FALSE && $ip4 !== "" && stripos($ip4,'.') !== FALSE){
			$replace = str_ireplace("[IP4]",$ip4,$replace);
			$replace = str_ireplace("[IPv4]",$ip4,$replace);
		}
		if(isset($ip6) && !empty($ip6) && $ip6 !== FALSE && $ip6 !== "" && stripos($ip6,':') !== FALSE){
			$replace = str_ireplace("[IP6]",$ip6,$replace);
			$replace = str_ireplace("[IPv6]",$ip6,$replace);
		}
		return $replace;
	}
	function dns_do_action_toevoegen($account,$type = 'domain',$admin = 2){
		global $lang;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		if(get_value_post('select_temp') != FALSE){
			$templates = dns_get_value_available_templates($account,3);
			$temp = 0;
			foreach($templates as $template_temp){
				if($template_temp['id'] == get_value_post('select_temp')){ $template = $template_temp; $temp = 1; }
			}
			$ns = 0;
			if($temp === 0){ $html = '<br /><br /><p>'.$lang->translate(737).'</p><br /><br />'; }else{
				$sql = 'SELECT * FROM `dns_templates_records` WHERE `template_id` LIKE "'.$mysqli->real_escape_string($template['id']).'"';
				$query = $mysqli->query($sql);
				$num = 0;
				if(!isset($query) || empty($query) || $query->num_rows == "0"){
					$html = '<br /><br /><p>'.$lang->translate(737).'</p><br /><br />';
				}else{
					while($row = $query->fetch_array(MYSQLI_ASSOC)){
						$num++;
						$return[$row['id']]['name'] = dns_do_action_fill_template($row['name'],get_value_post('domein'),get_value_post('ipv4'),get_value_post('ipv6'));
						$return[$row['id']]['type'] = dns_do_action_fill_template($row['type'],get_value_post('domein'),get_value_post('ipv4'),get_value_post('ipv6'));
						$return[$row['id']]['content'] = dns_do_action_fill_template($row['content'],get_value_post('domein'),get_value_post('ipv4'),get_value_post('ipv6'));
						$return[$row['id']]['ttl'] = dns_do_action_fill_template($row['ttl'],get_value_post('domein'),get_value_post('ipv4'),get_value_post('ipv6'));
						$return[$row['id']]['prio'] = dns_do_action_fill_template($row['prio'],get_value_post('domein'),get_value_post('ipv4'),get_value_post('ipv6'));
						if($ns === 0){
							if($return[$row['id']]['type'] == "NS"){ $ns = $return[$row['id']]['content']; }
						}
					}
				}
				if($num === 0 || $ns === 0){ $html = '<br /><br /><p>'.$lang->translate(737).'</p><br /><br />'; }else{
					if(get_value_post('koppelen') == 'ja'){
						$sql2 = 'INSERT INTO `domains` (`name`,`account`,`template`,`type`,`changed`) VALUES ("'.$mysqli_dns->real_escape_string(get_value_post('domein')).'","'.$mysqli_dns->real_escape_string($account).'","'.$mysqli_dns->real_escape_string($template['id']).'","NATIVE","1")';
					}else{
						$sql2 = 'INSERT INTO `domains` (`name`,`account`,`type`,`changed`) VALUES ("'.$mysqli_dns->real_escape_string(get_value_post('domein')).'","'.$mysqli_dns->real_escape_string($account).'","NATIVE","1")';
					}
					//var_dump($sql2);
					$query2 = $mysqli_dns->query($sql2);
					$id = $mysqli_dns->insert_id;
					//var_dump($mysqli_dns->insert_id);
					//var_dump($id);
					if($id != 0){
						$soa = $ns.' postmaster.dnsshop.org '.date("YmdH").' 3600 600 86400 900';
						$mysqli_dns->query("INSERT INTO `records` (`domain_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string(get_value_post('domein'))."','SOA','".$mysqli_dns->real_escape_string($soa)."','900')");
						$mysqli_dns->query("UPDATE `domains` SET `changed` = +1 WHERE `id` = ".$mysqli_dns->real_escape_string($id)." LIMIT 1");
						foreach($return as $record){
							if($type == 'domain'){
								if($record['type'] == "MX"){
									$mysqli_dns->query("INSERT INTO `records` (`domain_id`,`name`,`type`,`content`,`ttl`,`prio`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."','".$mysqli_dns->real_escape_string($record['prio'])."')");
								}else{
									if(stripos($record['name'],'[IPV6]') === FALSE && stripos($record['name'],'[IP6]') === FALSE && stripos($record['name'],'[IPV4]') === FALSE && stripos($record['name'],'[IP4]') === FALSE && stripos($record['content'],'[IPV6]') === FALSE && stripos($record['content'],'[IP6]') === FALSE && stripos($record['content'],'[IPV4]') === FALSE && stripos($record['content'],'[IP4]') === FALSE){
										$mysqli_dns->query("INSERT INTO `records` (`domain_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."')");
									}
								}
							}else{
								if($record['type'] == "MX"){
									$mysqli->query("INSERT INTO `dns_templates_records` (`template_id`,`name`,`type`,`content`,`ttl`,`prio`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."','".$mysqli_dns->real_escape_string($record['prio'])."')");
								}else{
									$mysqli->query("INSERT INTO `dns_templates_records` (`template_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."')");
								}
							}
						}
						$html = '<br /><p>'.$lang->translate(740).'</p><br />';
						if($type == 'domain'){ $typurl = 'dom'; }else{ $typurl = 'tem'; }
						if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bewerken',get_value_session('from_db','is_admin')) != FALSE){
							$html .= dns_create_html_records(get_value_get('id'),$id,$type,'bewerk',get_value_session('from_db','is_admin'),'?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bewerken&id='.get_value_get('id').'&domid='.$id);
						}elseif(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
							$html .= dns_create_html_records(get_value_get('id'),$id,$type,'bekijk',get_value_session('from_db','is_admin'));
						}
					}else{ $html = '<br /><br /><p>'.$lang->translate(737).'</p><br /><br />'; }
				}
			}
		}else{
			$count = count($_POST['id']);
			$count = $count-1;
			$ns = 0;
			$temp['name'] = get_value_post('name');
			$temp['ttl'] = get_value_post('ttl');
			$temp['prio'] = get_value_post('prio');
			$temp['type'] = get_value_post('type');
			$temp['content'] = get_value_post('content');
			for($i = 0; $i <= $count; $i++){
				if(get_value_post('domein') !== FALSE && $type == 'domain'){
					$temp['name'][$i] = dns_do_action_fill_template($temp['name'][$i],get_value_post('domein'));
					$temp['content'][$i] = dns_do_action_fill_template($temp['content'][$i],get_value_post('domein'));
				}
				$info[$i]['name'] = $temp['name'][$i];
				$info[$i]['ttl'] = $temp['ttl'][$i];
				$info[$i]['prio'] = $temp['prio'][$i];
				$info[$i]['type'] = strtoupper($temp['type'][$i]);
				$info[$i]['content'] = $temp['content'][$i];
				if($ns === 0){
					if($info[$i]['type'] == "NS"){ $ns = $info[$i]['content']; }
				}
			}
			if($ns === 0){ if($type == 'domain'){ $html = '<br /><br />'.$lang->translate(738).'<br /><br />'; }else{ $html = '<br /><br />'.$lang->translate(739).'<br /><br />'; }
			}else{
				if($type == 'domain'){
					global $mysqli_dns;
					if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
					global $mysqli_dns;
				}else{
					global $mysqli;
					if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
					global $mysqli;
				}
				if($type == 'domain'){
					$sql2 = 'INSERT INTO `domains` (`name`,`account`,`changed`,`type`) VALUES ("'.$mysqli_dns->real_escape_string(get_value_post('domein')).'","'.$mysqli_dns->real_escape_string($account).'","1","NATIVE")';
					$query2 = $mysqli_dns->query($sql2);
					$id = $mysqli_dns->insert_id;
					if($id == 0){ $html = '<br /><br />'.$lang->translate(738).'<br /><br />'; }else{
						$soa = $ns.' postmaster.dnsshop.org '.date("YmdH").' 3600 600 86400 900';
						$mysqli_dns->query("INSERT INTO `records` (`domain_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string(get_value_post('domein'))."','SOA','".$mysqli_dns->real_escape_string($soa)."','900')");
						$mysqli_dns->query("UPDATE `domains` SET `changed` = +1 WHERE `id` = ".$mysqli_dns->real_escape_string($id)." LIMIT 1");
					}
				}else{
					$sql2 = 'INSERT INTO `dns_templates` (`name`,`account`) VALUES ("'.$mysqli->real_escape_string(get_value_post('domein')).'","'.$mysqli_dns->real_escape_string($account).'")';
					$query2 = $mysqli->query($sql2);
					$id = $mysqli->insert_id;
					if($id == 0){ $html = '<br /><br />'.$lang->translate(739).'<br /><br />'; }
				}
				if($id != 0){
					foreach($info as $record){
						if($type == 'domain'){
							if($record['type'] == "MX"){
								$mysqli_dns->query("INSERT INTO `records` (`domain_id`,`name`,`type`,`content`,`ttl`,`prio`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."','".$mysqli_dns->real_escape_string($record['prio'])."')");
							}else{
								$mysqli_dns->query("INSERT INTO `records` (`domain_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."')");
							}
						}else{
							if($record['type'] == "MX"){
								$mysqli->query("INSERT INTO `dns_templates_records` (`template_id`,`name`,`type`,`content`,`ttl`,`prio`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."','".$mysqli_dns->real_escape_string($record['prio'])."')") or die($mysqli->error);
							}else{
								$mysqli->query("INSERT INTO `dns_templates_records` (`template_id`,`name`,`type`,`content`,`ttl`) VALUES ('".$mysqli_dns->real_escape_string($id)."','".$mysqli_dns->real_escape_string($record['name'])."','".$mysqli_dns->real_escape_string($record['type'])."','".$mysqli_dns->real_escape_string($record['content'])."','".$mysqli_dns->real_escape_string($record['ttl'])."')") or die($mysqli->error);
							}
						}
					}
				}
				if($id != 0){ if($type == 'domain'){ $html = '<br /><p>'.$lang->translate(740).'</p><br />'; }else{ $html = '<br /><p>'.$lang->translate(741).'</p><br />'; } }
				if($id != 0){ 
					if($type == 'domain'){ $typurl = 'dom'; }else{ $typurl = 'tem'; }
					if(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bewerken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= dns_create_html_records(get_value_get('id'),$id,$type,'bewerk',get_value_session('from_db','is_admin'),'?lang='.lang_get_value_defaultlang().'&page=dns&type='.$typurl.'bewerken&id='.get_value_get('id').'&domid='.$id);
					}elseif(check_user_right(get_value_session('from_db','id'),'dns'.$typurl.'bekijken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= dns_create_html_records(get_value_get('id'),$id,$type,'bekijk',get_value_session('from_db','is_admin'));
					}
				}
			}
		}
		return $html;
	}
	function dns_create_html_toevoegen($account,$type = 'domain',$admin = 2){
		global $lang;
		global $dns_record_types;
		$array_types = $dns_record_types;
		$i = 0;
		$html = '<br /><br /><p>';
		if($type == 'template'){
			$html .= $lang->translate(744).'<br /><br /></p>';
		}else{
			$html .= $lang->translate(801).'<br /><br /></p>';
		}
		$html .= '<form method="POST" onSubmit="return validate();">';
		if($type == 'domain'){ $html .= '<p>'.$lang->translate(732).'</p><br /><p><input type="text" id="domein" name="domein"><br /></p>'; }else{ $html .= '<p><br />'.$lang->translate(743).'</p><br /><br /><p><input type="text" id="domein" name="domein"></p><br />'; }

		$html .= '<div class="tablednstop"><table><tr><td width=170px>'.$lang->translate(1100).'</td><td width=65px>'.$lang->translate(1101).'</td><td width=85px>'.$lang->translate(1102).'</td><td width=50px>'.$lang->translate(1103).'</td><td colspan="3">'.$lang->translate(1104).'</td></tr></table></div>';
	

		$html .= '<div class=tabledns style="width:665px;"><table id="tableid">';
		
		sort($array_types);
		$array[1] = array("id" => 1,"name" => "@","ttl" => 900,"content" => "10.0.0.1", "type" => "A");
		$array[2] = array("id" => 2,"name" => "@","ttl" => 900,"content" => "mail.@", "type" => "MX", "prio" => 10);
		$array[3] = array("id" => 3,"name" => "@","ttl" => 900,"content" => "ns.@", "type" => "NS");
		foreach($array as $fields){
			$i++;
			if($fields['type'] == "SOA"){
				if(count($array) == $i){
					$html .= '<tr id="row_'.$i.'"><td></td><td></td><td></td><td></td><td></td><td></td><td id="addrow_'.$i.'"><input type="button" onclick="javascript:addnewrow('.$i.')" value="&nbsp;+&nbsp;"/></td></tr>';
				}
			}else{
				$html .= '
				<tr id="row_'.$i.'">
				<td>
				<input id="id_'.$i.'" name="id[]" type="hidden" value="'.$i.'"/>
				<input name="name[]" id="name_'.$i.'" value="'.$fields['name'].'"/></td>
				<td><input name="ttl[]" id="ttl_'.$i.'" value="'.$fields['ttl'].'" size=5/></td>
				<td>
				<select name="type[]"id="type_'.$i.'" onchange="onChangeType('.$i.')">';
				foreach($array_types as $types){
					if(strtoupper($types) == strtoupper($fields['type'])){
						$html .= '<option selected="selected" value="'.strtoupper($types).'">'.strtoupper($types).'</option>';
					}else{
						$html .= '<option value="'.strtoupper($types).'">'.strtoupper($types).'</option>';
					}
				}
				$html .= '</select>
				</td>';
				if(strtoupper($fields['type']) != 'MX'){
					$html .= '<td><input name="prio[]" id="prio_'.$i.'" style="display:none" size=2/></td>';
				}else{
					$html .= '<td><input name="prio[]" id="prio_'.$i.'" style="display:show" value=\''.$fields['prio'].'\'/ size=2></td>';
				}
				$html .= '<td><input name="content[]" id="content_'.$i.'" value=\''.$fields['content'].'\'></td>
				<td id="removerow_'.$i.'"><input type="button" onclick="javascript:removerow('.$i.')" value="&nbsp;-&nbsp;"/></td>';
				if(count($array) == $i){
					$html .= '<td id="addrow_'.$i.'"><input type="button" onclick="javascript:addnewrow('.$i.')" value="&nbsp;+&nbsp;"/></td>';
				}else{
					$html .= '<td id="addrow_'.$i.'"></td>';
				}
				$html .= '</tr>
				<tr>';
			}
		}
		if($type == 'template'){
			$html .= '</table></div><p><input type="submit" value="'.$lang->translate(745).'" id="submit" name="submit" class=button></p></form><br /><br />';
		}else{
			$html .= '</table></div><p><input type="submit" value="'.$lang->translate(742).'" id="submit" name="submit" class=button></p></form><br /><br />';
		}
		return $html;
	}
	function dns_do_action_koppelen($account,$id){
		global $lang;
		$domain = dns_get_value_domain($id);
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		if(get_value_post('select_temp') == FALSE){
			$newtemplate = 0;
		}else{
			$newtemplate = get_value_post('select_temp');
		}
		$query = "UPDATE `domains` SET `template` = '".$mysqli_dns->real_escape_string($newtemplate)."' WHERE `id` = '".$mysqli_dns->real_escape_string($id)."' AND `account` = '".$mysqli_dns->real_escape_string($account)."' LIMIT 1";
		$mysqli_dns->query($query);
		$html = '<br /><br /><div class=content><p>';
		$html .= $lang->translate(751).'</p></div><br /><br />';
		return $html;
	}
	function dns_create_html_koppelen($account,$id){
		global $lang;
		$domain = dns_get_value_domain($id);
		$templates = dns_get_value_available_templates($account,3);
		$html = '<br /><br />';
		if($domain == FALSE || $templates == FALSE){
			$html .= $lang->translate(746).'<br /><br />';
		}elseif($domain['account'] != $account){
			$html .= $lang->translate(746).'<br /><br />';
		}else{
			$html = '<br /><br /><form name="form1" method="post" action="">';
			$html .= '<div class=content><p>'.$lang->translate(747).'<br /><b>'.$domain['name'].'</b></p></div><br /><br />';
			$html .= '<div class=content><p>'.$lang->translate(748).'</p></div><br /><div class=content><p><select name="select_temp">';
			if($domain['template'] == "0"){
				$html .= '<option value="0">'.$lang->translate(749).'</option>';
			}else{
				$html .= '<option value="0">'.$lang->translate(749).'</option>';
			}
			foreach($templates as $template){
				if($template['id'] == $domain['template']){
					$html .= '<option value="'.$template['id'].'" selected>'.$template['name'].'</option>';
				}else{
					$html .= '<option value="'.$template['id'].'">'.$template['name'].'</option>';
				}
			}
			$html .= '</select></p></div><br /><br />';
			$html .= '<div class=content><p><input type="submit" value="'.$lang->translate(750).'" id="submit" name="submit" class=button></p></div><br /><br />';
		}
		return $html;
	}
	function dns_create_html_recglobbew($account){
		global $lang;
		$html = '<br /><br /><div class=content><p>'.$lang->translate(755).'</p>';
		$html .= '<br /><br /><form name="form1" method="post" action="">';
		$html .= '<p>'.$lang->translate(752).'<br /><input type="text" name="oud" id="oud"><br /><br /><br /></p>';
		$html .= '<p>'.$lang->translate(753).'<br /><input type="text" name="nieuw" id="nieuw"><br /><br /></p>';
		$html .= '<br /><p><input type="submit" value="'.$lang->translate(754).'" id="submit" name="submit" class=button></p></div><br /><br />';
		return $html;
	}
	function dns_do_action_recglobbew($account,$oud,$nieuw){
		global $lang;
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$numdom = dns_get_value_current_usage($account,'domain');
		$numtem = dns_get_value_current_usage($account,'template');
		$num = 0;
		if($numdom != FALSE && $numdom != 0){
			$sql2 = 'SELECT records.id,records.domain_id FROM records INNER JOIN domains ON (records.domain_id = domains.id) WHERE records.name LIKE "'.$mysqli_dns->real_escape_string($oud).'" AND records.type NOT LIKE "SOA" AND domains.account LIKE "'.$mysqli_dns->real_escape_string($account).'"';
			$query = $mysqli_dns->query($sql2);
			if(!isset($query) || empty($query) || $query->num_rows == "0"){
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$num++;
					$update = 'UPDATE `records` SET `name` = "'.$mysqli_dns->real_escape_string($nieuw).'" WHERE `id` = '.$row['id'].' LIMIT 1';
					$mysqli_dns->query($update);
					$mysqli_dns->query("UPDATE `domains` SET `changed` = +1 WHERE `id` = ".$row['domain_id']." LIMIT 1");
				}
			}
			$sql2 = 'SELECT records.id FROM records INNER JOIN domain ON (records.domain_id = domains.id) WHERE records.type LIKE "'.$mysqli_dns->real_escape_string($oud).'" AND records.type NOT LIKE "SOA" AND domains.account LIKE "'.$mysqli_dns->real_escape_string($account).'"';
			$query = $mysqli_dns->query($sql2);
			if(!isset($query) || empty($query) || $query->num_rows == "0"){
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$num++;
					$update = 'UPDATE `records` SET `type` = "'.$mysqli_dns->real_escape_string($nieuw).'" WHERE `id` = '.$row['id'].' LIMIT 1';
					$mysqli_dns->query($update);
					$mysqli_dns->query("UPDATE `domains` SET `changed` = +1 WHERE `id` = ".$row['domain_id']." LIMIT 1");
				}
			}
			$sql2 = 'SELECT records.id FROM records INNER JOIN domains ON (records.domain_id = domains.id) WHERE records.content LIKE "'.$mysqli_dns->real_escape_string($oud).'" AND records.type NOT LIKE "SOA" AND domains.account LIKE "'.$mysqli_dns->real_escape_string($account).'"';
			$query = $mysqli_dns->query($sql2);
			if(!isset($query) || empty($query) || $query->num_rows == "0"){
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$num++;
					$update = 'UPDATE `records` SET `content` = "'.$mysqli_dns->real_escape_string($nieuw).'" WHERE `id` = '.$row['id'].' LIMIT 1';
					$mysqli_dns->query($update);
					$mysqli_dns->query("UPDATE `domains` SET `changed` = +1 WHERE `id` = ".$row['domain_id']." LIMIT 1");
				}
			}
			$sql2 = 'SELECT records.id FROM records INNER JOIN domains ON (records.domain_id = domains.id) WHERE records.ttl LIKE "'.$mysqli_dns->real_escape_string($oud).'" AND records.type NOT LIKE "SOA" AND domains.account LIKE "'.$mysqli_dns->real_escape_string($account).'"';
			$query = $mysqli_dns->query($sql2);
			if(!isset($query) || empty($query) || $query->num_rows == "0"){
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$num++;
					$update = 'UPDATE `records` SET `ttl` = "'.$mysqli_dns->real_escape_string($nieuw).'" WHERE `id` = '.$row['id'].' LIMIT 1';
					$mysqli_dns->query($update);
					$mysqli_dns->query("UPDATE `domains` SET `changed` = +1 WHERE `id` = ".$row['domain_id']." LIMIT 1");
				}
			}
		}
		if($numtem != FALSE && $numtem != 0){
			$sql2 = 'SELECT dns_templates_records.id FROM dns_templates_records INNER JOIN dns_templates ON (dns_templates_records.template_id = dns_templates.id) WHERE dns_templates_records.name LIKE "'.$mysqli->real_escape_string($oud).'" AND dns_templates_records.type NOT LIKE "SOA" AND dns_templates.account LIKE "'.$mysqli->real_escape_string($account).'"';
			$query = $mysqli->query($sql2);
			if(!isset($query) || empty($query) || $query->num_rows == "0"){
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$num++;
					$update = 'UPDATE `dns_templates_records` SET `name` = "'.$mysqli->real_escape_string($nieuw).'" WHERE `id` = '.$row['id'].' LIMIT 1';
					$mysqli->query($update);
				}
			}
			$sql2 = 'SELECT dns_templates_records.id FROM dns_templates_records INNER JOIN dns_templates ON (dns_templates_records.template_id = dns_templates.id) WHERE dns_templates_records.type LIKE "'.$mysqli->real_escape_string($oud).'" AND dns_templates_records.type NOT LIKE "SOA" AND dns_templates.account LIKE "'.$mysqli->real_escape_string($account).'"';
			$query = $mysqli->query($sql2);
			if(!isset($query) || empty($query) || $query->num_rows == "0"){
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$num++;
					$update = 'UPDATE `dns_templates_records` SET `type` = "'.$mysqli->real_escape_string($nieuw).'" WHERE `id` = '.$row['id'].' LIMIT 1';
					$mysqli->query($update);
				}
			}
			$sql2 = 'SELECT dns_templates_records.id FROM dns_templates_records INNER JOIN dns_templates ON (dns_templates_records.template_id = dns_templates.id) WHERE dns_templates_records.content LIKE "'.$mysqli->real_escape_string($oud).'" AND dns_templates_records.type NOT LIKE "SOA" AND dns_templates.account LIKE "'.$mysqli->real_escape_string($account).'"';
			$query = $mysqli->query($sql2);
			if(!isset($query) || empty($query) || $query->num_rows == "0"){
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$num++;
					$update = 'UPDATE `dns_templates_records` SET `content` = "'.$mysqli->real_escape_string($nieuw).'" WHERE `id` = '.$row['id'].' LIMIT 1';
					$mysqli->query($update);
				}
			}
			$sql2 = 'SELECT dns_templates_records.id FROM dns_templates_records INNER JOIN dns_templates ON (dns_templates_records.template_id = dns_templates.id) WHERE dns_templates_records.ttl LIKE "'.$mysqli->real_escape_string($oud).'" AND dns_templates_records.type NOT LIKE "SOA" AND dns_templates.account LIKE "'.$mysqli->real_escape_string($account).'"';
			$query = $mysqli->query($sql2);
			if(!isset($query) || empty($query) || $query->num_rows == "0"){
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$num++;
					$update = 'UPDATE `dns_templates_records` SET `ttl` = "'.$mysqli->real_escape_string($nieuw).'" WHERE `id` = '.$row['id'].' LIMIT 1';
					$mysqli->query($update);
				}
			}
		}
		return '<br /><br /><p>'.$num.$lang->translate(756).'</p><br /><br />';
	}
	function dns_get_number_supermasters($account,$sort = FALSE){
		global $mysqli_dns;
		global $lang;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		if($sort == "ns"){
			$sql = "SELECT * FROM `supermasters` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY `nameserver` ASC";
		}elseif($sort == "ns2"){
			$sql = "SELECT * FROM `supermasters` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY `nameserver` DESC";
		}elseif($sort == "ip"){
			$sql = "SELECT * FROM `supermasters` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY `ip` ASC";
		}elseif($sort == "ip2"){
			$sql = "SELECT * FROM `supermasters` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' ORDER BY `ip` DESC";
		}else{
			$sql = "SELECT * FROM `supermasters` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."'";
		}
		$query = $mysqli_dns->query($sql);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$array[$row['id']]['id'] = $row['id'];
				$array[$row['id']]['ip'] = $row['ip'];
				$array[$row['id']]['nameserver'] = $row['nameserver'];
				$array[$row['id']]['account'] = $row['account'];
			}
			return $array;
		}
	}
	function dns_create_html_supertoevoegen($account,$admin = 2){
		global $lang;
		$html = '<br /><br />';
		$html .= '<p>'.$lang->translate(773).'<br /><br /><br /></p>';
		$html .= '<form method="POST">';
		$html .= '<p>'.$lang->translate(774).'<br /><br /></p><p><input type="text" id="ip" name="ip"></p><br /><br />';
		$html .= '<p>'.$lang->translate(775).'<br /><br /></p><p><input type="text" id="name" name="name"></p><br /><br />';
		$html .= '<p><input type="submit" value="'.$lang->translate(776).'" id="submit" name="submit" class="button"></p></form><br /><br />';
		return $html;
	}
	function dns_do_action_supertoevoegen($account){
		global $lang;
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		if(get_value_post('ip') == FALSE){
			$html = '<br /><br /><div class="content"><p>'.$lang->translate(777).'</p></div><br /><br />';
		}elseif(get_value_post('name') == FALSE){
			$html = '<br /><br /><div class="content"><p>'.$lang->translate(777).'</p></div><br /><br />';
		}else{
			$sql = 'INSERT INTO `supermasters` (`ip`,`nameserver`,`account`) VALUES ("'.$mysqli_dns->real_escape_string(get_value_post('ip')).'","'.$mysqli_dns->real_escape_string(get_value_post('name')).'","'.$mysqli_dns->real_escape_string($account).'")';
			$mysqli_dns->query($sql);
			$html = '<br /><br /><div class="content"><p>'.$lang->translate(778).'</p></div><br /><br />';
		}
		return $html;
	}
	function dns_get_value_supermaster($ip,$account){
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		$sql = "SELECT * FROM `domains` WHERE `master` LIKE '".$mysqli_dns->real_escape_string($ip)."' AND `account` LIKE '".$mysqli_dns->real_escape_string($account)."'";
		$query = $mysqli_dns->query($sql);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$array[$row['id']]['id'] = $row['id'];
				$array[$row['id']]['name'] = $row['name'];
				$array[$row['id']]['master'] = $row['master'];
				$array[$row['id']]['type'] = $row['type'];
			}
			$array['count'] = $query->num_rows;
			return $array;
		}
	}
	function dns_create_html_superoverzicht($account){
		global $lang;
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
		global $mysqli_dns;
		if(get_value_get('sort') != FALSE){
			$supermasters = dns_get_number_supermasters($account,get_value_get('sort'));
		}else{
			$supermasters = dns_get_number_supermasters($account);
		}
		if($supermasters == FALSE){
			$html .= '<div class="content"><p>'.$lang->translate(779).'</p></div><br /><br />';
		}else{
			$html .= '<div class=tablestop2><table>';
			$html .= '<tr><td colspan="5">';
			
			$html .= '<div style="float: left;"> ';
			
			$html .= '</td></tr>';
			$html .= '<tr><td>'.$lang->translate(784).'';
			
								if(get_value_session('from_db','is_admin') == '1'){
						if(get_value_get('id') !== FALSE){
		   $html .= '<div style="float: right;"><a href="?page=dns&type=supertoevoegen&id='.get_value_get('id').'"><img src="'.$template_dir.'plus.png" border="0" valign="middle" title="'.$lang->translate(334).'"></a></div>';
						}
					}
					
			$html .= '</td><td>'.$lang->translate(785).'';	
			
												if(get_value_session('from_db','is_admin') == '1'){
						if(get_value_get('id') !== FALSE){
		   $html .= '<div style="float: right;"><a href="?page=dns&type=supertoevoegen&id='.get_value_get('id').'"><img src="'.$template_dir.'plus.png" border="0" valign="middle" title="'.$lang->translate(334).'"></a></div>';
						}
					}	
			
			$html .= '</td><td>'.$lang->translate(786).'</td><td colspan="2">'.$lang->translate(787).'</td></tr>';
			foreach($supermasters as $supermaster){
				$domains = dns_get_value_supermaster($supermaster['ip'],$account);
				if($domains === FALSE){
					$domains['count'] = 0;
				}
				$html .= '<tr><td>';
				$html .= $supermaster['ip'];
				$html .= '</td><td>';
				$html .= $supermaster['nameserver'];
				$html .= '</td><td>';
				$html .= $domains['count'];
				$html .= '</td><td width="25px">';
			
				if(check_user_right(get_value_session('from_db','id'),'dnssmbewerken',get_value_session('from_db','is_admin')) != FALSE){
					
					$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=dns&type=superbewerken&id='.get_value_get('id').'&superid='.$supermaster['id'].'"><img src="'.$template_dir.'wijzigen.png" border="0" title="'.$lang->translate(788).'"></a></center>';
					
				}
				$html .= '</td><td width="25px">';
				if(check_user_right(get_value_session('from_db','id'),'dnssmverwijderen',get_value_session('from_db','is_admin')) != FALSE){
					
					$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(797).'\', \'?lang='.lang_get_value_defaultlang().'&page=dns&type=superverwijderen&id='.get_value_get('id').'&superid='.$supermaster['id'].'\')"><img src="'.$template_dir.'verwijderen.png" border="0" title="'.$lang->translate(789).'"></a></center>';
					
				}
				$html .= '</td></tr>';
			}
			$html .= '</table></div>';
		}
		return $html;
	}
	function dns_create_html_superbewerken($id,$account,$admin = 2){
		global $lang;
		$data = dns_get_value_super($id);
		if(pakketten_check_is_allowed($data['account'],'DNS',$admin)){
			$html = '<br /><br />';
			$html .= '<form method="POST">';
			$html .= '<p>'.$lang->translate(774).'</p><br><p><input type="text" id="ip" name="ip" value="'.$data['ip'].'"></p><br><br>';
			$html .= '<p>'.$lang->translate(775).'</p><br><p><input type="text" id="name" name="name" value="'.$data['nameserver'].'"></p><br><br>';
			$html .= '<p><input type="submit" value="'.$lang->translate(793).'" id="submit" name="submit" class="button"></p></form><br /><br />';
		}else{
			$html = '<br /><br />';
			$html .= '<p>'.$lang->translate(792).'</p><br /><br />';
		}
		return $html;
	}
	function dns_do_action_superbewerken($id,$account,$admin = 2){
		global $lang;
		$data = dns_get_value_super($id);
		if(pakketten_check_is_allowed($data['account'],'DNS',$admin)){
			global $mysqli_dns;
			if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
			global $mysqli_dns;
			if(get_value_post('ip') == FALSE){
				$html = '<br /><br /><p>'.$lang->translate(777).'</p><br /><br />';
			}elseif(get_value_post('name') == FALSE){
				$html = '<br /><br /><p>'.$lang->translate(777).'</p><br /><br />';
			}else{
				$sql = 'UPDATE `supermasters` SET `ip` = "'.$mysqli_dns->real_escape_string(get_value_post('ip')).'", `nameserver` = "'.$mysqli_dns->real_escape_string(get_value_post('name')).'", `account` = "'.$mysqli_dns->real_escape_string($account).'" WHERE `id` = "'.$mysqli_dns->real_escape_string($id).'"';
				$mysqli_dns->query($sql);
				$html = '<br /><br />'.$lang->translate(794).'<br /><br />';
			}
		}else{
			$html = '<br /><br />';
			$html .= $lang->translate(792).'<br /><br />';
		}
		return $html;
	}
	function dns_do_action_superontkoppelen($id,$account,$admin = 2){
		global $lang;
		$data = dns_get_value_domain($id);
		if(pakketten_check_is_allowed($data['account'],'DNS',$admin)){
			global $mysqli_dns;
			if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
			global $mysqli_dns;
			$sql = 'UPDATE `domains` SET `type` = "NATIVE", `master` = NULL, `last_check` = NULL WHERE `id` = "'.$mysqli_dns->real_escape_string($id).'"';
			$mysqli_dns->query($sql);
			$html = '<br /><br /><p>'.$lang->translate(798).'</p><br /><br />';
		}else{
			$html = '<br /><br />';
			$html .= $lang->translate(792).'<br /><br />';
		}
		return $html;
	}
	