<?php
	fix_is_included($index);
	if(check_user_right(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin')) !== FALSE && pakketten_check_is_allowed(get_value_session('from_db','id'),'dns',get_value_session('from_db','is_admin'))){
		$html = '<div class="paginatitel">'.$lang->translate(602).'</div>';
		if(get_value_get('type') == 'domoverzicht' && check_user_right(get_value_session('from_db','id'),'dnsdomoverzicht',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= dns_create_html_overview(get_value_get('id'),'domain');
		}elseif(get_value_get('type') == 'temoverzicht' && check_user_right(get_value_session('from_db','id'),'dnstemoverzicht',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= dns_create_html_overview(get_value_get('id'),'template');
		}elseif(get_value_get('type') == 'dombekijken' && check_user_right(get_value_session('from_db','id'),'dnsdombekijken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= dns_create_html_records(get_value_get('id'),get_value_get('domid'),'domain','bekijk',get_value_session('from_db','is_admin'));
		}elseif(get_value_get('type') == 'tembekijken' && check_user_right(get_value_session('from_db','id'),'dnstembekijken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= dns_create_html_records(get_value_get('id'),get_value_get('temid'),'template','bekijk',get_value_session('from_db','is_admin'));
		}elseif(get_value_get('type') == 'dombewerken' && check_user_right(get_value_session('from_db','id'),'dnsdombewerken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit')){ $replace = dns_do_action_replace_records(get_value_get('id'),get_value_get('domid'),'domain',get_value_session('from_db','is_admin')); if($replace === FALSE){ $html .= '<br /><div class="content"><p>'.$lang->translate(717).'<p /></div><br /><br />'; }else{ $html .= '<br /><div class="content"><p>'.$lang->translate(758).'<p /></div><br /><br />'; }}
			$html .= dns_create_html_records(get_value_get('id'),get_value_get('domid'),'domain','bewerk',get_value_session('from_db','is_admin'));
		}elseif(get_value_get('type') == 'tembewerken' && check_user_right(get_value_session('from_db','id'),'dnstembewerken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit')){ $replace = dns_do_action_replace_records(get_value_get('id'),get_value_get('temid'),'template',get_value_session('from_db','is_admin')); if($replace === FALSE){ $html .= '<br /><div class="content"><p>'.$lang->translate(717).'<p /></div><br /><br />'; }else{ $html .= '<br /><div class="content"><p>'.$lang->translate(758).'<p /></div><br /><br />'; }}
			$html .= dns_create_html_records(get_value_get('id'),get_value_get('temid'),'template','bewerk',get_value_session('from_db','is_admin'));
		}elseif(get_value_get('type') == 'temzoeken' && check_user_right(get_value_session('from_db','id'),'dnstemzoeken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit')){ $search = dns_create_html_searchresults(dns_do_action_search(get_value_get('id'),get_value_post('search'),'template',get_value_session('from_db','is_admin'))); if($search === FALSE){ $html .= '<br /><div class="content"><p>'.$lang->translate(718).'<p /></div><br /><br />'; }else{ $html .= $search; } }else{ $html .= dns_create_html_search('template'); }
		}elseif(get_value_get('type') == 'domzoeken' && check_user_right(get_value_session('from_db','id'),'dnsdomzoeken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit')){ $search = dns_create_html_searchresults(dns_do_action_search(get_value_get('id'),get_value_post('search'),'domain',get_value_session('from_db','is_admin'))); if($search === FALSE){ $html .= '<br /><div class="content"><p>'.$lang->translate(718).'<p /></div><br /><br />'; }else{ $html .= $search; } }else{ $html .= dns_create_html_search('domain'); }
		}elseif(get_value_get('type') == 'domverwijderen' && check_user_right(get_value_session('from_db','id'),'dnsdomverwijderen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= dns_do_action_delete(get_value_get('domid'),get_value_get('id'),'domain',get_value_session('from_db','is_admin'));
		}elseif(get_value_get('type') == 'temverwijderen' && check_user_right(get_value_session('from_db','id'),'dnstemverwijderen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= dns_do_action_delete(get_value_get('temid'),get_value_get('id'),'template',get_value_session('from_db','is_admin'));
		}elseif(get_value_get('type') == 'temtoevoegen' && check_user_right(get_value_session('from_db','id'),'dnstemtoevoegen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit') != FALSE){ $html .= dns_do_action_toevoegen(get_value_get('id'),'template',get_value_session('from_db','is_admin'));
			}else{ $html .= dns_create_html_toevoegen(get_value_get('id'),'template'); }
		}elseif(get_value_get('type') == 'domtoevoegen' && check_user_right(get_value_session('from_db','id'),'dnsdomtoevoegen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit') != FALSE){
				if(get_value_post('template') == "1"){ $html .= dns_create_html_toevoegen(get_value_get('id'),'domain'); }
				if(get_value_post('template') == "2"){ $html .= dns_create_html_met_template(get_value_get('id'),'nee'); }
				if(get_value_post('template') == "3"){ $html .= dns_create_html_met_template(get_value_get('id'),'ja'); }
				if(get_value_post('domein') != FALSE){ $html .= dns_do_action_toevoegen(get_value_get('id'),'domain',get_value_session('from_db','is_admin')); }
			}else{ $html .= dns_create_html_selectie(get_value_get('id')); }
		}elseif(get_value_get('type') == 'domkoppelen' && check_user_right(get_value_session('from_db','id'),'dnstemkoppelen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit') != FALSE){
				$html .= dns_do_action_koppelen(get_value_get('id'),get_value_get('domid'));
			}else{ $html .= dns_create_html_koppelen(get_value_get('id'),get_value_get('domid')); }
		}elseif(get_value_get('type') == 'recglobbew' && check_user_right(get_value_session('from_db','id'),'dnsrecglobbew',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit') != FALSE){
				$html .= dns_do_action_recglobbew(get_value_get('id'),get_value_post('oud'),get_value_post('nieuw'));
			}else{ $html .= dns_create_html_recglobbew(get_value_get('id')); }
		}elseif(get_value_get('type') == 'superzoeken' && check_user_right(get_value_session('from_db','id'),'dnssmzoeken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit')){ $search = dns_create_html_searchresults(dns_do_action_search(get_value_get('id'),get_value_post('search'),'super',get_value_session('from_db','is_admin'))); if($search === FALSE){ $html .= '<br /><br />'.$lang->translate(718).'<br /><br />'; }else{ $html .= $search; }
			}else{ $html .= dns_create_html_search('super'); }
		}elseif(get_value_get('type') == 'superoverzicht' && check_user_right(get_value_session('from_db','id'),'dnssmoverzicht',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= dns_create_html_superoverzicht(get_value_get('id'));
		}elseif(get_value_get('type') == 'supertoevoegen' && check_user_right(get_value_session('from_db','id'),'dnssmtoevoegen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit') != FALSE){
				$html .= dns_do_action_supertoevoegen(get_value_get('id'));
			}else{ $html .= dns_create_html_supertoevoegen(get_value_get('id')); }
		}elseif(get_value_get('type') == 'superbewerken' && check_user_right(get_value_session('from_db','id'),'dnssmbewerken',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			if(get_value_post('submit') != FALSE){
				$html .= dns_do_action_superbewerken(get_value_get('id'),get_value_get('superid'),get_value_session('from_db','admin'));
			}else{ $html .= dns_create_html_superbewerken(get_value_get('id'),get_value_get('superid'),get_value_session('from_db','admin')); }
		}elseif(get_value_get('type') == 'superverwijderen' && check_user_right(get_value_session('from_db','id'),'dnssmverwijderen',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= dns_do_action_delete(get_value_get('superid'),get_value_get('id'),'super',get_value_session('from_db','is_admin'));
			$html .= dns_create_html_superoverzicht(get_value_get('id'));
		}elseif(get_value_get('type') == 'domsuperontkoppelen' && check_user_right(get_value_session('from_db','id'),'dnssmdomontkop',get_value_session('from_db','is_admin')) !== FALSE && get_value_get('id') !== FALSE){
			$html .= dns_do_action_superontkoppelen(get_value_get('domid'),get_value_get('id'),get_value_session('from_db','is_admin'));
		}else{
			//$html .= '<br /><br />'.$lang->translate(603).'';
			$html .= dns_create_html_overview(get_value_get('id'),'domain');
			
		}
	}else{
		$html .= '<br /><br />'.$lang->translate(601).'<br /><br />';
	}
  
?>