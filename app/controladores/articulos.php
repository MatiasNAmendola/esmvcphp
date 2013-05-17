<?php
namespace controladores;

class articulos extends \core\Controlador {

	
	
	/**
	 * Presenta una <table> con las filas de la tabla con igual nombre que la clase.
	 * @param array $datos
	 */
	public function index(array $datos=array()) {
		
		$clausulas['order_by'] = 'nombre';
		$datos["filas"] = \datos\Datos_SQL::select('articulos', $clausulas); // Recupera todas las filas ordenadas
		$datos = array_merge($datos, $this->datos);
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
		
	}
	
	
	public function form_insertar(array $datos=array()) {
		
		$clausulas['order_by'] = " nombre ";
		$datos['categorias'] = \datos\Datos_SQL::select('categorias', $clausulas);
		$datos = array_merge($datos, $this->datos);
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
		
	}

	public function validar_form_insertar(array $datos=array())
	{	
		$validaciones=array(
			 "nombre" =>"errores_requerido && errores_texto && errores_unicidad_insertar:nombre/articulos/nombre"
			, "precio" => "errores_precio"
			, "unidades_stock" => "errores_precio"
			, 'categoria_nombre' => 'errores_requerido && errores_refernecia:categoria_nombre/categorias/nombre'
		);
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos))
            $datos["errores"]["errores_validacion"]="Corrige los errores.";
		else {
			$datos['values']['precio'] = \core\Conversiones::decimal_coma_a_punto($datos['values']['precio']);
			$datos['values']['unidades_sctock'] = \core\Conversiones::decimal_coma_a_punto($datos['values']['unidades_stock']);
			if ( ! $validacion = \datos\Datos_SQL::insert($datos["values"], 'articulos')) // Devuelve true o false
				$datos["errores"]["errores_validacion"]="No se han podido grabar los datos en la bd.";
		}
		if ( ! $validacion) //Devolvemos el formulario para que lo intente corregir de nuevo
			$this->form_insertar($datos);
		else
		{
			// Se ha grabado la modificación. Devolvemos el control al la situacion anterior a la petición del form_modificar
			$datos = array("alerta" => "Se han grabado correctamente los detalles");
			// Definir el controlador que responderá después de la inserción
			$this->index($datos);		
		}
	}

	
	
	public function form_modificar(array $datos=array()) {
		
		if ( ! count($datos)) { // Si no es un reenvío desde una validación fallida
			$validaciones=array(
				"id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/articulos/id"
			);
			if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
				$datos['mensaje'] = 'Datos erróneos para identificar el artículo a modificar';
				$this->cargar_controlador('mensajes', '', $datos);
				return;
			}
			else {
				$clausulas['where'] = " id = {$datos['values']['id']} ";
				if ( ! $filas = \datos\Datos_SQL::select('articulos', $clausulas)) {
					$datos['mensaje'] = 'Error al recuperar la fila de la base de datos';
					$this->cargar_controlador('mensajes', '', $datos);
					return;
				}
				else {
					$datos['values'] = $filas[0];
					$datos['values']['precio'] = \core\Conversiones::decimal_punto_a_coma_y_miles($datos['values']['precio']);
					$datos['values']['unidades_stock'] = \core\Conversiones::decimal_punto_a_coma_y_miles($datos['values']['unidades_stock']);
					
					$clausulas = array('order_by' => " nombre ");
					$datos['categorias'] = \datos\Datos_SQL::select('categorias', $clausulas);
				}
			}
		}
		
		$datos = array_merge($datos, $this->datos);
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}

	public function validar_form_modificar(array $datos=array())
	{	
		$validaciones=array(
			 "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/articulos/id"
			, "nombre" =>"errores_requerido && errores_texto && errores_unicidad_modificar:id,nombre/articulos/nombre,id"
			, "precio" => "errores_precio"
			, "unidades_stock" => "errores_precio"
			, 'categoria_nombre' => 'errores_requerido && errores_refernecia:categoria_nombre/categorias/nombre'
		);
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			print_r($datos);
            $datos["errores"]["errores_validacion"] = "Corrige los errores.";
		}
		else {
			$datos['values']['precio'] = \core\Conversiones::decimal_coma_a_punto($datos['values']['precio']);
			$datos['values']['unidades_stock'] = \core\Conversiones::decimal_coma_a_punto($datos['values']['unidades_stock']);
			if ( ! $validacion = \datos\Datos_SQL::update($datos["values"], 'articulos')) // Devuelve true o false
				$datos["errores"]["errores_validacion"]="No se han podido grabar los datos en la bd.";
		}
		if ( ! $validacion) //Devolvemos el formulario para que lo intente corregir de nuevo
			$this->form_modificar($datos);
		else
		{
			$datos = array("alerta" => "Se han modificado correctamente.");
			// Definir el controlador que responderá después de la inserción
			$this->index($datos);		
		}
	}

	
	
	public function form_borrar(array $datos=array()) {
		
		$validaciones=array(
			"id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/articulos/id"
		);
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$datos['mensaje'] = 'Datos erróneos para identificar el artículo a borrar';
			$datos['url_continuar'] = \core\URL::http('?menu=articulos');
			$this->cargar_controlador('mensajes', '', $datos);
			return;
		}
		else {
			$clausulas['where'] = " id = {$datos['values']['id']} ";
			if ( ! $filas = \datos\Datos_SQL::select('articulos', $clausulas)) {
				$datos['mensaje'] = 'Error al recuperar la fila de la base de datos';
				$this->cargar_controlador('mensajes', '', $datos);
				return;
			}
			else {
				$datos['values'] = $filas[0];
				$datos['values']['precio'] = \core\Conversiones::decimal_punto_a_coma_y_miles($datos['values']['precio']);
				$datos['values']['unidades_stock'] = \core\Conversiones::decimal_punto_a_coma_y_miles($datos['values']['unidades_stock']);
				$clausulas = array('order_by' => " nombre ");
				$datos['categorias'] = \datos\Datos_SQL::select('categorias', $clausulas);
			}
		}
		$datos = array_merge($datos, $this->datos);
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}

	
	
	
	
	
	
	
	
	
	
	public function validar_form_borrar(array $datos=array()) {	
		$validaciones=array(
			 "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/articulos/id"
		);
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$datos['mensaje'] = 'Datos erróneos para identificar el artículo a borrar';
			$datos['url_continuar'] = \core\URL::http('?menu=articulos');
			$this->cargar_controlador('mensajes', '', $datos);
			return;
		}
		else
		{
			if ( ! $validacion = \datos\Datos_SQL::delete($datos["values"], 'articulos')) {// Devuelve true o false
				$datos['mensaje'] = 'Error al borrar en la bd';
				$datos['url_continuar'] = \core\URL::http('?menu=articulos');
				$this->cargar_controlador('mensajes', '', $datos);
				return;
			}
			else
			{
			$datos = array("alerta" => "Se borrado correctamente.");
			$this->index($datos);		
			}
		}
	}
	
	
	public function listado_pdf(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \datos\Datos_SQL::select('articulos', $select);		
		
		$datos['html_para_pdf'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		require_once(PATH_APP."lib/php/dompdf/dompdf_config.inc.php");

		$html =
		  '<html><body>'.
		  '<p>Put your html here, or generate it with your favourite '.
		  'templating system.</p>'.
		  '</body></html>';

		$dompdf = new \DOMPDF();
		$dompdf->load_html($datos['html_para_pdf']);
		$dompdf->render();
		$dompdf->stream("sample.pdf", array("Attachment" => 0));
		
		// \core\Respuesta::cambiar_tipo_mime('application/pdf');
		// \core\Respuesta::enviar($datos, 'plantilla_pdf');
		
	}
	

	/**
	 * Genera una respuesta json.
	 * 
	 * @param array $datos
	 */
	public function listado_js(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \datos\Datos_SQL::select('articulos', $select);
				
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		\core\Respuesta::cambiar_tipo_mime('text/json');
		\core\Respuesta::enviar($datos, 'plantilla_json');
		
	}
	
	/**
	 * Genera una respuesta json con un array que contiene objetos, siendo cada objeto una fila.
	 * @param array $datos
	 */
	public function listado_js_array(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \datos\Datos_SQL::select('articulos', $select);
				
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		\core\Respuesta::cambiar_tipo_mime('text/json');
		\core\Respuesta::enviar($datos, 'plantilla_json');
		
	}
	
	
	/**
	 * Genera una respuesta xml.
	 * 
	 * @param array $datos
	 */
	public function listado_xml(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($_datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$_datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \datos\Datos_SQL::select('articulos', $select);
				
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		\core\Respuesta::cambiar_tipo_mime('text/xml');
		\core\Respuesta::enviar($datos, 'plantilla_xml');
		
	}
	
	
	
	
	/**
	 * Genera una respuesta excel.
	 * @param array $datos
	 */
	public function listado_xls(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($_datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$_datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \datos\Datos_SQL::select('articulos', $select);
				
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		\core\Respuesta::cambiar_tipo_mime('application/excel');
		\core\Respuesta::enviar($datos, 'plantilla_xls');
		
	}
	
	
} // Fin de la clase