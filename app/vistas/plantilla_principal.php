<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo TITULO; ?></title>
		<meta name="Description" content="" /> 
		<meta name="Keywords" content="palabras en castellano e ingles separadas por comas" /> 
		<meta name="Generator" content="quíen o ha hecho" /> 
		<meta name="Origen" content="Quíen lo ha hecho" /> 
		<meta name="Author" content="nombre del autor" /> 
		<meta name="Locality" content="Madrid, España" /> 
		<meta name="Lang" content="es" /> 
		<meta name="Viewport" content="maximum-scale=10.0" /> 
		<meta name="revisit-after" content="1 days" /> 
		<meta name="robots" content="INDEX,FOLLOW,NOODP" /> 
		<meta http-equiv="Content-Type" content="text/html;charset=utf8" /> 

		<link rel="stylesheet" type="text/css" href="" />
		<?php
		if (isset($datos['css']))
			foreach ($datos['css'] as $css_link => $value) {
				echo "$css_link";	
			}
		?>
		<style type="text/css" >
		/* Hoja de estilos interna */
		</style>

		<script type="text/javascript" src=""></script>
		<?php 
		if (isset($datos['js']))
			foreach ($datos['js'] as $js_script => $value) {
				echo "$js_script";	
			}
		?>
		<script type="text/javascript" >
		/* líneas del script */
		</script>
	
	</head>
	<body style='border: 1px solid; width: 995px; margin-left: auto; margin-right: auto;  padding: 5px;'>
		<div id='header-left' style='width: 50%; float: left;'>
			<h1>Aplicación my MVC</h1>
		</div>
		<div id='header-right' style='width: 50%; float: right; text-align: right;'>
			Usuario: <?php 
							echo "<b>".\core\Usuario::$login."</b>";
							if (\core\Usuario::$login != 'anonimo')
								echo " (<a href='?menu=usuarios&submenu=desconectar'>desconectar</a>)";
							if (\core\Usuario::$login != 'anonimo') {
								echo "<br />Tiempo máximo de sesión = ".gmdate('H:i:s', \core\Configuracion::$sesion_minutos_maxima_duracion*60);
								echo "<br />Tiempo máximo de inactividad = ".gmdate('H:i:s', \core\Configuracion::$sesion_minutos_inactividad *60);
							}
							// Para todos los usuarios
							echo "<br />Tiempo transcurrido desde anterior petición =  <span id='sesion_tiempo_desde_peticion'>".gmdate('h:i:s', \core\Usuario::$sesion_segundos_inactividad)."</span>";
							echo "<br />Tiempo desde conexión =  <span id='sesion_tiempo_desde_conexion'>".gmdate('h:i:s', \core\Usuario::$sesion_segundos_duracion)."</span>";
							if (\core\Usuario::$login != 'anonimo') {
								echo "<br />Tiempo restante de sesión = <span id='sesion_tiempo_restante'>".gmdate('h:i:s', \core\Configuracion::$sesion_minutos_maxima_duracion *60 - \core\Usuario::$sesion_segundos_duracion)."</span>";
							}
							if (isset($_SESSION['usuario']['contador_paginas_visitadas']))
								echo "<br />Páginas visitadas: [{$_SESSION['usuario']['contador_paginas_visitadas']}]";
					?>
		</div>
		<hr style='clear: both;'/>
		<div id="menu" style='border: 1px solid red;'>
			<a href='?'>Inicio</a>
			<a href='?menu=usuarios'>Usuarios</a>
			<a href='?menu=articulos'>Artículos</a>
			<?php 
				if (\core\Usuario::$login == 'anonimo')
					echo "<a href='?menu=usuarios&submenu=form_login_email' style='float: right;'>Conectar</a>";
			?>
		</div>
		<div id='contenido_principal' >
			<fieldset style='margin-top: 10px; border: 2px solid blue; background-color: lightskyblue;'>
				<legend>Vista</legend>
			<?php echo ( isset($datos['contenido_principal']) ? $datos['contenido_principal'] : ''); ?>
			</fieldset>
		</div>
		
		<div style='margin-top: 10px; padding: 5px; border: 1px solid grey; background-color: lightgrey;'>
			<h3>Información de arrays globales y variables globales definidas por el usuario. $GLOBALS =</h3>
			<pre>
				<?php print_r($GLOBALS); ?>
			</pre>
		</div>
		
		
		<script type='text/javascript'>
			<?php 
				if (isset($datos['alerta']))
				echo "alert('{$datos['alerta']}');"; 
			?>
			var login = '<?php echo \core\Usuario::$login; ?>';
			var sesion_ms_desde_peticion = 0;
			var sesion_ms_desde_conexion = <?php  echo \core\Usuario::$sesion_segundos_duracion * 1000;?> ;
			var sesion_ms_restante = <?php  echo (\core\Configuracion::$sesion_minutos_maxima_duracion *60 - \core\Usuario::$sesion_segundos_duracion) * 1000;?> ;
			
			function dos_digitos(numero) {
				if (numero<10) {
				  numero="0" + numero;
				}
				return numero;
			}

			function actualizar_tiempos() {
				// alert('actualizar_tiempos');
				if (login != 'anonimo') {
					var hora = new Date(sesion_ms_restante);
					document.getElementById('sesion_tiempo_restante').innerHTML = dos_digitos(hora.getUTCHours())+":"+dos_digitos(hora.getMinutes())+":"+dos_digitos(hora.getSeconds());
					sesion_ms_restante = sesion_ms_restante - 1000;
				}
				objeto = document.getElementById('sesion_tiempo_desde_peticion');
				hora = new Date(sesion_ms_desde_peticion);
				objeto.innerHTML = dos_digitos(hora.getUTCHours())+":"+ dos_digitos(hora.getMinutes())+":"+dos_digitos(hora.getSeconds());
				sesion_ms_desde_peticion += 1000;
				
				objeto = document.getElementById('sesion_tiempo_desde_conexion');
				hora = new Date(sesion_ms_desde_conexion);
				objeto.innerHTML = dos_digitos(hora.getUTCHours())+":"+ dos_digitos(hora.getMinutes())+":"+dos_digitos(hora.getSeconds());
				sesion_ms_desde_conexion += 1000;
			}
			
			var myVar = setInterval(function(){actualizar_tiempos()}, 1000);
			// window.setInterval("actualizar_tiempos" , 1000);
			
		</script>
		
	</body>
</html>
