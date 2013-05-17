/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function hola() {
	alert('Hola');
}


function confirmar_borrar(fichero_foto) {
	if (confirm("¿Estás seguro de querer borrar la foto " + fichero_foto))
		location.assign("?menu=galeria_fotos&submenu=validar_form_borrar&fichero_foto="+fichero_foto);
}