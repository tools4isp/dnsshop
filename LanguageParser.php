<?php
	// All rights for this file are owned by Mark Scholten
	// This file is called LanguageParser.php
	fix_is_included($index);
/*

De languageParser wordt gebruikt om de vertalingen voor de verschillende talen te kunnen weergeven.
De taalbestanden dienen .csv bestanden te zijn (bijvoorbeeld: nl.csv en en.csv).
De bestanden dienen volgens de volgende structuur opgezet te worden

De codes in de csv dienen numeriek te zijn.

code,vertaling;code2,vertaling; enz

Vertalingen kunnen verkregen worden op de onderstaande manier:

Eerst dien er een instance van de Languageparser gemaakt te worden voor een bepaalde taal: $parser=LanguageParser::getInstance(taal);

daarna kan er een vertaling voor een bepaalde code verkregen worden op de volgende manier:
$parser->translate(code);

*/
class LanguageParser
{
	
	private static $instance;
	private static $language;
	private static $lang_dir;
	private static $languageArray = array();
	
	private function __construct($language,$lang_dir)
	{
		$this->language=$language;
		$this->setupLanguageArray($language,$lang_dir);
	}
	
	public function __destruct()
	{
		unset($instance);
	}
	
	private function setupLanguageArray($language,$lang_dir)
	{
		$file=$lang_dir.'/'.$language.'.csv';
		if($fileHandler=@fopen($file, 'rb'))
		{
			LanguageParser::$languageArray = LanguageParser::parseLanguageCSV(fread($fileHandler, filesize($file)));
		}
		else
		{
			trigger_error("Language File not found, searched for: ".$language.".csv in lang directory (".$lang_dir.") ", 512);			
		}
	}
	
	private function parseLanguageCSV($content)
	{
		$content = str_replace("\n", "", $content);
		$content = str_replace("\r", "", $content);
		$splitted = explode(";",$content);
		if(strlen($splitted[count($splitted)-1]<1))
		{
			array_pop($splitted);
		}
		foreach($splitted as $translation)
		{
			$temp=explode(",",$translation,2);
			$code=$temp[0];
			$translation=$temp[1];
			$this->languageArray[$code]=$translation;
		}
	}
	
	public static function getInstance($language,$lang_dir)
	{
		if(strlen($language)<1)
		{
			trigger_error("No language passed to getInstance", 512);
		}
		if(strlen($lang_dir)<1)
		{
			trigger_error("No language dir passed to getInstance", 512);
		}
		else
		{
			if(LanguageParser::$language==$language)
			{
				return $this->instance;
			}
			elseif(LanguageParser::$lang_dir==$lang_dir)
			{
				return $this->instance;
			}else
			{
				LanguageParser::$instance = new LanguageParser($language,$lang_dir);
				return LanguageParser::$instance;
		}
		}
	}
	
	public function translate($code)
	{
		if(is_numeric($code))
		{
			if(is_array($this->languageArray))
			{
				if(array_key_exists($code, $this->languageArray))
				{
					return $this->languageArray[$code];
				}
				else
				{
					trigger_error("Translation code not found, code:".$code, 512);
					return "Not Found";
				}
			}
			else
			{
				trigger_error("languageArray not constructed", 512);
			}
		}
		else
		{
			trigger_error("Translation code not numeric, code:".$code, 512);
		}
	}
}
?>
