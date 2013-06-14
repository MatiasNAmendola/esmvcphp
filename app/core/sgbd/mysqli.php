<?php
namespace core\sgbd;

class mysqli {
	
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


	
	public static function conectar()
	{
		self::$conexion = mysqli_connect(\core\Configuracion::$mysql['server'], \core\Configuracion::$mysql['user'], \core\Configuracion::$mysql['password'],\core\Configuracion::$mysql['dbname']);
		if ( ! self::$conexion)
		{
			throw new \Exception(__METHOD__.' Mysql: Could not connect: ' );
		}
		
		self::$prefix_ = \core\Configuracion::$mysql['prefix_'];
		
		return self::$conexion;
		
	}
	
	
	
	public static function desconectar()
	{
		$ok = mysqli_close(self::$conexion);
	}
	
	
	
	public static function ejecutar_consulta($sql)
	{
		if (self::$depuracion) {echo __METHOD__." \$sql = $sql <br />";}
		
		self::$resultado = mysqli_query(self::$conexion,$sql);
		if ( self::$resultado === false)
			throw new \Exception(__METHOD__." Consulta= $sql Error = ".  mysqli_error(self::$conexion));
		return self::$resultado;
	}
	
	
	
	public static function recuperar_filas($sql = null)
	{
		if ($sql)
			self::ejecutar_consulta($sql);
		$filas = array();
		while ($fila = mysqli_fetch_assoc(self::$resultado))
			array_push($filas,$fila);
		mysqli_free_result(self::$resultado);
		return $filas;
	}


	
	public static function columnas_set(array $columnas)
	{
		$columnas_set=" ";
		$i=0;
		foreach ($columnas as $key => $value) {
			if ($value=='' || strlen($value)==0 || $value==null)
				$columnas_set .= "$key = default ";
			elseif (is_numeric($value))
				$columnas_set .= "$key = $value ";
			elseif (strtoupper($value) == 'DEFAULT')
				$columnas_set .= "$key = $value ";
			else // suponemos que es una cadena
				$columnas_set .= "$key = '$value' ";

			if ($i < count($columnas)-1)
				$columnas_set .= ", ";
			$i++;
		}
		return $columnas_set;
	}
	
	
	
	public static function insert($datos , $tabla)
	{
		if (isset($datos['id']))
			throw new \Exception(__METHOD__." Error: no pude insertarse la columna id.");
		
		$columnas_set = self::columnas_set($datos);
		
		$sql = "insert into	".self::$prefix_.$tabla."
			set $columnas_set
		;
		";
		
		return self::ejecutar_consulta($sql);
	}
	
	
	
	
	public static function insertar($datos, $tabla) {
	
		return $this->insert($datos, $tabla);
		
	}
	
	
	
		
	public static function update($datos , $tabla)
	{
		if ( ! isset($datos['id']))
			throw new \Exception(__METHOD__." Error: debe aportarse la id.");
		
		$columnas_set = self::columnas_set($datos);
		
		$sql = "
			update	".self::$prefix_.$tabla."
			set $columnas_set
			where id = {$datos['id']}
		;
		";
		
		return self::ejecutar_consulta($sql);
	}
	
	
	public static function modificar($datos, $tabla) {
	
		return $this->update($datos, $tabla);
		
	}
	
	
	
	public static function delete($datos , $tabla)
	{
		if ( ! isset($datos['id']))
			throw new \Exception(__METHOD__." Error: debe aportarse la id.");
		
		$sql = "
			delete
			from ".self::$prefix_.$tabla."
			where id = {$datos['id']}
			;
		";
		
		return self::ejecutar_consulta($sql);
	}
	
	
	public static function borrar($datos, $tabla) {
	
		return self::delete($datos, $tabla);
		
	}
	
	
	
	/**
	 * Inserta una fila en una tabla.
	 * 
	 * @param type $tabla
	 * @param array $clausulas
	 * @return type
	 */
	public static function select(
			$tabla,
			array $clausulas = array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => '',
			)
	)
	{
		$columnas = ((isset($clausulas['columnas']) and strlen($clausulas['columnas'])) ? $clausulas['columnas'] : '*');
		$where = ((isset($clausulas['where']) and strlen($clausulas['where'])) ? "where ".$clausulas['where'] : '');
		$order_by = ((isset($clausulas['order_by']) and strlen($clausulas['order_by'])) ? "order by ".$clausulas['order_by'] : '');	
		$group_by = ((isset($clausulas['group_by']) and strlen($clausulas['group_by'])) ? "group by ".$clausulas['group_by'] : '');
		$having = ((isset($clausulas['having']) and strlen($clausulas['having'])) ? "having ".$clausulas['having'] : '');
		
		$sql = "
				select $columnas
				from ".self::$prefix_.$tabla."
				$where
				$group_by
				$having
				$order_by
				;
		";
		
		return self::recuperar_filas($sql);
	}
	
	
	
	public static function recuperar(
			$tabla,
			array $clausulas = array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => '',
			)
	)
	{
		return $this->select($tabla, $clausulas);
	}
	
	
	
	public static function last_insert_id() {
		
		$sql = " select last_insert_id() as id;";
		
		$filas = self::recuperar_filas($sql);
		
		return $filas[0]['id'];
	}
	
	
} // Fin de la clase