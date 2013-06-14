<div>
	<h1>Listado de articulos
		<?php 
			if ($datos['values']['categoria_nombre']) {
				echo " de la categoría [{$datos['values']['categoria_nombre']}]";
			}
			else {
				echo " de todas las categorías";
			}
		?>
	</h1>
	<p>
		<a href='?menu=articulos&submenu=listado_js' title='Devuelve objeto json con una propiedad que contiene un array'>Listado en json</a> - 
		<a href='?menu=articulos&submenu=listado_js_array&nombre=a'  title='Devuelve un array que contiene objetos json'>Listado en json con array de articulos que contiene "a" en su nombre</a> - 
		<a href='?menu=articulos&submenu=listado_xml'>Listado en xml</a> - 
		<a href='?menu=articulos&submenu=listado_xls'>Descargar Listado en excel (.xls)</a>
		 - 
		<a href='?menu=articulos&submenu=listado_pdf'>Descargar pdf</a>
	</p>
	
	<table border='1'>
		<thead>
			<tr>
				<th>categoría</th>
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
						<td>{$fila['categoria_nombre']}</td>
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
					<td colspan='4'></td>
					<td><a class='boton' href='?menu=articulos&submenu=form_insertar".($datos['values']['categoria_nombre'] ? "&categoria_nombre={$datos['values']['categoria_nombre']}": "")."' >insertar</a></td>
				</tr>
			";
			?>
		</tbody>
	</table>
</div>