<div>
	<h1>Listado de articulos</h1>
	
	<table border='1'>
		<thead>
			<tr>
				<th>nombre</th>
				<th>precio</th>
				<th>unidades en stock</th>
				<th>acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datos['filas'] as $fila)
			{
				echo "
					<tr>
						<td>{$fila['nombre']}</td>
						<td>".\core\Conversiones::decimal_punto_a_coma_y_miles($fila['precio'])."</td>
						<td>".\core\Conversiones::decimal_punto_a_coma_y_miles($fila['unidades_stock'])."</td>
						<td>
							<a class='boton' href='?menu=articulos&submenu=form_modificar&id={$fila['id']}' >modificar</a>
							<a class='boton' href='?menu=articulos&submenu=form_borrar&id={$fila['id']}' >borrar</a>
						</td>
					</tr>
					";
			}
			echo "
				<tr>
					<td colspan='3'></td>
						<td><a class='boton' href='?menu=articulos&submenu=form_insertar' >insertar</a></td>
				</tr>
			";
			?>
		</tbody>
	</table>
</div>