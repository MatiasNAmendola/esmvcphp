<div >
	<h2>Borrar una categoría</h2>
	<?php include "form_and_inputs.php"; ?>
	<script type='text/javascript'>
		function validar_form_borrar() {
			return confirm("¿Estás seguro de borrar estos datos? La operación no podrá deshacerse.");
		}
		
		
		window.document.getElementById("nombre").readOnly='readonly';
		window.document.getElementById("descripcion").readOnly='readonly';
	</script>
</div>