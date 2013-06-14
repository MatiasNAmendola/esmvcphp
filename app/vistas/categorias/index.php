<div>
	<h1>Listado de categorías</h1>
	
	<table border='1'>
		<thead>
			<tr>
				<th>nombre</th>
				<th>descripcion</th>
				<th>acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datos['filas'] as $fila)
			{
				echo "
					<tr>
						<td><a class='boton' href='?menu=articulos&submenu=index&categoria_nombre={$fila['nombre']}' >{$fila['nombre']}</a></td>
						<td>{$fila['descripcion']}</td>
						<td>
							<a class='boton' href='?menu={$datos['controlador_clase']}&submenu=form_modificar&id={$fila['id']}' >modificar</a>
							<a class='boton' href='?menu={$datos['controlador_clase']}&submenu=form_borrar&id={$fila['id']}' >borrar</a>
							<a class='boton' href='?menu=articulos&submenu=index&categoria_nombre={$fila['nombre']}' >ver artículos</a>	
						</td>
					</tr>
					";
			}
			echo "
				<tr>
					<td colspan='2'></td>
						<td><a class='boton' href='?menu={$datos['controlador_clase']}&submenu=form_insertar' >insertar</a></td>
				</tr>
			";
			?>
		</tbody>
	</table>
</div>