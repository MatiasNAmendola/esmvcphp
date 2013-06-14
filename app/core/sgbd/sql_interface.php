<?php
namespace core\sgbd;


/**
 * Esta clase abstracta se debe extender por el programador para construir la clase que intercambie datos con el SGBD elegido (mysql, db2, oracle, etc).
 * 
 * 
 */
abstract class SQL_interface  {
	
	protected static $depuracion = false;
	public static $conexion;
	public static $prefix_;
	public static $tabla;
	public static $resultado;
	
	public function __construct($tabla = null)
	{
		if ( is_string($tabla) && strlen($tabla))
			self::$tabla = $tabla;
	}


	/**
	 * Inicia una conexión con el servidor de bases de datos, cuyos parámetros de configuración están en \core\configuracion.php
	 * <br />
	 * También debe cargar el la propiedad self::$prefix_ el valor del prefijo usado para los elementos guardados en la base de datos.
	 * 
	 * @return false|resource Devuelve false si fallo y un objeto si éxito. El retorno deverá quedar guardado en la propiedad self::$connexion
	 */
	abstract public static function conectar();
	
	
	/**
	 * Cierra la conexión con el SGBD.
	 */
	abstract public static function desconectar();
	
	
	/**
	 * Ejecuta la consulta SQL que se pasa en el parámetro $consulta.
	 * Se ejecuta sobre la conexión iniciada con el SGBD.
	 * El resultado de la ejecución (resource) se almacena en self::$resultado.
	 * 
	 * @param string $consulta Cadena con la consulta SQL
	 * @return boolean|resource
	 */
	abstract public static function ejecutar_consulta($consulta);
	
	
	/**
	 * Ejecuta la consulta que se pase como parámetro o si no se pasa, se supone que se ha ejecutado la consulta previamente.
	 * Recupera el resultado de la ejecución de la consulta de self::$resultado y a partír de ahí obtiene un array de índice entero, conteniendo en cada entrada otro array asociativo con los datos de cada una fila de las filas recuperadas por la ejecución de la consulta.
	 * Es solo válido para filas que devuelvan filas.
	 * 
	 * @param string $consulta Cadena con la consulta SQL
	 * @return fasle|array array()|array(0=>array('col1'=>val1, 'col2'=>val2, ...), ...) Devuleve false si hubo un error de ejecución de la consulta. Devuelve array vacío si no hay resultado.
	 */
	abstract public static function recuperar_filas($consulta = null);


	
	
	abstract public static function insert_row(array &$datos , $tabla);
	
	
	
	public static function insertar_fila(array &$datos, $tabla) {
	
		return self::insert_row($datos, $tabla);
		
	}
	
			
	abstract public static function update_row(array &$datos , $tabla);
	
	
	
	public static function modifica_fila(array &$datos, $tabla) {
	
		return self::update_row($datos, $tabla);
		
	}
	
	
	/**
	 * Borrar las filas de la tabla pasada como parámetro cuyas columnas sean los índices del array $dato['values'] y cuyo valor sea el conetenido en esas entradas;
	 * 
	 * @returns boolean True si éxito en ejecución, False si error de sintáxis.
	 */
	abstract public static function delete_row(array &$datos , $tabla = null);
	
	
	
	
	public static function borrar_fila(array &$datos, $tabla) {
	
		return self::delete($datos, $tabla);
		
	}
	
	
	
	/**
	 * Inserta una fila en una tabla.
	 * 
	 * @param type $tabla
	 * @param array $clausulas
	 * @return type
	 */
	abstract public static function select(
			array $clausulas = array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => '',
			),
			$tabla = null
	);
	
	
	public static function recuperar(
			array $clausulas = array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => '',
			),
			$tabla = null
	) {
		return self::select($clausulas, $tabla);
	}
	
	
	
	abstract public static function last_insert_id() ;
	
} // Fin de la clase