<?php

namespace controladores;

class categorias {
	
	public function form_insertar(array $datos = array()) {
		
		// Envía el formulario de insertar
		?>
<form action="?m=categorias&sm=form_insertar_recibir">
	Categoría: <input name="categoria" value="<?php echo \core\Array_Datos::values('categoria', $datos); ?>"/>
	<?php echo \core\HTML_Tag::span_error('descricion', $datos); ?>
	<br />
	Categoría: <input name="descricion" value="<?php echo \core\Array_Datos::values('descricion', $datos); ?>"/>
	<?php echo \core\HTML_Tag::span_error('descricion', $datos); ?>
	<br />
	<?php echo \core\HTML_Tag::span_error('errores_validacion', $datos); ?>
	<br />
	<input type='submit' value='Enviar' />
	
</form>

		<?php
		
	}
	
	
	public function form_insertar_recibir( array $datos = array() ) {
		
		
		$fila = array(
			'values' => array(
				'categoria' => $_POST['categoria'],
				'descripcion' => $_POST['descripcion'],
			)
		);
		
		$fila['values'] = $_POST;
		
		if ( ! \datos\categorias::insertar($fila) ) {
			$fila['values']['errores_validacion'] = "Corrige los errores";
			$this->form_insertar($fila);
		}
		
		
	}
	
	
}