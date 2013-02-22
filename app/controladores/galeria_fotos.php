<?php
namespace controladores;

class galeria_fotos extends \core\Controlador {

	
	
	/**
	 * Presenta una <table> con las filas de la tabla con igual nombre que la clase.
	 * @param array $datos
	 */
	public function index(array $datos=array()) {
		
		$datos["fotos"] = array();
		
		//Open images directory
		$dir = opendir(PATH_ROOT."recursos/imagenes/galeria_fotos");

		//List files in images directory
		while (($file = readdir($dir)) !== false)
		  {
			if ($file != '.' and $file != '..')
				array_push($datos['fotos'], $file);
		  }
		closedir($dir);
	
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
		
	}
	
	
	
	
	
	
	public function form_subir(array $datos=array()) {
		
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
		
	}

	
	
	
	public function validar_form_subir(array $datos=array())
	{	
		$validacion = true;
		$allowedExts = array("jpg", "jpeg", "gif", "png"); // Extensiones válidas
		$partes_nombre = explode(".", $_FILES["file"]["name"]);
		$extension = end($partes_nombre);
		if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/png")
			|| ($_FILES["file"]["type"] == "image/pjpeg"))
			&& ($_FILES["file"]["size"] < 1024*1024 ) // Tamaño menor que 1 mega
			&& in_array($extension, $allowedExts)
		) {
			if ($_FILES["file"]["error"] > 0)
			{
				$validacion = false;
				$datos['errores']['validacion'] = "Errore en la subida. Return Code: " . $_FILES["file"]["error"];
			}
			else {
				//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				//echo "Type: " . $_FILES["file"]["type"] . "<br>";
				//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

				if (file_exists(PATH_ROOT."recursos/imagenes/" . $_FILES["file"]["name"])) {
					$datos['errores']['validacion'] = $_FILES["file"]["name"] . " already exists. ";
					$validacion = false;
				}
				else {
					move_uploaded_file(
						$_FILES["file"]["tmp_name"],
						PATH_ROOT."recursos/imagenes/" . $_FILES["file"]["name"]
					);
					//$datos['alerta'] = "Stored in: " .PATH_ROOT."recursos/imagenes/" . $_FILES["file"]["name"];
					$datos['alerta'] = "Stored succefuly";
				}
			}
		}
		else {
			$datos['errores']['validacion'] = "Invalid file";
			$validacion = false;
		}
		
		if ( ! $validacion) { //Devolvemos el formulario para que lo intente corregir de nuevo
			$datos['values']['file'] = $_FILES["file"]["name"];
			print_r($datos);
			
			$this->cargar_controlador('galeria_fotos', 'form_subir', $datos);
		}
		else
		{
			// Definir el controlador que responderá después de la inserción
			print_r($datos);
			$this->cargar_controlador('galeria_fotos', 'index', $datos);
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
				}
			}
		}
		print_r($datos);
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
			}
		}
		
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}

	public function validar_form_borrar(array $datos=array())
	{	
		$validaciones=array(
			 "fichero_foto" => "errores_requerido && errores_texto"
		);
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$datos['mensaje'] = 'Obligatorio aportar nombre del fichero de la foto.';
			$datos['url_continuar'] = \core\URL::http('?menu=articulos');
			$this->cargar_controlador('mensajes', '', $datos);
			return;
		}
		else {
			$fichero_foto_path = PATH_ROOT."recursos/imagenes/galeria_fotos/{$datos['values']['fichero_foto']}";
			if (is_file($fichero_foto_path)) {
				unlink($fichero_foto_path);
				$datos = array("alerta" => "Se borrado correctamente el fichero {$datos['values']['fichero_foto']}.");
			}
			else {
				$datos = array("alerta" => "El fichero {$datos['values']['fichero_foto']} no existe.");
			}
			$this->index($datos);		
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
	

} // Fin de la clase
?>