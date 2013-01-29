<h1>Identificación de usuario</h1>
<form method='post' action="?menu=usuarios&submenu=validar_form_login" >
	Login: <input id='login' name='login' type='text' size='30' value='Escribe tu loguin aquí'/>
	<br />
	Contraseña: <input id='contrasena' name='contrasena' type='password' size='30' value=''/>
	<br />
	<?php
		if (isset($datos['errores']['validacion']))
			echo "<span style='color: red;'>{$datos['errores']['validacion']}</span><br />";
	?>
	<input type='submit' value='enviar'>
</form>