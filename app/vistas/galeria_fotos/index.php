<h1>Galería de fotos</h1>
<button type='button' onclick='location.assign("?menu=galeria_fotos&submenu=form_subir");' >Subir una foto</button>
<div style="clear: both;"></div>
<?php
foreach ($datos['fotos'] as $fichero_foto) {
	echo "<div class='marco' style='width: 200px; height: 230px; float: left; border: 4px black outset; margin: 10px;'>";
	echo "<img style='width: 200px;' src='".\core\URL::http ()."recursos/imagenes/galeria_fotos/$fichero_foto' title='$fichero_foto'/><br />$fichero_foto";
	echo "<img style='float: right;' src='".\core\URL::http ()."recursos/imagenes/generales/borrar.jpg' width='25px' title='Borrar' onclick='confirmar_borrar(\"$fichero_foto\");' />";
	echo "</div>";
}
?>
<script type="text/javascript">
	function confirmar_borrar(fichero_foto) {
		if (confirm("¿Estás seguro de querer borrar la foto " + fichero_foto))
			location.assign("?menu=galeria_fotos&submenu=validar_form_borrar&fichero_foto="+fichero_foto);
	}
</script>