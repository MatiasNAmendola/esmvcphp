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
	
		$this->js_script_tag('recursos/js/galeria_fotos.js', $datos);
		$this->css_link_tag('recursos/css/galeria_fotos.css', $datos);
		
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

				if (file_exists(PATH_ROOT."recursos/imagenes/galeria_fotos/" . $_FILES["file"]["name"])) {
					$datos['errores']['validacion'] = $_FILES["file"]["name"] . " already exists. ";
					$validacion = false;
				}
				else {
					move_uploaded_file(
						$_FILES["file"]["tmp_name"],
						PATH_ROOT."recursos/imagenes/galeria_fotos/" . $_FILES["file"]["name"]
					);
					//$datos['alerta'] = "Stored in: " .PATH_ROOT."recursos/imagenes/" . $_FILES["file"]["name"];
					$datos['alerta'] = "Subida satisfactoria.";
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

	
	
	
	
	
	public function form_borrar(array $datos=array()) {
		
		// No se utiliza este método pues se ha programado en javascript en la vista index.php de este controlador.
		
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
	
	
	
	

} // Fin de la clase
?>