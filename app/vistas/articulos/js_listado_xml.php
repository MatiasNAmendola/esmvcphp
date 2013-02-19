<opciones>
<?php 
		foreach ($datos['filas'] as $fila) {
			//echo "<opcion id='{$fila['id']}'  nombre='{$fila['nombre']}'/>";
			echo "<opcion>\n";
			echo "\t<id>{$fila['id']}</id>\n\t<nombre>'{$fila['nombre']}'</nombre>\n";
			echo "</opcion>\n";
		}
	?>

</opciones>