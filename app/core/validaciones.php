<?php
namespace core;

/**
 * Validaciones para contenidos de parámetros enviados por el cliente mediante POST o GET, que serán los contenidos de columnas que se van a insertar en la base de datos.
 * 
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Validaciones  {

	protected static $depuracion = false;

	/**
	 * Valida el número aleatorio que identifica a un formulario y el método por el que se espera, y opcionalmente el id de la fila que se envió
	 * sea recibido
	 * @param string $form_id Id del formulario o __FUNCTION__. Si __FUNCTION__ contiene 'validar_' es reemplazado por ''.
	 * @return false|array false|array("numero"=>"Descripción error", "metodo"=>"Descripción error")
	 */
	
	public static function request($parametro)
	{
		return self::evitar_inyeccion_sql(array_key_exists($parametro, $_REQUEST) ? $_REQUEST[$parametro] : null);
	}
	


	/**
	 * Valida los datos que se indiquen en $parametros a partir de las funciones de validación.
	 * Los datos son tomados del $_REQUEST que está almacenado en el objeto $this->aplicacion->request.
	 * Un parámetro del request puede ser validado por más de una función, por lo que se podrá incorporar al array
	 * de parámetros una o más veces.<br />
	 * La función devuelve un array con dos índices:
	 * <li><ul>$array[valores][entrada]=>"valor" que contiene para cada entrada validada el valor recuperado del REQUEST</ul>
	 * <ul>$array[errores][entrada]=>"mensaje" que contiene las entradas para las que se han encontrado mensajes de error</ul>
	 * </li>
	 * Se considera la validación correcta si el array retornado está vacío en la clave errores.<br />
	 * 
	 * Formatos para <b>funcion_validacion</b> podran ser:
	 * <ul><li>funcion_validacion</li>
	 *		<li>funcion_validacion_lista:(opcion1,opcion2, ..)</li>
	 * 		<li>funcion_validacion_lista:(opcion1;opcion2; ..) si alguna de las opciones incluye ,</li>
	 *		<li>funcion_validacion:request_index1, request_index2,.../nombre_tabla_referencia/columna1, columna2, .... Este formato se empleará para errores_unicidad y para errores_referencia</li>
	 * </ul>
	 * Si se aporta el array $datos, como parámetro, entonces la función incorpora en dicho array los elementos [values] y [errores], y retorna false si no hay errores y true si los hay. Si no está este parámetro la función devuelve un array con los dos índices [values] y [errores].
	 * @param array $parametros =array("parametro1=>funcion_validacion_a","parametro1=>funcion_validacion_b","parametro2=>funcion_validacion",...)
	 * @param array $datos (por referencia) Si se aporta sobre el se devolverá el array de return y el return será count($datos[errores])
	 * @return array("values"=>array("parametro"=>valor,...),"errores"=>array("parametro"=>"mesaje&nbsp;de&nbsp;errore",...)
	 */
	public static function errores_validacion_request(array $validaciones, array &$datos=null) {
		$resultados_validacion = array("values" => array(), "errores" => array()); // Array para guardar la validación
		// Tratamos cada una de las validaciones del array $validaciones
		foreach ($validaciones as $columna => $validacion)
		{
			if (is_string($columna))
				$validacion = $columna."=>".$validacion;
			
			$validacion = str_replace(' ', '', $validacion); // Quitamos espacios en blanco
			
			if ( ! preg_match("/=>/", $validacion)) {
				$validacion.="=>".(isset(self::$validacion_por_defecto[$validacion])?self::$validacion_por_defecto[$validacion]:'errores_texto');
			}
			$partes = explode('=>', $validacion);
			$parametro = $partes[0];
			$validadores=$partes[1];
			$secuencia_validador = explode('&&', $validadores);
			foreach ($secuencia_validador as $validador)
			{
				if (preg_match("/:\(/", $validador)) { // Validacion de lista
					$validador_partes=explode(":", $validador);
					$validador=$validador_partes[0];
					$validador_partes[1]=str_replace(array('(', ')'), '', $validador_partes[1]);
					if (preg_match("/;/",$validador_partes[1] ))
						$opciones=explode(";",$validador_partes[1]);
					else
						$opciones=explode(",",$validador_partes[1]);
					$resultados_validacion["values"][$parametro] = self::request($parametro); // La función parametro($parametro) devuelve un valor o null
					$resultado_validador=self::$validador($resultados_validacion["values"][$parametro], $opciones);
				}
				elseif (preg_match("/referencia|unicidad/", $validador)) { // Validación de unicidad o referencia
					$validador_partes = explode(":", $validador);
					$validador = $validador_partes[0];
					$parametros_partes = explode("/",$validador_partes[1]);
					$request_indices = explode(",",$parametros_partes[0]);
					$tabla = $parametros_partes[1];
					$tabla_columnas = explode(",",$parametros_partes[2]);

					$valores = array();
					foreach ($request_indices as $parametro) {
						$resultados_validacion["values"][$parametro] = self::request($parametro); // La función parametro($parametro) devuelve un valor o null
						array_push($valores,$resultados_validacion["values"][$parametro]);
					}
					
					if (preg_match("/_modificar/i", $validador))
					{
						if ($request_indices[0] != 'id')
							throw new \Exception(__METHOD__." Line: ".__LINE__." <b>Error: parametro $parametro, validador $validador debe contener el la columna id.</b>");
						$parametro = $request_indices[1]; // Pues el índice 0 es para la primary key que será la columna id
					}
					else
						$parametro = $request_indices[0];

					if (self::$depuracion) {
						print_r($valores); print("/$tabla/ ");print_r($tabla_columnas);print("<br />");
					}
					$resultado_validador=self::$validador($valores, $tabla, $tabla_columnas);
				}
				elseif (preg_match("/longitud/", $validador)) { // Validación de longitud
					$validador_partes=explode(":", $validador);
					$validador=$validador_partes[0];
					if ( ! isset($validador_partes[1]))
						throw new \Exception(__METHOD__." Error: errores_$validador debe indicar como mínimo la logitud mínima.");
					$rangos=explode(",",$validador_partes[1]);
					
					$resultados_validacion["values"][$parametro] = self::request($parametro);
					
					$resultado_validador=self::$validador($resultados_validacion["values"][$parametro], $rangos);
				}
				else {
					// Validación de tipo de datos
					$resultados_validacion["values"][$parametro] =  self::request($parametro);
					$resultado_validador=self::$validador($resultados_validacion["values"][$parametro]);
				}
				if ($resultado_validador) {// La variable $validador contienen el nombre de un método de validación o validador
					if (isset($resultados_validacion["errores"][$parametro]))
						$resultados_validacion["errores"][$parametro].=" --php: $validador: ".$resultado_validador;
					else
						$resultados_validacion["errores"][$parametro]=" --php: $validador:  ".$resultado_validador;
				}
			}
		}
		
		if (self::$depuracion) {
			echo "<br />\$resultados_validacion=";
			print_r($resultados_validacion);
			echo "<!-- ";
		}
		if (is_array($datos)) { // Si se aporta $datos y es array
			$datos["values"] = $resultados_validacion["values"];
			$datos["errores"] = $resultados_validacion["errores"];
			return(count($datos["errores"]));
		}			
		else
			return $resultados_validacion;
	}
	

	/**
	 * Aplica técnicas para evitar la inyección de SQL malicioso
	 * @param array $datos = array("columna"=>valor, ....)
	 * @return array $datos = array("columna"=>valor, ....) el mismo array que se pasó como parámetro
	 */
	public static function evitar_inyeccion_sql($input)
	{
		$input = mysql_real_escape_string($input);
		// si $datos[$key]==false hay error en la aplicación de los caracteres de escape
		preg_replace (
			array('/insert/i' , '/select/i' ,'/update/i' ,'/delete/i' ,'/script/i' ,'/truncate/i','/union/i'  )
			,array('/insert_/i', '/select_/i','/update_/i','/delete_/i','/script_/i','/truncate_/i','/union_/i' )
			,$input
		);
		return $input;
	}

	/**
	 *  Valida que la cadena no esté vacía
	 *  @autor jequeto
	*/
	public static function errores_requerido($cadena) {
		$msj_error=""; // Ningún error de validación
		if ($cadena==null || strlen($cadena)<1) {
		  $msj_error = "Esta entrada es obligatoria, no puede quedarse vacía.";
		}
		if ($msj_error=="") $msj_error=false;
		return $msj_error;
	}



	/**
	 * Texto: cadena con letras (\w), espacios (\s)  , . ; : - /
	 * @autor jequeto
	 */
	public static function errores_texto($cadena=null) {
		$mensaje="";
		if ($cadena !=null && ! strlen($cadena)) {
			$mensaje = "-php- Debe ser una cadena con un carácter como mínimo.";
		}
		return $mensaje;
	}
	
	public static function errores_longitud($cadena=null, array $rango)
	{
		$mensaje="";
		if ($cadena != null)
			if ( ! isset($rango[1]) && strlen($cadena) != (integer)$rango[0])
				$mensaje = "-php- Debe ser una cadena de longitud {$rango[0]}.";
			elseif (strlen($cadena) < (integer)$rango[0] or strlen($cadena) > (integer)$rango[1])
				$mensaje = "-php- Debe ser una cadena de longitud entre {$rango[0]} y {$rango[1]}.";
		return $mensaje;
	}

	/**
	 * Un login_valido
	 * Entre 4 y 20 caracteres
	 * letras y números, sin números al principio
	 * @param string $cadena
	 */
	public static function errores_login($cadena=null) {
		$mensaje="";
		if ($cadena != null || strlen($cadena)>0 ) {
			$patron = '/^[a-z]{1}\w{3,19}$/i';
			if ( ! preg_match($patron, $cadena)) {
				$mensaje.="Contiene carcateres no válidos. Sólo se admiten letras y números, y no puede comenzar por número, su longitud es menor de 4 o mayor de 20 caracteres. ";
			}
		}
		return $mensaje;
	}

	/**
	 * Una constraseña válida
	 * Entre 6 y 20 letras y números
	 * debe contener como mínimo 2 números.
	 *
	 * @param string $cadena
	 * @return boolean|string
	 */
	public static function errores_contrasena($cadena=null) {
		$mensaje="";
		if ($cadena!=null) {
			$patron = '/^\w{6,20}$/i';
			$encuentros=array();
			if ( ! preg_match($patron, $cadena)) {
				$mensaje.="Contiene carcteres no válidos. Solo se admiten letras y números o su longitud no está entre 6 y 20 caracteres . ";
			}
			else {
				$patron = '/\d/i';
				if (preg_match_all($patron, $cadena, $encuentros) < 2) 
					$mensaje .= "Como mínimo debe haber dos números";	
				$patron = '/[a-z]/i';
				if (preg_match_all($patron, $cadena, $encuentros) < 2 ) 
					$mensaje .= "Como mínimo debe haber dos letras";	
			}
		}
		return $mensaje;
	}

	
	
	
	public static function errores_email($cadena) {
		$mensaje="";
		if($cadena!=null) {
			$patron= '/^([a-z]{1}[a-z\d]{0,})(([\.|\_\-]{1}[a-z\d]{1,}){1,}){0,}@([a-z]{1}[a-z\d]{0,})(([\.|\_\-]{1}[a-z\d]{1,}){1,}){0,}(\.[a-z]{2,4})$/i';
			$encontrados=array();
			if(preg_match($patron, $cadena, $encontrados)) { // Hay encuentros
				if (self::$depuracion) {print("encuentros: "); print_r($encontrados);}
				if ($cadena!=$encontrados[0])
					$mensaje.="El email es incorrecto. Formato cuenta@servidor.net ";
			}
			else {
				if (self::$depuracion) {print("encuentros: "); print_r($encontrados);}
				$mensaje.="El email es incorrecto. Formato cuenta@servidor.net ";
			}
		}
		if ($mensaje=="") $mensaje=false;
		return $mensaje;

	} // Fin método


	
	
		/**
	 * Valida un precio en formato europeo, con el . como separador de miles y la , como separador decimal, máximo 2 decimales. También da por válid un número sin separador de miles.
	 * Ejemplo 10.001,26 10001,26
	 * 
	 * @param string $cadena
	 * @return boolean|string
	 */
	public static function errores_precio($cadena=null) {
		
		$mensaje_error=false; // Optimista
		
		if ( ! is_null($cadena) && strlen($cadena)) {
			$patron="/^((\d{1,3}(\.\d{3}){0,}|\d{1,})(\,\d{1,2}){0,1})$/i";
			if( ! preg_match($patron, $cadena))
				$mensaje_error="-php- Error: El número puede escribirse con separador de miles(.) y coma decimal(,) y máximo dos decimales";
		}
		return $mensaje_error;		
	}

	/**
	 * Valida un número de teléfono compuesto por 9 dígitos numéricos, que pueden estar separados por espacios.
	 * Debe empezar por 9, 6 o 7, y no tener una longitud superior a 17 caracteres entre números y espacios.
	 * 
	 * @param string $cadena
	 * @return string
	 */
	public static function errores_telefono($cadena=null) {
		$mensaje_error=""; // Es equivalente a false
		$encuentros=array();
		if (!is_null($cadena) && strlen($cadena)) {	
			
			if (preg_match_all("/\d/",$cadena,$encuentros)!=9) {
				$mensaje_error="El telefono debe tener 9 números y puede contener espacios opcionales.";
			}
			/*
			elseif (preg_match("/[^\d\s]/",$cadena)) {
				$mensaje_error="Solo se admiten espacios para separar los números, ejemplo: 91 331 45 03";
			}
			elseif (strlen($cadena)>17) {
				$mensaje_error="La longitud del teléfono solo puede ser de 17 caracteres con 9 números y espacios";
			}
			*/
			elseif (!preg_match("/^[9|7|6]{1}[\d\s]{8,16}$/",$cadena)) {
				$mensaje_error="El número debe empezar por 9, 7 ó 6, debe contener 9 dígitos y la longitud del teléfono solo puede ser como máximo de 17 caracteres con 9 números y espacios";
			}
				
		}
		return $mensaje_error;
	}

	
	
	
	/**
	 * Comprueba que los valores pasados no existen en las columnas de la tabla de referencia
	 * @param array $valores array de índice numérico
	 * @param type $tabla
	 * @param array $columnas  array de índice numérico
	 */
	public static function errores_unicidad(array $valores, $tabla, array $columnas) {
		return self::errores_unicidad_insertar($valores, $tabla, $columnas);
	}

	 /**
	 * Comprueba que los valores pasados no existen en otra fila de la tabla de referencia
	 * El primer valor debe ser no nulo.
	 * @param array $valores array de índice numérico
	 * @param type $tabla
	 * @param array $columnas  array de índice numérico
	 */
	public static function errores_unicidad_insertar(array $valores, $tabla, array $columnas) {
		$msj_error="";
		$no_null=0;
		for ($i=0; $i<count($valores); $i++) {
			if (strlen($valores[$i])>0)
				$no_null++;
		}
		// Si hay por lo menos un valor no nulo
		if ($no_null) {
			$valores_aportados="";

			$parametros["where"]="";
			for ($i=0; $i<count($columnas); $i++) {
				if (is_null($valores[$i]) || $valores[$i]=="" || strlen($valores[$i])==0) {
					$parametros["where"].="{$columnas[$i]} is null";
					$valores_aportados.="{$columnas[$i]}= ";
				}
				else {
					$parametros["where"].="{$columnas[$i]}=".((is_numeric($valores[$i]))?$valores[$i]:"'{$valores[$i]}'");
					$valores_aportados.="{$columnas[$i]}=".$valores[$i];
				}
				if ($i<(count($columnas))-1) {
					$parametros["where"].=" and ";
					$valores_aportados.=" , ";
				}
			}
			$filas = \datos\Datos_SQL::select($tabla, $parametros);
			if ($filas && count($filas))
				$msj_error.="El/Los valor/es aportado/s <b>[ $valores_aportados ]</b> ya existe/n en la base de datos. Escribe un valor no existente.";
		}
		if ($msj_error=="")
			$msj_error=false;
		return $msj_error;
	}

	/**
	 * Comprueba que los valores pasados no existen en las filas de la tabla de referencia
	 * en otras filas distintas de la fila que se está modificando,
	 * de la cual se debe aportar la primary key, que es el primer valor y la primera columna
	 * @param array $valores array(val_id, val_col1 , val_col2, ..) array de índice numérico
	 * @param type $tabla
	 * @param array $columnas  array('id', 'col1' , 'col2', ..) array de índice numérico
	 * @return false|string
	 */
	public static function errores_unicidad_modificar(array $valores, $tabla, array $columnas) {
		$msj_error="";
		$no_null=0;
		for ($i=0; $i<count($valores); $i++) {
			if (strlen($valores[$i])>0)
				$no_null++;
		}
		// El primer valor es el id de la fila que se modifica
		if ($valores[0] != null && $no_null>1) {
			$valores_aportados = "";
			
			$parametros["where"]="$columnas[0] != $valores[0] and (";
			for ($i=1; $i<count($columnas); $i++) {
				if (is_null($valores[$i]) || $valores[$i]=="" || strlen($valores[$i])==0) {
					$parametros["where"].="{$columnas[$i]} is null";
					$valores_aportados.="{$columnas[$i]}= ";
				}
				else {
					$parametros["where"].="{$columnas[$i]}=".((is_numeric($valores[$i]))?$valores[$i]:"'{$valores[$i]}'");
					$valores_aportados.="{$columnas[$i]}=".$valores[$i];
				}
				if ($i<(count($columnas))-1) {
					$parametros["where"].=" and ";
					$valores_aportados.=" , ";
				}
			}
			$parametros["where"].=")";
			$filas = \datos\Datos_SQL::select($tabla, $parametros);
			if ($filas && count($filas))
				$msj_error.="El/Los valor/es aportado/s <b>[ $valores_aportados ]</b> ya existe/n en la base de datos en otra fila. Escribe un/os valor/es no existente/s.";
		}
		if ($msj_error=="")
			$msj_error=false;
		return $msj_error;
	}

	/**
	 * Comprueba la existencia de los valores pasados en las columnas de la tabla de referencia.
	 * El primer valor debe ser no nulo
	 * @param array $valores
	 * @param type $tabla
	 * @param array $columnas
	 * @return false|string
	 */
	public static function errores_referencia(array $valores, $tabla, array $columnas) {
		$msj_error="";
		$no_null=0;
		for ($i=0; $i<count($valores); $i++) {
			if (strlen($valores[$i])>0)
				$no_null++;
		}
		if (strlen($valores[0]) && $no_null) {
			$valores_aportados="";
			
			$parametros["where"]="";
			for ($i=0; $i<count($columnas); $i++) {
				if (is_null($valores[$i]) || $valores[$i]=="" || strlen($valores[$i])==0) {
					$parametros["where"].="{$columnas[$i]} is null";
					$valores_aportados.="{$columnas[$i]}= ";
				}
				else {
					$parametros["where"].=$columnas[$i]."=".((is_numeric($valores[$i]))?$valores[$i]:"'{$valores[$i]}'");
					$valores_aportados.="{$columnas[$i]}=".$valores[$i];
				}
				if ($i<(count($columnas))-1) {
					$parametros["where"].=" and ";
					$valores_aportados.=" , ";
				}
			}

			$filas = \datos\Datos_SQL::select($tabla, $parametros);
			if (!$filas || !count($filas)) {
				$msj_error.="El/Los valor/es aportado/s <b>[ $valores_aportados ]</b> no existe/n en la tabla de referencia [$tabla] en las columnas [{$parametros["where"]}] . Escribe un valor que sí exista.";
			}

			if (self::$depuracion) {
				print("<br />".__METHOD__.":"); print_r($valores); print_r($columnas); print_r($parametros); print_r($filas); print("c=".count($filas)); print("msj_error: $msj_error");
			}
		}
		if ($msj_error=="")
			$msj_error=false;
		return $msj_error;
	}


// TODO: Preparar array de validaciones por defecto
// El siguiente array recoje las validaciones por defecto para cada columna de tablas de base de datos cuyos datos pueden entrar a través de $_REQUEST
// Para cada entrada se define: "columna"=>"validador" // Tipo de datos sql de la columna en la tabla

	public static $validacion_por_defecto=array(
		
	);

	

} // Fin de la clase


?>