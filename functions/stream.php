<?php
// Created by Mark Scholten
	function stream_get_number_streams($account){
		global $mysqli;
		global $lang;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."'");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$array[$row['id']]['id'] = $row['id'];
				$array[$row['id']]['name'] = $row['name'];
				$array[$row['id']]['poort'] = $row['poort'];
				$array[$row['id']]['admin_user'] = $row['admin_user'];
				$array[$row['id']]['bitrate'] = $row['bitrate'];
				$array[$row['id']]['host'] = $row['host'];
				$array[$row['id']]['max_listeners'] = $row['max_listeners'];
				$array[$row['id']]['source_pass'] = $row['source_pass'];
				$array[$row['id']]['source_pass_1'] = $row['source_pass_1'];
				$array[$row['id']]['source_pass_2'] = $row['source_pass_2'];
				$array[$row['id']]['source_pass_3'] = $row['source_pass_3'];
				$array[$row['id']]['source_pass_4'] = $row['source_pass_4'];
				$array[$row['id']]['source_pass_5'] = $row['source_pass_5'];
				$array[$row['id']]['source_pass_auto'] = $row['source_pass_auto'];
				$array[$row['id']]['relay_pass'] = $row['relay_pass'];
				$array[$row['id']]['mountpoint'] = $row['mountpoint'];
				$array[$row['id']]['master_server'] = $row['master_server'];
				$array[$row['id']]['master_port'] = $row['master_port'];
				$array[$row['id']]['master_user'] = $row['master_user'];
				$array[$row['id']]['master_pass'] = $row['master_pass'];
				$array[$row['id']]['status'] = $row['status'];
			}
			return $array;
		}
	}
	function stream_get_value_overview($account,$admin = 2){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if(get_value_get('sort') == "poort"){
			$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."' ORDER BY `poort` ASC,`name` ASC");
		}elseif(get_value_get('sort') == "poort2"){
			$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."' ORDER BY `poort` DESC,`name` ASC");
		}elseif(get_value_get('sort') == "bitrate"){
			$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."' ORDER BY `bitrate` ASC,`name` ASC");
		}elseif(get_value_get('sort') == "bitrate2"){
			$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."' ORDER BY `bitrate` DESC,`name` ASC");
		}elseif(get_value_get('sort') == "host"){
			$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."' ORDER BY `host` ASC,`name` ASC");
		}elseif(get_value_get('sort') == "host2"){
			$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."' ORDER BY `host` DESC,`name` ASC");
		}elseif(get_value_get('sort') == "name2"){
			$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."' ORDER BY `name` DESC");
		}else{
			$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."' ORDER BY `name` ASC");
		}
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			$num = 0;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$temp = 0;
				if($admin === 1){
					$temp = 1;
				}elseif(pakketten_check_is_allowed($account,'stream',$admin) != FALSE){
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
	function stream_get_details($streamid,$account,$limit=1){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if($limit === 1){
			$sql2 = 'SELECT * FROM `stream_streams` WHERE `id` LIKE "'.$mysqli->real_escape_string($streamid).'" AND `account` LIKE "'.$mysqli->real_escape_string($account).'" LIMIT 1';
		}else{
			$sql2 = 'SELECT * FROM `stream_streams` WHERE `id` LIKE "'.$mysqli->real_escape_string($streamid).'" AND `account` LIKE "'.$mysqli->real_escape_string($account).'"';
		}
		$query2 = $mysqli->query($sql2);
		if(!isset($query2) || empty($query2) || $query2->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query2->fetch_array(MYSQLI_ASSOC)){
				if($limit === 1){
					return $row;
				}
				$return[] = $row;
			}
			return $return;
		}
	}
	function stream_create_html_action($account,$streamid,$actie){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		global $lang;
		$details = stream_get_details($streamid,$account);
		if($details === FALSE){
			return '';
		}
		$html = '';
		if($actie == 'streamdjstop'){
			$sql = "UPDATE `stream_streams` SET `djstatus` = '0',`changed2` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
			$html .= '<br /><br />'.$lang->translate(981).': '.$details['name'].'<br />';
		}elseif($actie == 'streamdjstart'){
			if($details['status'] != '1' && $details['status'] == '0'){
				$sql = "UPDATE `stream_streams` SET `djstatus` = '1',`changed2` = '1',`status` = '1',`changed` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
				$html .= '<br /><br />'.$lang->translate(978).': '.$details['name'].'<br />';
			}else{
				$sql = "UPDATE `stream_streams` SET `djstatus` = '1',`changed2` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
			}
			$html .= '<br /><br />'.$lang->translate(980).': '.$details['name'].'<br />';
		}elseif($actie == 'streamstop'){
			if($details['djstatus'] == '1' && $details['djstatus'] != '0'){
				$html .= '<br /><br />'.$lang->translate(981).': '.$details['name'].'<br />';
			}
			$html .= '<br /><br />'.$lang->translate(979).': '.$details['name'].'<br />';
			$sql = "UPDATE `stream_streams` SET `status` = '0',`changed` = '1',`djstatus` = '0',`changed2` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
		}elseif($actie == 'streamstart'){
			$html .= '<br /><br />'.$lang->translate(978).': '.$details['name'].'<br />';
			$sql = "UPDATE `stream_streams` SET `status` = '1',`changed` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
		}else{
			return '';
		}
		$mysqli->query($sql);
		return $html;
	}
	function stream_create_html_overview($account){
		global $lang;
		$overview = stream_get_value_overview(get_value_get('id'),get_value_session('from_db','is_admin'));

		if($overview == FALSE){
			$html = '<br /><br />'.$lang->translate(701).'<br /><br />';
		}else{
			$html = '';
			$html .= '<br /><br />'.$lang->translate(759).':<br />';
			$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&id='.get_value_get('id').'&sort=name">'.$lang->translate(909).'</a><br />'; // naam (a->z)
			$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&id='.get_value_get('id').'&sort=name2">'.$lang->translate(910).'</a><br />'; // naam (z->a)
			$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&id='.get_value_get('id').'&sort=poort">'.$lang->translate(911).'</a><br />'; // Poort (laag-hoog)
			$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&id='.get_value_get('id').'&sort=poort2">'.$lang->translate(912).'</a><br />'; // Poort (hoog-laag)
			$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&id='.get_value_get('id').'&sort=bitrate">'.$lang->translate(913).'</a><br />'; // Kwaliteit (laag-hoog)
			$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&id='.get_value_get('id').'&sort=bitrate2">'.$lang->translate(914).'</a><br />'; // Kwaliteit (hoog-laag)
			$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&id='.get_value_get('id').'&sort=host">'.$lang->translate(915).'</a><br />'; // Host (laag-hoog)
			$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&id='.get_value_get('id').'&sort=host2">'.$lang->translate(916).'</a><br /><br />'; // Host (hoog-laag)
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			if(check_user_right(get_value_session('from_db','id'),'streambewerken',get_value_session('from_db','is_admin')) != FALSE){
				if(get_value_post('stream') != FALSE && get_value_post('actie') != FALSE && get_value_post('actie') != "none"){
					foreach(get_value_post('stream') as $streamid){
						$details = stream_get_details($streamid,$account);
						if(get_value_post('actie') == 'startstream'){
							$sql = "UPDATE `stream_streams` SET `status` = '1',`changed` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
							$html .= $lang->translate(978).': '.$details['name'].'<br />';
						}
						if(get_value_post('actie') == 'stopstream'){
							if($details['djstatus'] == '1' && $details['djstatus'] != '0'){
								$html .= $lang->translate(981).': '.$details['name'].'<br />';
							}
							$sql = "UPDATE `stream_streams` SET `status` = '0',`changed` = '1',`djstatus` = '0',`changed2` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
							$html .= $lang->translate(979).': '.$details['name'].'<br />';
						}
						if(get_value_post('actie') == 'startdj'){
							if($details['status'] != '1' && $details['status'] == '0'){
								$sql = "UPDATE `stream_streams` SET `djstatus` = '1',`changed2` = '1',`status` = '1',`changed` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
								$html .= $lang->translate(978).': '.$details['name'].'<br />';
							}else{
								$sql = "UPDATE `stream_streams` SET `djstatus` = '1',`changed2` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
							}
							$html .= $lang->translate(980).': '.$details['name'].'<br />';
						}
						if(get_value_post('actie') == 'stopdj'){
							$sql = "UPDATE `stream_streams` SET `djstatus` = '0',`changed2` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string($streamid)."' LIMIT 1";
							$html .= $lang->translate(981).': '.$details['name'].'<br />';
						}
						$mysqli->query($sql);
					}
				}
			}
			
			$html .= '<form method="post" action=""><div class="tablestop2"><table>';
			$html .= '<tr><td colspan="7"></td></tr>';
			$html .= '<tr>';
			if(check_user_right(get_value_session('from_db','id'),'streambewerken',get_value_session('from_db','is_admin')) != FALSE){
				$html .= '<td></td>';
			}
			$html .= '<td>'.$lang->translate(901).'</td>';
			$html .= '<td>'.$lang->translate(987).'</td>';
			if(check_user_right(get_value_session('from_db','id'),'streambewerken',get_value_session('from_db','is_admin')) != FALSE){
				$html .= '<td>'.$lang->translate(972).'</td>';
			}
			$html .= '<td>'.$lang->translate(902).':'.$lang->translate(917).'</td><td>'.$lang->translate(918).'</td><td>'.$lang->translate(923).'</td></tr>';
			foreach($overview as $stream){
				if(check_user_right(get_value_session('from_db','id'),'streambewerken',get_value_session('from_db','is_admin')) != FALSE){
					$html .= '<tr><td>';
					$html .= '<input type="checkbox" name="stream[]" value="'.$stream['id'].'">';
					$html .= '</td><td><a href="?lang='.lang_get_value_defaultlang().'&page=stream&type=streambewerken&id='.get_value_get('id').'&streamid='.$stream['id'].'">'.$stream['name'].'</a></td>';
				}elseif(check_user_right(get_value_session('from_db','id'),'streambekijken',get_value_session('from_db','is_admin')) != FALSE){
					$html .= '<tr><td><a href="?lang='.lang_get_value_defaultlang().'&page=stream&type=streambekijken&id='.get_value_get('id').'&streamid='.$stream['id'].'">'.$stream['name'].'</a></td>';
				}else{
					$html .= '<tr><td>'.$stream['name'].'</td>';
				}
				$html .= '<td>'.$stream['type'].'</td>';
				if(check_user_right(get_value_session('from_db','id'),'streambewerken',get_value_session('from_db','is_admin')) != FALSE){
					$details = stream_get_details($stream['id'],$account);
					$html .= '<td>';
					if($details['status'] == '1' && $details['status'] != '0'){
						$html .= $lang->translate(973);
					}else{
						$html .= $lang->translate(974);
					}
					$html .= '<br />';
					if($details['djstatus'] == '1' && $details['djstatus'] != '0'){
						$html .= $lang->translate(975);
					}else{
						$html .= $lang->translate(976);
					}
					$html .= '</td>';
				}
				$html .= '<td>'.$stream['host'].':'.$stream['poort'].'</td>';
				$html .= '<td>'.$stream['bitrate'].' kbps</td>';
				
				$html .= '<td>';
				$slash = 0;
				if(check_user_right(get_value_session('from_db','id'),'streambewerken',get_value_session('from_db','is_admin')) != FALSE){
					if($slash === 1){ $html .= ' / '; }
					$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&type=streambewerken&id='.get_value_get('id').'&streamid='.$stream['id'].'">'.$lang->translate(919).'</a>';
					$slash = 1;
				}
				if(check_user_right(get_value_session('from_db','id'),'streambekijken',get_value_session('from_db','is_admin')) != FALSE){
					if($slash === 1){ $html .= ' / '; }
					$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&type=streambekijken&id='.get_value_get('id').'&streamid='.$stream['id'].'">'.$lang->translate(920).'</a>';
					$slash = 1;
				}
				if(check_user_right(get_value_session('from_db','id'),'streamverwijderen',get_value_session('from_db','is_admin')) != FALSE){
					if($slash === 1){ $html .= ' / '; }
					$html .= '<a href="javascript:confirm_text(\''.$lang->translate(922).'\', \'?lang='.lang_get_value_defaultlang().'&page=stream&type=streamverwijderen&id='.get_value_get('id').'&streamid='.$stream['id'].'\')">'.$lang->translate(921).'</a>';
					$slash = 1;
				}
				$html .= '</td></tr>';
			}
			if(check_user_right(get_value_session('from_db','id'),'streambewerken',get_value_session('from_db','is_admin')) != FALSE){
				$html .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><select name="actie">
				<option value="none" selected></option>
				<option value="startstream">'.$lang->translate(968).'</option>
				<option value="stopstream">'.$lang->translate(969).'</option>
				<option value="startdj">'.$lang->translate(970).'</option>
				<option value="stopdj">'.$lang->translate(971).'</option>
				</select>
				<input type="submit" value="'.$lang->translate(977).'" id="submit" name="submit"/></td></tr>';
				$html .= '</table>';
				$html .= $lang->translate(982);
			}else{
				$html .= '</table></div>';
			}
			$html .= '</form>';
		}
		return $html;
	}
	function stream_get_value_pakket($account,$type = 'listeners'){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$query = $mysqli->query("SELECT * FROM `pakketten_stream` WHERE `id` LIKE '".$mysqli->real_escape_string($account)."' LIMIT 1");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				if($type == 'listeners'){
					return $row['max_listeners'];
				}else{
					return FALSE;
				}
			}
		}
	}
	function stream_get_value_current_usage($account,$type = 'listeners'){
		if($type == 'listeners'){
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `account` LIKE '".$mysqli->real_escape_string($account)."'");
		}
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return '0';
		}else{
			$num = 0;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$num = $num+$row['max_listeners'];
			}
			return $num;
		}
	}
	function stream_do_action_search($id,$search,$admin = 2){
		global $lang;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$results = 0;
		$sql = 'SELECT * FROM `stream_streams` WHERE `poort` LIKE "%'.$mysqli->real_escape_string($search).'%" AND account LIKE "'.$mysqli->real_escape_string($id).'" OR `bitrate` LIKE "%'.$mysqli->real_escape_string($search).'%" AND account LIKE "'.$mysqli->real_escape_string($id).'" OR `admin_user` LIKE "%'.$mysqli->real_escape_string($search).'%" AND account LIKE "'.$mysqli->real_escape_string($id).'" OR `name` LIKE "%'.$mysqli->real_escape_string($search).'%" AND account LIKE "'.$mysqli->real_escape_string($id).'" OR `host` LIKE "%'.$mysqli->real_escape_string($search).'%" AND account LIKE "'.$mysqli->real_escape_string($id).'"';
		$query = $mysqli->query($sql);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$results = 1;
				$array[$row['id']] = $row;
			}
		}
		if($results !== 1){
			return FALSE;
		}
		$html = '<br /><table border="1">';
		$html .= '<tr><td>'.$lang->translate(901).'</td><td>'.$lang->translate(917).'</td><td>'.$lang->translate(902).'</td><td>'.$lang->translate(903).'</td><td>'.$lang->translate(787).'</td></tr>';
		foreach($array as $stream){
			$html .= '<tr><td>';
			$html .= $stream['name'];
			$html .= '</td><td>';
			$html .= $stream['host'].'</td><td>'.$stream['poort'];
			$html .= '</td><td>';
			$html .= $stream['max_listeners'];
			$html .= '</td><td>';
			$slash = 0;
			if(check_user_right(get_value_session('from_db','id'),'streambekijken',get_value_session('from_db','is_admin')) != FALSE){
				if($slash === 1){ $html .= ' / '; }
				$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&type=streambekijken&id='.$stream['account'].'&streamid='.$stream['id'].'">'.$lang->translate(216).'</a>';
				$slash = 1;
			}
			if(check_user_right(get_value_session('from_db','id'),'streambewerken',get_value_session('from_db','is_admin')) != FALSE){
				if($slash === 1){ $html .= ' / '; }
				$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&type=streambewerken&id='.$stream['account'].'&streamid='.$stream['id'].'">'.$lang->translate(788).'</a>';
				$slash = 1;
			}
			if(check_user_right(get_value_session('from_db','id'),'streamverwijderen',get_value_session('from_db','is_admin')) != FALSE){
				if($slash === 1){ $html .= ' / '; }
				$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=stream&type=streamverwijderen&id='.$stream['account'].'&streamid='.$stream['id'].'">'.$lang->translate(789).'</a>';
				$slash = 1;
			}
			$html .= '</td></tr>';
		}
		$html .= '</table>';
		return $html;
	}
	function stream_create_html_search($type='listener'){
		global $lang;
		$html = '<br /><br /><form name="form1" method="post" action=""><table>';
		$html .= '<tr><td>'.$lang->translate(653).'</td><td><input type="text" id="search" name="search"></td></tr>';
		$html .= '<tr><td></td><td><input type="submit" value="'.$lang->translate(924).'" id="submit" name="submit"></td></tr></table></form>';
		return $html;
	}
	function stream_do_action_delete($id,$account,$admin = 2){
		global $lang;
		$stream = stream_get_value_stream($id,$account);
		if($stream == FALSE){
			$return = '<br /><br />'.$lang->translate(936).'<br /><br />';
		}else{
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			$mysqli->query("DELETE FROM `stream_streams` WHERE `id` LIKE '".$mysqli->real_escape_string($id)."'");
			$mysqli->query("DELETE FROM `stream_ftpuser` WHERE `userid` LIKE 'stream".$mysqli->real_escape_string($id)."'");
			$mysqli->query("DELETE FROM `stream_ftpquotalimits` WHERE `name` LIKE 'stream".$mysqli->real_escape_string($id)."'");
			$mysqli->query("DELETE FROM `stream_ftpquotatallies` WHERE `name` LIKE 'stream".$mysqli->real_escape_string($id)."'");
			$return = '<br /><br />'.$lang->translate(968).'<br /><br />';
		}
		return $return;
	}
	function stream_get_value_stream($id,$account){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$query = $mysqli->query("SELECT * FROM `stream_streams` WHERE `id` LIKE '".$mysqli->real_escape_string($id)."' AND `account` LIKE '".$mysqli->real_escape_string($account)."' LIMIT 1");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				if(pakketten_check_is_allowed(get_value_get('id'),'stream',get_value_session('from_db','is_admin')) === FALSE){
					return FALSE;
				}else{
					return $row;
				}
			}
		}
	}
	function stream_do_action_replace_streamdetails($account,$streamid,$admin=2){
		global $lang;
		global $stream_bitrates;
		global $stream_host_poorten;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$sql2 = 'SELECT * FROM `stream_streams` WHERE `id` LIKE "'.$mysqli->real_escape_string($streamid).'" AND `account` LIKE "'.$mysqli->real_escape_string($account).'" LIMIT 1';
		$query2 = $mysqli->query($sql2);
		if(!isset($query2) || empty($query2) || $query2->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query2->fetch_array(MYSQLI_ASSOC)){
				$update = '';
				if($row['bitrate'] != get_value_post('bitrate') && get_value_post('bitrate') != FALSE){
					foreach($stream_bitrates as $type){
						if(get_value_post('bitrate') == $type){
							$update .= ',`bitrate`="'.$mysqli->real_escape_string($type).'"';
						}
					}
				}
				$post_port = get_value_post('poort');
				if($post_port !== FALSE){
					$post_port2 = explode(':',$post_port);
					if($row['host'] != $post_port[0] && $row['poort'] != $post_port[1]){
						foreach($stream_host_poorten as $hostname=>$ports){
							if($post_port2[0] == $hostname){
								foreach($ports as $port){
									if($post_port2[1] == $port){
										$sql = 'SELECT * FROM `stream_streams` WHERE `host` LIKE "'.$mysqli->real_escape_string($hostname).'" AND `poort` LIKE "'.$mysqli->real_escape_string($port).'" LIMIT 1';
										$query = $mysqli->query($sql);
										if(!isset($query) || empty($query) || $query->num_rows == "0"){
											$update .= ',`poort`="'.$mysqli->real_escape_string($port).'"';
											$update .= ',`host`="'.$mysqli->real_escape_string($hostname).'"';
										}
									}
								}
							}
						}
					}
				}
				if($row['admin_user'] != get_value_post('admin_user')){
					$update .= ',`admin_user`="'.$mysqli->real_escape_string(get_value_post('admin_user')).'"';
				}
				if($row['admin_pass'] != get_value_post('admin_pass')){
					$update .= ',`admin_pass`="'.$mysqli->real_escape_string(get_value_post('admin_pass')).'"';
				}
				if($row['name'] != get_value_post('name')){
					$update .= ',`name`="'.$mysqli->real_escape_string(get_value_post('name')).'"';
				}
				if($row['relay_pass'] != get_value_post('relay_pass')){
					$update .= ',`relay_pass`="'.$mysqli->real_escape_string(get_value_post('relay_pass')).'"';
				}
				if($row['source_pass'] != get_value_post('source_pass')){
					$update .= ',`source_pass`="'.$mysqli->real_escape_string(get_value_post('source_pass')).'"';
				}
				if($row['source_pass_1'] != get_value_post('source_pass_1')){
					$update .= ',`source_pass_1`="'.$mysqli->real_escape_string(get_value_post('source_pass_1')).'"';
				}
				if($row['source_pass_2'] != get_value_post('source_pass_2')){
					$update .= ',`source_pass_2`="'.$mysqli->real_escape_string(get_value_post('source_pass_2')).'"';
				}
				if($row['source_pass_3'] != get_value_post('source_pass_3')){
					$update .= ',`source_pass_3`="'.$mysqli->real_escape_string(get_value_post('source_pass_3')).'"';
				}
				if($row['source_pass_4'] != get_value_post('source_pass_4')){
					$update .= ',`source_pass_4`="'.$mysqli->real_escape_string(get_value_post('source_pass_4')).'"';
				}
				if($row['source_pass_5'] != get_value_post('source_pass_5')){
					$update .= ',`source_pass_5`="'.$mysqli->real_escape_string(get_value_post('source_pass_5')).'"';
				}
				if($row['mountpoint'] != get_value_post('mountpoint')){
					$update .= ',`mountpoint`="'.$mysqli->real_escape_string(get_value_post('mountpoint')).'"';
				}
				if($row['public'] != get_value_post('public')){
					$update .= ',`public`="'.$mysqli->real_escape_string(get_value_post('public')).'"';
				}
				if($row['genre'] != get_value_post('genre')){
					$update .= ',`genre`="'.$mysqli->real_escape_string(get_value_post('genre')).'"';
				}
				if($row['description'] != get_value_post('description')){
					$update .= ',`description`="'.$mysqli->real_escape_string(get_value_post('description')).'"';
				}
				if($row['url'] != get_value_post('url')){
					$update .= ',`url`="'.$mysqli->real_escape_string(get_value_post('url')).'", `changed2` = 1';
				}
				if($row['shoutcastkey'] != get_value_post('shoutcastkey')){
					$update .= ',`shoutcastkey`="'.$mysqli->real_escape_string(get_value_post('shoutcastkey')).'", `changed2` = 1';
				}
				if(get_value_post('master') === 1){
					if($row['master_server'] != get_value_post('master_server')){
						$update .= ',`master_server`="'.$mysqli->real_escape_string(get_value_post('master_server')).'"';
					}
					if($row['master_port'] != get_value_post('master_port')){
						$update .= ',`master_port`="'.$mysqli->real_escape_string(get_value_post('master_port')).'"';
					}
					if($row['master_user'] != get_value_post('master_user')){
						$update .= ',`master_user`="'.$mysqli->real_escape_string(get_value_post('master_user')).'"';
					}
					if($row['master_pass'] != get_value_post('master_pass')){
						$update .= ',`master_pass`="'.$mysqli->real_escape_string(get_value_post('master_pass')).'"';
					}
				}else{
					$update .= ',`master_server`=NULL';
					$update .= ',`master_port`=NULL';
					$update .= ',`master_user`=""';
					$update .= ',`master_pass`=""';
				}
				if($row['status'] != get_value_post('status')){
					$update .= ',`status`="'.$mysqli->real_escape_string(get_value_post('status')).'"';
				}
				//var_dump(get_value_post('status'));
				if(get_value_post('status') == "0"){
					$update .= ',`djstatus`="0", `changed2` = 1';
				}elseif($row['djstatus'] != get_value_post('djstatus')){
					$update .= ',`djstatus`="'.$mysqli->real_escape_string(get_value_post('djstatus')).'",`changed2` = 1';
				}
				$sql3 = 'SELECT * FROM `stream_ftpuser` WHERE `userid` LIKE "stream'.$mysqli->real_escape_string($row['id']).'" LIMIT 1';
				$query3 = $mysqli->query($sql3);
				if(!isset($query3) || empty($query3) || $query3->num_rows == "0"){
					if(get_value_post('ftp_pass') != FALSE && get_value_post('ftp_pass') != ''){
						$sql = 'INSERT INTO `stream_ftpuser` (`userid`,`passwd`,`homedir`) VALUES ("stream'.$row['id'].'","'.$mysqli->real_escape_string(get_value_post('ftp_pass')).'","/home/icecast/stream'.$row['id'].'")';
						$mysqli->query($sql);
						$sql = 'INSERT INTO `stream_ftpquotalimits` (`name`,`limit_type`) VALUES ("stream'.$row['id'].'","hard")';
						$mysqli->query($sql);
					}
				}else{
					while($row3 = $query3->fetch_array(MYSQLI_ASSOC)){
						if($row3['passwd'] != get_value_post('ftp_pass')){
							if(get_value_post('ftp_pass') == FALSE || get_value_post('ftp_pass') == ''){
								$sql = 'DELETE FROM `stream_ftpuser` WHERE `userid` LIKE "stream'.$row['id'].'" LIMIT 1';
								$mysqli->query($sql);
								$sql = 'DELETE FROM `stream_ftpuser` WHERE `name` LIKE "stream'.$row['id'].'" LIMIT 1';
								$mysqli->query($sql);
							}else{
								$sql = 'UPDATE `stream_ftpuser` SET `passwd` = "'.$mysqli->real_escape_string(get_value_post('ftp_pass')).'" WHERE `userid` LIKE "stream'.$row['id'].'" LIMIT 1';
								$mysqli->query($sql);
							}
						}
					}
				}
				if($ftppass != get_value_post('ftp_pass')){
					$update .= ',`status`="'.$mysqli->real_escape_string(get_value_post('status')).'"';
				}
				
				if($row['max_listeners'] != get_value_post('max_listeners')){
					if($row['max_listeners'] > get_value_post('max_listeners')){
						$update .= ',`max_listeners`="'.$mysqli->real_escape_string(get_value_post('max_listeners')).'"';
					}else{
						$availablelisteners = pakketten_get_value_size_stream(get_value_session('from_db','id'),'listeners')-pakketten_get_value_used_stream(get_value_session('from_db','id'),'listeners')+$row['max_listeners'];
						if(get_value_post('max_listeners') < $availablelisteners){
							$update .= ',`max_listeners`="'.$mysqli->real_escape_string(get_value_post('max_listeners')).'"';
						}else{
							return FALSE;
						}
					}
				}
				
				$sql = 'UPDATE `stream_streams` SET `changed` = "1", `changed2` = "1"'.$update.' WHERE `id` LIKE "'.$mysqli->real_escape_string($streamid).'" AND `account` LIKE "'.$mysqli->real_escape_string($account).'"';
				$mysqli->query($sql);
				return TRUE;
			}
		}
	}
	function stream_create_html_streamdetails($account,$streamid,$action='bekijk',$admin = 2){
		global $lang;
		global $stream_bitrates;
		global $stream_host_poorten;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$i = 0;
		$html = '<br /><br />';
		$sql2 = 'SELECT * FROM `stream_streams` WHERE `id` LIKE "'.$mysqli->real_escape_string($streamid).'" AND `account` LIKE "'.$mysqli->real_escape_string($account).'" LIMIT 1';
		$query2 = $mysqli->query($sql2);
		if(!isset($query2) || empty($query2) || $query2->num_rows == "0"){
			$html = '<br /><br />'.$lang->translate(932).'<br /><br />';
		}else{
			while($row = $query2->fetch_array(MYSQLI_ASSOC)){
				$sql3 = 'SELECT * FROM `stream_ftpuser` WHERE `userid` LIKE "stream'.$mysqli->real_escape_string($row['id']).'" LIMIT 1';
				$query3 = $mysqli->query($sql3);
				if(!isset($query3) || empty($query3) || $query3->num_rows == "0"){
					$ftppass = '';
				}else{
					while($row3 = $query3->fetch_array(MYSQLI_ASSOC)){
						$ftppass = $row3['passwd'];
					}
				}
				if($row['status'] == 0){
					$status = '<option value="0" selected>'.$lang->translate(937).'</option><option value="1">'.$lang->translate(938).'</option>';
					$status2 = $lang->translate(937);
				}else{
					$status = '<option value="0">'.$lang->translate(937).'</option><option value="1" selected>'.$lang->translate(938).'</option>';
					$status2 = $lang->translate(938);
				}
				if($row['public'] == 0){
					$public = '<option value="0" selected>'.$lang->translate(967).'</option><option value="1">'.$lang->translate(966).'</option>';
					$public2 = $lang->translate(967);
				}else{
					$public = '<option value="0">'.$lang->translate(967).'</option><option value="1" selected>'.$lang->translate(966).'</option>';
					$public2 = $lang->translate(966);
				}
				if($row['djstatus'] == 0){
					$djstatus = '<option value="0" selected>'.$lang->translate(958).'</option><option value="1">'.$lang->translate(957).'</option>';
					$djstatus2 = $lang->translate(958);
				}else{
					$djstatus = '<option value="0">'.$lang->translate(958).'</option><option value="1" selected>'.$lang->translate(957).'</option>';
					$djstatus2 = $lang->translate(957);
				}
				if($row['master_server'] == NULL){
					$master = '<option value="0" selected>'.$lang->translate(941).'</option><option value="1">'.$lang->translate(942).'</option>';
					$master2 = $lang->translate(941);
				}else{
					$master = '<option value="0">'.$lang->translate(941).'</option><option value="1" selected>'.$lang->translate(942).'</option>';
					$master2 = $lang->translate(942);
				}
				$html .= $lang->translate(954).': http://'.$row['host'].':'.$row['poort'].'/'.$row['mountpoint'].'<br />';
				$html .= $lang->translate(955).': http://'.$row['host'].':'.$row['poort'].'/admin/<br />';
				if($row['type'] == 'shoutcast'){
					$altport = $row['poort']+3;
					$html .= $lang->translate(986).': http://'.$row['host'].':'.$altport.'<br />';
				}
				if($action == "bewerk"){
					sort($stream_bitrates);
					$options = '';
					foreach($stream_bitrates as $type){
						if($row['bitrate'] == $type){
							$options .= '<option selected value="'.$type.'">'.$type.' kbps</option>';
						}else{
							$options .= '<option value="'.$type.'">'.$type.' kbps</option>';
						}
					}
					$poorten = '';
					$poorten .= '<option selected value="'.$row['host'].':'.$row['poort'].'">'.$row['host'].':'.$row['poort'].'</option>';
					foreach($stream_host_poorten as $host=>$ports){
						sort($ports);
						foreach($ports as $port){
							$sql = 'SELECT * FROM `stream_streams` WHERE `host` LIKE "'.$mysqli->real_escape_string($host).'" AND `poort` LIKE "'.$mysqli->real_escape_string($port).'" LIMIT 1';
							$query = $mysqli->query($sql);
							if(!isset($query) || empty($query) || $query->num_rows == "0"){
								$poorten .= '<option value="'.$host.':'.$port.'">'.$host.':'.$port.'</option>';
							}
						}
					}
					
					$html .= '<form name="form1" method="post" action="">';
					if($row['type'] == 'shoutcast'){
						$html .= '<table><tr><td>'.$lang->translate(987).'</td><td>Shoutcast</td><td></td></tr>';
					}else{
						$html .= '<table><tr><td>'.$lang->translate(987).'</td><td>Icecast</td><td></td></tr>';
					}
					$html .= '<tr><td>'.$lang->translate(925).'</td><td><input type="text" id="name" name="name" value="'.$row['name'].'"></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(962).'</td><td><input type="text" id="genre" name="genre" value="'.$row['genre'].'"></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(963).'</td><td><input type="text" id="description" name="description" value="'.$row['description'].'"></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(964).'</td><td><input type="text" id="url" name="url" value="'.$row['url'].'"></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(900).'</td><td><input type="text" id="max_listeners" name="max_listeners" value="'.$row['max_listeners'].'"></td><td></td></tr>';
					if($row['type'] == 'shoutcast'){
						$html .= '<tr><td>'.$lang->translate(985).'</td><td><input type="text" id="shoutcastkey" name="shoutcastkey" value="'.$row['shoutcastkey'].'"></td><td></td></tr>';
					}
					$html .= '<tr><td>'.$lang->translate(926).'</td><td><input type="text" id="admin_user" name="admin_user" value="'.$row['admin_user'].'"></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(927).'</td><td><input type="password" id="admin_pass" name="admin_pass" value="'.$row['admin_pass'].'"></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(948).'</td><td><input type="password" id="source_pass" name="source_pass" value="'.$row['source_pass'].'"></td><td>'.$lang->translate(949).'</td></tr>';
					if($row['type'] == 'shoutcast'){
					}else{
						$html .= '<tr><td>'.$lang->translate(995).' 1</td><td><input type="password" id="source_pass_1" name="source_pass_1" value="'.$row['source_pass_1'].'"></td><td>'.$lang->translate(996).'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(995).' 2</td><td><input type="password" id="source_pass_2" name="source_pass_2" value="'.$row['source_pass_2'].'"></td><td>'.$lang->translate(996).'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(995).' 3</td><td><input type="password" id="source_pass_3" name="source_pass_3" value="'.$row['source_pass_3'].'"></td><td>'.$lang->translate(996).'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(995).' 4</td><td><input type="password" id="source_pass_4" name="source_pass_4" value="'.$row['source_pass_4'].'"></td><td>'.$lang->translate(996).'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(995).' 5</td><td><input type="password" id="source_pass_5" name="source_pass_5" value="'.$row['source_pass_5'].'"></td><td>'.$lang->translate(996).'</td></tr>';
					}
					if($row['type'] == 'shoutcast'){
					}else{
						$html .= '<tr><td>'.$lang->translate(946).'</td><td><input type="password" id="relay_pass" name="relay_pass" value="'.$row['relay_pass'].'"></td><td>'.$lang->translate(947).'</td></tr>';
					}
					$html .= '<tr><td>'.$lang->translate(944).'</td><td><input type="text" id="mountpoint" name="mountpoint" value="'.$row['mountpoint'].'"></td><td>'.$lang->translate(945).'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(940).'</td><td><select name="master">'.$master.'</select></td><td>'.$lang->translate(943).'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(950).'</td><td><input type="text" id="master_server" name="master_server" value="'.$row['master_server'].'"></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(951).'</td><td><input type="text" id="master_port" name="master_port" value="'.$row['master_port'].'"></td><td></td></tr>';
					if($row['type'] == 'shoutcast'){
					}else{
						$html .= '<tr><td>'.$lang->translate(952).'</td><td><input type="text" id="master_user" name="master_user" value="'.$row['master_user'].'"></td><td></td></tr>';
						$html .= '<tr><td>'.$lang->translate(953).'</td><td><input type="password" id="master_pass" name="master_pass" value="'.$row['master_pass'].'"></td><td></td></tr>';
					}
					$html .= '<tr><td>'.$lang->translate(959).'</td><td>stream'.$row['id'].'</td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(960).'</td><td><input type="password" id="ftp_pass" name="ftp_pass" value="'.$ftppass.'"></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(918).'</td><td><select name="bitrate">'.$options.'</select></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(928).'</td><td><select name="poort">'.$poorten.'</select></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(939).'</td><td><select name="status">'.$status.'</select></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(965).'</td><td><select name="public">'.$public.'</select></td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(956).'</td><td><select name="djstatus">'.$djstatus.'</select></td><td>'.$lang->translate(961).'</td></tr>';
					$html .= '<tr><td></td><td><input type="submit" value="'.$lang->translate(933).'" id="submit" name="submit"></td><td></td></tr></table></form>';
				}else{
					$html .= '<table>';
					if($row['type'] == 'shoutcast'){
						$html .= '<tr><td>'.$lang->translate(987).'</td><td>Shoutcast</td></tr>';
					}else{
						$html .= '<tr><td>'.$lang->translate(987).'</td><td>Icecast</td></tr>';
					}
					$html .= '<tr><td>'.$lang->translate(925).'</td><td>'.$row['name'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(962).'</td><td>'.$row['genre'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(963).'</td><td>'.$row['description'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(964).'</td><td>'.$row['url'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(900).'</td><td>'.$row['max_listeners'].'</td></tr>';
					if($row['type'] == 'shoutcast'){
						$html .= '<tr><td>'.$lang->translate(985).'</td><td>'.$row['shoutcastkey'].'</td></tr>';
					}
					$html .= '<tr><td>'.$lang->translate(926).'</td><td>'.$row['admin_user'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(959).'</td><td>stream'.$row['id'].'</td><td></td></tr>';
					$html .= '<tr><td>'.$lang->translate(944).'</td><td>'.$row['mountpoint'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(918).'</td><td>'.$row['bitrate'].' kbps</td></tr>';
					$html .= '<tr><td>'.$lang->translate(928).'</td><td>'.$row['host'].':'.$row['poort'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(939).'</td><td>'.$status2.'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(940).'</td><td>'.$master2.'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(965).'</td><td>'.$public2.'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(956).'</td><td>'.$djstatus2.'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(950).'</td><td>'.$row['master_server'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(951).'</td><td>'.$row['master_port'].'</td></tr>';
					if($row['type'] == 'shoutcast'){
					}else{
						$html .= '<tr><td>'.$lang->translate(952).'</td><td>'.$row['master_user'].'</td></tr>';
					}
					
					$html .= '</table>';
				}
			}
		}
		return $html;
	}
	function stream_create_html_toevoegen($account,$admin = 2){
		global $lang;
		global $stream_bitrates;
		global $stream_host_poorten;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$i = 0;
		$html = '<br /><br />';
		sort($stream_bitrates);
		$options = '';
		foreach($stream_bitrates as $type){
			$options .= '<option value="'.$type.'">'.$type.' kbps</option>';
		}
		$poorten = '';
		foreach($stream_host_poorten as $host=>$ports){
			sort($ports);
			foreach($ports as $port){
				$sql = 'SELECT * FROM `stream_streams` WHERE `host` LIKE "'.$mysqli->real_escape_string($host).'" AND `poort` LIKE "'.$mysqli->real_escape_string($port).'" LIMIT 1';
				$query = $mysqli->query($sql);
				if(!isset($query) || empty($query) || $query->num_rows == "0"){
					$poorten .= '<option value="'.$host.':'.$port.'">'.$host.':'.$port.'</option>';
				}
			}
		}
		$status = '<option value="0">'.$lang->translate(937).'</option><option value="1" selected>'.$lang->translate(938).'</option>';
		$master = '<option value="0" selected>'.$lang->translate(941).'</option><option value="1">'.$lang->translate(942).'</option>';
		$djstatus = '<option value="0" selected>'.$lang->translate(958).'</option><option value="1">'.$lang->translate(957).'</option>';
		$public = '<option value="0">'.$lang->translate(967).'</option><option value="1" selected>'.$lang->translate(966).'</option>';
		$streamtype = '<option value="icecast" selected>Icecast</option><option value="shoutcast">Shoutcast</option>';
		
		$html .= '<form name="form1" method="post" action="">';
		$html .= '<table border="1"><tr><td></td><td>Icecast</td><td>Shoutcast</td><td></td></tr>';
		$html .= '<tr><td>'.$lang->translate(925).'</td><td>x</td><td>x</td><td><input type="text" id="name" name="name"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(984).'</td><td>x</td><td>x</td><td><select name="streamtype">'.$streamtype.'</select></td></tr>';
		$html .= '<tr><td>'.$lang->translate(962).'</td><td>x</td><td>x</td><td><input type="text" id="genre" name="genre"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(963).'</td><td>x</td><td>x</td><td><input type="text" id="description" name="description"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(964).'</td><td>x</td><td>x</td><td><input type="text" id="url" name="url"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(900).'</td><td>x</td><td>x</td><td><input type="text" id="max_listeners" name="max_listeners"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(985).'</td><td></td><td>x</td><td><input type="text" id="shoutcastkey" name="shoutcastkey"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(926).'</td><td>x</td><td>x</td><td><input type="text" id="admin_user" name="admin_user"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(927).'</td><td>x</td><td>x</td><td><input type="password" id="admin_pass" name="admin_pass"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(948).'</td><td>x</td><td>x</td><td><input type="password" id="source_pass" name="source_pass""></td><td>'.$lang->translate(949).'</td></tr>';
		$html .= '<tr><td>'.$lang->translate(946).'</td><td>x</td><td></td><td><input type="password" id="relay_pass" name="relay_pass""></td><td>'.$lang->translate(947).'</td></tr>';
		$html .= '<tr><td>'.$lang->translate(944).'</td><td>x</td><td>x</td><td><input type="text" id="mountpoint" name="mountpoint" value="listen.pls"></td><td>'.$lang->translate(945).'</td></tr>';
		$html .= '<tr><td>'.$lang->translate(940).'</td><td>x</td><td>x</td><td><select name="master">'.$master.'</select></td><td>'.$lang->translate(943).'</td></tr>';
		$html .= '<tr><td>'.$lang->translate(950).'</td><td>x</td><td>x</td><td><input type="text" id="master_server" name="master_server""></td></tr>';
		$html .= '<tr><td>'.$lang->translate(951).'</td><td>x</td><td>x</td><td><input type="text" id="master_port" name="master_port""></td></tr>';
		$html .= '<tr><td>'.$lang->translate(952).'</td><td>x</td><td></td><td><input type="text" id="master_user" name="master_user""></td></tr>';
		$html .= '<tr><td>'.$lang->translate(953).'</td><td>x</td><td></td><td><input type="password" id="master_pass" name="master_pass""></td></tr>';
		$html .= '<tr><td>'.$lang->translate(960).'</td><td>x</td><td>x</td><td><input type="password" id="ftp_pass" name="ftp_pass"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(918).'</td><td>x</td><td>x</td><td><select name="bitrate">'.$options.'</select></td></tr>';
		$html .= '<tr><td>'.$lang->translate(928).'</td><td>x</td><td>x</td><td><select name="poort">'.$poorten.'</select></td></tr>';
		$html .= '<tr><td>'.$lang->translate(939).'</td><td>x</td><td>x</td><td><select name="status">'.$status.'</select></td></tr>';
		$html .= '<tr><td>'.$lang->translate(965).'</td><td>x</td><td>x</td><td><select name="public">'.$public.'</select></td></tr>';
		$html .= '<tr><td></td><td></td><td></td><td><input type="submit" value="'.$lang->translate(644).'" id="submit" name="submit"></td></tr></table></form><br />'.$lang->translate(983);
		
		return $html;
	}
	function stream_do_action_toevoegen($account,$admin = 2){
		global $lang;
		global $stream_bitrates;
		global $stream_host_poorten;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		
		$bitrate = FALSE;
		$host = FALSE;
		$poort = FALSE;
		$admin_user = FALSE;
		$admin_pass = FALSE;
		$name = FALSE;
		$max_listeners = FALSE;
		
		foreach($stream_bitrates as $type){
			if(get_value_post('bitrate') == $type){
				$bitrate = $type;
			}
		}
		$post_port = get_value_post('poort');
		if($post_port !== FALSE){
			$post_port2 = explode(':',$post_port);
			foreach($stream_host_poorten as $hostname=>$ports){
				if($post_port2[0] == $hostname){
					foreach($ports as $port){
						if($post_port2[1] == $port){
							$sql = 'SELECT * FROM `stream_streams` WHERE `host` LIKE "'.$mysqli->real_escape_string($hostname).'" AND `poort` LIKE "'.$mysqli->real_escape_string($port).'" LIMIT 1';
							$query = $mysqli->query($sql);
							if(!isset($query) || empty($query) || $query->num_rows == "0"){
								$poort = $port;
								$host = $hostname;
							}
						}
					}
				}
			}
		}
		$admin_user = get_value_post('admin_user');
		$admin_pass = get_value_post('admin_pass');
		$name = get_value_post('name');
		$max_listeners = get_value_post('max_listeners');
		$genre = get_value_post('genre');
		$description = get_value_post('description');
		$url = get_value_post('url');
		$source_pass = get_value_post('source_pass');
		$relay_pass = get_value_post('relay_pass');
		$mountpoint = get_value_post('mountpoint');
		$master_server = get_value_post('master_server');
		$master_port = get_value_post('master_port');
		$master_user = get_value_post('master_user');
		$master_pass = get_value_post('master_pass');
		$ftp_pass = get_value_post('ftp_pass');
		$streamtype = get_value_post('streamtype');
		$shoutcastkey = get_value_post('shoutcastkey');
		
		if($bitrate === FALSE || $host === FALSE || $poort === FALSE || $admin_user === FALSE || $admin_pass === FALSE || $name === FALSE || $max_listeners === FALSE){
			$html = '<br /><br />'.$lang->translate(929).'<br /><br />';
		}else{
			$availablelisteners = pakketten_get_value_size_stream(get_value_session('from_db','id'),'listeners')-pakketten_get_value_used_stream(get_value_session('from_db','id'),'listeners');
			if($max_listeners < $availablelisteners){
				$sql2 = 'INSERT INTO `stream_streams` (`poort`,`admin_user`,`admin_pass`,`max_listeners`,`bitrate`,`account`,`name`,`host`,`genre`,`description`,`url`,`source_pass`,`relay_pass`,`mountpoint`,`master_server`,`master_port`,`master_user`,`master_pass`,`changed2`,`changed`,`type`,`shoutcastkey`) VALUES ("'.$mysqli->real_escape_string($poort).'","'.$mysqli->real_escape_string($admin_user).'","'.$mysqli->real_escape_string($admin_pass).'","'.$mysqli->real_escape_string($max_listeners).'","'.$mysqli->real_escape_string($bitrate).'","'.$mysqli->real_escape_string($account).'","'.$mysqli->real_escape_string($name).'","'.$mysqli->real_escape_string($host).'","'.$mysqli->real_escape_string($genre).'","'.$mysqli->real_escape_string($description).'","'.$mysqli->real_escape_string($url).'","'.$mysqli->real_escape_string($source_pass).'","'.$mysqli->real_escape_string($relay_pass).'","'.$mysqli->real_escape_string($mountpoint).'","'.$mysqli->real_escape_string($master_server).'","'.$mysqli->real_escape_string($master_port).'","'.$mysqli->real_escape_string($master_user).'","'.$mysqli->real_escape_string($master_pass).'","1","1","'.$mysqli->real_escape_string($streamtype).'","'.$mysqli->real_escape_string($shoutcastkey).'")';
				$mysqli->query($sql2);
				$id = $mysqli->insert_id;
				if(get_value_post('ftp_pass') != FALSE && get_value_post('ftp_pass') != ''){
					$sql = 'INSERT INTO `stream_ftpuser` (`userid`,`passwd`,`homedir`) VALUES ("stream'.$id.'","'.$mysqli->real_escape_string(get_value_post('ftp_pass')).'","/home/icecast/stream'.$id.'")';
					$mysqli->query($sql);
					$sql = 'INSERT INTO `stream_ftpquotalimits` (`name`,`limit_type`) VALUES ("stream'.$id.'","hard")';
					$mysqli->query($sql);
				}
				$html = '<br /><br />'.$lang->translate(930).'<br /><br />';
			}else{
				$html = '<br /><br />'.$lang->translate(931).'<br /><br />';
			}
		}
		return $html;
	}
	