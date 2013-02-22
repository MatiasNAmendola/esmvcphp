<?php // print_r($datos); ?>
<h1>Subir una foto</h1>
<form action="?menu=galeria_fotos&submenu=validar_form_subir" method="post"
enctype="multipart/form-data">
	<label for="file">Fichero con la foto:</label>
	<input type="file" name="file" id="file" value="<?php echo \core\Array_Datos::values('file', $datos)?>" onclick="document.getElementById('error_validacion').innerHTML = '';"><br>
	<?php echo "<b>".\core\HTML_Tag::span_error('validacion', $datos)."</b>"; echo "<br />"; ?>
	
	<input type="submit" name="submit" value="Subir">
	<input type="button" name="cancelar" value="Cancelar" onclick='location.assign("?menu=galeria_fotos");'>
</form>