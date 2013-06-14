<?php

namespace datos;

class categorias {
	
	private static $tabla = 'categorias';
	
	
	public static function create_table() {
		
		$consulta = "
			create table ".self::$tabla."
			( id integer unsigned auto_increment primary key
			, categoria varchar(50) not null unique
			, descripcion varchar(500) null
			)
			engine = innodb;	
		";
		
		return mysqli_query($link, $consulta);
		
	}
	
	
	/**
	 * El parámetro fila es un array que trae detro en otro array asociado al índice 'values' los valores de las columnas a insertar.
	 * Si hay errores en el mismos array se devuelven dentro del índice 'errores'.
	 * @param array &$fila = array('values' =>array('col1' => valo1, ), 'errores' => array('col1' => 'error1', ))
	 * @return boolean
	 */
	public static function insertar(array &$fila ) {
		
		
		$validacion = true;
		if ( ! isset($fila['values']['categoria']) or ! is_string($fila['values']['categoria'])) {
			$validacion = false;
			$fila['errores']['categoria'] = "Esta columna no puede esta vacía y debe ser un string.";
		}
		if ( ! isset($fila['values']['descripcion']) ) {
			$fila['values']['descripcion'] = null;
		}
		elseif ( ! is_strin($fila['values']['descripcion'])) {
			$validacion = false;
			$fila['errores']['descripcon'] = "Esta columna debe ser un string.";
		}
		
		if ($validacion) {
		
			$consulta = "
				insert into ".self::$tabla."
					set categoria = '{$fila['values']['categoria']}',
						descripcion = '{$fila['values']['descripcion']}'
				;		
			";

			return mysqli_query($link, $consulta);
		}
		else {
			return false;
		}
	}
			
	 
	
	
	public static function borrar(array &$fila ) {
		
		
		$validacion = true;
		if ( ! isset($fila['values']['id']))  {
			$validacion = false;
			throw new \Exception(".....");
		}
		
		
		if ($validacion) {
		
			$consulta = "
				delete from ".self::$tabla."
					where categoria = '{$fila['values']['categoria']}' or id = {$fila['values']['id']}
				;		
			";

			return mysqli_query($link, $consulta);
		}
		else {
			return false;
		}
	}
		
	
}