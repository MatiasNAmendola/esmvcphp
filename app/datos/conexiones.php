<?php
namespace datos;

class conexiones extends \core\sgbd\bd {
		
	
	public static function recuperar(array $datos = array()) {
		
		$where = " where 1 ";
		if (isset($datos['login'])) {
			$where .= " and usuario_login = '{$datos['login']}' " ;
		}
		
		$sql = "
			select	
				fecha_hora_ini as fecha_hora_inicio,
				usuario_login,
				timestampdiff(minute,fecha_hora_ini, ifnull(fecha_hora_fin,now() )) as duracion_minutos
			from conexiones
			$where
			order by fecha_hora_ini desc, usuario_login asc
			limit 1000
			;

		";
		
		$resultado = mysqli_query($link, $sql);
		
		$filas = array();
		while ($fila = mysql_fetch_assoc($resultado)) {
			array_push($filas, $fila);
		}
		
		return $filas;
		
	}
	
	
}