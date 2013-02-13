<div>
	<h1>Listado de usuarios</h1>
	<table border='1'>
		<thead>
			<tr>
				<th>login</th>
				<th>email</th>
				<th>acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datos['filas'] as $fila)
			{
				echo "
					<tr>
						<td>{$fila['login']}</td>
						<td>{$fila['email']}</td>
						<td>
							<a class='boton' href='?menu=usuarios&submenu=form_modificar&id={$fila['id']}' >modificar</a>
							<a class='boton' href='?menu=usuarios&submenu=form_borrar&id={$fila['id']}' >borrar</a>
						</td>
					</tr>
					";
			}
			echo "
				<tr>
					<td colspan='2'></td>
						<td><a class='boton' href='?menu=usuarios&submenu=form_insertar' >insertar</a></td>
				</tr>
			";
			?>
		</tbody>
	</table>
</div>