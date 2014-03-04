<?php
	// Created by Mark Scholten
	// This file is called index.php and is the start for everything, it includes everything that is required
	// This file should be small
	// Start session
	session_start();
	// Debugging, can be enabled here or disabled
	ini_set("show_errors","on");
	error_reporting(E_ALL);
	ini_set('display_errors',0);
	
	// Enable check to see if things where included (a small security feature)
	$index = 1;
	
	// Includes some basic files used by many parts of the scripts
	require_once("function.php");
	require_once("config.php");
	require_once('LanguageParser.php');
	$lang=LanguageParser::getInstance(addslashes(htmlentities(strtolower(lang_get_value_defaultlang()))),addslashes(htmlentities(strtolower($lang_dir))));
	
	$html = '';
	// Check if someone is logged in
	if(check_is_loggedin() == FALSE){
		if(isset($_POST) && !empty($_POST) && isset($_POST['login']) && !empty($_POST['login'])){
			if(login_do_action_checkcredentials() == TRUE){ login_do_action_createsession(); }else{ echo login_create_loginscreen(); exit(); }
		}else{ echo login_create_loginscreen(); exit(); }
	}
	if(get_value_get('page') != FALSE){
		if(get_value_get('page') != 'uitloggen'){ require_once(fix_is_file('content/'.get_value_get('page').'.php','content/home.php')); }
		switch ( get_value_get('page') ) {
			case "home":		$menu = menu_create_information('home');			break;
			case "gegevens":	$menu = menu_create_information('home');			break;
			case "producten":	$menu = menu_create_information('producten');		break;
			case "klanten":		$menu = menu_create_information('klanten');			break;
			case "wachtwoord":	$menu = menu_create_information('home');			break;
			case "statistiek":	$menu = menu_create_information('statistiek');		break;
			case "dns":			$menu = menu_create_information('dns');				break;
			case "stream":		$menu = menu_create_information('stream');			break;
			case "vps":			$menu = menu_create_information('vps');				break;
			case "email":		$menu = menu_create_information('email');			break;
			case "firewall":	$menu = menu_create_information('firewall');		break;
			case "voip":		$menu = menu_create_information('voip');			break;
			case "uitloggen":	$menu = '';		session_destroy();	echo login_create_loginscreen();	exit;
			default:			$menu = menu_create_information('home');			break;
		}
	}else{
		require_once('content/home.php');
		$menu = menu_create_information('home');
	}
	echo template_do_action_parse($html,$menu,"default",$cp_version);
	
?>
