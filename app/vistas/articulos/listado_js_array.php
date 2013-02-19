[
<?php 
		$i = 0;
		foreach ($datos['filas'] as $fila) {
			echo "{'id': '{$fila['id']}', 'nombre': '{$fila['nombre']}', 'precio': '".\core\Conversiones::decimal_punto_a_coma($fila['precio'])."', 'unidades_stock': '".\core\Conversiones::decimal_punto_a_coma($fila['unidades_stock'])."'}";
			$i++;
			if ($i < count($datos['filas']))
				echo " , \n";
		}
	?>

]