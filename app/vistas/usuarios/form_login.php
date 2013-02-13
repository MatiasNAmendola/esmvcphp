<h1>Identificación de usuario</h1>
<form method='post' action="?menu=usuarios&submenu=validar_form_login" >
	Login: <input id='login' name='login' type='text' size='30' value='<?php echo (\core\Array_Datos::values('login', $datos) ? \core\Array_Datos::values('login', $datos) : 'Escribe tu login aquí'); ?>'/>
	<?php echo \core\HTML_Tag::span_error('login', $datos); ?>
	<br />
	Contraseña: <input id='contrasena' name='contrasena' type='password' size='30' value=''/>
	<?php echo \core\HTML_Tag::span_error('contrasena', $datos); ?>
	<br />
	<?php
		if (isset($datos['errores']['validacion']))
			echo "<span style='color: red;'>{$datos['errores']['validacion']}</span><br />";
	?>
	<input type='submit' value='enviar'>
</form>