<?php 
// En excel las columnas se separan con tabuladores -> \t
// Encabezado
echo "id\tnombre\tprecio\tunidades en stock\n";
foreach ($datos['filas'] as $fila) {
	echo "{$fila['id']}";
	echo "\t{$fila['nombre']}";
	echo "\t".\core\Conversiones::decimal_punto_a_coma($fila['precio']);
	echo "\t".\core\Conversiones::decimal_punto_a_coma($fila['unidades_stock']);
	echo "\n"; // Fin de fila que es fin de línea o linea nueva -> \n
}
?>