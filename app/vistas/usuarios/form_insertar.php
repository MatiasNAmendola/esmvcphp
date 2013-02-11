<div >
	<h2>Alta de un nuevo usuario</h2>
<form method='post' action="?menu=usuarios&submenu=validar_form_insertar" >
	<input id='id'  name='id' type='hidden' value='<?php echo \core\Array_Datos::values('id', $datos); ?>' />
	Login: <input id='login' name='login' type='text' size='30'  maxlength='30' value='<?php echo \core\Array_Datos::values("login", $datos); ?>'/>
	<br />
	<?php 
	// (isset($datos['values']['login']) ? $datos['values']['login']:''); 
		if (isset($datos['errores']['login']))
			echo "<span style='color: red;'>{$datos['errores']['login']}</span>"; 
	?>
	<br />
	email: <input id='email' name='email' type='text' size='100' maxlength='100' value='<?php echo (isset($datos['values']['email']) ? $datos['values']['email']:''); ?>'/>
	<br />
	<?php 
		if (isset($datos['errores']['email']))
			echo "<span style='color: red;'>{$datos['errores']['email']}</span>"; 
	?>
	<br />
	Contraseña: <input id='password' name='password' type='password' size='30'  maxlength='30' value='<?php echo (isset($datos['values']['password']) ? $datos['values']['password']:''); ?>'/>
	<br />
	<?php 
		if (isset($datos['errores']['password']))
			echo "<span style='color: red;'>{$datos['errores']['password']}</span>"; 
	?>
	<br />
	<?php
		if (isset($datos['error_validacion']))
			echo "<span style='color: red;'>{$datos['error_validacion']}</span><br />";
	?>
	<input type='submit' value='Enviar'>
	<input type='reset' value='Limpiar'>
	<button type='button' onclick='location.assign("?menu=usuarios&submenu=index");'>Cancelar</button>
</form>
</div>