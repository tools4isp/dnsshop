<?php
	fix_is_included($index);
	if(check_user_right(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin')) !== FALSE && pakketten_check_is_allowed(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin'))){
		$html = '<b>'.$lang->translate(908).'</b>';
		if(get_value_get('type') == 'streamoverzicht' && check_user_right(get_value_session('from_db','id'),'streamoverzicht',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= stream_create_html_overview(get_value_get('id'));
		}elseif(get_value_get('type') == 'streamzoeken' && check_user_right(get_value_session('from_db','id'),'streamzoeken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit')){ $search = stream_do_action_search(get_value_get('id'),get_value_post('search'),get_value_session('from_db','is_admin')); if($search === FALSE){ $html .= '<br /><br />'.$lang->translate(718).'<br /><br />'; }else{ $html .= $search; } }else{ $html .= stream_create_html_search(); }
		}elseif(get_value_get('type') == 'streamtoevoegen' && check_user_right(get_value_session('from_db','id'),'streamtoevoegen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit') != FALSE){ $html .= stream_do_action_toevoegen(get_value_get('id'),get_value_session('from_db','is_admin'));
			}else{ 
			$html .= stream_create_html_toevoegen(get_value_get('id'),get_value_session('from_db','is_admin')); 
			}
		}elseif(get_value_get('type') == 'streambekijken' && check_user_right(get_value_session('from_db','id'),'streambekijken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= stream_create_html_streamdetails(get_value_get('id'),get_value_get('streamid'),'bekijk',get_value_session('from_db','is_admin'));
		}elseif(get_value_get('type') == 'streambewerken' && check_user_right(get_value_session('from_db','id'),'streambewerken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit')){ $replace = stream_do_action_replace_streamdetails(get_value_get('id'),get_value_get('streamid'),get_value_session('from_db','is_admin')); if($replace === FALSE){ $html .= '<br /><br />'.$lang->translate(934).'<br />'; }else{ $html .= '<br /><br />'.$lang->translate(935).'<br />'; }}
			$html .= stream_create_html_streamdetails(get_value_get('id'),get_value_get('streamid'),'bewerk',get_value_session('from_db','is_admin'));
		}elseif(get_value_get('type') == 'streamverwijderen' && check_user_right(get_value_session('from_db','id'),'streamverwijderen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= stream_do_action_delete(get_value_get('streamid'),get_value_get('id'),get_value_session('from_db','is_admin'));
		}elseif(get_value_get('type') == 'streamstart' && check_user_right(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE && get_value_get('streamid') !== FALSE){
			$html .= stream_create_html_action(get_value_get('id'),get_value_get('streamid'),get_value_get('type'));
			$html .= '<br /><br />'.$lang->translate(603).'';
			$html .= stream_create_html_overview(get_value_get('id'));
		}elseif(get_value_get('type') == 'streamstop' && check_user_right(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE && get_value_get('streamid') !== FALSE){
			$html .= stream_create_html_action(get_value_get('id'),get_value_get('streamid'),get_value_get('type'));
			$html .= '<br /><br />'.$lang->translate(603).'';
			$html .= stream_create_html_overview(get_value_get('id'));
		}elseif(get_value_get('type') == 'streamdjstart' && check_user_right(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE && get_value_get('streamid') !== FALSE){
			$html .= stream_create_html_action(get_value_get('id'),get_value_get('streamid'),get_value_get('type'));
			$html .= '<br /><br />'.$lang->translate(603).'';
			$html .= stream_create_html_overview(get_value_get('id'));
		}elseif(get_value_get('type') == 'streamdjstop' && check_user_right(get_value_session('from_db','id'),'stream',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE && get_value_get('streamid') !== FALSE){
			$html .= stream_create_html_action(get_value_get('id'),get_value_get('streamid'),get_value_get('type'));
			$html .= '<br /><br />'.$lang->translate(603).'';
			$html .= stream_create_html_overview(get_value_get('id'));
		}else{
			$html .= '<br /><br />'.$lang->translate(603).'';
			$html .= stream_create_html_overview(get_value_get('id'));
		}
	}
?>