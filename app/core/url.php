<?php
namespace core;


class URL {
	
	public static function http($query_string) {
		
		$carpeta = str_replace('index.php', '',$_SERVER['SCRIPT_NAME']);
		return "http://{$_SERVER['HTTP_HOST']}$carpeta$query_string";
		
	}
	
}