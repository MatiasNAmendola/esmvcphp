<?php 
echo "id\tnombre\tprecio\tunidades en stock\n";
foreach ($datos['filas'] as $fila) {
	echo "{$fila['id']}\t{$fila['nombre']}\t{$fila['precio']}\t{$fila['unidades_stock']}\n";
}
?>