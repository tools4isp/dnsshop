<?php
	// Created by Mark Scholten
	// This file is called home.php this is the default page if a page doesn't exists and after login without parameters in the url set
	fix_is_included($index);
      $html = '<div class="paginatitel">'.$lang->translate(8).'</div>';
      //$html .= '<br><p>'.$lang->translate(1109).'</p><br>';
    
    
    if(check_user_right(get_value_session('from_db','id'),'pakketoverzicht',get_value_session('from_db','is_admin')) != FALSE){
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
								$html .= dns_do_action_search($pakket['pakket_id'],get_value_post('search'),'domain',get_value_session('from_db','is_admin'));
							}
							if(check_user_right(get_value_session('from_db','id'),'dnstemzoeken',get_value_session('from_db','is_admin')) !== FALSE){
								$html .= dns_do_action_search($pakket['pakket_id'],get_value_post('search'),'template',get_value_session('from_db','is_admin'));
							}
							if(check_user_right(get_value_session('from_db','id'),'dnssuperzoeken',get_value_session('from_db','is_admin')) !== FALSE){
								$html .= dns_do_action_search($pakket['pakket_id'],get_value_post('search'),'super',get_value_session('from_db','is_admin'));
							}
						}
						if(check_user_right(get_value_session('from_db','id'),'dnsdomzoeken',get_value_session('from_db','is_admin')) === FALSE && check_user_right(get_value_session('from_db','id'),'dnstemzoeken',get_value_session('from_db','is_admin')) === FALSE){
							$html .= '<br /><br />'.$lang->translate(658).'<br /><br />';
						}
					}else{
						$html .= '<br /><br />'.$lang->translate(658).'<br /><br />';
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
							$html .= '<br /><br />'.$lang->translate(658).'<br /><br />';
						}
					}else{
						$html .= '<br /><br />'.$lang->translate(658).'<br /><br />';
					}
				}else{
					$html .= '<br /><br />'.$lang->translate(658).'<br /><br />';
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
					$html .= '<select name="category" class="dropdown">'.$options.'</select>';
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
					$html .= '<tr><td><a href="?lang='.lang_get_value_defaultlang().'&page='.$product['type'].'&id='.$product['pakket_id'].'"><u>'.$type.' '.$product['pakket_id'].' - '.$product['pakket_name'].'</u></a></td><td>';
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
		}
    
?>
