<?php
	// Created by Mark Scholten
	// This file is called function.php and contains functions (where possible all functions)
	// Where possible functions should be used to keep things clear and easy to maintain
	// Every module has it's own function file called functions/<module>.php.
	// Function naming:
	// (module_) -> the name for the module or group or missing if not used
	// - (module_)fix_is_<what is checked/fixed> -> if no reason to take another action return FALSE, if no action required return TRUE
	// - (module_)check_is_<what is checked> -> return TRUE if yes and return FALSE if not (no action taken)
	// - (module_)get_value_<what is checked> -> return the value for it (no type check) or if it doesn't exist return FALSE
	// - (module_)get_number_<what is checked> -> return the value for it (is a number or FALSE if a number can't be provided)
	// - (module_)do_action_<action needed> -> return FALSE if failed (else depends on the function)
	// - (module_)change_db_<what to change> -> return TRUE on success or FALSE if failed
	// - (module_)change_var_<what to change> -> return TRUE on success or FALSE if failed
	// - (module_)set_db_<what to set> -> return TRUE on success or FALSE if failed
	// - (module_)set_var_<what to set> -> return TRUE on success or FALSE if failed
	// - (module_)unset_db_<what to unset/delete> -> return TRUE on success or FALSE if failed
	// - (module_)unset_var_<what to unset> -> return TRUE on success or FALSE if failed
	// - (module_)create_<what to create> -> return created
	// - create_db_connection -> FALSE if failed else TRUE
	// - check_user_right -> TRUE if an user has the right, FALSE if an user doesn't have the right

	function fix_is_included($index = "0"){
		if($index == "1"){
			return TRUE;
		}else{
			header("Location: http://".$_SERVER["SERVER_NAME"]."/");
			return FALSE;
			exit();
		}
	}
	fix_is_included($index);
	function get_current_version($cp_version){
		$latest_version_tmp = dns_get_record('dnsshop.version.tools4isp.com',DNS_TXT);
		$latest_version = $latest_version_tmp[0]['txt'];
		if($cp_version < $latest_version){
			$warning_version = '<p><b>Update available!</b> Version '.$latest_version.' is available and you are running '.$cp_version.'.</p>';
		}else{
			$warning_version = '';
		}
		return $warning_version;
	}
	function get_value_get($item,$item2 = FALSE){
		if($item2 == FALSE){
			if(isset($_GET) && !empty($_GET) && isset($_GET[$item]) && !empty($_GET[$item]) && $_GET[$item] != ""){
				return $_GET[$item];
			}else{
				return FALSE;
			}
		}else{
			if(isset($_GET) && !empty($_GET) && isset($_GET[$item][$item2]) && !empty($_GET[$item][$item2]) && $_GET[$item][$item2] != ""){
				return $_GET[$item][$item2];
			}else{
				return FALSE;
			}
		}
	}
	function get_value_post($item,$item2 = FALSE){
		if($item2 == FALSE){
			//var_dump($item,$_POST[$item]);
			if(isset($_POST) && !empty($_POST) && isset($_POST[$item]) && !empty($_POST[$item]) && $_POST[$item] != ""){
				return $_POST[$item];
			}else{
				return FALSE;
			}
		}else{
			if(isset($_POST) && !empty($_POST) && isset($_POST[$item][$item2]) && !empty($_POST[$item][$item2]) && $_POST[$item][$item2] != ""){
				return $_POST[$item][$item2];
			}else{
				return FALSE;
			}
		}
	}
	function get_value_session($item,$item2 = FALSE){
		if($item2 == FALSE){
			if(isset($_SESSION) && !empty($_SESSION) && isset($_SESSION[$item]) && !empty($_SESSION[$item]) && $_SESSION[$item] != ""){
				return $_SESSION[$item];
			}else{
				return FALSE;
			}
		}else{
			if(isset($_SESSION) && !empty($_SESSION) && isset($_SESSION[$item][$item2]) && !empty($_SESSION[$item][$item2]) && $_SESSION[$item][$item2] != ""){
				return $_SESSION[$item][$item2];
			}else{
				return FALSE;
			}
		}
	}
	function get_value_server($item,$item2 = FALSE){
		if($item2 == FALSE){
			if(isset($_SERVER) && !empty($_SERVER) && isset($_SERVER[$item]) && !empty($_SERVER[$item]) && $_SERVER[$item] != ""){
				return $_SERVER[$item];
			}else{
				return FALSE;
			}
		}else{
			if(isset($_SERVER) && !empty($_SERVER) && isset($_SERVER[$item][$item2]) && !empty($_SERVER[$item][$item2]) && $_SERVER[$item][$item2] != ""){
				return $_SERVER[$item][$item2];
			}else{
				return FALSE;
			}
		}
	}
	function get_linkback(){
		if(get_value_get('page') === FALSE){
			return FALSE;
		}elseif(get_value_get('page') === 'klanten'){
			if(get_value_get('id') === FALSE && get_value_get('type') === FALSE){
				return FALSE;
			}elseif(get_value_get('id') === FALSE && get_value_get('type') === 'overzicht'){
				return FALSE;
			}else{
				$data = get_userdata(get_value_get('id'),array('id_master'));
				if($data['id_master'] != 0){
    				if($data['id_master'] == get_value_session('from_db','id')){
						return '?lang='.lang_get_value_defaultlang().'&page=klanten&type=overzicht';
					}else{
						return '?lang='.lang_get_value_defaultlang().'&page=klanten&type=overzicht&id='.$data['id_master'];
					}
				}else{
					return '?lang='.lang_get_value_defaultlang().'&page=klanten&type=overzicht';
				}
			}
		}elseif(get_value_get('page') === 'gegevens'){
			if(get_value_get('type') === FALSE){
				return FALSE;
			}else{
				return '?lang='.lang_get_value_defaultlang().'&page=gegevens';
			}
		}elseif(get_value_get('page') === 'producten'){
			if(get_value_get('type') === FALSE || get_value_get('type') === 'overzicht'){
				return FALSE;
			}else{
				return '?lang='.lang_get_value_defaultlang().'&page=producten&type=overzicht';
			}
		}elseif(get_value_get('page') === 'dns'){
			if(get_value_get('type') === FALSE || get_value_get('type') === 'domoverzicht'){
				return '?lang='.lang_get_value_defaultlang().'&page=producten&type=overzicht';
			}elseif(get_value_get('type') === 'temtoevoegen'){
				if(get_value_get('id') !== FALSE){
					return '?lang='.lang_get_value_defaultlang().'&page=dns&type=temoverzicht&id='.get_value_get('id');
				}else{
					return FALSE;
				}
			}elseif(get_value_get('type') === 'supertoevoegen'){
				if(get_value_get('id') !== FALSE){
					return '?lang='.lang_get_value_defaultlang().'&page=dns&type=superoverzicht&id='.get_value_get('id');
				}else{
					return FALSE;
				}
			}elseif(get_value_get('id') !== FALSE){
				return '?lang='.lang_get_value_defaultlang().'&page=dns&type=domoverzicht&id='.get_value_get('id');
			}else{
				return TRUE;
			}
		}
		return FALSE;
	}
    function get_currentlink($lang = NULL){
		if($lang === NULL){
			$return = '?lang='.lang_get_value_defaultlang();
		}else{
			$return = '?lang='.$lang;
		}
		if(get_value_get('page') !== FALSE){
			$return .= '&page='.get_value_get('page');
		}
		if(get_value_get('id') !== FALSE){
			$return .= '&id='.get_value_get('id');
		}
		if(get_value_get('type') !== FALSE){
			$return .= '&type='.get_value_get('type');
		}
		/*if(get_value_get('page') !== FALSE){
			$return .= '&page='.get_value_get('page');
		}
		if(get_value_get('page') !== FALSE){
			$return .= '&page='.get_value_get('page');
		}*/
		return $return;
	}
	function check_is_loggedin(){
		$valid = 0;
		if(isset($_SESSION) && !empty($_SESSION) && get_value_session('login') != FALSE && get_value_session('ip') != FALSE){
			if(get_value_session('login') == 1 && get_value_server('REMOTE_ADDR') == get_value_session('ip')){
				$valid = 1;
			}
		}
		if($valid === 1){
			return TRUE;
		}else{
			return FALSE;
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
	function test_function(){
		global $users;
		if(isset($users) && !empty($users)){
		//var_dump($users);
		}
	}
	function login_do_action_checkcredentials(){
		global $mysqli;
		global $lang;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if(is_valid_email(get_value_post('user')) !== FALSE){
			$query = $mysqli->query("SELECT * FROM `user` WHERE `email` LIKE '".$mysqli->real_escape_string(get_value_post('user'))."' AND `pass` = '".$mysqli->real_escape_string(md5(get_value_post('pass')))."' LIMIT 1");
		}else{
			$query = $mysqli->query("SELECT * FROM `user` WHERE `username` LIKE '".$mysqli->real_escape_string(get_value_post('user'))."' AND `pass` = '".$mysqli->real_escape_string(md5(get_value_post('pass')))."' LIMIT 1");
		}
		if($query->num_rows == "0"){
		  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>'.$lang->translate(23).'</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link href="templates/develop/css/style.css" rel="stylesheet" type="text/css" media="screen"/></head><body>';

			echo '<b>'.$lang->translate(4).'</b><br />'.$lang->translate(5).'<br /><br />';
			return FALSE;
		}else{
			while($row_users = $query->fetch_array(MYSQLI_ASSOC)){
				if($row_users['suspend'] == "1"){
					echo '<b>'.$lang->translate(4).'</b><br />'.$lang->translate(5).'<br /><br />';
					//$error = "1";
					return FALSE;
				}else{
					return TRUE;
				}
			}
		}
	}
	function login_do_action_createsession(){
		global $lang;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if(is_valid_email(get_value_post('user')) !== FALSE){
			$query = $mysqli->query("SELECT * FROM `user` WHERE `email` LIKE '".$mysqli->real_escape_string(get_value_post('user'))."' AND `pass` = '".$mysqli->real_escape_string(md5(get_value_post('pass')))."' LIMIT 1");
		}else{
			$query = $mysqli->query("SELECT * FROM `user` WHERE `username` LIKE '".$mysqli->real_escape_string(get_value_post('user'))."' AND `pass` = '".$mysqli->real_escape_string(md5(get_value_post('pass')))."' LIMIT 1");
		}
		if(!isset($query) || empty($query) || $query->num_rows != "1"){
			return FALSE;
		}else{
			while($row_users = $query->fetch_array(MYSQLI_ASSOC)){
				$_SESSION['from_db'] = $row_users;
				$_SESSION['from_db']['id'] = $_SESSION['from_db']['id']+1;
				$_SESSION['from_db']['id'] = $_SESSION['from_db']['id']-1;
				$_SESSION['from_db']['is_admin'] = $_SESSION['from_db']['is_admin']+1;
				$_SESSION['from_db']['is_admin'] = $_SESSION['from_db']['is_admin']-1;
				$_SESSION['ip'] = get_value_server('REMOTE_ADDR');
				$_SESSION['login'] = 1;
				$query = "UPDATE `user` SET `aantal_login` = aantal_login+1 WHERE `id` = '".$mysqli->real_escape_string($row_users['id'])."' LIMIT 1";
				$mysqli->query($query) or die($mysqli->error);
				$mysqli->query("INSERT INTO `user_login_history` (`user_id`,`ip`) VALUES ('".$row_users['id']."','".get_value_server('REMOTE_ADDR')."')") or die($mysqli->error);
				return TRUE;
			}
		}
	}
	function record_change_user($userid){
		global $lang;
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$query = $mysqli->query("SELECT * FROM `user` WHERE `id` LIKE '".$mysqli->real_escape_string($userid)."' LIMIT 1");
		if(!isset($query) || empty($query) || $query->num_rows != "1"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$query = $mysqli->query("UPDATE `user` SET `laatste_wijziging` = '".date("Y-m-d H:i:s")."' WHERE `id` LIKE '".$mysqli->real_escape_string($userid)."' LIMIT 1");
				$sql = 'INSERT INTO `user_history` (`user_id`,`ip`) VALUES ("'.$mysqli->real_escape_string($userid).'","'.$mysqli->real_escape_string(get_value_server('REMOTE_ADDR')).'")';
				$mysqli->query($sql);
				$id = $mysqli->insert_id;
				$mysqli->query('UPDATE `user_history` SET `username` = "'.$row['username'].'", `pass` = "'.$row['pass'].'", `is_admin` = "'.$row['is_admin'].'", `subsuspend` = "'.$row['subsuspend'].'", `id_master` = "'.$row['id_master'].'", `aanmaak_datum` = "'.$row['aanmaak_datum'].'", `suspend` = "'.$row['suspend'].'", `default_lang` = "'.$row['default_lang'].'", `handelsnaam` = "'.$row['handelsnaam'].'", `home_page` = "'.$row['home_page'].'", `layout` = "'.$row['layout'].'", `email` = "'.$row['email'].'", `aantal_login` = "'.$row['aantal_login'].'", `aantal_wijzigingen` = "'.$row['aantal_wijzigingen'].'" WHERE `user_id` LIKE "'.$mysqli->real_escape_string($userid).'" AND `id` LIKE "'.$id.'" LIMIT 1');
				return TRUE;
			}
		}
	}
	function login_create_loginscreen($melding = FALSE){
		global $lang;
		$echo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>'.$lang->translate(23).'</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link href="templates/develop/css/style.css" rel="stylesheet" type="text/css" media="screen"/></head><body>';
		if($melding === FALSE){
		$echo .= '';
		}else{
		$echo .= $melding;
		}
		if(get_value_get('page') != 'uitloggen' && get_value_get('page') != 'gegevens'){
			$echo .= '<table class="loginform"><tr><th colspan=2>Control Panel</th></tr><tr><form name="form1" method="POST" action="index.php">';
		}else{ 
			$echo .= '<table class="loginform"><tr><th colspan=2>Control Panel</th></tr><tr><form name="form1" method="POST" action="index.php">';
		}
		$echo .= '<td colspan="2"><br>'.$lang->translate(1403).'<br><br></td></tr><tr>';
		$echo .= '<td>'.$lang->translate(1).':</td><td><input type="text" id="user" name="user" value="'.$_POST[user].'"/></td></tr><tr><td>';
		$echo .= $lang->translate(2).':</td><td><input type="password" id="pass" name="pass" value="'.$_POST[pass].'"/></td></tr>';
    $echo .= '<tr><td>'.$lang->translate(1402).'</td><td><select name="lang" onchange="document.form1.submit()">';
    if(get_value_post('lang') != FALSE){ 
    $echo .= '<option value="'.$_POST[lang].'">'.$lang->translate(1401).'</option>';
    } else { 
    $echo .= '<option value="en">Default</option> ';
    }
    $echo .= '<option value="nl">Nederlands</option> 
              <option value="en">English</option> 
              </select>
              </td></tr>';
		$echo .= '<tr><td></td><td><input type="submit" value="'.$lang->translate(1404).'" name="login" class="button"/></td></tr></form>';

    $echo .= '</table></body></html>';
		return $echo;
	}
	function lang_get_value_defaultlang(){
		global $default_lang;
		if(get_value_session('from_db','default_lang') != FALSE){ $default_lang = get_value_session('from_db','default_lang'); }
		if(get_value_post('default_lang') != FALSE){ $default_lang = get_value_post('default_lang'); }
		if(get_value_post('lang') != FALSE){ $default_lang = get_value_post('lang'); }
		if(get_value_get('lang') != FALSE){ $default_lang = get_value_get('lang'); }
		switch ( $default_lang ) {
			case "dutch":	$default_lang = 'nl';	break;
			case "Dutch":	$default_lang = 'nl';	break;
			case "nl":		$default_lang = 'nl';	break;
			case "NL":		$default_lang = 'nl';	break;
			case "en":		$default_lang = 'en';	break;
			case "EN":		$default_lang = 'en';	break;
			default:		$default_lang = 'nl';	break;
		}
		return $default_lang;
	}
	function check_user_right($userid,$right,$admin = FALSE,$id = FALSE,$array = FALSE){
    	global $mysqli;
		global $lang;
		global $check_user_right_func_intern;

		//var_dump($check_user_right_func_intern);
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$userid = $userid+1;
		$userid = $userid-1;
		if($admin == '1' && $userid != '0'){
			$check_user_right_func_intern[$userid][$right] = 1;
			global $check_user_right_func_intern;
			return 1;
		}else{
			if(!isset($check_user_right_func_intern[$userid][$right]) || $check_user_right_func_intern[$userid][$right] !== FALSE && $check_user_right_func_intern[$userid][$right] !== TRUE){
				$query = $mysqli->query("SELECT * FROM `user_right` WHERE `userid` LIKE '".$mysqli->real_escape_string($userid)."' AND `right` LIKE '".$mysqli->real_escape_string($right)."' LIMIT 1");
				if(!isset($query) || empty($query) || $query->num_rows != "1"){
					$check_user_right_func_intern[$userid][$right] = FALSE;
					global $check_user_right_func_intern;
					return FALSE;
				}else{
					while($row = $query->fetch_array(MYSQLI_ASSOC)){
						if($row['user'] == 0){
							$check_user_right_func_intern[$userid][$right] = FALSE;
							global $check_user_right_func_intern;
							return FALSE;
						}else{
							$check_user_right_func_intern[$userid][$right] = $row['user'];
							global $check_user_right_func_intern;
							return $row['user'];
						}
					}
				}
			}else{
				return $check_user_right_func_intern[$userid][$right];
			}
		}
	}
	function check_subuser_right($userid,$right,$admin = FALSE,$id = FALSE,$array = FALSE){
		global $mysqli;
		global $lang;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if($admin == '1' && $userid != '0'){
			return 2;
		}else{
			$query = $mysqli->query("SELECT * FROM `user_right` WHERE `userid` LIKE '".$mysqli->real_escape_string($userid)."' AND `right` LIKE '".$mysqli->real_escape_string($right)."' LIMIT 1");
			if(!isset($query) || empty($query) || $query->num_rows != "1"){
				return FALSE;
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					if($row['subuser'] == 0){
						return FALSE;
					}else{
						return $row['subuser'];
					}
				}
			}
		}
	}
	function check_user_subuser($userid,$subuser,$type = 3){
		global $mysqli;
		global $lang;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if($type === 3){
			$query = $mysqli->query("SELECT * FROM `user_subuser` WHERE `userid` LIKE '".$mysqli->real_escape_string($userid)."' AND `subuserid` LIKE '".$mysqli->real_escape_string($subuser)."' AND `type` LIKE '%'") or die($mysqli->error);
		}else{
			$query = $mysqli->query("SELECT * FROM `user_subuser` WHERE `userid` LIKE '".$mysqli->real_escape_string($userid)."' AND `subuserid` LIKE '".$mysqli->real_escape_string($subuser)."' AND `type` LIKE '".$mysqli->real_escape_string($type)."'") or die($mysqli->error);
		}
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	function get_masterusers($userid,$type = 3){
		global $mysqli;
		global $lang;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if($type === 3){
			$query = $mysqli->query("SELECT * FROM `user_subuser` WHERE `subuserid` LIKE '".$mysqli->real_escape_string($userid)."' AND `type` LIKE '%'") or die($mysqli->error);
		}else{
			$query = $mysqli->query("SELECT * FROM `user_subuser` WHERE `subuserid` LIKE '".$mysqli->real_escape_string($userid)."' AND `type` LIKE '".$mysqli->real_escape_string($type)."'") or die($mysqli->error);
		}
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$array[$row['userid']]['id'] = $row['userid'];
				$array[$row['userid']]['type'] = $row['type'];
			}
			return $array;
		}
	}
	function get_subusers($userid,$type = 1){
		global $mysqli;
		global $lang;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if($type === 3){
			$query = $mysqli->query("SELECT * FROM `user_subuser` WHERE `userid` LIKE '".$mysqli->real_escape_string($userid)."' ORDER BY `subuserid` ASC") or die($mysqli->error);
		}else{
			$query = $mysqli->query("SELECT * FROM `user_subuser` WHERE `userid` LIKE '".$mysqli->real_escape_string($userid)."' AND `type` LIKE '".$mysqli->real_escape_string($type)."'  ORDER BY `subuserid` ASC") or die($mysqli->error);
		}
		if($query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$array[$row['subuserid']]['id'] = $row['subuserid'];
				$array[$row['subuserid']]['type'] = $row['type'];
			}
			return $array;
		}
	}
	function get_search_results($userid,$search,$type = 'users',$admin = 0){
		global $mysqli;
		global $lang;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$array = '';
		if($type == 'users'){
			unset($array);
			$sql = 'SELECT `id` FROM `user` WHERE `id` LIKE "'.$mysqli->real_escape_string($search).'" OR `username` LIKE "%'.$mysqli->real_escape_string($search).'%" OR `id_master` LIKE "'.$mysqli->real_escape_string($search).'" OR `handelsnaam` LIKE "%'.$mysqli->real_escape_string($search).'%" OR `email` LIKE "%'.$mysqli->real_escape_string($search).'%" OR`aanmaak_datum` LIKE "%'.$mysqli->real_escape_string($search).'%" OR `laatste_wijziging` LIKE "%'.$mysqli->real_escape_string($search).'%"';
			$query = $mysqli->query($sql);
			if(!isset($query) || empty($query) || $query->num_rows == "0" || $query->num_rows == '-1'){
				$array = '';
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$results[$row['id']]['id'] = $row['id'];
				}
				if($admin === 1){
					foreach($results as $temp){
						$array[$temp['id']]['id'] = $temp['id'];
					}
				}else{
					foreach($results as $temp){
						if(check_user_subuser($userid,$temp['id']) != FALSE){
							$array[$temp['id']]['id'] = $temp['id'];
						}elseif($userid == $temp['id']){
							$array[$temp['id']]['id'] = $temp['id'];
						}
					}
				}
			}
		}
		return $array;
	}
	function get_userdata($userid,$limit = FALSE){
		global $mysqli;
		global $lang;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		if($limit === FALSE){
			$query = $mysqli->query("SELECT * FROM `user` WHERE `id` LIKE '".$mysqli->real_escape_string($userid)."'") or die($mysqli->error);
		}else{
			$sql = "SELECT ";
			$i = 0;
			foreach($limit as $item){
				if($i === 1){
					$sql .= ',';
				}
				$sql .= $item.' ';
				$i = 1;
			}
			$sql .= "FROM `user` WHERE `id` LIKE '".$mysqli->real_escape_string($userid)."'";
			$query = $mysqli->query($sql) or die($mysqli->error);
		}
		$query = $mysqli->query("SELECT * FROM `user` WHERE `id` LIKE '".$mysqli->real_escape_string($userid)."'") or die($mysqli->error);
		if($query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$array = $row;
			}
			return $array;
		}
	}
	function send_email($email,$subject,$message,$mailfrom = 'noreply@sinnerg.nl',$namefrom = 'Do not reply'){
		global $phpmailer;
		global $config;
		$phpmailer->IsSMTP();
		$phpmailer->Host	   = $config['smtp']['host']; // sets the SMTP server
		$phpmailer->Port	   = $config['smtp']['port'];					// set the SMTP port for the GMAIL server
		$phpmailer->AddReplyTo($mailfrom, $namefrom);
		$phpmailer->AddAddress($email, $email);
		$phpmailer->SetFrom($mailfrom,$namefrom);
		$phpmailer->Subject = $subject;
		$phpmailer->MsgHTML($message);
		$phpmailer->Send();
	}
	function menu_create_information_default($page){
		global $lang;
		global $modules;
		$menu[$lang->translate(7)][$lang->translate(8)]['url'] = '';
		$menu[$lang->translate(7)][$lang->translate(8)]['plaatje'] = 'home.png';
		
		if(isset($modules['klanten']) && !empty($modules['klanten']) && $modules['klanten'] === 1){ 
			if(check_user_right(get_value_session('from_db','id'),'reseller',get_value_session('from_db','is_admin')) != FALSE){ 
				$menu[$lang->translate(7)][$lang->translate(11)]['url'] = '&page=klanten&type=overzicht'; 
				$menu[$lang->translate(7)][$lang->translate(11)]['plaatje'] = 'klanten.png';
			} 
		}
	
		if(isset($modules['producten']) && !empty($modules['producten']) && $modules['producten'] === 1){
			if(check_user_right(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin')) != FALSE || check_user_right(get_value_session('from_db','id'),'pakketten',get_value_session('from_db','is_admin')) != FALSE || check_user_right(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin')) != FALSE || check_user_right(get_value_session('from_db','id'),'vps',get_value_session('from_db','is_admin')) != FALSE || check_user_right(get_value_session('from_db','id'),'email',get_value_session('from_db','is_admin')) != FALSE){
				$menu[$lang->translate(7)][$lang->translate(22)]['url'] = '&page=producten&type=overzicht'; 
				$menu[$lang->translate(7)][$lang->translate(22)]['plaatje'] = 'pakketten.png';
			} 
		}
		if(isset($modules['dns']) && !empty($modules['dns']) && $modules['dns'] === 1){ 
			if(check_user_right(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin')) != FALSE){ 
				$menu[$lang->translate(7)]['dns']['url'] = '&page=dns'; 
				$menu[$lang->translate(7)]['dns']['plaatje'] = 'dns.png';
			} 
		}
		$menu[$lang->translate(7)][$lang->translate(30)]['url'] = '&page=gegevens';
		$menu[$lang->translate(7)][$lang->translate(30)]['plaatje'] = 'mijn_gegevens.png';
		//$menu[$lang->translate(7)][$lang->translate(33)]['url'] = '&page=gegevens&type=feedback';
		//$menu[$lang->translate(7)][$lang->translate(33)]['plaatje'] = 'feedback.png';
		$menu[$lang->translate(7)][$lang->translate(14)]['url'] = '&page=uitloggen';
		$menu[$lang->translate(7)][$lang->translate(14)]['plaatje'] = 'uitloggen.png';
		if($page == 'home'){

		}
		
		$pakketten = pakketten_get_value_overview(get_value_session('from_db','id'));
		if($pakketten !== FALSE){
			foreach($pakketten as $pakket){
				if($pakket['user_id'] == get_value_session('from_db','id')){
					if($pakket['type'] == 'dns'){
						//$menu[$lang->translate(25)][$pakket['pakket_name']]['url'] = '&page='.$pakket['type'].'&id='.$pakket['pakket_id'];
						//$menu[$lang->translate(25)][$lang->translate(26).' '.$pakket['pakket_id']]['plaatje'] = 'dns.png';
					}elseif($pakket['type'] == 'stream'){
						//$menu[$lang->translate(25)][$pakket['pakket_name']]['url'] = '&page='.$pakket['type'].'&id='.$pakket['pakket_id'];
						//$menu[$lang->translate(25)][$lang->translate(27).' '.$pakket['pakket_id']]['plaatje'] = 'stream.png';
					}
				}
			}
		}
		//statistiek
		//voip
		//vps
		//email
		//firewall
		return $menu;
	}
	function menu_create_information($page){
		if(function_exists('menu_create_information_'.$page) !== FALSE){
			$function = 'menu_create_information_'.$page;
			$return = $function($page);
		}else{
			$return = menu_create_information_default($page);
		}
		return $return;
	}
	function fix_is_file($file,$default){
		if(file_exists($file) === TRUE){
			return $file;
		}else{
			return $default;
		}
	}
	function template_do_action_parse($html,$menu,$template,$cp_version=FALSE){
		global $layout_dir;
		global $index;
		global $lang;
		global $template_dir;
		$layout = fix_is_file($layout_dir.'/'.$template.'.php',$layout_dir.'/default.php');
		require_once($layout);
		return $content;
	}
	function is_valid_email($email) {
		@list($user, $host) = explode('@', $email);
		if (empty($user) || empty($host)) {
			return FALSE;
		}elseif (strstr($host, '.') === FALSE) {
			return FALSE;
		}
		return TRUE;
	}
	function gegevens_action_do_changepass($old,$new,$new2){
		if($old === $new || $old === $new2 || $new !== $new2){
			return FALSE;
		}elseif(get_value_session('from_db','pass') !== md5($old)){
			return FALSE;
		}else{
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			$query = $mysqli->query("UPDATE `user` SET `pass` = '".$mysqli->real_escape_string(md5($new))."' WHERE `id` LIKE '".$mysqli->real_escape_string(get_value_session('from_db','id'))."' AND `pass` = '".$mysqli->real_escape_string(get_value_session('from_db','pass'))."' LIMIT 1") or die($mysqli->error);
			if($mysqli->affected_rows == "0" || $mysqli->affected_rows == "-1"){
				return FALSE;
			}else{
				return TRUE;
			}
		}
	}
	function gegevens_do_action_changemail($new,$userid,$pass){
		if(is_valid_email($new) === FALSE){
			return FALSE;
		}else{
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			$query = $mysqli->query("UPDATE `user` SET `email` = '".$mysqli->real_escape_string($new)."' WHERE `id` LIKE '".$mysqli->real_escape_string($userid)."' AND `pass` = '".$mysqli->real_escape_string($pass)."' LIMIT 1") or die($mysqli->error);
			if($mysqli->affected_rows == "0" || $mysqli->affected_rows == "-1"){
				return FALSE;
			}else{
				return TRUE;
			}
		}
	}
	function PassGen() {
		$chars=array();
		for($i=48;$i<=57;$i++) {
			array_push($chars, chr($i));
		}
		for($i=65;$i<=90;$i++) {
			array_push($chars, chr($i));
		}
		for($i=97;$i<=122;$i++) {
			array_push($chars, chr($i));
		}
			while(list($k, $v)=each($chars)) {
		}
		$passwd = '';
		for($i=0;$i<14;$i++) {
			mt_srand((double)microtime()*1000000);
			$passwd.=$chars[mt_rand(0,count($chars))];
		}
		return $passwd;
	}
	function menu_create_information_klanten($page){
		global $lang;
		$menu = menu_create_information_default($page);
		if($page == 'klanten'){
			if(check_user_right(get_value_session('from_db','id'),'reseller',get_value_session('from_db','is_admin')) != FALSE){
				/*if(check_user_right(get_value_session('from_db','id'),'klantoverzicht',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(get_subusers('0',1) != FALSE){
				//			$menu[$lang->translate(11)][$lang->translate(16)] = '&page=klanten&type=overzicht';
						}
					}elseif(get_subusers(get_value_session('from_db','id'),1) != FALSE) {
			  //  		$menu[$lang->translate(11)][$lang->translate(16)] = '&page=klanten&type=overzicht';
					}
				}*/
				/*if(check_user_right(get_value_session('from_db','id'),'klanttoevoegen',get_value_session('from_db','is_admin')) != FALSE){
			  //  		$menu[$lang->translate(11)][$lang->translate(17)] = '&page=klanten&type=toevoegen';
				}*/
				/*if(get_value_session('from_db','is_admin') == '1'){
		    //		$temp = get_subusers('0');
				}else{
		    //		$temp = get_subusers(get_value_session('from_db','id'));
				}*/
				//if($temp != FALSE){
					/*if(check_user_right(get_value_session('from_db','id'),'klantzoeken',get_value_session('from_db','is_admin')) != FALSE){
			  //  	$menu[$lang->translate(11)][$lang->translate(18)] = '&page=klanten&type=zoeken';
					}
					if(check_user_right(get_value_session('from_db','id'),'klantoverzetten',get_value_session('from_db','is_admin')) != FALSE){
						if(get_value_session('from_db','is_admin') == '1'){
							if(get_subusers('0',2) != FALSE){
				//		$menu[$lang->translate(11)][$lang->translate(19)] = '&page=klanten&type=overzetten';
							}
						}elseif(get_subusers(get_value_session('from_db','id'),1) != FALSE) {
				//		$menu[$lang->translate(11)][$lang->translate(19)] = '&page=klanten&type=overzetten';
						}
					}*/
				//}
			}
		}
		return $menu;
	}
	function pakketten_check_is_allowed($id,$type,$admin = 2){
		if($admin == 1){
			return TRUE;
		}elseif(get_value_get('id') === FALSE){
			return FALSE;
		}else{
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			$query = $mysqli->query("SELECT * FROM `pakketten` WHERE `type` LIKE '".$mysqli->real_escape_string($type)."' AND `pakket_id` LIKE '".$mysqli->real_escape_string(get_value_get('id'))."' LIMIT 1");
			if(!isset($query) || empty($query) || $query->num_rows != "1"){
				return FALSE;
			}else{
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$userid = get_value_session('from_db','id');
					if($admin == "1"){
						return TRUE;
					}elseif(stripos($id,'%') === FALSE){
						if($row['user_id'] == $userid){
							return TRUE;
						}elseif(check_user_subuser($userid,$row['user_id']) !== FALSE){
							return TRUE;
						}else{
							return FALSE;
						}
					}else{
						return FALSE;
					}
				}
			}
		}
	}
	function pakketten_get_value_details($userid,$id,$admin = 2){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$query = $mysqli->query("SELECT * FROM `pakketten` WHERE `id` LIKE '".$mysqli->real_escape_string($id)."' LIMIT 1");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			$num = 0;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$temp = 0;
				$admin = $admin+1;
				$admin = $admin-1;
				if($admin === 1){
					$temp = 1;
				}elseif($row['user_id'] == $userid){
					$temp = 1;
				}elseif(check_user_subuser($userid,$row['user_id']) !== FALSE){
					$temp = 1;
				}else{
					$temp = 0;
				}
				if($temp === 1){
					$num++;
					$return['id'] = $row['id'];
					$return['type'] = $row['type'];
					$return['user_id'] = $row['user_id'];
					$return['pakket_id'] = $row['pakket_id'];
					$return['pakket_name'] = $row['pakket_name'];
				}
			}
			if($num === 0){
				return FALSE;
			}else{
				return $return;
			}
		}
	}
	function pakketten_get_value_overview($userid,$type = '%',$admin = 2){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$query = $mysqli->query("SELECT * FROM `pakketten` WHERE `type` LIKE '".$mysqli->real_escape_string($type)."' ORDER BY `id` ASC");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			$num = 0;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$temp = 0;
				if($admin === 1){
					$temp = 1;
				}elseif($row['user_id'] == $userid){
					$temp = 1;
				}elseif(check_user_subuser($userid,$row['user_id']) !== FALSE){
					$temp = 1;
				}else{
					$temp = 0;
				}
				if($temp === 1){
					$num++;
					$return[$row['id']]['id'] = $row['id'];
					$return[$row['id']]['type'] = $row['type'];
					$return[$row['id']]['user_id'] = $row['user_id'];
					$return[$row['id']]['pakket_id'] = $row['pakket_id'];
					$return[$row['id']]['pakket_name'] = $row['pakket_name'];
				}
			}
			if($num === 0){
				return FALSE;
			}else{
				return $return;
			}
		}
	}
    function menu_create_information_dns($page){
		global $lang;
		$menu = menu_create_information_default($page);
		if($page == 'dns'){
			if(check_user_right(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin')) != FALSE && pakketten_check_is_allowed(get_value_get('id'),'dns',get_value_session('from_db','is_admin'))){
				if(check_user_right(get_value_session('from_db','id'),'dnsdomoverzicht',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(get_value_get('id') !== FALSE){
							if(dns_get_number_domains(get_value_get('id'),'%') !== FALSE){
								$menu[$lang->translate(604)][$lang->translate(605)]['url'] = '&page=dns&type=domoverzicht&id='.get_value_get('id').'';				
								$menu[$lang->translate(604)][$lang->translate(605)]['plaatje'] = 'dns_domoverzicht.png';
							}
						}else{
							if(dns_get_number_domains('%','%') !== FALSE){
								$menu[$lang->translate(604)][$lang->translate(605)]['url'] = '&page=dns&type=domoverzicht';
								$menu[$lang->translate(604)][$lang->translate(605)]['plaatje'] = 'dns_domoverzicht.png';
							}
						}
					}elseif(dns_get_number_domains(get_value_get('id'),'%') !== FALSE){
						$menu[$lang->translate(604)][$lang->translate(605)]['url'] = '&page=dns&type=domoverzicht&id='.get_value_get('id').'';
						$menu[$lang->translate(604)][$lang->translate(605)]['plaatje'] = 'dns_domoverzicht.png';
					}
				}				
				if(check_user_right(get_value_session('from_db','id'),'dnsdomtoevoegen',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_get('id') !== FALSE){
						if(dns_get_number_domains(get_value_get('id'),'%') == FALSE){
							$menu[$lang->translate(604)][$lang->translate(606)]['url'] = '&page=dns&type=domtoevoegen&id='.get_value_get('id').'';
							$menu[$lang->translate(604)][$lang->translate(606)]['plaatje'] = 'dns_domtoevoegen.png';
						}
					}
				}
				if(check_user_right(get_value_session('from_db','id'),'dnstemoverzicht',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(get_value_get('id') !== FALSE){
							if(dns_get_number_templates(get_value_get('id'),1) !== FALSE){
								$menu[$lang->translate(604)][$lang->translate(607)]['url'] = '&page=dns&type=temoverzicht&id='.get_value_get('id').'';
								$menu[$lang->translate(604)][$lang->translate(607)]['plaatje'] = 'dns_temoverzicht.png';
							}
						}else{
							if(dns_get_number_templates('%',1) !== FALSE){
								$menu[$lang->translate(604)][$lang->translate(607)]['url'] = '&page=dns&type=temoverzicht';
								$menu[$lang->translate(604)][$lang->translate(607)]['plaatje'] = 'dns_temoverzicht.png';
							}
						}
					}elseif(dns_get_number_templates(get_value_get('id'),1) !== FALSE){
						$menu[$lang->translate(604)][$lang->translate(607)]['url'] = '&page=dns&type=temoverzicht&id='.get_value_get('id').'';
						$menu[$lang->translate(604)][$lang->translate(607)]['plaatje'] = 'dns_temoverzicht.png';
					}
				}
				if(check_user_right(get_value_session('from_db','id'),'dnstemtoevoegen',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_get('id') !== FALSE){
						if(dns_get_number_templates(get_value_get('id'),1) == FALSE){
							$menu[$lang->translate(604)][$lang->translate(608)]['url'] = '&page=dns&type=temtoevoegen&id='.get_value_get('id').'';
							$menu[$lang->translate(604)][$lang->translate(608)]['plaatje'] = 'dns_temtoevoegen.png';
						}
					}
				} 
				if(check_user_right(get_value_session('from_db','id'),'dnssmtoevoegen',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_get('id') !== FALSE){
						if(dns_get_number_supermasters(get_value_get('id')) == FALSE){
							$menu[$lang->translate(604)][$lang->translate(770)]['url'] = '&page=dns&type=supertoevoegen&id='.get_value_get('id').'';
							$menu[$lang->translate(604)][$lang->translate(770)]['plaatje'] = 'dns_supertoevoegen.png';
						}
					}
				}
				if(check_user_right(get_value_session('from_db','id'),'dnssmoverzicht',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(get_value_get('id') !== FALSE){
							//var_dump(dns_get_number_supermasters(get_value_get('id')));
							if(dns_get_number_supermasters(get_value_get('id')) !== FALSE){
								$menu[$lang->translate(604)][$lang->translate(771)]['url'] = '&page=dns&type=superoverzicht&id='.get_value_get('id').'';
								$menu[$lang->translate(604)][$lang->translate(771)]['plaatje'] = 'dns_superoverzicht.png';
							}
						}
					}else{
						if(dns_get_number_supermasters(get_value_get('id')) !== FALSE){
							$menu[$lang->translate(604)][$lang->translate(771)]['url'] = '&page=dns&type=superoverzicht&id='.get_value_get('id').'';
							$menu[$lang->translate(604)][$lang->translate(771)]['plaatje'] = 'dns_superoverzicht.png';
						}
					}
				}
				if(check_user_right(get_value_session('from_db','id'),'dnsrecglobbew',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(get_value_get('id') !== FALSE){
							if(dns_get_number_domains(get_value_get('id')) !== FALSE){
								$menu[$lang->translate(604)][$lang->translate(612)]['url'] = '&page=dns&type=recglobbew&id='.get_value_get('id').'';
								$menu[$lang->translate(604)][$lang->translate(612)]['plaatje'] = 'dns_recglobbew.png';
							}
						}else{
							if(dns_get_number_domains('%') !== FALSE){
								$menu[$lang->translate(604)][$lang->translate(612)]['url'] = '&page=dns&type=recglobbew';
								$menu[$lang->translate(604)][$lang->translate(612)]['plaatje'] = 'dns_recglobbew.png';
							}
						}
					}elseif(dns_get_number_domains(get_value_get('id')) !== FALSE){
						$menu[$lang->translate(604)][$lang->translate(612)]['url'] = '&page=dns&type=recglobbew&id='.get_value_get('id').'';
						$menu[$lang->translate(604)][$lang->translate(612)]['plaatje'] = 'dns_recglobbew.png';
					}
				}
			}
		}
		return $menu;
	}
	function menu_create_information_stream($page){
		global $lang;
		$menu = menu_create_information_default($page);
		if($page == 'stream'){
			if(check_user_right(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin')) != FALSE && pakketten_check_is_allowed(get_value_get('id'),'stream',get_value_session('from_db','is_admin'))){
				if(check_user_right(get_value_session('from_db','id'),'streamoverzicht',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(get_value_get('id') !== FALSE){
							if(stream_get_number_streams(get_value_get('id')) !== FALSE){
								$menu[$lang->translate(904)][$lang->translate(905)]['url'] = '&page=stream&type=streamoverzicht&id='.get_value_get('id').'';
								$menu[$lang->translate(904)][$lang->translate(905)]['plaatje'] = '';
							}
						}else{
							if(stream_get_number_streams('%') !== FALSE){
								$menu[$lang->translate(904)][$lang->translate(905)]['url'] = '&page=stream&type=streamoverzicht';
								$menu[$lang->translate(904)][$lang->translate(905)]['plaatje'] = '';
							}
						}
					}elseif(stream_get_number_streams(get_value_get('id')) !== FALSE){
						$menu[$lang->translate(904)][$lang->translate(905)]['url'] = '&page=stream&type=streamoverzicht&id='.get_value_get('id').'';
						$menu[$lang->translate(904)][$lang->translate(905)]['plaatje'] = '';
					}
				}
				$streams = stream_get_details('%',get_value_get('id'),0);
				if($streams !== FALSE){
					foreach($streams as $stream){
						//var_dump($stream['status']);
						if($stream['status'] == '1' && $stream['status'] != '0'){
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(989).': '.$stream['name']]['url'] = '&page=stream&type=streamstop&id='.get_value_get('id').'&streamid='.$stream['id'];
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(989).': '.$stream['name']]['plaatje'] = '';
						}else{
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(988).': '.$stream['name']]['url'] = '&page=stream&type=streamstart&id='.get_value_get('id').'&streamid='.$stream['id'];
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(988).': '.$stream['name']]['plaatje'] = '';
						}
						if($stream['djstatus'] == '1' && $stream['djstatus'] != '0'){
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(992).': '.$stream['name']]['url'] = '&page=stream&type=streamdjstop&id='.get_value_get('id').'&streamid='.$stream['id'];
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(992).': '.$stream['name']]['plaatje'] = '';
						}else{
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(991).': '.$stream['name']]['url'] = '&page=stream&type=streamdjstart&id='.get_value_get('id').'&streamid='.$stream['id'];
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(991).': '.$stream['name']]['plaatje'] = '';
						}
						if($stream['status'] == '1' && $stream['status'] != '0'){
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(993).': '.$stream['name']]['extlink'] = 'http://'.$stream['host'].':'.$stream['poort'].'/'.$stream['mountpoint'];
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(993).': '.$stream['name']]['extlink']['plaatje'] = '';
						}
						if($stream['status'] == '1' && $stream['status'] != '0' && $stream['type'] == 'shoutcast'){
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(994).': '.$stream['name']]['extlink'] = 'http://'.$stream['host'].':'.$stream['poort'].'/';
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(994).': '.$stream['name']]['extlink']['plaatje'] = '';
						}elseif($stream['status'] == '1' && $stream['status'] != '0' && $stream['type'] == 'icecast'){
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(997).': '.$stream['name']]['extlink'] = 'http://'.$stream['host'].':'.$stream['poort'].'/';
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(997).': '.$stream['name']]['extlink']['plaatje'] = '';
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(998).': '.$stream['name']]['extlink'] = 'http://'.$stream['host'].':'.$stream['poort'].'/admin/';
							$menu[$lang->translate(990).' '.$stream['id']][$lang->translate(998).': '.$stream['name']]['extlink']['plaatje'] = '';
						}
					}
				}
				if(check_user_right(get_value_session('from_db','id'),'streamtoevoegen',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(get_value_get('id') !== FALSE){
							$menu[$lang->translate(904)][$lang->translate(906)]['url'] = '&page=stream&type=streamtoevoegen&id='.get_value_get('id').'';
							$menu[$lang->translate(904)][$lang->translate(906)]['plaatje'] = '';
						}
					}else{
						$menu[$lang->translate(904)][$lang->translate(906)]['url'] = '&page=stream&type=streamtoevoegen&id='.get_value_get('id').'';
						$menu[$lang->translate(904)][$lang->translate(906)]['plaatje'] = '';
					}
				}
				if(check_user_right(get_value_session('from_db','id'),'streamzoeken',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(get_value_get('id') !== FALSE){
							if(stream_get_number_streams(get_value_get('id')) !== FALSE){
								$menu[$lang->translate(904)][$lang->translate(907)]['url'] = '&page=stream&type=streamzoeken&id='.get_value_get('id').'';
								$menu[$lang->translate(904)][$lang->translate(907)]['plaatje'] = '';
							}
						}else{
							if(stream_get_number_streams('%') !== FALSE){
								$menu[$lang->translate(904)][$lang->translate(907)]['url'] = '&page=stream&type=streamzoeken';
								$menu[$lang->translate(904)][$lang->translate(907)]['plaatje'] = '';
							}
						}
					}elseif(stream_get_number_streams(get_value_get('id')) !== FALSE){
						$menu[$lang->translate(904)][$lang->translate(907)]['url'] = '&page=stream&type=streamzoeken&id='.get_value_get('id').'';
						$menu[$lang->translate(904)][$lang->translate(907)]['plaatje'] = '';
					}
				}
			}
		}
		return $menu;
	}
	function menu_create_information_producten($page){
		global $lang;
		$menu = menu_create_information_default($page);
		if($page == 'producten'){
			if(check_user_right(get_value_session('from_db','id'),'pakketten',get_value_session('from_db','is_admin')) != FALSE){
				/*if(check_user_right(get_value_session('from_db','id'),'pakketoverzicht',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(pakketten_get_value_overview('%','%',get_value_session('from_db','is_admin')) != FALSE){
							//$menu[$lang->translate(624)][$lang->translate(622)] = '&page=producten&type=overzicht';
						}
					}elseif(pakketten_get_value_overview(get_value_session('from_db','id'),'%') != FALSE) {
						//$menu[$lang->translate(624)][$lang->translate(622)] = '&page=producten&type=overzicht';
					}
				}
				if(check_user_right(get_value_session('from_db','id'),'pakkettoevoegen',get_value_session('from_db','is_admin')) != FALSE){
				//	$menu[$lang->translate(624)][$lang->translate(621)] = '&page=producten&type=toevoegen';
				}
				if(check_user_right(get_value_session('from_db','id'),'pakketzoeken',get_value_session('from_db','is_admin')) != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						if(pakketten_get_value_overview('%','%',get_value_session('from_db','is_admin')) != FALSE){
						//	$menu[$lang->translate(624)][$lang->translate(623)] = '&page=producten&type=zoeken';
						}
					}elseif(pakketten_get_value_overview(get_value_session('from_db','id'),'%') != FALSE) {
					//	$menu[$lang->translate(624)][$lang->translate(623)] = '&page=producten&type=zoeken';
					}
				}*/
			}
		}
		return $menu;
	}
	function pakketten_do_action_html_dns(){
		global $lang;
		$option = '<option value="none"></option>';
		if(check_user_right(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin')) == FALSE){
			$html = '.$lang->translate(635).';
		}else{
			if(get_value_session('from_db','is_admin') == '1'){
				$users = get_subusers('0',3);
				$userdata = get_userdata(get_value_session('from_db','id'));
				$options = '<option value="'.$userdata['id'].'">'.$userdata['username'].'</option>';
			}else{
				$users = get_subusers(get_value_session('from_db','id'),3);
				$options = '<option value=""></option>';
			}
			if(is_array($users) != FALSE){
				foreach($users as $user){
					if($user['id'] !== get_value_session('from_db','id')){
						$userdata = get_userdata($user['id']);
						$options .= '<option value="'.$userdata['id'].'">'.$userdata['username'].'</option>';
					}
				}
			}
			$html = '<p>'.$lang->translate(31).'</p><br />';
			$html .= '<div class="formtable"><form name="form1" method="post" action=""><input type="hidden" id="step2" name="step2" value="step2"><input type="hidden" id="type" name="type" value="dns">';
			$html .= '<table><tr><td>'.$lang->translate(642).'</td><td><input type="text" id="domain" name="domain"></td></tr>';
			$html .= '<tr><td>'.$lang->translate(643).'</td><td><input type="text" id="templates" name="templates"></td></tr>';
			$html .= '<tr><td>'.$lang->translate(659).'</td><td><select name="klant">'.$options.'</select></td></tr>';
			$html .= '<tr><td></td><td><input type="submit" value="'.$lang->translate(644).'" id="submit" name="submit" class="button"></td></tr></table></form></div>';
		}
		return $html;
	}
	function pakketten_get_value_used_dns($userid, $typ = 'domain'){
		$pakketten = pakketten_get_value_overview($userid,'dns');
		$useddomains = 0;
		$usedtemplates = 0;
		if(is_array($pakketten) !== FALSE){
			foreach($pakketten as $product){
				if($product['user_id'] == $userid){
					$useddomains = $useddomains+dns_get_value_current_usage($product['pakket_id'],'domain');
					$usedtemplates = $usedtemplates+dns_get_value_current_usage($product['pakket_id'],'template');
				}else{
					$useddomains = $useddomains+dns_get_value_pakket($product['pakket_id'],'domain');
					$usedtemplates = $usedtemplates+dns_get_value_pakket($product['pakket_id'],'template');
				}
			}
		}
		if($typ == 'domain'){
			return $useddomains;
		}else{
			return $usedtemplates;
		}
	}
	function pakketten_get_value_used_stream($userid, $typ = 'listeners'){
		$pakketten = pakketten_get_value_overview($userid,'stream');
		$usedlisteners = 0;
		if(is_array($pakketten) !== FALSE){
			foreach($pakketten as $product){
				if($product['user_id'] == $userid){
					$usedlisteners = $usedlisteners+stream_get_value_current_usage($product['pakket_id'],'listeners');
				}else{
					$usedlisteners = $usedlisteners+stream_get_value_pakket($product['pakket_id'],'listeners');
				}
			}
		}
		if($typ == 'listeners'){
			return $usedlisteners;
		}else{
			return FALSE;
		}
	}
	function pakketten_get_value_size_dns($userid, $typ = 'domain'){
		$pakketten = pakketten_get_value_overview($userid,'dns');
		$useddomains = 0;
		$usedtemplates = 0;
		if(is_array($pakketten) !== FALSE){
			foreach($pakketten as $product){
				if($product['user_id'] == $userid){
					$useddomains = $useddomains+dns_get_value_pakket($product['pakket_id'],'domain');
					$usedtemplates = $usedtemplates+dns_get_value_pakket($product['pakket_id'],'template');
				}
			}
		}
		if($typ == 'domain'){
			return $useddomains;
		}else{
			return $usedtemplates;
		}
	}
	function pakketten_get_value_size_stream($userid, $typ = 'listeners'){
		$pakketten = pakketten_get_value_overview($userid,'stream');
		$usedlisteners = 0;
		if(is_array($pakketten) !== FALSE){
			foreach($pakketten as $product){
				if($product['user_id'] == $userid){
					$usedlisteners = $usedlisteners+stream_get_value_pakket($product['pakket_id'],'listeners');
				}
			}
		}
		if($typ == 'listeners'){
			return $usedlisteners;
		}else{
			return FALSE;
		}
	}
	function pakketten_do_action_create_dns(){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$temp = 1;
		if(get_value_session('from_db','is_admin') == 1){
			$sql = 'INSERT INTO `pakketten_dns` (`max_domain`,`max_templates`) VALUES ("'.$mysqli->real_escape_string(get_value_post('domain')).'","'.$mysqli->real_escape_string(get_value_post('templates')).'")';
			$mysqli->query($sql);
			$id = $mysqli->insert_id;
			if($id != 0){
				$sql = 'INSERT INTO `pakketten` (`type`,`user_id`,`pakket_id`) VALUES ("dns","'.$mysqli->real_escape_string(get_value_post('klant')).'","'.$mysqli->real_escape_string($id).'")';
				$mysqli->query($sql);
				$id2 = $mysqli->insert_id;
				if($id2 != 0){
					return TRUE;
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}else{
			if(check_user_subuser(get_value_session('from_db','id'),get_value_post('klant'))){
				$availabledomains = pakketten_get_value_size_dns(get_value_session('from_db','id'),'domain')-pakketten_get_value_used_dns(get_value_session('from_db','id'),'domain');
				$availabletemplates = pakketten_get_value_size_dns(get_value_session('from_db','id'),'template')-pakketten_get_value_used_dns(get_value_session('from_db','id'),'template');
				
				if(get_value_post('domain') < $availabledomains && get_value_post('template') < $availabletemplates){
					$sql = 'INSERT INTO `pakketten_dns` (`max_domain`,`max_templates`) VALUES ("'.$mysqli->real_escape_string(get_value_post('domain')).'","'.$mysqli->real_escape_string(get_value_post('templates')).'")';
					$mysqli->query($sql);
					$id = $mysqli->insert_id;
					if($id != 0){
						$sql = 'INSERT INTO `pakketten` (`type`,`user_id`,`pakket_id`) VALUES ("dns","'.$mysqli->real_escape_string(get_value_post('klant')).'","'.$mysqli->real_escape_string($id).'")';
						$mysqli->query($sql);
						$id2 = $mysqli->insert_id;
						if($id2 != 0){
							return TRUE;
						}else{
							return FALSE;
						}
					}else{
						return FALSE;
					}
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}
	}
	function pakketten_do_action_changeuser($userid,$pakketid){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$temp = 1;
		if($userid == "%" || $pakketid == "%"){
			return FALSE;
		}else{
			$sql = 'UPDATE `pakketten` SET `user_id` = "'.$mysqli->real_escape_string($userid).'" WHERE `id` = "'.$mysqli->real_escape_string($pakketid).'" LIMIT 1';
			$mysqli->query($sql);
			$id = $mysqli->affected_rows;
			$id = 1;
			if($id != 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	function pakketten_do_action_search_dns($search){
		global $mysqli_dns;
		if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','central'); }
		global $mysqli_dns;
		$temp = 1;
		$sql = 'SELECT domains.id, domains.name, domains.account FROM domains INNER JOIN records ON (domains.id = records.domain_id) WHERE domains.name LIKE "'.$mysqli_dns->real_escape_string($search).'" OR records.name LIKE "'.$mysqli_dns->real_escape_string($search).'" OR records.content LIKE "'.$mysqli_dns->real_escape_string($search).'"';
		$query = $mysqli_dns->query($sql);
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			$num = 0;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				if($row['account'] == get_value_get('id')){
					$num++;
					$array[$row['id']] = $row['name'];
				}
			}
			if($num === 0){
				return FALSE;
			}else{
				return $array;
			}
		}
	}
	function pakketten_do_action_verwijder($userid,$id){
		$pakket = pakketten_get_value_details($userid,$id);
		if($pakket['type'] == 'dns'){
			$domains = dns_get_value_overview($pakket['pakket_id'],'domain');
			foreach($domains as $domain){
				dns_do_action_delete($domain['id'],$pakket['pakket_id'],'domain');
			}
			$templates = dns_get_value_overview($pakket['pakket_id'],'template');
			foreach($templates as $template){
				dns_do_action_delete($template['id'],$pakket['pakket_id'],'template');
			}
			$supers = dns_get_number_supermasters($pakket['pakket_id']);
			foreach($supers as $super){
				dns_do_action_delete($template['id'],$pakket['pakket_id'],'super');
			}
			return pakketten_do_action_delete($userid,$id);
		}elseif($pakket['type'] == 'stream'){
			$domains = stream_get_value_overview($pakket['pakket_id']);
			foreach($domains as $domain){
				stream_do_action_delete($domain['id'],$pakket['pakket_id']);
			}
			return pakketten_do_action_delete($userid,$id);
		}else{
			return FALSE;
		}
	}
	function pakketten_do_action_get_user($id,$type){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$query = $mysqli->query("SELECT `user_id` FROM `pakketten` WHERE `pakket_id` LIKE '".$mysqli->real_escape_string($id)."' AND `type` LIKE '".$mysqli->real_escape_string($type)."' LIMIT 1");
		if(!isset($query) || empty($query) || $query->num_rows == "0"){
			return FALSE;
		}else{
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				return $row['user_id'];
			}
		}
	}
	function pakketten_do_action_delete($userid,$id,$admin = 2){
		global $lang;
		$pakket = pakketten_get_value_details($userid,$id);
		if($pakket == FALSE){
			$return = FALSE;
		}else{
			global $mysqli;
			if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
			global $mysqli;
			if($pakket['type'] == 'dns'){
				$mysqli->query("DELETE FROM `pakketten_dns` WHERE `id` LIKE '".$mysqli->real_escape_string($pakket['pakket_id'])."'");
				$mysqli->query("DELETE FROM `pakketten` WHERE `id` LIKE '".$mysqli->real_escape_string($id)."'");
				return TRUE;
			}elseif($pakket['type'] == 'stream'){
				$mysqli->query("DELETE FROM `pakketten_stream` WHERE `id` LIKE '".$mysqli->real_escape_string($pakket['pakket_id'])."'");
				$mysqli->query("DELETE FROM `pakketten` WHERE `id` LIKE '".$mysqli->real_escape_string($id)."'");
				return TRUE;
			}else{
				return FALSE;
			}
			$return = TRUE;
		}
		return $return;
	}
	function pakketten_do_action_html_stream(){
		global $lang;
		$option = '<option value="none"></option>';
		if(check_user_right(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin')) == FALSE){
			$html = ''.$lang->translate(635).'<br /><br />';
		}else{
			if(get_value_session('from_db','is_admin') == '1'){
				$users = get_subusers('0',3);
				$userdata = get_userdata(get_value_session('from_db','id'));
				$options = '<option value="'.$userdata['id'].'">'.$userdata['username'].'</option>';
			}else{
				$users = get_subusers(get_value_session('from_db','id'),3);
				$options = '<option value=""></option>';
			}
			if(is_array($users) != FALSE){
				foreach($users as $user){
					if($user['id'] !== get_value_session('from_db','id')){
						$userdata = get_userdata($user['id']);
						$options .= '<option value="'.$userdata['id'].'">'.$userdata['username'].'</option>';
					}
				}
			}
			$html = '<form name="form1" method="post" action=""><input type="hidden" id="step2" name="step2" value="step2"><input type="hidden" id="type" name="type" value="stream">';
			$html .= '<table><tr><td>'.$lang->translate(900).'</td><td><input type="text" id="listeners" name="listeners"></td></tr>';
			$html .= '<tr><td>'.$lang->translate(659).'</td><td><select name="klant">'.$options.'</select></td></tr>';
			$html .= '<tr><td></td><td><input type="submit" value="'.$lang->translate(644).'" id="submit" name="submit"></td></tr></table></form>';
		}
		return $html;
	}
	function pakketten_do_action_create_stream(){
		global $mysqli;
		if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
		global $mysqli;
		$temp = 1;
		if(get_value_session('from_db','is_admin') == 1){
			$sql = 'INSERT INTO `pakketten_stream` (`max_listeners`) VALUES ("'.$mysqli->real_escape_string(get_value_post('listeners')).'")';
			$mysqli->query($sql);
			$id = $mysqli->insert_id;
			if($id != 0){
				$sql = 'INSERT INTO `pakketten` (`type`,`user_id`,`pakket_id`) VALUES ("stream","'.$mysqli->real_escape_string(get_value_post('klant')).'","'.$mysqli->real_escape_string($id).'")';
				$mysqli->query($sql);
				$id2 = $mysqli->insert_id;
				if($id2 != 0){
					return TRUE;
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}else{
			if(check_user_subuser(get_value_session('from_db','id'),get_value_post('klant'))){
				$availablelisteners = pakketten_get_value_size_stream(get_value_session('from_db','id'),'listeners')-pakketten_get_value_used_stream(get_value_session('from_db','id'),'listeners');
				
				if(get_value_post('listeners') < $availablelisteners){
					$sql = 'INSERT INTO `pakketten_stream` (`max_listeners`) VALUES ("'.$mysqli->real_escape_string(get_value_post('listeners')).'")';
					$mysqli->query($sql);
					$id = $mysqli->insert_id;
					if($id != 0){
						$sql = 'INSERT INTO `pakketten` (`type`,`user_id`,`pakket_id`) VALUES ("stream","'.$mysqli->real_escape_string(get_value_post('klant')).'","'.$mysqli->real_escape_string($id).'")';
						$mysqli->query($sql);
						$id2 = $mysqli->insert_id;
						if($id2 != 0){
							return TRUE;
						}else{
							return FALSE;
						}
					}else{
						return FALSE;
					}
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}
	}
