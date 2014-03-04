<?php
	// Created by Mark Scholten
	// This file is called config.php and has most config options in it
	fix_is_included($index);
  
	// Directory with language files
	$default_lang = "nl";
	$lang_dir = "/var/www/devmark/lang";

	// Template selcection
	$template_name = "develop";
	$layout_dir = "/var/www/devmark/templates/".$template_name;
	$template_dir = "/devmark/templates/develop/img/icons/";

	// phpmailer
	require('phpmailer/class.phpmailer.php');
	$phpmailer = new PHPMailer(true);
	
	// Central mysql database
	$config['db']['mysql']['central']['host'] = "localhost"; //mysql server
	$config['db']['mysql']['central']['user'] = ""; // mysql user
	$config['db']['mysql']['central']['pass'] = ""; // mysql pass
	$config['db']['mysql']['central']['database'] = ""; //mysql database
	
	// DNS mysql database
	$config['db']['mysql']['dns']['host'] = "localhost"; //mysql server
	$config['db']['mysql']['dns']['user'] = ""; // mysql user
	$config['db']['mysql']['dns']['pass'] = ""; // mysql pass
	$config['db']['mysql']['dns']['database'] = ""; //mysql database
	
	$cp_version = '2.2.6';
	// Version 2.2.5
	/*
		Roadmap (not yet finished/todo items):
			- Add DNSSEC support (including (hidden) supermasters)
			- VoIP support (where possible everything)
			- Advanced email options (adding/deleting/managing email addresses, domain aliases)
			- Basic email options (adding/deleting/changing settings for spamfiltering per domain)
			- Basic VPS management (start/stop/reboot/statistics)
			- API (incl. documentation) for everything that has a web interface
			- DA koppeling (de koppeling met DA bestaat nu dat als je een domeinnaam in DirectAdmin toevoegt je ook de DNS vanuit DirectAdmin kunt beheren. Deze staat vervolgens wel op onze naamservers (wordt automatisch geupdate). Andersom zal ik ook naar kijken of dat mogelijk te maken is, dit heeft alleen voorlopig iets minder prioriteit voor ons (en ik moet hier goed over nadenken ivm de veiligheid))
			- Meerdere andere vormen van sorteren (kan nog niet)
			- Improved search (Zoeken op begin of eind van een domeinnaam)
		Version history:
			V2.2.6: Alle wijzigingen worden ook in git.streamservice.nl opgenomen, bugfixes, layout verbetering en feedback formulier toevoegen
			V2.2.5: Bug fixes + layout vervangen + pakket zoeken verbeterd
			V2.2.4: Enkele bug fixes
			V2.2.3: Enkele bug fixes
			V2.2.2: Bug fixes + auto DJ + icecast vervangen door shoutcast ivm dat dat meer standaard is en MP3 encoding mogelijk maakt
			V2.2.1: Bug fixes
			V2.2: Streaming (icecast)
			V2.1.1: Sorting options for domainnames
			V2.1: Contains all important DNS functions and most package functions
			V2.0: Contains all important user functions and basic options to extend it
			V1.9: Right mangement added
			V1.8: Reseller option added
			V1.0: Contains some user functions and a template function
	*/
	
	// DNS record types die ondersteund worden (SOA nooit vermelden, deze wordt automatisch gegenereerd)
	$dns_record_types = array("AAAA","CNAME","NS","A","MX","TXT","PTR","SRV","SPF","TLSA");
	
	// Stream available bitrates
	$stream_bitrates = array(40,56,80,96,112,128);
	$stream_host_poorten = array("213.189.17.176" => array(8000,8010,8020,8030,8040,8050,8060,8070,8080,8090,8100,8110,8120,8130,8140,8150,8160,8170,8180,8190,8200));
	
	// Modules to list in the general menu (change to 0 to disable and to 1 to enable), some modules don't have an option to be listed in the general menu (they are disabled by default)
	$modules['klanten'] = 1;
	$modules['producten'] = 1;
	$modules['dns'] = 0; // disabled by default, no language option for the module name in this menu (it is very recommended to keep this option disabled when possible)
	$modules['stream'] = 0; // disabled by default, no language option for the module name in this menu (it is very recommended to keep this option disabled when possible)
?>
