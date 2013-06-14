<div >
	<h2>Alta de un artículo
	<?php 
		if ($datos['values']['categoria_nombre']) {
			echo "en la categoría {$datos['values']['categoria_nombre']}";
		}
	?>
	</h2>
<?php include "form_and_inputs.php"; ?>
</div>