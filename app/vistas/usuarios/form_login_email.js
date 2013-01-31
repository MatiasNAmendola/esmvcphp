function limpiar_inicial(input) {
	if (input.value.indexOf('Escribe') >= 0)
		input.value = '';
}

function limpiar_input(input_id) {
	input = document.getElementById(input_id);
	input.value = '';
}