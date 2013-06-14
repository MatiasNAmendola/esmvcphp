<div>
	<h1>Listado de conexiones</h1>
	
	<table border='1'>
		<thead>
			<tr>
				<th>fecha_hora_inicio</td>
				<th>usuarios_login</td>
				<th>duracion_minutos</td>
				
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datos['filas'] as $fila)
			{
				echo "
					<tr>
						<td>{$fila['fecha_hora_inicio']}</td>
						<td>{$fila['usuarios_login']}</td>
						<td>{$fila['duracion_minutos']}</td>
					</tr>
					";
			}
			?>
		</tbody>
	</table>
	
	
</div>