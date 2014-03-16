<?php

	fix_is_included($index);  
		  
	if(get_value_get('type') == 'overzicht'){
		$html = '<div class="paginatitel">'.$lang->translate(30).'</div>';
		
		$html .= '<br><div class="tablehome"><table>';
		$html .= '<tr>';
		$html .= '<td><a href="?page=gegevens&type=email"><img src="'.$template_dir.'/desktop_email.png" border="0"></a></td>';
		$html .= '<td><a href="?page=gegevens&type=api"><img src="'.$template_dir.'/desktop_api.png" border="0"></a></td>';
		$html .= '<td><a href="?page=gegevens&type=wachtwoord"><img src="'.$template_dir.'/desktop_wachtwoord.png" border="0"></a></td>';
		$html .= '<td>&nbsp;</td>';
		$html .= '<td>&nbsp;</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td><a href="?page=gegevens&type=email">'.$lang->translate(9).'</a></td>';
		$html .= '<td><a href="?page=gegevens&type=api">'.$lang->translate(122).'</a></td>';
		$html .= '<td><a href="?page=gegevens&type=wachtwoord">'.$lang->translate(221).'</a></td>';
		$html .= '<td>&nbsp;</td>';
		$html .= '<td>&nbsp;</td>';
		$html .= '</tr>';
		$html .= '</table></div>';
	}elseif(get_value_get('type') == 'api' && check_user_right(get_value_session('from_db','id'),'apitoegang',get_value_session('from_db','is_admin')) != FALSE){
		$html = '<div class="paginatitel">'.$lang->translate(119).'</div><div class="content"><br /><p>';
		$html .= $lang->translate(120).': '.get_value_session('from_db','username').'<br />';
		$html .= $lang->translate(121).': '.base64_encode(get_value_session('from_db','pass')).'<br /><br /></p></div>';
	}elseif(get_value_get('type') == 'email'){
		$melding = '';
		if(get_value_post('submit') != FALSE){
			if(get_value_post('email') == FALSE){
				$melding = '<b>'.$lang->translate(114).'</b><br />'.$lang->translate(115).'<br />';
			}elseif(gegevens_do_action_changemail(get_value_post('email'),get_value_session('from_db','id'),get_value_session('from_db','pass')) == FALSE){
				$melding = '<b>'.$lang->translate(114).'</b><br />'.$lang->translate(116).'<br />';
			}else{
				$melding = $lang->translate(117).'<br />';
				$_SESSION['login'] = 0;
				if(check_is_loggedin() == FALSE){
					if(isset($_POST) && !empty($_POST) && isset($_POST['login']) && !empty($_POST['login'])){
						if(login_do_action_checkcredentials() == TRUE){ login_do_action_createsession(); }else{ echo login_create_loginscreen($melding); exit(); }
					}else{ echo login_create_loginscreen($melding); exit(); }
				}
			}
		}
		$html = '<div class="paginatitel">'.$lang->translate(30).'</div><div class="content"><p /><br />'.$melding.'<br /></p></div><p>'.$lang->translate(118).': <b>'.get_value_session('from_db','email').'</b><br><br></p><form name="form1" method="post" action=""><br><p>'.$lang->translate(112).'</p><br><br><p><input type="text" id="email" name="email"></p><br><br>
		<div class="content"><p><input type="submit" value="'.$lang->translate(113).'" id="submit" name="submit" class="button"></p></div></form><br /><br />';
	}elseif(get_value_get('type') == 'wachtwoord'){
		$melding = '';
		if(get_value_post('submit') != FALSE){
			if(get_value_post('curr_password') == FALSE || get_value_post('password') == FALSE || get_value_post('password2') == FALSE){
				$melding = '<b>'.$lang->translate(106).'</b><br />'.$lang->translate(107).'<br />';
			}elseif(gegevens_action_do_changepass(get_value_post('curr_password'),get_value_post('password'),get_value_post('password2')) == FALSE){
				$melding = '<b>'.$lang->translate(106).'</b><br />'.$lang->translate(108).'<br />';
			}else{
				$melding = $lang->translate(109).'<br />';
				$_SESSION['login'] = 0;
				if(check_is_loggedin() == FALSE){
					if(isset($_POST) && !empty($_POST) && isset($_POST['login']) && !empty($_POST['login'])){
						if(login_do_action_checkcredentials() == TRUE){ login_do_action_createsession(); }else{ echo login_create_loginscreen($melding); exit(); }
					}else{ echo login_create_loginscreen($melding); exit(); }
				}
			}
		}	
		$html = '<div class="paginatitel">'.$lang->translate(30).'</div><div class="content"><p><br />'.$melding.'<br /></p></div>';
		$html .= '<DIV class="formtable"><table><tr><td colspan="2"><b>'.$lang->translate(221).'</b><br></td>';
		$html .= '<tr><td  width="200px"><form name="form1" method="post" action="">'.$lang->translate(102).'</td><td><input type="password" id="curr_password" name="curr_password"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(103).'</td><td><input type="password" id="password" name="password"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(104).'</td><td><input type="password" id="password2" name="password2"></td></tr></table></div>';
		$html .= '<div class="content"><p><input type="submit" value="'.$lang->translate(105).'" id="submit" name="submit" class="button"></p></div></form><br /><br />';
	}elseif(get_value_get('type') == 'feedback'){
		$melding = '';
		if(get_value_post('submit') != FALSE){
			$melding = '<br /><br />'.$lang->translate(150).'<br /><br />';
			$message = "Via het contact formulier op dnsshop heeft ".get_value_post('naam')." (".get_value_post('mail234').") onderstaande feedback gegevens: \r\n\r\n ".get_value_post('inhoud')."\r\n Het IP adres van ".get_value_post('naam')." is ".$_SERVER["REMOTE_ADDR"]." en de login is ".get_value_session('from_db','username').".";
			$subject = "Feedback via contactformulier op dnsshop: ".get_value_post('onderwerp');
			$headers = "MIME-Version: 1.0\r\n".
				"Content-type: multipart/alternative\r\n".
				" 	boundary=\"----=_NextPart_000_002A_01C5CD23.F7D29650\"\r\n".
				"X-Priority: 3\r\n".
				"X-MSMail-Priority: Normal\r\n".
				"X-Mailer: Microsoft Outlook Express 6.00.2900.2670\r\n".
				"X-MimeOLE: Produced By Microsoft MimeOLE V6.00.2900.2670\r\n".
				"From: ".get_value_post('mail234')."  (".get_value_post('naam')." )\r\n".
				"Subject: ".$subject."";
			mail("info@streamservice.nl", $subject, $message, $header);
		}
		$html = '<div class="paginatitel">'.$lang->translate(33).'</div><div class="content"><p><br />'.$melding.'<br /></p></div>';
		$html .= '<form name="form1" method="post" action=""><div class="formtable"><table><tr><td colspan="2"><b>'.$lang->translate(33).'</b><br></td>';
		$html .= '<tr><td  width="200px">'.$lang->translate(151).'</td><td><input type="text" id="naam" name="naam" value="'.get_value_session('from_db','username').'"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(152).'</td><td><input type="text" id="mail1234" name="mail1234" value="'.get_value_session('from_db','email').'"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(153).'</td><td><input type="text" id="onderwerp" name="onderwerp"></td></tr>';
		$html .= '<tr><td>'.$lang->translate(154).'</td><td><textarea name="inhoud" rows="10" cols="42"></textarea></td></tr></table></div>';
		$html .= '<div class="content"><p><input type="submit" value="'.$lang->translate(155).'" id="submit" name="submit" class="button"></p></div></form><br /><br />';
	}else{
		$html = '<div class="paginatitel">'.$lang->translate(30).'</div>';
		$html .= '<br><br><div class="tablehome"><table>';
		$html .= '<tr>';
		//$html .= '<td><a href="?page=gegevens&type=overzicht"><img src="'.$template_dir.'/desktop_mijngegevens.png" border="0"></a></td>';
		$html .= '<td><a href="?page=gegevens&type=email"><img src="'.$template_dir.'/desktop_email.png" border="0"></a></td>';
		$html .= '<td><a href="?page=gegevens&type=api"><img src="'.$template_dir.'/desktop_api.png" border="0"></a></td>';
		$html .= '<td><a href="?page=gegevens&type=wachtwoord"><img src="'.$template_dir.'/desktop_wachtwoord.png" border="0"></a></td>';
		$html .= '<td>&nbsp;</td>';
		$html .= '<td>&nbsp;</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		//$html .= '<td><a href="?page=gegevens&type=overzicht">'.$lang->translate(30).'</a></td>';
		$html .= '<td><a href="?page=gegevens&type=email">'.$lang->translate(9).'</a></td>';
		$html .= '<td><a href="?page=gegevens&type=api">'.$lang->translate(122).'</a></td>';
		$html .= '<td><a href="?page=gegevens&type=wachtwoord">'.$lang->translate(221).'</a></td>';
		$html .= '<td>&nbsp;</td>';
		$html .= '<td>&nbsp;</td>';
		$html .= '</tr>';
		$html .= '</table></div>';
	}
?>