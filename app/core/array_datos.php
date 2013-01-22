<?php
namespace core;

class Array_Datos
{
	
	
	public static function values($indice , $datos )
	{
		return ( (array_key_exists('values', $datos) and array_key_exists($indice, $datos['values'])) ? $datos['values'][$indice] : null);		
	}
	
	
	
	public static function errores($indice , $datos )
	{
		return ( (array_key_exists('errores', $datos) and array_key_exists($indice, $datos['errores'])) ? $datos['errores'][$indice] : null);		
	}
	
	
	
}
