<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo TITULO; ?></title>
	</head>
	<body style='border: 1px solid; width: 995px; margin-left: auto; margin-right: auto;  padding: 5px;'>
		<h1>Aplicación my MVC</h1>
		<hr>
		<div id="menu" style='border: 1px solid red;'>
			<a href='?'>Inicio</a>
			<a href='?menu=usuarios&submenu=index'>Usuarios</a>
			<a href='?menu=usuarios&submenu=form_login' style='float: right;'>Conectar</a>
		</div>
		<div id='contenido_principal'>
			<?php echo $datos['contenido_principal']; ?>
		</div>
		<script type='text/javascript'>
			<?php 
				if (isset($datos['alerta']))
				echo "alert('{$datos['alerta']}');"; 
			?>
		</script>
		<div>
			<pre>
				<?php print_r($GLOBALS); ?>
			</pre>
		</div>
	</body>
</html>
