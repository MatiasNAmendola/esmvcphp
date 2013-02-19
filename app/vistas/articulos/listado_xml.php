<opciones>
<?php 
		foreach ($datos['filas'] as $fila) {
			//echo "<opcion id='{$fila['id']}'  nombre='{$fila['nombre']}'/>";
			echo "<opcion>\n";
			echo "\t<id>{$fila['id']}</id>\n";
			echo "\t<nombre>{$fila['nombre']}</nombre>\n";
			echo "\t<precio>".\core\Conversiones::decimal_punto_a_coma($fila['precio'])."</precio>\n";
			echo "\t<unidades_stock>'".\core\Conversiones::decimal_punto_a_coma($fila['unidades_stock'])."</unidades_stock>\n";
			echo "</opcion>\n";
		}
	?>

</opciones>