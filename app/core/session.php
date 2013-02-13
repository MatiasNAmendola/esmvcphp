<?php
namespace core;

class SESSION {
	
	public static function iniciar() {
		
		session_start();
		
	}
	
	public static function destruir() {
		
		session_destroy();
		
	}
	
	public static function regenerar_id() {
		
		session_regenerate_id(true);
		
	}
}