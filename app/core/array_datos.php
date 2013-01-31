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
	
	/**
	 * Devuelve el contenido de una entrada del array que se pasa por parámetro.
	 * Si la entrada no existe devuelve null.
	 * 
	 * @param string|integer $indice
	 * @param array $array
	 * @return mixed
	 */
	public static function contenido($indice, $datos)
	{
		if ( ! is_string($indice) && ! is_integer($indice))
			throw new \Exception(__METHOD__." Error: parámetro \$indice=$indice debe ser entero o string");
		elseif ( !is_array($datos))
			throw new \Exception(__METHOD__." Error: parámetro \$datos debe ser un array");
		
		return (array_key_exists($indice, $datos) ? $datos[$indice] : null);
	}
	
}
