<?php
	// Created by Mark Scholten
	// This file is up-to-date to V2.0
	// This file is called function.php and contains functions (where possible all functions)
	// Where possible functions should be used to keep things clear and easy to maintain
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
		$phpmailer->IsSMTP();
		$phpmailer->Host	   = "spamfilter01.streamservice.nl"; // sets the SMTP server
		$phpmailer->Port	   = 25;					// set the SMTP port for the GMAIL server
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
		$menu[$lang->translate(7)][$lang->translate(33)]['url'] = '&page=gegevens&type=feedback';
		$menu[$lang->translate(7)][$lang->translate(33)]['plaatje'] = 'feedback.png';
		$menu[$lang->translate(7)][$lang->translate(14)]['url'] = '&page=uitloggen';
		$menu[$lang->translate(7)][$lang->translate(14)]['plaatje'] = 'uitloggen.png';
		if($page == 'home'){

		}
		
		$pakketten = pakketten_get_value_overview(get_value_session('from_db','id'));
		if($pakketten !== FALSE){
			foreach($pakketten as $pakket){
				if($pakket['user_id'] == get_value_session('from_db','id')){
					if($pakket['type'] == 'dns'){
						$menu[$lang->translate(25)][$lang->translate(26).' '.$pakket['pakket_id']]['url'] = '&page='.$pakket['type'].'&id='.$pakket['pakket_id'];
						$menu[$lang->translate(25)][$lang->translate(26).' '.$pakket['pakket_id']]['plaatje'] = 'dns.png';
					}elseif($pakket['type'] == 'stream'){
						$menu[$lang->translate(25)][$lang->translate(27).' '.$pakket['pakket_id']]['url'] = '&page='.$pakket['type'].'&id='.$pakket['pakket_id'];
						$menu[$lang->translate(25)][$lang->translate(27).' '.$pakket['pakket_id']]['plaatje'] = 'stream.png';
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
	function dns_get_value_overview($account,$type = 'domain',$admin = 2){
		if($type == 'domain'){
			global $mysqli_dns;
			if(!isset($mysqli_dns) || empty($mysqli_dns)){ create_db_connection('mysqli_dns','dns'); }
			global $mysqli_dns;
			
			if ( isset( $_POST['submit'] ) ) {
			  $query = $mysqli_dns->query("SELECT * FROM `domains` WHERE `account` LIKE '".$mysqli_dns->real_escape_string($account)."' AND `name` LIKE '%$_POST[search]%' LIMIT 10"); 
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
	function stream_create_html_search($type='listener'){
		global $lang;
		$html = '<br /><br /><form name="form1" method="post" action=""><table>';
		$html .= '<tr><td>'.$lang->translate(653).'</td><td><input type="text" id="search" name="search"></td></tr>';
		$html .= '<tr><td></td><td><input type="submit" value="'.$lang->translate(924).'" id="submit" name="submit"></td></tr></table></form>';
		return $html;
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

	
?>