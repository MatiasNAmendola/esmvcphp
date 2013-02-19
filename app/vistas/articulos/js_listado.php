{"opciones": [
<?php 
		$i = 0;
		foreach ($datos['filas'] as $fila) {
			echo "\t{'id': '{$fila['id']}', 'nombre': '{$fila['nombre']}'}";
			$i++;
			if ($i < count($datos['filas']))
				echo " , \n";
		}
	?>

	]
}

