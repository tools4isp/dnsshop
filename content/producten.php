<?php
	// Created by Mark Scholten
	// This file is called producten.php
	fix_is_included($index);
	
	
	
	// Types:
	// - zoeken (niet af)... > verplaatst 
	
	if(check_user_right(get_value_session('from_db','id'),'pakketten',get_value_session('from_db','is_admin')) !== FALSE){
		$html = '<div class="paginatitel">'.$lang->translate(625).'</div>';
		if(get_value_get('type') == 'verwijderen' && check_user_right(get_value_session('from_db','id'),'pakketverwijderen',get_value_session('from_db','is_admin')) !== FALSE){
			$pakket = pakketten_get_value_details(get_value_session('from_db','id'),get_value_get('id'),get_value_session('from_db','is_admin'));
			if($pakket !== FALSE){
				if(check_user_subuser(get_value_session('from_db','id'),$pakket['user_id'],$type = 3) !== FALSE || $pakket['user_id'] == get_value_session('from_db','id') || get_value_session('from_db','is_admin') == '1'){
					if(pakketten_do_action_verwijder($pakket['user_id'],get_value_get('id')) == FALSE){
						$html .= '<br /><p><b>'.$lang->translate(676).'</b></p><br /><br />';
					}else{
						$html .= '<br /><p><b>'.$lang->translate(677).'</b></p><br /><br />';
					}
				}
			}
		}elseif(get_value_get('type') == 'bewerken' && check_user_right(get_value_session('from_db','id'),'pakketbewerken',get_value_session('from_db','is_admin')) !== FALSE){
			$pakket = pakketten_get_value_details(get_value_session('from_db','id'),get_value_get('id'),get_value_session('from_db','is_admin'));
			if($pakket !== FALSE){
				if(check_user_subuser(get_value_session('from_db','id'),$pakket['user_id'],$type = 3) !== FALSE || $pakket['user_id'] == get_value_session('from_db','id') || get_value_session('from_db','is_admin') == '1'){
					if(get_value_get('p') == "dns"){
						if(get_value_post('submit')){
							if(!isset($mysqli) || empty($mysqli)){ create_db_connection('mysqli','central'); }
							$domlimiet = get_value_post('domlimit');
							$temlimiet = get_value_post('temlimit');
							if($domlimiet !== FALSE && $temlimiet !== FALSE){
								$sql = "UPDATE `pakketten_dns` SET `max_domain` = '".$mysqli->real_escape_string($domlimiet)."', `max_templates` = '".$mysqli->real_escape_string($temlimiet)."' WHERE `id` LIKE '".$mysqli->real_escape_string($pakket['pakket_id'])."'";
								$query = $mysqli->query($sql);
								$html .= '<br /><p><b>'.$lang->translate(673).'</b></p><br /><br />';
							}else{
								$html .= '<br /><p><b>'.$lang->translate(674).'</b></p><br /><br />';
							}
						}else{
							$domain_pakket_limit = dns_get_value_pakket($pakket['pakket_id'],'domain');
							$template_pakket_limit = dns_get_value_pakket($pakket['pakket_id'],'template');
							$domain_pakket_used = dns_get_value_current_usage($pakket['pakket_id'],'domain');
							$template_pakket_used = dns_get_value_current_usage($pakket['pakket_id'],'template');
							$domain_total_limit = pakketten_get_value_size_dns($pakket['user_id'],'domain');
							$template_total_limit = pakketten_get_value_size_dns($pakket['user_id'],'template');
							$domain_total_used = pakketten_get_value_used_dns($pakket['user_id'],'domain');
							$template_total_used = pakketten_get_value_used_dns($pakket['user_id'],'template');
							$html .= '<br />';
							$userdata = get_userdata($pakket['user_id']);
					$html .= '<p>'.$lang->translate(670).'<b>'.$userdata['username'].'</b><br />';
						$html .= ''.$lang->translate(671).'<br /><br /></p>';
						$html .= '<form name="form1" method="post" action="">';
						$html .= '<div class="tablestop1"><table>';
							
							$html .= '<tr><td>'.$lang->translate(1200).'</td><td>'.$lang->translate(664).'</td><td>'.$lang->translate(665).'</td></tr>';
							$html .= '<tr><td>'.$lang->translate(666).'</td><td>'.$domain_pakket_used.'</td><td><input type="text" name="domlimit" id="domlimit" value="'.$domain_pakket_limit.'"></td></tr>';
							$html .= '<tr><td>'.$lang->translate(667).'</td><td>'.$template_pakket_used.'</td><td><input type="text" name="temlimit" id="temlimit" value="'.$template_pakket_limit.'"></td></tr>';
							$html .= '<tr><td>'.$lang->translate(668).'</td><td>'.$domain_total_used.'</td><td>'.$domain_total_limit.'</td></tr>';
							$html .= '<tr><td>'.$lang->translate(669).'</td><td>'.$template_total_used.'</td><td>'.$template_total_limit.'</td></tr>';
							$html .= '</table></div><div class="content"><p><input type="submit" value="'.$lang->translate(672).'" id="submit" name="submit" class="button"></p></div></form><br /><br />';
						}
					}
				}else{
					$html .= '<br /><br />'.$lang->translate(663).'<br /><br />';
				}
			}else{
				$html .= '<br /><br />'.$lang->translate(663).'<br /><br />';
			}
		}elseif(get_value_get('type') == 'bekijken' && check_user_right(get_value_session('from_db','id'),'pakketbekijken',get_value_session('from_db','is_admin')) !== FALSE){
			$pakket = pakketten_get_value_details(get_value_session('from_db','id'),get_value_get('id'),get_value_session('from_db','is_admin'));
			if($pakket !== FALSE){
				if(get_value_get('p') == "dns"){
					if(check_user_subuser(get_value_session('from_db','id'),$pakket['user_id'],$type = 3) !== FALSE || $pakket['user_id'] == get_value_session('from_db','id') || get_value_session('from_db','is_admin') == '1'){
						$domain_pakket_limit = dns_get_value_pakket($pakket['pakket_id'],'domain');
						$template_pakket_limit = dns_get_value_pakket($pakket['pakket_id'],'template');
						$domain_pakket_used = dns_get_value_current_usage($pakket['pakket_id'],'domain');
						$template_pakket_used = dns_get_value_current_usage($pakket['pakket_id'],'template');
						$domain_total_limit = pakketten_get_value_size_dns($pakket['user_id'],'domain');
						$template_total_limit = pakketten_get_value_size_dns($pakket['user_id'],'template');
						$domain_total_used = pakketten_get_value_used_dns($pakket['user_id'],'domain');
						$template_total_used = pakketten_get_value_used_dns($pakket['user_id'],'template');
						$html .= '<br />';
						$userdata = get_userdata($pakket['user_id']);
						$html .= '<p>'.$lang->translate(670).'<b>'.$userdata['username'].'</b><br />';
						$html .= ''.$lang->translate(671).'<br /><br /></p>';
						$html .= '<div class="tablestop1"><table>';
						$html .= '<tr><td>'.$lang->translate(1200).'</td><td>'.$lang->translate(664).'</td><td>'.$lang->translate(665).'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(666).'</td><td>'.$domain_pakket_used.'</td><td>'.$domain_pakket_limit.'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(667).'</td><td>'.$template_pakket_used.'</td><td>'.$template_pakket_limit.'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(668).'</td><td>'.$domain_total_used.'</td><td>'.$domain_total_limit.'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(669).'</td><td>'.$template_total_used.'</td><td>'.$template_total_limit.'</td></tr>';
						$html .= '</table></div>';
					}
				}elseif(get_value_get('p') == "stream"){
					if(check_user_subuser(get_value_session('from_db','id'),$pakket['user_id'],$type = 3) !== FALSE || $pakket['user_id'] == get_value_session('from_db','id') || get_value_session('from_db','is_admin') == '1'){
						$listeners_pakket_limit = stream_get_value_pakket($pakket['pakket_id'],'listeners');
						$listeners_pakket_used = stream_get_value_current_usage($pakket['pakket_id'],'listeners');
						$listeners_total_limit = pakketten_get_value_size_stream($pakket['user_id'],'listeners');
						$listeners_total_used = pakketten_get_value_used_stream($pakket['user_id'],'listeners');
						$html .= '<br /><br />';
						$userdata = get_userdata($pakket['user_id']);
						$html .= ''.$lang->translate(670).$userdata['username'].'<br />';
						$html .= ''.$lang->translate(682).'<br /><br />';
						$html .= '<table border="1">';
						$html .= '<tr><td></td><td>'.$lang->translate(664).'</td><td>'.$lang->translate(665).'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(680).'</td><td>'.$listeners_pakket_used.'</td><td>'.$listeners_pakket_limit.'</td></tr>';
						$html .= '<tr><td>'.$lang->translate(681).'</td><td>'.$listeners_total_used.'</td><td>'.$listeners_total_limit.'</td></tr>';
						$html .= '</table>';
					}
				}else{
					$html .= '<br /><br />'.$lang->translate(663).'<br /><br />';
				}
			}else{
				$html .= '<br /><br />'.$lang->translate(663).'<br /><br />';
			}
		}elseif(get_value_get('type') == 'zoeken' && check_user_right(get_value_session('from_db','id'),'pakketzoeken',get_value_session('from_db','is_admin')) !== FALSE){
			if(get_value_post('submit') !== FALSE){
				if(get_value_post('category') == FALSE){
					$html .= '<br /><br />'.$lang->translate(656).'<br /><br />';
				}elseif(get_value_post('search') == FALSE){
					$html .= '<br /><br />'.$lang->translate(657).'<br /><br />';
				}elseif(get_value_post('category') == "1"){
					if(get_value_session('from_db','is_admin') == '1'){
						$pakketten = pakketten_get_value_overview('%','dns',get_value_session('from_db','is_admin'));
					}else{
						$pakketten = pakketten_get_value_overview(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin'));
					}
					if($pakketten !== FALSE){
						foreach($pakketten as $pakket){
							//$html .= '<br /><br />';
							if(check_user_right(get_value_session('from_db','id'),'dnsdomzoeken',get_value_session('from_db','is_admin')) !== FALSE){
								$html .= dns_create_html_searchresults(dns_do_action_search($pakket['pakket_id'],get_value_post('search'),'domain',get_value_session('from_db','is_admin')));
							}
							if(check_user_right(get_value_session('from_db','id'),'dnstemzoeken',get_value_session('from_db','is_admin')) !== FALSE){
								$html .= dns_create_html_searchresults(dns_do_action_search($pakket['pakket_id'],get_value_post('search'),'template',get_value_session('from_db','is_admin')));
							}
							if(check_user_right(get_value_session('from_db','id'),'dnssuperzoeken',get_value_session('from_db','is_admin')) !== FALSE){
								$html .= dns_create_html_searchresults(dns_do_action_search($pakket['pakket_id'],get_value_post('search'),'super',get_value_session('from_db','is_admin')));
							}
						}
						if(check_user_right(get_value_session('from_db','id'),'dnsdomzoeken',get_value_session('from_db','is_admin')) === FALSE && check_user_right(get_value_session('from_db','id'),'dnstemzoeken',get_value_session('from_db','is_admin')) === FALSE){
							$html .= '<br /><p>'.$lang->translate(658).'</p><br /><br />';
						}
					}else{
						$html .= '<br /><p>'.$lang->translate(658).'</p><br /><br />';
					}
				}elseif(get_value_post('category') == "2"){
					if(get_value_session('from_db','is_admin') == '1'){
						$pakketten = pakketten_get_value_overview('%','stream',get_value_session('from_db','is_admin'));
					}else{
						$pakketten = pakketten_get_value_overview(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin'));
					}
					if($pakketten !== FALSE){
						foreach($pakketten as $pakket){
							$html .= '<br /><br />';
							if(check_user_right(get_value_session('from_db','id'),'streamzoeken',get_value_session('from_db','is_admin')) !== FALSE){
								$html .= stream_do_action_search($pakket['pakket_id'],get_value_post('search'),get_value_session('from_db','is_admin'));
							}
						}
						if(check_user_right(get_value_session('from_db','id'),'dnsdomzoeken',get_value_session('from_db','is_admin')) === FALSE && check_user_right(get_value_session('from_db','id'),'dnstemzoeken',get_value_session('from_db','is_admin')) === FALSE){
							$html .= '<br /><p>'.$lang->translate(658).'</p><br />';
						}
					}else{
						$html .= '<br /><p>'.$lang->translate(658).'</p><br />';
					}
				}else{
					$html .= '<br /><p>'.$lang->translate(658).'</p><br />';
				}
			}else{
				//$type = $lang->translate(631); // dns
				//$type = $lang->translate(632); // vps
				if(get_value_session('from_db','is_admin') == '1'){
					if(pakketten_get_value_overview('%','%',get_value_session('from_db','is_admin')) != FALSE){
						$num = 1;
					}else{
						$num = 0;
					}
				}elseif(pakketten_get_value_overview(get_value_session('from_db','id'),'%') != FALSE) {
					$num = 1;
				}else{
					$num = 0;
				}
				if($num == 1){
					//$options = '<option value="0"></option>';
					if(check_user_right(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin')) !== FALSE){
						$options .= '<option value="1">'.$lang->translate(631).'</option>'; // dns
					}
					if(check_user_right(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin')) !== FALSE){
						$options .= '<option value="2">'.$lang->translate(679).'</option>'; // streaming
					}
					//$options .= '<option value="2">'.$lang->translate(632).'</option>'; // vps
					$html .= '<br /><br /><form name="form1" method="post" action=""><input type="hidden" id="step1" name="step1" value="step1"><table>';
					$html .= '<tr><td>'.$lang->translate(653).'</td><td><input type="text" id="search" name="search"></td></tr>';
					$html .= '<tr><td>'.$lang->translate(654).'</td><td><select name="category">'.$options.'</select></td></tr>';
					$html .= '<tr><td></td><td><input type="submit" value="'.$lang->translate(655).'" id="submit" name="submit"></td></tr></table></form>';
				}else{
					$html .= '<br /><br />'.$lang->translate(652).'<br /><br />';
				}
			}
		}
		
		elseif(get_value_get('type') == 'overdragen' && check_user_right(get_value_session('from_db','id'),'pakketoverdragen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html = '<div class="paginatitel">'.$lang->translate(625).'</div>';
			$options = '';
			if(get_value_post('submit') !== FALSE){
				if(get_value_session('from_db','id') != FALSE && get_value_post('newuserid') || get_value_session('from_db','is_admin') == 1){
					$result = pakketten_do_action_changeuser(get_value_post('newuserid'),get_value_get('id'));
					if($result == FALSE){
						$html .= '<br /><p><b>'.$lang->translate(650).'</b></p><br /><br />';
					}else{
						$html .= '<br /><p><b>'.$lang->translate(651).'</b></p><br /><br />';
					}
				}else{
					$html .= '<br /><p><b>'.$lang->translate(650).'</b></p><br /><br />';
				}
			}else{
				if(get_value_session('from_db','is_admin') == '1'){
					$row = pakketten_get_value_details('%',get_value_get('id'),get_value_session('from_db','is_admin'));
				}else{
					$row = pakketten_get_value_details(get_value_session('from_db','id'),get_value_get('id'),get_value_session('from_db','is_admin'));
				}
				if($row != FALSE){
					if(get_value_session('from_db','is_admin') == '1'){
						$temp = get_subusers('0',3);
					}else{
						$temp = get_subusers(get_value_session('from_db','id'),3);
					}
					if($temp != FALSE){
						foreach($temp as $item){
							$userdata2 = get_userdata($item['id']);
							//if(get_value_session('from_db','id') == $userdata2['id'] && get_value_session('from_db','is_admin') != 1){
								if($row['user_id'] == $userdata2['id']){
									$options .= '<option value="'.$userdata2['id'].'" selected>'.$userdata2['username'].'</option>';
								}else{
									$options .= '<option value="'.$userdata2['id'].'">'.$userdata2['username'].'</option>';
								}
							//}
						}
					}elseif(get_value_session('from_db','is_admin') == '1'){
						$userdata2 = get_userdata(get_value_session('from_db','id'));
						$options .= '<option value="'.$userdata2['id'].'">'.$userdata2['username'].'</option>';
					}else{
						$options .= '<option value=""></option>';
					}
					$userdata = get_userdata($row['user_id']);
					$html .= '<br /><p>'.$lang->translate(1201).'</p>';
					$html .= '<br /><div class=formtable><form name="form1" method="post" action=""><input type="hidden" id="step1" name="step1" value="step1"><table>';
					$html .= '<tr><td>'.$lang->translate(646).'</td><td><b>'.$userdata['username'].'</b></td></tr>';
					$html .= '<tr><td>'.$lang->translate(647).'</td><td><select name="newuserid">'.$options.'</select></td></tr>';
					$html .= '<tr><td></td><td></td></tr></table>';
					$html .= '<p><input type="submit" value="'.$lang->translate(640).'" id="submit" name="submit" class="button"></p>';
					$html .= '</form><div>';
				}else{
					$html .= '<br /><br />'.$lang->translate(648).'<br /><br />';
				}
			}
		}elseif(get_value_get('type') == 'toevoegen' && check_user_right(get_value_session('from_db','id'),'pakkettoevoegen',get_value_session('from_db','is_admin')) !== FALSE){
			if(get_value_post('submit') !== FALSE){
				if(get_value_post('step1') !== FALSE){
					$type = get_value_post('type');
					if(function_exists('pakketten_do_action_html_'.$type) !== FALSE && function_exists('pakketten_do_action_create_'.$type) !== FALSE && check_user_right(get_value_session('from_db','id'),$type,get_value_session('from_db','is_admin')) != FALSE){
						$function = 'pakketten_do_action_html_'.$type;
						$html .= '<br />'.$function().'';
					}else{
						$temp = 0;
						$option = '<option value="none"></option>';
						foreach($modules as $type => $item){
							if(function_exists('pakketten_do_action_html_'.$type) !== FALSE && function_exists('pakketten_do_action_create_'.$type) !== FALSE && check_user_right(get_value_session('from_db','id'),$type,get_value_session('from_db','is_admin')) != FALSE){
								if($type == 'vps'){ $option .= '<option value="vps">'.$lang->translate(636).'</option>'; $temp++; }
								if($type == 'dns'){ $option .= '<option value="dns">'.$lang->translate(637).'</option>'; $temp++; }
								if($type == 'stream'){ $option .= '<option value="stream">'.$lang->translate(678).'</option>'; $temp++; }
							}
						}
						if($temp === 0){
							$html .= '<br /><br />'.$lang->translate(635).'<br /><br />';
						}else{
              $html .= '<br /><p>'.$lang->translate(31).'</p><br />';
              $html .= '<div class=formtable><form name="form1" method="post" action=""><input type="hidden" id="step1" name="step1" value="step1">';
							$html .= '<table><tr><td>'.$lang->translate(641).'</td><td><select name="type">'.$option.'</select></td></tr>';
							$html .= '<tr><td></td><td><input type="submit" value="'.$lang->translate(640).'" id="submit" name="submit" class="button"></td></tr></table></form></div>';
						}
					}
				}else{
					$type = get_value_post('type');
					if(function_exists('pakketten_do_action_html_'.$type) !== FALSE && function_exists('pakketten_do_action_create_'.$type) !== FALSE && check_user_right(get_value_session('from_db','id'),$type,get_value_session('from_db','is_admin')) != FALSE){
						$function = 'pakketten_do_action_create_'.$type;
						if($function() !== FALSE){
						  $html .= '<br /><p>'.$lang->translate(31).'<br />';
							$html .= '<br /><b>'.$lang->translate(638).'</b><br /><br /></p>';
						}else{
							$html .= '<br /><br />'.$lang->translate(639).'<br /><br />';
						}
					}else{
						$html .= '<br /><br />'.$lang->translate(635).'<br /><br />';
					}
				}
			}else{
				$temp = 0;
				$option = '<option value="none"></option>';
				$pakketten = pakketten_get_value_overview(get_value_session('from_db','id'),'%',get_value_session('from_db','is_admin'));
				$paktype['dns'] = '';
				$paktype['vps'] = '';
				$paktype['stream'] = '';
				foreach($pakketten as $pakket){
					if($pakket['user_id'] == get_value_session('from_db','id')){
						$paktype[$pakket['type']] = $pakket['type'];
					}
				}
				foreach($modules as $type => $item){
					if(function_exists('pakketten_do_action_html_'.$type) !== FALSE && function_exists('pakketten_do_action_create_'.$type) !== FALSE && check_user_right(get_value_session('from_db','id'),$type,get_value_session('from_db','is_admin')) != FALSE){
						if($type == 'vps'){ if($paktype['vps'] == "vps" || get_value_session('from_db','is_admin') == "1"){ $option .= '<option value="vps">'.$lang->translate(636).'</option>'; $temp++; } }
						if($type == 'dns'){ if($paktype['dns'] == "dns" || get_value_session('from_db','is_admin') == "1"){ $option .= '<option value="dns">'.$lang->translate(637).'</option>'; $temp++; } }
						if($type == 'stream'){ if($paktype['stream'] == "stream" || get_value_session('from_db','is_admin') == "1"){ $option .= '<option value="stream">'.$lang->translate(678).'</option>'; $temp++; } }
					}
				}
				if($temp === 0){
					$html .= '<br />'.$lang->translate(635).'<br /><br />';
				}else{
				  $html .= '<br /><p>'.$lang->translate(31).'</p><br />';
					$html .= '<div class=formtable><form name="form1" method="post" action=""><input type="hidden" id="step1" name="step1" value="step1">';
					$html .= '<table><tr><td>'.$lang->translate(641).'</td><td><select name="type">'.$option.'</select></td></tr>';
					$html .= '<tr><td></td><td><input type="submit" value="'.$lang->translate(640).'" id="submit" name="submit" class="button"></td></tr></table></form></div>';
				}
			}
		}elseif(get_value_get('type') == 'overzicht' && check_user_right(get_value_session('from_db','id'),'pakketoverzicht',get_value_session('from_db','is_admin')) != FALSE){
			if(get_value_session('from_db','is_admin') == '1'){
				if(get_value_get('userid') !== FALSE){
					if(get_value_get('p') !== FALSE){
						$overview = pakketten_get_value_overview(get_value_get('userid'),get_value_get('p'),get_value_session('from_db','is_admin'));
					}else{
						$overview = pakketten_get_value_overview(get_value_get('userid'),'%',get_value_session('from_db','is_admin'));
					}
				}else{
					if(get_value_get('p') !== FALSE){
						$overview = pakketten_get_value_overview('%',get_value_get('p'),get_value_session('from_db','is_admin'));
					}else{
						$overview = pakketten_get_value_overview('%','%',get_value_session('from_db','is_admin'));
					}
				}
			}else{
				if(get_value_get('userid') !== FALSE && check_user_subuser(get_value_session('from_db','id'),get_value_get('userid'))){
					if(get_value_get('p') !== FALSE){
						$overview = pakketten_get_value_overview(get_value_get('userid'),get_value_get('p'),get_value_session('from_db','is_admin'));
					}else{
						$overview = pakketten_get_value_overview(get_value_get('userid'),'%',get_value_session('from_db','is_admin'));
					}
				}else{
					if(get_value_get('p') !== FALSE){
						$overview = pakketten_get_value_overview(get_value_session('from_db','id'),get_value_get('p'),get_value_session('from_db','is_admin'));
					}else{
						$overview = pakketten_get_value_overview(get_value_session('from_db','id'),'%',get_value_session('from_db','is_admin'));
					}
				}
			}
			if($overview == FALSE){
				$html .= '<br /><br />'.$lang->translate(627).'<br /><br />';
			}else{
			
			//////////
				$html .= '<div class="tablestop2">';
				$html .= '<table>';
				$html .= '<tr><td colspan="6"><div style="width:665px;"><div style="float: left;">';
				

				
				if(get_value_get('type') == 'zoeken' && check_user_right(get_value_session('from_db','id'),'pakketzoeken',get_value_session('from_db','is_admin')) !== FALSE){
				//if(get_value_post('submit') !== FALSE){
					if(get_value_post('category') == FALSE){
						$html .= '<br /><br />'.$lang->translate(656).'<br /><br />';
					}elseif(get_value_post('search') == FALSE){
						$html .= '<br /><br />'.$lang->translate(657).'<br /><br />';
					}elseif(get_value_post('category') == "1"){
						if(get_value_session('from_db','is_admin') == '1'){
							$pakketten = pakketten_get_value_overview('%','dns',get_value_session('from_db','is_admin'));
						}else{
							$pakketten = pakketten_get_value_overview(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin'));
						}
						if($pakketten !== FALSE){
							foreach($pakketten as $pakket){
								//$html .= '<br /><br />';
								if(check_user_right(get_value_session('from_db','id'),'dnsdomzoeken',get_value_session('from_db','is_admin')) !== FALSE){
									$html .= dns_create_html_searchresults(dns_do_action_search($pakket['pakket_id'],get_value_post('search'),'domain',get_value_session('from_db','is_admin')));
								}
								if(check_user_right(get_value_session('from_db','id'),'dnstemzoeken',get_value_session('from_db','is_admin')) !== FALSE){
									$html .= dns_create_html_searchresults(dns_do_action_search($pakket['pakket_id'],get_value_post('search'),'template',get_value_session('from_db','is_admin')));
								}
								if(check_user_right(get_value_session('from_db','id'),'dnssuperzoeken',get_value_session('from_db','is_admin')) !== FALSE){
									$html .= dns_create_html_searchresults(dns_do_action_search($pakket['pakket_id'],get_value_post('search'),'super',get_value_session('from_db','is_admin')));
								}
							}
							if(check_user_right(get_value_session('from_db','id'),'dnsdomzoeken',get_value_session('from_db','is_admin')) === FALSE && check_user_right(get_value_session('from_db','id'),'dnstemzoeken',get_value_session('from_db','is_admin')) === FALSE){
								$html .= '<br /><p>'.$lang->translate(658).'</p><br />';
							}
						}else{
							$html .= '<br /><p>'.$lang->translate(658).'</p><br />';
						}
					}elseif(get_value_post('category') == "2"){
						if(get_value_session('from_db','is_admin') == '1'){
							$pakketten = pakketten_get_value_overview('%','stream',get_value_session('from_db','is_admin'));
						}else{
							$pakketten = pakketten_get_value_overview(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin'));
						}
						if($pakketten !== FALSE){
							foreach($pakketten as $pakket){
								$html .= '<br /><br />';
								if(check_user_right(get_value_session('from_db','id'),'streamzoeken',get_value_session('from_db','is_admin')) !== FALSE){
									$html .= stream_do_action_search($pakket['pakket_id'],get_value_post('search'),get_value_session('from_db','is_admin'));
								}
							}
							if(check_user_right(get_value_session('from_db','id'),'dnsdomzoeken',get_value_session('from_db','is_admin')) === FALSE && check_user_right(get_value_session('from_db','id'),'dnstemzoeken',get_value_session('from_db','is_admin')) === FALSE){
								$html .= '<br /><p>'.$lang->translate(658).'</p><br />';
							}
						}else{
							$html .= '<br /><p>'.$lang->translate(658).'</p><br />';
						}
					}else{
						$html .= '<br /><p>'.$lang->translate(658).'</p><br />';
					}
				}else{
					//$type = $lang->translate(631); // dns
					//$type = $lang->translate(632); // vps
					if(get_value_session('from_db','is_admin') == '1'){
						if(pakketten_get_value_overview('%','%',get_value_session('from_db','is_admin')) != FALSE){
							$num = 1;
						}else{
							$num = 0;
						}
					}elseif(pakketten_get_value_overview(get_value_session('from_db','id'),'%') != FALSE) {
						$num = 1;
					}else{
						$num = 0;
					}
					if($num == 1){
						//$options = '<option value="0"></option>';
						if(check_user_right(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin')) !== FALSE){
							$options .= '<option value="1">'.$lang->translate(631).'</option>'; // dns
						}
						if(check_user_right(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin')) !== FALSE){
							$options .= '<option value="2">'.$lang->translate(679).'</option>'; // streaming
						}
						//$options .= '<option value="2">'.$lang->translate(632).'</option>'; // vps
						$html .= '</div><div style="float: right;"><form name="form1" method="post" action="?page=producten&type=zoeken"><input type="hidden" id="step1" name="step1" value="step1">';

						$html .= '<input type="text" id="search" name="search" class="search">';
						$html .= '<input type="hidden" name="category" value="1">';
						$html .= '<input type="submit" value="'.$lang->translate(533).'" id="submit" name="submit" class="searchbutton"></form></div>';
					}else{
						$html .= '<br /><br />'.$lang->translate(652).'<br /><br />';
					}
				//}
				}
				$html .= '</td></tr>';
				$html .= '<tr><td>'.$lang->translate(633).'';
				
				if(check_user_right(get_value_session('from_db','id'),'pakkettoevoegen',get_value_session('from_db','is_admin')) != FALSE){

				$html .= '<div style="float: right;"><a href="?page=producten&type=toevoegen"><img src="'.$template_dir.'/plus.png" border="0" valign="middle" title="'.$lang->translate(1204).'"></a></div>';
					}
				
				$html .= '</td><td width="270px">'.$lang->translate(629).'</td><td colspan=4>'.$lang->translate(630).'</td></tr>';
				if(get_value_get('userid') !== FALSE){ $urluserid = '&userid='.get_value_get('userid'); }else{ $urluserid = ''; }
				foreach($overview as $product){
					$userdata = get_userdata($product['user_id']);
					$type = '';
					if($product['type'] == 'dns'){
						$type = $lang->translate(631);
					}elseif($product['type'] == 'vps'){
						$type = $lang->translate(632);
					}elseif($product['type'] == 'stream'){
						$type = $lang->translate(679);
					}
					$html .= '<tr><td><a href="?lang='.lang_get_value_defaultlang().'&page='.$product['type'].'&id='.$product['pakket_id'].'"><u>'.$type.' '.$product['pakket_id'].'</u></a></td><td>';
					if(check_user_right(get_value_session('from_db','id'),'klantbekijken',get_value_session('from_db','is_admin')) != FALSE){
					$html .= '<u><a href="?lang='.lang_get_value_defaultlang().'&page=klanten&type=bekijken&id='.$userdata['id'].'">'.$userdata['username'].'</a></u>';
					}else{
					$html .= ''.$userdata['username'].'';				
					}
					$html .= '</td><td width="25px">';
										if(check_user_right(get_value_session('from_db','id'),'pakketbekijken',get_value_session('from_db','is_admin')) != FALSE){
						$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=producten&type=bekijken&id='.$product['id'].'&p='.$product['type'].'"><img src="'.$template_dir.'/info.png" border="0" title="'.$lang->translate(1200).'"></a></center>';
					}
					$html .= '</td><td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'pakketbewerken',get_value_session('from_db','is_admin')) != FALSE){
						if($product['user_id'] != get_value_session('from_db','id') || get_value_session('from_db','is_admin') == 1){
							$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=producten&type=bewerken&id='.$product['id'].'&p='.$product['type'].'"><img src="'.$template_dir.'/wijzigen.png" border="0" title="'.$lang->translate(1202).'"></a></center>';
						}
					}
					$html .= '</td><td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'pakketoverdragen',get_value_session('from_db','is_admin')) != FALSE){
						if($product['user_id'] != get_value_session('from_db','id') || get_value_session('from_db','is_admin') == 1){
							$html .= '<center><a href="?lang='.lang_get_value_defaultlang().'&page=producten&type=overdragen&id='.$product['id'].'"><img src="'.$template_dir.'/overdragen.png" border="0" title="'.$lang->translate(1201).'"></a></center>';
						}
					}
					$html .= '</td><td width="25px">';
					if(check_user_right(get_value_session('from_db','id'),'pakketverwijderen',get_value_session('from_db','is_admin')) != FALSE){
						if($product['user_id'] != get_value_session('from_db','id') || get_value_session('from_db','is_admin') == 1){
							$html .= '<center><a href="javascript:confirm_text(\''.$lang->translate(675).'\', \'?lang='.lang_get_value_defaultlang().'&page=producten&type=verwijderen&id='.$product['id'].'&p='.$product['type'].'\')"><img src="'.$template_dir.'/verwijderen.png" border="0" title="'.$lang->translate(1203).'"></a></center>';
						}
					}
					$html .= '</td></tr>';
				}
				$html .= '</table></div>';
						
				
			}
		}else{
			$html .= '<br /><br />'.$lang->translate(626).'<br /><br />';
		}
	}else{
		$html = '<b>'.$lang->translate(625).'</b>';
		$html .= '<br /><br />'.$lang->translate(626).'<br /><br />';
	}
?>