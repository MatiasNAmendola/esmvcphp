<p>
<?php
echo $datos['mensaje'];

if (isset($datos['url_continuar']))
	echo "<p><a href='{$datos['url_continuar']}'>Continuar</a></p>";

?>
</p>