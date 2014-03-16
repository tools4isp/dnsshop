<?php  
	// Created by Mark Scholten
	// This file is called klanten.php and contains most (if not all) pages to change users
	fix_is_included($index);
	if(check_user_right(get_value_session('from_db','id'),'reseller',get_value_session('from_db','is_admin')) == FALSE){
		$html = '<b>'.$lang->translate(201).'</b>';
	}else{
		if(get_value_get('type') == 'overzetten'){
			$html = '<div class="paginatitel">'.$lang->translate(32).'</div>';
			if(check_user_right(get_value_session('from_db','id'),'klantoverzetten',get_value_session('from_db','is_admin')) != FALSE){
				$results = 0;
				$error = 0;
				if(get_value_post('submit') !== FALSE){
					if(check_user_subuser(get_value_session('from_db','id'),get_value_post('overtedragen')) != FALSE || check_user_subuser(get_value_session('from_db','id'),get_value_post('overdragenaan')) != FALSE || 	get_value_session('from_db','is_admin') == '1'){
						if(get_value_post('overtedragen') == get_value_post('overdragenaan')){
							$error = 2;
						}elseif(get_value_post('overdragenaan') == "-1"){
							$sql = 'DELETE FROM `user_subuser` WHERE `subuserid` LIKE "'.$mysqli->real_escape_string(get_value_post('overtedragen')).'"';
							record_change_user(get_value_post('overtedragen'));
							$sql2 = 'UPDATE `user` SET `id_master` = "0" WHERE `id` LIKE "'.$mysqli->real_escape_string(get_value_post('overtedragen')).'"';
							$mysqli->query($sql);
							$mysqli->query($sql2);
							$sql3 = 'INSERT INTO `user_subuser` (`userid`,`subuserid`,`type`) VALUES ("0","'.$mysqli->real_escape_string(get_value_post('overtedragen')).'","1")';
							$mysqli->query($sql3);
							$error = 1;
						}elseif(check_user_subuser(get_value_post('overtedragen'),get_value_post('overdragenaan')) !== FALSE){
							$error = 2;
						}else{
							$sql = 'DELETE FROM `user_subuser` WHERE `subuserid` LIKE "'.$mysqli->real_escape_string(get_value_post('overtedragen')).'"';
							record_change_user(get_value_post('overtedragen'));
							$sql2 = 'UPDATE `user` SET `id_master` = "'.$mysqli->real_escape_string(get_value_post('overdragenaan')).'" WHERE `id` LIKE "'.$mysqli->real_escape_string(get_value_post('overtedragen')).'"';
							$mysqli->query($sql);
							$mysqli->query($sql2);
							$masterusers = get_masterusers(get_value_post('overdragenaan'));
							if($masterusers !== FALSE){
								foreach($masterusers as $muser){
									$sql3 = 'INSERT INTO `user_subuser` (`userid`,`subuserid`,`type`) VALUES ("'.$mysqli->real_escape_string($muser['id']).'","'.$mysqli->real_escape_string(get_value_post('overtedragen')).'","2")';
									$mysqli->query($sql3);
								}
							}
							$sql3 = 'INSERT INTO `user_subuser` (`userid`,`subuserid`,`type`) VALUES ("'.$mysqli->real_escape_string(get_value_post('overdragenaan')).'","'.$mysqli->real_escape_string(get_value_post('overtedragen')).'","1")';
							$mysqli->query($sql3);
							$error = 1;
						}
					}else{ $error = 3; }
				}
				if(get_value_session('from_db','is_admin') == '1'){
					if(get_subusers('0',3) != FALSE){
						$subusers = get_subusers('0',3);
						$results = 1;
					}
				}elseif(get_subusers(get_value_session('from_db','id'),3) != FALSE) {
					$subusers = get_subusers(get_value_session('from_db','id'),3);
					$results = 1;
				}
				$userdata = get_userdata(get_value_get('id'));
				$form = '<br><p>'.$lang->translate(561).'</p><br>';
				$form .= '<form name="form2" method="post" action=""><div class=formtable><table>';
				
				$form .= '<tr><td width="175px">'.$lang->translate(565).'</td><td>';
				
				$form .= '<b>'.$userdata['username'].'</b>';
				$form .= '<input name="overtedragen" type="hidden" value="'.$userdata['id'].'"';				
				$form .= '</td></tr>';
				$form .= '<tr><td>'.$lang->translate(566).'</td><td><select name="overdragenaan">';
				$form .= '<option value="'.get_value_session('from_db','id').'" selected>'.get_value_session('from_db','username').'</option>';
				if(get_value_session('from_db','is_admin') == "1"){
					$form .= '<option value="-1">'.$lang->translate(569).'</option>';
				}
				foreach($subusers as $susers){
					if($susers['id'] != get_value_session('from_db','id')){
						$userdata = get_userdata($susers['id']);
						$form .= '<option value="'.$userdata['id'].'">'.$userdata['username'].'</option>';
					}
				}
				$form .= '</select></td></tr>';
				
				$form .= '</table></div><div class="content"><p><input type="submit" value="'.$lang->translate(563).'" id="submit" name="submit" class="button"></p></div></form><br /><br />';
				if($error === 1){
					$html .= '<br /><p>'.$lang->translate(567).'</p><br /><br />';
				}elseif($error === 2){
					$html .= '<br /><p>'.$lang->translate(564).'</p><br /><br />';
					$html .= $form;
				}elseif($error === 3){
					$html .= '<br /><p>'.$lang->translate(568).'</p><br /><br />';
					$html .= $form;
				}elseif($results === 1){
					$html .= $form;
				}else{
					$html .= '<br /><p>'.$lang->translate(562).'</p><br /><br />';
				}
			}else{
				$html .= '<br /><p>'.$lang->translate(562).'</p><br /><br />';
			}
		}elseif(get_value_get('type') == 'verwijderen'){
			$html = '<b>'.$lang->translate(551).'</b><br /><br />';
			if(check_user_right(get_value_session('from_db','id'),'klantbekijken',get_value_session('from_db','is_admin')) != FALSE){
				if(check_user_subuser(get_value_session('from_db','id'),get_value_get('id')) != FALSE || get_value_session('from_db','is_admin') == '1'){
					$succes = 0;
					if(get_value_post('submit')){
						if(get_subusers(get_value_get('id'),3) !== FALSE){
							$html .= '<br /><br />'.$lang->translate(570).'<br /><br />';
						}else{
							$pakketten = pakketten_get_value_overview(get_value_get('id'),'%');
							if($pakketten !== FALSE){
								foreach($pakketten as $pakket){
									pakketten_do_action_verwijder(get_value_get('id'),$pakket['id']);
								}
							}
							$sql = 'DELETE FROM `user_subuser` WHERE `subuserid` LIKE "'.$mysqli->real_escape_string(get_value_get('id')).'"';
							record_change_user(get_value_get('id'));
							$sql2 = 'DELETE FROM `user` WHERE `id` LIKE "'.$mysqli->real_escape_string(get_value_get('id')).'"';
							$mysqli->query($sql);
							$mysqli->query($sql2);
							$succes = 1;
						}
					}
					if($succes !== 0){
						$html .= '<br /><br />'.$lang->translate(554).'<br /><br />';
					}elseif(get_subusers(get_value_get('id'),1) != FALSE){
						$html .= '<br /><br />'.$lang->translate(555).'<br /><br />';
					}else{
						$html .= '<form name="form2" method="post" action=""><table border="1">';
						$userdata = get_userdata(get_value_get('id'));
						if($userdata['suspend'] == "0"){ $suspend = $lang->translate(505); }else{ $suspend = $lang->translate(504); }
						$html .= '<tr><td>'.$lang->translate(502).'</td><td>'.$userdata['username'].'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(503).'</td><td>'.$suspend.'</td></tr>';
						if($userdata['is_admin'] != "0"){
							$html .= '<tr><td>'.$lang->translate(506).'</td><td>'.$lang->translate(507).'</td></tr>';
						}
						if($userdata['subsuspend'] != "0"){
							$html .= '<tr><td>'.$lang->translate(506).'</td><td>'.$lang->translate(508).'</td></tr>';
						}
						if($userdata['id_master'] != "0"){
							if(check_user_subuser(get_value_session('from_db','id'),$userdata['id_master']) != FALSE || get_value_session('from_db','is_admin') == '1'){
								$userdata2 = get_userdata($userdata['id_master']);
								$html .= '<tr><td>'.$lang->translate(516).'</td><td><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=bekijken&id='.$userdata['id_master'].'">'.$userdata2['username'].'</a></td></tr>';
							}
						}else{
							$html .= '<tr><td>'.$lang->translate(516).'</td><td>'.$lang->translate(517).'</td></tr>';
						}
						$html .= '<tr><td>'.$lang->translate(509).'</td><td>'.$userdata['handelsnaam'].'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(510).'</td><td><a href="'.$userdata['home_page'].'" target="_blank">'.$userdata['home_page'].'</a></td></tr>';
						$html .= '<tr><td>'.$lang->translate(511).'</td><td>'.$userdata['email'].'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(512).'</td><td>'.$userdata['aantal_login'].'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(513).'</td><td>'.$userdata['aanmaak_datum'].'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(514).'</td><td>'.$userdata['aantal_wijzigingen'].'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(515).'</td><td>'.$userdata['laatste_wijziging'].'</td></tr>';
						$html .= '</table><input type="submit" value="'.$lang->translate(553).'" id="submit" name="submit"></form><br /><br />';
						if(check_user_right(get_value_session('from_db','id'),'klantaanpassen',get_value_session('from_db','is_admin')) != FALSE){
							$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=gegevens&id='.$userdata['id'].'">'.$lang->translate(215).'</a><br />';
						}
						if(check_user_right(get_value_session('from_db','id'),'klantrechten',get_value_session('from_db','is_admin')) != FALSE){
							$html .= '<a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=rechten&id='.$userdata['id'].'">'.$lang->translate(217).'</a><br /><br />';
						}
					}
				}else{
					$html .= '<br />'.$lang->translate(552).'<br />';
				}
			}else{
				$html .= '<br />'.$lang->translate(552).'<br />';
			}
		}elseif(get_value_get('type') == 'toevoegen'){
			$html = '<div class="paginatitel">'.$lang->translate(541).'</div>';
			if(check_user_right(get_value_session('from_db','id'),'klanttoevoegen',get_value_session('from_db','is_admin')) != FALSE){
				$userdata = get_userdata(get_value_session('from_db','id'));
				$succes = 0;
				$tempuser['email'] = '';
				$tempuser['username'] = '';
				$tempuser['admin'] = 0;
				$tempuser['home_page'] = $userdata['home_page'];
				$tempuser['handelsnaam'] = $userdata['handelsnaam'];
				if(get_value_post('submit')){
					$error = 0;
					if(get_value_post('email') === FALSE){ $error++; }elseif(is_valid_email(get_value_post('email')) == FALSE){ $error++; }else{ $tempuser['email'] = get_value_post('email'); }
					if(get_value_post('username') === FALSE){ $error++; }else{ $tempuser['username'] = get_value_post('username'); }
					if(get_value_post('password') === FALSE){ $error++; }
					if(get_value_post('handelsnaam') === FALSE){ $error++; }else{ $tempuser['handelsnaam'] = get_value_post('handelsnaam'); }
					if(get_value_post('home_page') === FALSE){ $error++; }else{ $tempuser['home_page'] = get_value_post('home_page'); }
					if(get_value_session('from_db','is_admin') == '1'){ if(get_value_post('admin') === FALSE){ $error++; }else{ $tempuser['admin'] = get_value_post('admin'); } }
					if($error !== 0){
						$html .= '<br /><div class="content"><p>'.$lang->translate(545).'</p></div><br />';
					}else{
						$query = $mysqli->query("SELECT * FROM `user` WHERE `username` LIKE '".$mysqli->real_escape_string(get_value_post('username'))."'");
						if(!isset($query) || empty($query) || $query->num_rows == "0"){
							if(get_value_session('from_db','is_admin') == '1'){ if(get_value_post('admin') == "1"){ $is_admin = 1; $master = 0; }else{ $is_admin = 0; $master = get_value_session('from_db','id'); } }else{ $is_admin = 0; $master = get_value_session('from_db','id'); }
							$sql = 'INSERT INTO `user` (`username`,`pass`,`is_admin`,`id_master`,`default_lang`,`handelsnaam`,`home_page`,`layout`,`email`) VALUES ("'.$mysqli->real_escape_string(get_value_post('username')).'", "'.$mysqli->real_escape_string(md5(get_value_post('password'))).'","'.$mysqli->real_escape_string($is_admin).'","'.$mysqli->real_escape_string($master).'","nl","'.$mysqli->real_escape_string(get_value_post('handelsnaam')).'","'.$mysqli->real_escape_string(get_value_post('home_page')).'","default","'.$mysqli->real_escape_string(get_value_post('email')).'")';
							$mysqli->query($sql);
							$id = $mysqli->insert_id;
							$userdata2 = get_userdata($id);
							$succes = 1;
							if($is_admin === 1){
							}else{
								$masterusers = get_masterusers($master);
								foreach($masterusers as $muser){
									$sql2 = 'INSERT INTO `user_subuser` (`userid`,`subuserid`,`type`) VALUES ("'.$mysqli->real_escape_string($muser['id']).'","'.$mysqli->real_escape_string($id).'","2")';
									$mysqli->query($sql2);
								}
							}
							$sql2 = 'INSERT INTO `user_subuser` (`userid`,`subuserid`,`type`) VALUES ("'.$mysqli->real_escape_string($master).'","'.$mysqli->real_escape_string($id).'","1")';
							$mysqli->query($sql2);
						}else{
							$html .= '<br /><div class="content"><p>'.$lang->translate(547).'</p></div>';
						}
					}
				}
				if($succes !== 1 || !isset($userdata2) || empty($userdata2) || $userdata2 == FALSE){
					$html .= '<form name="form2" method="post" action=""><DIV class="tables"><table>';
          $html .= '<tr><td colspan="2">'.$lang->translate(541).'</td></tr>';
          $html .= '<tr><td colspan="2"></td></tr>';
					$html .= '<tr><td>'.$lang->translate(502).'</td><td><input type="text" id="username" name="username" value="'.$tempuser['username'].'"></td></tr>';
					$html .= '<tr><td>'.$lang->translate(544).'</td><td><input type="text" id="password" name="password" value="'.PassGen().'"></td></tr>';
					if(get_value_session('from_db','is_admin') == '1'){
						if($tempuser['admin'] == "1"){ $html .= '<tr><td>'.$lang->translate(507).'</td><td><select name="admin"><option value="1" selected>'.$lang->translate(522).'</option><option value="2">'.$lang->translate(523).'</option></td></tr>'; }else{ $html .= '<tr><td>'.$lang->translate(507).'</td><td><select name="admin"><option value="1">'.$lang->translate(522).'</option><option value="2" selected>'.$lang->translate(523).'</option></td></tr>'; }
					}
					$html .= '<tr><td>'.$lang->translate(509).'</td><td><input type="text" id="handelsnaam" name="handelsnaam" value="'.$tempuser['handelsnaam'].'"></td></tr>';
					$html .= '<tr><td>'.$lang->translate(525).'</td><td><input type="text" id="home_page" name="home_page" value="'.$tempuser['home_page'].'"></td></tr>';
					$html .= '<tr><td>'.$lang->translate(511).'</td><td><input type="text" id="email" name="email" value="'.$tempuser['email'].'"></td></tr>';
					$html .= '<tr><td colspan="2"></td></tr>';
					$html .= '</table></div><br /><div class="content"><p><input type="submit" value="'.$lang->translate(543).'" id="submit" class="button" name="submit"></p></div></form><br><br>';
				}else{
					$html .= '<br /><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=rechten&id='.$userdata2['id'].'"><div class="content"><p>'.$lang->translate(546).'</p></div></a><br /><br />';
				}
			}else{
				$html .= '<br /><br />'.$lang->translate(542).'<br /><br />';
			}
		}elseif(get_value_get('type') == 'zoeken'){
			$html = '<div class="paginatitel">'.$lang->translate(531).'</div>';
			if(check_user_right(get_value_session('from_db','id'),'klantzoeken',get_value_session('from_db','is_admin')) != FALSE){
				if(get_value_post('submit')){
					$temp = get_search_results(get_value_session('from_db','id'),get_value_post('search'),'users',get_value_session('from_db','is_admin'));
					if($temp == FALSE){
						$html .= '<br /><p>'.$lang->translate(532).'</p><br /><br />';
					}else{
		$html .= '<DIV class="tablestop2"><table><tr><td align="right" colspan="10"><div style="width:665px;">';
					$html .= '<div style="float: right;"> <form name="form2" method="post" action="?page=klanten&type=zoeken">';
          $html .= '<input type="text" id="search" name="search" class="search">';
          $html .= '<input type="submit" value="'.$lang->translate(533).'" id="submit" name="submit" class="searchbutton"></form></div></div>';	
					$html .= '</td></tr>';	
					$html .= '<tr><td>'.$lang->translate(213).'';
					if(check_user_right(get_value_session('from_db','id'),'klanttoevoegen',get_value_session('from_db','is_admin')) != FALSE){
            $html .= '<div style="float: right;"><a href="?page=klanten&type=toevoegen"><img src="'.$template_dir.'/plus.png" border="0" valign="middle" title="'.$lang->translate(541).'"></a></div>';
					}					
					$html .= '</td><td>'.$lang->translate(509).'</td><td colspan="8">'.$lang->translate(630).'</td></tr>';
					foreach($temp as $item){
						$userdata = get_userdata($item['id']);
						if($userdata != FALSE){
							if(check_user_right(get_value_session('from_db','id'),'klantbekijken',get_value_session('from_db','is_admin')) != FALSE){
								$html .= '<tr><td><u><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=bekijken&id='.$userdata['id'].'">'.$userdata['username'].'</a></u>';
							}else{
								$html .= '<tr><td>'.$userdata['username'].'';				
							}
							$html .= '</td>';
              $html .= '<td>';
							$html .= ''.$userdata['handelsnaam'].'';
							$html .= '</td>';
              $html .= '<td  width="25px">';
           		if(check_user_right(get_value_session('from_db','id'),'klantbekijken',get_value_session('from_db','is_admin')) != FALSE){
								$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=bekijken&id='.$userdata['id'].'"><img src="'.$template_dir.'/info.png" border="0" title="'.$lang->translate(1210).'"></a></center>';								
							}else{
								$html .= '';								
							}	
							$html .= '</td>';	
							$html .= '<td width="25px">';
		          if(check_user_right(get_value_session('from_db','id'),'klantaanpassen',get_value_session('from_db','is_admin')) != FALSE){
                $html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=gegevens&id='.$userdata['id'].'"><img src="'.$template_dir.'/wijzigen.png" border="0" title="'.$lang->translate(1211).'"></a></center>';
						  }
              $html .= '</td>';	
              $html .= '<td width="25px">';
              if(check_user_right(get_value_session('from_db','id'),'klantrechten',get_value_session('from_db','is_admin')) != FALSE){
                $html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=rechten&id='.$userdata['id'].'"><img src="'.$template_dir.'/rechten.png" border="0" title="'.$lang->translate(1212).'"></a></center>';
              }
              $html .= '</td>';							
              $html .= '<td width="25px">';
							if($userdata['suspend'] == '0'){
							if(check_user_right(get_value_session('from_db','id'),'suspend',get_value_session('from_db','is_admin')) != FALSE){
									$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(1216).'\', \'?lang='.lang_get_value_defaultlang().'&page=klanten&type=suspend&id='.$userdata['id'].'\')"><img src="'.$template_dir.'/up.png" border="0" title="'.$lang->translate(1213).'"></center></a>';
								}
							}else{
								if(check_user_right(get_value_session('from_db','id'),'unsuspend',get_value_session('from_db','is_admin')) != FALSE){
									$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(1215).'\', \'?lang='.lang_get_value_defaultlang().'&page=klanten&type=unsuspend&id='.$userdata['id'].'\')"><img src="'.$template_dir.'/down.png" border="0" title="'.$lang->translate(1214).'"></center></a>';	
								}
							}
							$html .= '</td>';
							$html .= '<td width="25px">';
							if(check_user_right(get_value_session('from_db','id'),'wwreset',get_value_session('from_db','is_admin')) != FALSE){
								$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(1218).'\', \'?lang='.lang_get_value_defaultlang().'&page=klanten&type=wwreset&id='.$userdata['id'].'\')"><img src="'.$template_dir.'/wachtwoord.png" border="0" title="'.$lang->translate(1217).'"></center></a>';
							}
              $html .= '</td>';           
              $html .= '<td width="25px">';
							if(check_user_right(get_value_session('from_db','id'),'klantoverzetten',get_value_session('from_db','is_admin')) != FALSE){
								$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=overzetten&id='.$userdata['id'].'"><img src="'.$template_dir.'/overdragen.png" border="0" title="'.$lang->translate(1220).'"></center></a>';
							}
              $html .= '</td>';            
							$html .= '<td width="25px">';			
							if(get_subusers($userdata['id'],1) != FALSE){
								$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=overzicht&id='.$userdata['id'].'"><img src="'.$template_dir.'/Klanten.png" border="0" title="'.$lang->translate(11).' van '.$userdata['username'].'"></a></center>';
							}							
							$html .= '</td>';
							$html .= '<td  width="25px">';
              if(check_user_right(get_value_session('from_db','id'),'klantverwijderen',get_value_session('from_db','is_admin')) != FALSE){
							if($userdata['id'] != get_value_session('from_db','id')){
								$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(224).'\', \'?lang='.lang_get_value_defaultlang().'&page=klanten&type=verwijderen&id='.$userdata['id'].'\')"><img src="'.$template_dir.'/verwijderen.png" border="0" title="'.$lang->translate(1219).'"></a></center>';
								}
							}	
              $html .= '</td>';
              $html .= '</tr>';
						}				
					}
					$html .= '</table></DIV>';			
							}
				}
			}else{
				$html .= '<br /><br />'.$lang->translate(532).'<br /><br />';
			}
		}elseif(get_value_get('type') == 'gegevens'){
			$html = '<div class="paginatitel">'.$lang->translate(32).'</div>';
			if(check_user_right(get_value_session('from_db','id'),'klantaanpassen',get_value_session('from_db','is_admin')) != FALSE){
				if(check_user_subuser(get_value_session('from_db','id'),get_value_get('id')) != FALSE || get_value_session('from_db','is_admin') == '1'){
					if(get_value_post('submit')){
						$userdata = get_userdata(get_value_get('id'));
						$update = 0;
						if(get_value_post('suspend') != $userdata['suspend']){ $update++; }
						if(get_value_post('admin') != $userdata['is_admin']){ $update++; }
						if(get_value_post('handelsnaam') != $userdata['handelsnaam']){ $update++; }
						if(get_value_post('home_page') != $userdata['home_page']){ $update++; }
						if(get_value_post('email') != $userdata['email']){ gegevens_do_action_changemail(get_value_post('email'),$userdata['id'],$userdata['pass']); }
						if($update !== 0 && get_value_get('id') !== FALSE){
							$update = 0;
							$sql = '';
							if(get_value_post('suspend') == "1" && get_value_post('suspend') !== FALSE){ $suspend = '1'; }else{ $suspend = '0'; }
							if(get_value_post('suspend') != $userdata['suspend']){ if($update !== 0){ $sql .= ', ';} $update++; $sql .= '`suspend` = "'.$mysqli->real_escape_string($suspend).'"'; }
							if(get_value_post('admin') == "1" && get_value_post('admin') !== FALSE){ $is_admin = '1'; }else{ $is_admin = '0'; }
							if(get_value_post('admin') != $userdata['is_admin']){ if($update !== 0){ $sql .= ', ';} $update++; $sql .= '`is_admin` = "'.$mysqli->real_escape_string($is_admin).'"'; }
							if(get_value_post('handelsnaam') != $userdata['handelsnaam'] && get_value_post('handelsnaam') !== FALSE){ if($update !== 0){ $sql .= ', '; } $update++; $sql .= '`handelsnaam` = "'.$mysqli->real_escape_string(get_value_post('handelsnaam')).'"'; }
							if(get_value_post('home_page') != $userdata['home_page'] && get_value_post('home_page') !== FALSE){ if($update !== 0){ $sql .= ', '; } $update++; $sql .= '`home_page` = "'.$mysqli->real_escape_string(get_value_post('home_page')).'"'; }
							record_change_user(get_value_get('id'));
							$sql2 = 'UPDATE `user` SET '.$sql.' WHERE `id` LIKE "'.$mysqli->real_escape_string(get_value_get('id')).'" LIMIT 1';
							$mysqli->query($sql2) or die($mysqli->error);
						}
					}
					$html .= '<form name="form1" method="post" action=""><div class=tablestop1><table>';
					$userdata = get_userdata(get_value_get('id'));
					$html .= '<tr><td colspan="2"><div style="float: left;">'.$lang->translate(1107).'  '.$userdata['username'].'</div></td></tr>';					
					$html .= '<tr><td>'.$lang->translate(502).'</td><td>'.$userdata['username'].'</td></tr>';
					if(check_user_right(get_value_session('from_db','id'),'suspend',get_value_session('from_db','is_admin')) != FALSE)
					{ 
					$html .= '<tr><td>'.$lang->translate(503).'</td><td>';
					if(get_value_session('from_db','is_admin') == '1'){
					if($userdata['suspend'] == '1'){
					$html .= '<select name="suspend"><option value="1" selected>'.$lang->translate(522).'</option><option value="2">'.$lang->translate(523).'</option>';
					}else{
					$html .= '<select name="suspend"><option value="1">'.$lang->translate(522).'</option><option value="2" selected>'.$lang->translate(523).'</option>';
					  }
					}	
					$html .= '</td></tr>'; 
					}
					if(get_value_session('from_db','is_admin') == '1'){
						if($userdata['is_admin'] == "1"){
							$html .= '<tr><td>'.$lang->translate(507).'</td><td><select name="admin"><option value="1" selected>'.$lang->translate(522).'</option><option value="2">'.$lang->translate(523).'</option></td></tr>';
						}else{
							$html .= '<tr><td>'.$lang->translate(507).'</td><td><select name="admin"><option value="1">'.$lang->translate(522).'</option><option value="2" selected>'.$lang->translate(523).'</option></td></tr>';
						}
					}
					if($userdata['subsuspend'] != "0"){
						$html .= '<tr><td>'.$lang->translate(506).'</td><td>'.$lang->translate(508).'</td></tr>';
					}
					if($userdata['id_master'] != "0"){
						if(check_user_subuser(get_value_session('from_db','id'),$userdata['id_master']) != FALSE || get_value_session('from_db','is_admin') == '1'){
							$userdata2 = get_userdata($userdata['id_master']);
							$html .= '<tr><td>'.$lang->translate(516).'</td><td><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=gegevens&id='.$userdata['id_master'].'">'.$userdata2['username'].'</a></td></tr>';
						}
					}else{
						$html .= '<tr><td>'.$lang->translate(516).'</td><td>'.$lang->translate(517).'</td></tr>';
					}
					$html .= '<tr><td>'.$lang->translate(509).'</td><td><input type="text" id="handelsnaam" name="handelsnaam" value="'.$userdata['handelsnaam'].'"></td></tr>';
					$html .= '<tr><td>'.$lang->translate(525).'</td><td><input type="text" id="home_page" name="home_page" value="'.$userdata['home_page'].'"></td></tr>';
					$html .= '<tr><td>'.$lang->translate(511).'</td><td><input type="text" id="email" name="email" value="'.$userdata['email'].'"></td></tr>';
					$html .= '</table></div><p><input type="submit" value="'.$lang->translate(524).'" id="submit" name="submit" class="button"></p></form><br /><br />';
				}else{
					$html .= '<br /><br />'.$lang->translate(518).'<br /><br />';
				}
			}else{
				$html .= '<br /><br />'.$lang->translate(518).'<br /><br />';
			}
		}elseif(get_value_get('type') == 'bekijken'){
			$html = '<div class="paginatitel">'.$lang->translate(32).'</div>';
			
			

			
			
			
			
			
			if(check_user_right(get_value_session('from_db','id'),'klantbekijken',get_value_session('from_db','is_admin')) != FALSE){
				if(check_user_subuser(get_value_session('from_db','id'),get_value_get('id')) != FALSE || get_value_session('from_db','is_admin') == '1'){
					$html .= '<DIV class="tablestop1"><table>';
					$html .= '<tr><td colspan="2">'.$lang->translate(501).'</td></tr>';				
					$userdata = get_userdata(get_value_get('id'));
					if($userdata['suspend'] == "0"){ $suspend = $lang->translate(505); }else{ $suspend = $lang->translate(504); }
					
					
					$html .= '<tr><td>'.$lang->translate(502).'</td><td>'.$userdata['username'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(503).'</td><td>'.$suspend.'</td></tr>';
					if($userdata['is_admin'] != "0"){
						$html .= '<tr><td>'.$lang->translate(506).'</td><td>'.$lang->translate(507).'</td></tr>';
					}
					if($userdata['subsuspend'] != "0"){
						$html .= '<tr><td>'.$lang->translate(506).'</td><td>'.$lang->translate(508).'</td></tr>';
					}
					if($userdata['id_master'] != "0"){
						if(check_user_subuser(get_value_session('from_db','id'),$userdata['id_master']) != FALSE || get_value_session('from_db','is_admin') == '1'){
							$userdata2 = get_userdata($userdata['id_master']);
							$html .= '<tr><td>'.$lang->translate(516).'</td><td><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=bekijken&id='.$userdata['id_master'].'">'.$userdata2['username'].'</a></td></tr>';
						}
					}else{
						$html .= '<tr><td>'.$lang->translate(516).'</td><td>'.$lang->translate(517).'</td></tr>';
					}
					$html .= '<tr><td>'.$lang->translate(509).'</td><td>'.$userdata['handelsnaam'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(510).'</td><td><a href="'.$userdata['home_page'].'" target="_blank">'.$userdata['home_page'].'</a></td></tr>';
					//$html .= '<tr><td>'.$lang->translate(501).'</td><td>'.$userdata['layout'].'</td></tr>';
					//$html .= '<tr><td>'.$lang->translate(501).'</td><td>'.$userdata['default_lang'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(511).'</td><td>'.$userdata['email'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(512).'</td><td>'.$userdata['aantal_login'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(513).'</td><td>'.$userdata['aanmaak_datum'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(514).'</td><td>'.$userdata['aantal_wijzigingen'].'</td></tr>';
					$html .= '<tr><td>'.$lang->translate(515).'</td><td>'.$userdata['laatste_wijziging'].'</td></tr>';
					$html .= '</table></div><br />';
				}else{
					$html .= '<br /><br />'.$lang->translate(518).'<br /><br />';
				}
			}else{
				$html .= '<br /><br />'.$lang->translate(518).'<br /><br />';
			}
		}elseif(get_value_get('type') == 'rechten'){
			$html = '<div class="paginatitel">'.$lang->translate(32).'</div>';
			if(check_user_right(get_value_session('from_db','id'),'klantrechten',get_value_session('from_db','is_admin')) != FALSE){
				if(check_user_subuser(get_value_session('from_db','id'),get_value_get('id')) != FALSE && get_value_session('from_db','id') != get_value_get('id')|| get_value_session('from_db','is_admin') == '1'){
					if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
					if(get_value_post('submit') != FALSE){
						$query = $mysqli->query("SELECT * FROM `user_right` WHERE `userid` LIKE '0' AND `subuser` NOT LIKE '0'") or die($mysqli->error);
						if($query->num_rows == "0"){
						}else{
							$user_rights = 0;
							while($row = $query->fetch_array(MYSQLI_ASSOC)){
								$check_user_right = check_user_right(get_value_session('from_db','id'),$row['right'],get_value_session('from_db','is_admin'));
								$check_subuser_right = check_subuser_right(get_value_session('from_db','id'),$row['right'],get_value_session('from_db','is_admin'));
								if($check_user_right == 1 && $check_subuser_right == 1 || $check_user_right == 1 && $check_subuser_right == 2){
									$check_user_right2 = check_user_right(get_value_get('id'),$row['right']);
									$check_subuser_right2 = check_subuser_right(get_value_get('id'),$row['right']);
									if($check_user_right2 === FALSE){ $check_user_right2 = 0; }
									if($check_subuser_right2 === FALSE){ $check_subuser_right2 = 0; }
									if(get_value_post('group') != FALSE){
										$group = get_value_post('group');
										if(isset($group[$row['user']]) && !empty($group[$row['user']]) && $group[$row['user']] != "0"){
											if($group[$row['user']] == 1){
												$user_right[$row['user']][$row['right']]['user'] = 0;
												$user_right[$row['user']][$row['right']]['subuser'] = 0;
											}elseif($group[$row['user']] == 2){
												$user_right[$row['user']][$row['right']]['user'] = 1;
												$user_right[$row['user']][$row['right']]['subuser'] = 0;
											}elseif($group[$row['user']] == 3){
												$user_right[$row['user']][$row['right']]['user'] = 1;
												$user_right[$row['user']][$row['right']]['subuser'] = 1;
											}elseif($group[$row['user']] == 4){
												$user_right[$row['user']][$row['right']]['user'] = 1;
												$user_right[$row['user']][$row['right']]['subuser'] = 2;
											}
											$user_right[$row['user']][$row['right']]['right'] = $row['right'];
											$user_right[$row['user']][$row['right']]['id'] = $row['id'];
											$user_right[$row['user']][$row['right']]['group'] = $row['user'];
											$user_rights++;
										}else{
											$rights = get_value_post('right');
											if(isset($rights[$row['id']]) && !empty($rights[$row['id']])){
												if($rights[$row['id']] == 1){
													$user_right[$row['user']][$row['right']]['user'] = 0;
													$user_right[$row['user']][$row['right']]['subuser'] = 0;
												}elseif($rights[$row['id']] == 2){
													$user_right[$row['user']][$row['right']]['user'] = 1;
													$user_right[$row['user']][$row['right']]['subuser'] = 0;
												}elseif($rights[$row['id']] == 3){
													$user_right[$row['user']][$row['right']]['user'] = 1;
													$user_right[$row['user']][$row['right']]['subuser'] = 1;
												}elseif($rights[$row['id']] == 4){
													$user_right[$row['user']][$row['right']]['user'] = 1;
													$user_right[$row['user']][$row['right']]['subuser'] = 2;
												}
												$user_right[$row['user']][$row['right']]['lang'] = $row['subuser'];
												$user_right[$row['user']][$row['right']]['right'] = $row['right'];
												$user_right[$row['user']][$row['right']]['id'] = $row['id'];
												$user_right[$row['user']][$row['right']]['group'] = $row['user'];
												$user_rights++;
											}
										}
									}else{
										$rights = get_value_post('right');
										if(isset($rights[$row['id']]) && !empty($rights[$row['id']])){
											if($rights[$row['id']] == 1){
												$user_right[$row['user']][$row['right']]['user'] = 0;
												$user_right[$row['user']][$row['right']]['subuser'] = 0;
											}elseif($rights[$row['id']] == 2){
												$user_right[$row['user']][$row['right']]['user'] = 1;
												$user_right[$row['user']][$row['right']]['subuser'] = 0;
											}elseif($rights[$row['id']] == 3){
												$user_right[$row['user']][$row['right']]['user'] = 1;
												$user_right[$row['user']][$row['right']]['subuser'] = 1;
											}elseif($rights[$row['id']] == 4){
												$user_right[$row['user']][$row['right']]['user'] = 1;
												$user_right[$row['user']][$row['right']]['subuser'] = 2;
											}
											$user_right[$row['user']][$row['right']]['lang'] = $row['subuser'];
											$user_right[$row['user']][$row['right']]['right'] = $row['right'];
											$user_right[$row['user']][$row['right']]['id'] = $row['id'];
											$user_right[$row['user']][$row['right']]['group'] = $row['user'];
											$user_rights++;
										}
									}
								}
							}
							if($user_rights === 0){
							}else{
								foreach($user_right as $type => $item){
									$query = $mysqli->query("SELECT * FROM `user_right` WHERE `userid` LIKE '0' AND `user` LIKE '".$mysqli->real_escape_string($type)."' AND `subuser` LIKE '0' LIMIT 1");
									if(!isset($query) || empty($query) || $query->num_rows != "1"){
									}else{
										while($row = $query->fetch_array(MYSQLI_ASSOC)){
											$group_rights[$type]['right'] = $row['right'];
											$group_rights[$type]['user'] = 0;
										}
									}
									foreach($item as $right2){
										$mysqli->query('INSERT INTO `user_right` (`userid`,`right`,`user`,`subuser`) VALUES ("'.$mysqli->real_escape_string(get_value_get('id')).'","'.$mysqli->real_escape_string($right2['right']).'","'.$mysqli->real_escape_string($right2['user']).'","'.$mysqli->real_escape_string($right2['subuser']).'") ON DUPLICATE KEY UPDATE `user` = "'.$mysqli->real_escape_string($right2['user']).'",`subuser` = "'.$mysqli->real_escape_string($right2['subuser']).'"') or die($mysqli->error);
										if($right2['user'] != "0" || $right2['subuser'] != "0"){
											$group_rights[$right2['group']]['user'] = 1;
										}
									}
								}
								foreach($group_rights as $groups){
									$mysqli->query('INSERT INTO `user_right` (`userid`,`right`,`user`,`subuser`) VALUES ("'.$mysqli->real_escape_string(get_value_get('id')).'","'.$mysqli->real_escape_string($groups['right']).'","'.$mysqli->real_escape_string($groups['user']).'","'.$mysqli->real_escape_string($groups['user']).'") ON DUPLICATE KEY UPDATE `user` = "'.$mysqli->real_escape_string($groups['user']).'",`subuser` = "'.$mysqli->real_escape_string($groups['user']).'"') or die($mysqli->error);
								}
							}
						}
					}
					unset($user_right);
					$query = $mysqli->query("SELECT * FROM `user_right` WHERE `userid` LIKE '0' AND `subuser` NOT LIKE '0'") or die($mysqli->error);
					if($query->num_rows == "0"){
						$html .= '<br /><br />'.$lang->translate(262).'<br /><br />';
					}else{
						$user_rights = 0;
						while($row = $query->fetch_array(MYSQLI_ASSOC)){
							$check_user_right = check_user_right(get_value_session('from_db','id'),$row['right'],get_value_session('from_db','is_admin'));
							$check_subuser_right = check_subuser_right(get_value_session('from_db','id'),$row['right'],get_value_session('from_db','is_admin'));
							if($check_user_right == 1 && $check_subuser_right == 1 || $check_user_right == 1 && $check_subuser_right == 2){
								$check_user_right2 = check_user_right(get_value_get('id'),$row['right']);
								$check_subuser_right2 = check_subuser_right(get_value_get('id'),$row['right']);
								if($check_user_right2 === FALSE){ $check_user_right2 = 0; }
								if($check_subuser_right2 === FALSE){ $check_subuser_right2 = 0; }
								$user_right[$row['user']][$row['right']]['deel'] = $check_subuser_right;
								$user_right[$row['user']][$row['right']]['user'] = $check_user_right2;
								$user_right[$row['user']][$row['right']]['subuser'] = $check_subuser_right2;
								$user_right[$row['user']][$row['right']]['lang'] = $row['subuser'];
								$user_right[$row['user']][$row['right']]['right'] = $row['right'];
								$user_right[$row['user']][$row['right']]['id'] = $row['id'];
								$user_rights++;
							}
						}
						if($user_rights === 0){
							var_dump($user_rights);
							$html .= '<p>'.$lang->translate(262).'</p><br /><br />';
						}else{
							$html .= '<form name="form1" method="post" action=""><div class="tablestop2"><table>';
							$userdata = get_userdata(get_value_get('id'));
							$html .= '<tr><td colspan="2"><div style="float: left;">'.$lang->translate(1106).' <b>'.$userdata['username'].'</b></div></td></tr>';
							$html .= '<tr><td>'.$lang->translate(267).'</td><td>'.$lang->translate(268).'</td></tr>';
							foreach($user_right as $type => $item){
								if($type == '1'){ $lang_type = 271; }
								if($type == '2'){ $lang_type = 272; }
								if($type == '3'){ $lang_type = 273; }
								if($type == '4'){ $lang_type = 274; }
								if($type == '5'){ $lang_type = 275; }
								if($type == '6'){ $lang_type = 276; }
								if($type == '7'){ $lang_type = 277; }
								$html .= '<tr><td><b>'.$lang->translate($lang_type).'</b></td><td><select name="group['.$type.']"><option value="0" selected></option>
								<option value="1">'.$lang->translate(263).'</option>
								<option value="2">'.$lang->translate(264).'</option>
								<option value="3">'.$lang->translate(265).'</option>
								<option value="4">'.$lang->translate(266).'</option></select></td></tr>';
								foreach($item as $right2){
									$html .= '<tr><td>'.$lang->translate($right2['lang']).'</td><td><select name="right['.$right2['id'].']">';
									if($right2['user'] == 0){ $html .= '<option value="1" selected>'.$lang->translate(263).'</option>'; }else{ $html .= '<option value="1">'.$lang->translate(263).'</option>'; }
									if($right2['subuser'] == 0 && $right2['user'] == 1){ $html .= '<option value="2" selected>'.$lang->translate(264).'</option>'; }else{ $html .= '<option value="2">'.$lang->translate(264).'</option>'; }
									if($right2['subuser'] == 1 && $right2['user'] == 1){ $html .= '<option value="3" selected>'.$lang->translate(265).'</option>'; }else{ $html .= '<option value="3">'.$lang->translate(265).'</option>'; }
									if($right2['deel'] == 2){ if($right2['subuser'] == 2 && $right2['user'] == 1){ $html .= '<option value="4" selected>'.$lang->translate(266).'</option>'; }else{ $html .= '<option value="4">'.$lang->translate(266).'</option>'; }}
									$html .= '</select></td></tr>';
								}
							}
							$html .= '</table></div><p><input type="submit" value="'.$lang->translate(281).'" id="submit" name="submit" class="button"></p></form><br /><br />';
							$html .= '<br />';
						}
					}
				}else{
					$html .= '<br /><br />'.$lang->translate(262).'<br /><br />';
				}
			}else{
				$html .= '<br /><br />'.$lang->translate(262).'<br /><br />';
			}
		}elseif(get_value_get('type') == 'unsuspend'){
			$html = '<div class="paginatitel">'.$lang->translate(32).'</div>';
			if(check_user_right(get_value_session('from_db','id'),'unsuspend',get_value_session('from_db','is_admin')) != FALSE){
				if(get_value_session('from_db','id') == get_value_get('id')){
					$html .= '<br /><br /><div class="content"><p>'.$lang->translate(253).'</p></div><br /><br />';
				}elseif(check_user_subuser(get_value_session('from_db','id'),get_value_get('id')) != FALSE || get_value_session('from_db','is_admin') == '1'){
					if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
					record_change_user(get_value_get('id'));
					$query = $mysqli->query("UPDATE `user` SET `suspend` = '0' WHERE `id` LIKE '".$mysqli->real_escape_string(get_value_get('id'))."' LIMIT 1") or die($mysqli->error);
					if($mysqli->affected_rows == "0" || $mysqli->affected_rows == "-1"){
						$html .= '<br /><br /><div class="content"><p>'.$lang->translate(254).'</p></div><br /><br />';
					}else{
						$html .= '<br /><br /><div class="content"><p>'.$lang->translate(252).'</p></div><br /><br />';
					}
				}else{
					$html .= '<br /><br /><div class="content"><p>'.$lang->translate(253).'</p></div><br /><br />';
				}
			}else{
				$html .= '<br /><br /><div class="content"><p>'.$lang->translate(253).'</p></div><br /><br />';
			}
		}elseif(get_value_get('type') == 'suspend'){
			$html = '<div class="paginatitel">'.$lang->translate(32).'</div>';
			if(check_user_right(get_value_session('from_db','id'),'suspend',get_value_session('from_db','is_admin')) != FALSE){
				if(get_value_session('from_db','id') == get_value_get('id')){
					$html .= '<br /><br />'.$lang->translate(243).'<br /><br />';
				}elseif(check_user_subuser(get_value_session('from_db','id'),get_value_get('id')) != FALSE || get_value_session('from_db','is_admin') == '1'){
					if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
					record_change_user(get_value_get('id'));
					$query = $mysqli->query("UPDATE `user` SET `suspend` = '1' WHERE `id` LIKE '".$mysqli->real_escape_string(get_value_get('id'))."' LIMIT 1") or die($mysqli->error);
					if($mysqli->affected_rows == "0" || $mysqli->affected_rows == "-1"){
						$html .= '<br /><br /><div class="content"><p>'.$lang->translate(244).'</p></div><br /><br />';
					}else{
						$html .= '<br /><br /><div class="content"><p>'.$lang->translate(242).'</p></div><br /><br />';
					}
				}else{
					$html .= '<br /><br /><div class="content"><p>'.$lang->translate(243).'</p></div><br /><br />';
				}
			}else{
				$html .= '<br /><br /><div class="content"><p>'.$lang->translate(243).'</p></div><br /><br />';
			}
		}elseif(get_value_get('type') == 'wwreset'){
			$html = '<div class="paginatitel">'.$lang->translate(231).'</div>';
			if(check_user_right(get_value_session('from_db','id'),'wwreset',get_value_session('from_db','is_admin')) != FALSE){
				if(get_value_session('from_db','id') == get_value_get('id')){
					$html .= '<br><div class="content"><p>'.$lang->translate(233).'</p></div>';
				}elseif(check_user_subuser(get_value_session('from_db','id'),get_value_get('id')) != FALSE || get_value_session('from_db','is_admin') == '1'){
					if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
					$new = PassGen();
					record_change_user(get_value_get('id'));
					$query = $mysqli->query("UPDATE `user` SET `pass` = '".$mysqli->real_escape_string(md5($new))."' WHERE `id` LIKE '".$mysqli->real_escape_string(get_value_get('id'))."' LIMIT 1") or die($mysqli->error);
					if($mysqli->affected_rows == "0" || $mysqli->affected_rows == "-1"){
						$html .= '<br /><br />'.$lang->translate(233).'<br /><br />';
					}else{
						$subject = $lang->translate(234);
						$body = $lang->translate(238)."<br /><br />".$new."<br /><br />".$lang->translate(239);
						$mailfrom = get_value_session('from_db','email');
						$userdata = get_userdata(get_value_get('id'));
						$namefrom = $userdata['handelsnaam'];
						$email = $userdata['email'];
						send_email($email,$subject,$body,$mailfrom,$namefrom);
						$html .= '<br /><br />'.$lang->translate(232).'<br /><br />'.$lang->translate(237).$new.'<br /><br />';
					}
				}else{
					$html .= '<br /><br />'.$lang->translate(233).'<br /><br />';
				}
			}else{
				$html .= '<br /><br />'.$lang->translate(233).'<br /><br />';
			}
		}elseif(get_value_get('type') == 'overzicht'){
		
      $html .= '<div class="paginatitel">'.$lang->translate(32).'</div>';	
			
			if(check_user_right(get_value_session('from_db','id'),'klantoverzicht',get_value_session('from_db','is_admin')) != FALSE){
				if(get_value_get('id') != FALSE){
					if(check_user_subuser(get_value_session('from_db','id'),get_value_get('id')) != FALSE || get_value_session('from_db','is_admin') == '1'){
						$temp = get_subusers(get_value_get('id'));
					}else{
						$temp = FALSE;
					}
				}else{
					if(get_value_session('from_db','is_admin') == '1'){
						$temp = get_subusers('0');
					}else{
						$temp = get_subusers(get_value_session('from_db','id'));
					}
				}
				if($temp == FALSE){
					$html .= '<br /><br /><div class="content"><p>'.$lang->translate(212).'</p></div><br /><br />';
				}else{							
          $html .= '<DIV class="tablestop2"><table><tr><td align="right" colspan="10"><div style="width:665px;">';
					$html .= '<div style="float: right;"> <form name="form2" method="post" action="?page=klanten&type=zoeken">';
          $html .= '<input type="text" id="search" name="search" class="search">';
          $html .= '<input type="submit" value="'.$lang->translate(533).'" id="submit" name="submit" class="searchbutton"></form></div></div>';	
					$html .= '</td></tr>';	
					$html .= '<tr><td>'.$lang->translate(213).'';
					if(check_user_right(get_value_session('from_db','id'),'klanttoevoegen',get_value_session('from_db','is_admin')) != FALSE){
            $html .= '<div style="float: right;"><a href="?page=klanten&type=toevoegen"><img src="'.$template_dir.'/plus.png" border="0" valign="middle" title="'.$lang->translate(541).'"></a></div>';
					}					
					$html .= '</td><td>'.$lang->translate(509).'</td><td colspan="8">'.$lang->translate(630).'</td></tr>';
					foreach($temp as $item){
						$userdata = get_userdata($item['id']);
						if($userdata != FALSE){
							if(check_user_right(get_value_session('from_db','id'),'klantbekijken',get_value_session('from_db','is_admin')) != FALSE){
								$html .= '<tr><td><u><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=bekijken&id='.$userdata['id'].'">'.$userdata['username'].'</a></u>';
							}else{
								$html .= '<tr><td>'.$userdata['username'].'';				
							}
							$html .= '</td>';
              $html .= '<td>';
							$html .= ''.$userdata['handelsnaam'].'';
							$html .= '</td>';
              $html .= '<td  width="25px">';
           		if(check_user_right(get_value_session('from_db','id'),'klantbekijken',get_value_session('from_db','is_admin')) != FALSE){
								$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=bekijken&id='.$userdata['id'].'"><img src="'.$template_dir.'/info.png" border="0" title="'.$lang->translate(1210).'"></a></center>';								
							}else{
								$html .= '';								
							}	
							$html .= '</td>';	
							$html .= '<td width="25px">';
		          if(check_user_right(get_value_session('from_db','id'),'klantaanpassen',get_value_session('from_db','is_admin')) != FALSE){
                $html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=gegevens&id='.$userdata['id'].'"><img src="'.$template_dir.'/wijzigen.png" border="0" title="'.$lang->translate(1211).'"></a></center>';
						  }
              $html .= '</td>';	
              $html .= '<td width="25px">';
              if(check_user_right(get_value_session('from_db','id'),'klantrechten',get_value_session('from_db','is_admin')) != FALSE){
                $html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=rechten&id='.$userdata['id'].'"><img src="'.$template_dir.'/rechten.png" border="0" title="'.$lang->translate(1212).'"></a></center>';
              }
              $html .= '</td>';							
              $html .= '<td width="25px">';
							if($userdata['suspend'] == '0'){
							if(check_user_right(get_value_session('from_db','id'),'suspend',get_value_session('from_db','is_admin')) != FALSE){
									$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(1216).'\', \'?lang='.lang_get_value_defaultlang().'&page=klanten&type=suspend&id='.$userdata['id'].'\')"><img src="'.$template_dir.'/up.png" border="0" title="'.$lang->translate(1213).'"></center></a>';
								}
							}else{
								if(check_user_right(get_value_session('from_db','id'),'unsuspend',get_value_session('from_db','is_admin')) != FALSE){
									$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(1215).'\', \'?lang='.lang_get_value_defaultlang().'&page=klanten&type=unsuspend&id='.$userdata['id'].'\')"><img src="'.$template_dir.'/down.png" border="0" title="'.$lang->translate(1214).'"></center></a>';	
								}
							}
							$html .= '</td>';
							$html .= '<td width="25px">';
							if(check_user_right(get_value_session('from_db','id'),'wwreset',get_value_session('from_db','is_admin')) != FALSE){
								$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(1218).'\', \'?lang='.lang_get_value_defaultlang().'&page=klanten&type=wwreset&id='.$userdata['id'].'\')"><img src="'.$template_dir.'/wachtwoord.png" border="0" title="'.$lang->translate(1217).'"></center></a>';
							}
              $html .= '</td>';  
              $html .= '<td width="25px">';
							if(check_user_right(get_value_session('from_db','id'),'klantoverzetten',get_value_session('from_db','is_admin')) != FALSE){
								$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=overzetten&id='.$userdata['id'].'"><img src="'.$template_dir.'/overdragen.png" border="0" title="'.$lang->translate(1220).'"></center></a>';
							}
              $html .= '</td>';  
              $html .= '<td width="25px">';			
 
              
              if(get_subusers($userdata['id'],1) != FALSE){	
							$html .= '&nbsp;<a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=overzicht&id='.$userdata['id'].'"><img src="'.$template_dir.'/Klanten.png" border="0" title="'.$lang->translate(11).' van '.$userdata['username'].'"></a>';
						}
							$html .= '</td>';
              $html .= '<td  width="25px">';
              if(check_user_right(get_value_session('from_db','id'),'klantverwijderen',get_value_session('from_db','is_admin')) != FALSE){
							if($userdata['id'] != get_value_session('from_db','id')){
								$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(224).'\', \'?lang='.lang_get_value_defaultlang().'&page=klanten&type=verwijderen&id='.$userdata['id'].'\')"><img src="'.$template_dir.'/verwijderen.png" border="0" title="'.$lang->translate(1219).'"></a></center>';
								}
							}	
              $html .= '</td>';
              $html .= '</tr>';
						}				
					}
					$html .= '</table></DIV>';
     
				}
			}else{
				$html .= '<br /><br /><div class="content"><p>'.$lang->translate(212).'</p></div><br /><br />';			
			}
		}else{
			$html = '<b>'.$lang->translate(202).'</b>';
			$html .= '<br /><br />'.$lang->translate(203).'<br /><br />';
		}
	}

?>