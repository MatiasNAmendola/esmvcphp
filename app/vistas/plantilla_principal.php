<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo TITULO; ?></title>
	</head>
	<body style='border: 1px solid; width: 995px; margin-left: auto; margin-right: auto;  padding: 5px;'>
		<div id='header-left' style='width: 50%; float: left;'>
			<h1>Aplicación my MVC</h1>
		</div>
		<div id='header-right' style='width: 50%; float: right; text-align: right;'>
			Usuario: <?php echo \core\Usuario::$login;
							if (\core\Usuario::$login != 'anonimo')
								echo " (<a href='?menu=usuarios&submenu=desconectar'>desconectar</a>)";
							if (isset($_SESSION['usuario']['contador_paginas_visitadas']))
								echo "<br />Páginas visitadas: [{$_SESSION['usuario']['contador_paginas_visitadas']}]";
					?>
		</div>
		<hr style='clear: both;'/>
		<div id="menu" style='border: 1px solid red;'>
			<a href='?'>Inicio</a>
			<a href='?menu=usuarios&submenu=index'>Usuarios</a>
			<?php 
				if (\core\Usuario::$login == 'anonimo')
					echo "<a href='?menu=usuarios&submenu=form_login' style='float: right;'>Conectar</a>";
			?>
		</div>
		<div id='contenido_principal' >
			<fieldset style='margin-top: 10px; border: 2px solid blue; background-color: lightskyblue;'>
				<legend>Vista</legend>
			<?php echo $datos['contenido_principal']; ?>
			</fieldset>
		</div>
		<script type='text/javascript'>
			<?php 
				if (isset($datos['alerta']))
				echo "alert('{$datos['alerta']}');"; 
			?>
		</script>
		<div style='margin-top: 10px; border: 1px solid grey; background-color: lightgrey;'>
			<pre>
				<?php print_r($GLOBALS); ?>
			</pre>
		</div>
	</body>
</html>
