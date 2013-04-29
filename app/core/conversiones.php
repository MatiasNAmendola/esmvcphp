<?php
namespace core;

/**
 * Conversiones de tipos de datos y de fechas
 * 
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Conversiones {

	public static function decimal_punto_a_coma($decimal) {
		$decimal = (string)$decimal;
		$decimal = preg_replace("/\./", ",", $decimal);
		return $decimal;
	}

	/**
	 * Transforma un numero tipo es a numero en formato inglés sin separador de miles.
	 * 
	 * @param string $decimal
	 * @return string
	 */
	public static function decimal_coma_a_punto($decimal) {
		$decimal = (string)$decimal;
		$decimal = preg_replace("/\./", "_", $decimal);
		$decimal = preg_replace("/\,/", ".", $decimal);
		$decimal = preg_replace("/\_/", "", $decimal);
		return $decimal;
	}
	
	/**
	 * Pone el . de separación de miles.
	 * 
	 * @param decimal $decimal Numero con , decimal y sin separador de miles
	 * @return string
	 */
	public static function poner_punto_separador_miles($decimal) {
		
		$decimal=(string)$decimal;		
		$patron="/^((-){0,1}\d{1,})((,){1}\d{1,}){0,1}$/";
		if ( ! preg_match($patron, $decimal))
			return;
		
		$partes=explode(",",$decimal); // Separamos parte entera y parte decimal.

		$resto=strlen($partes[0])%3;
		$parte_entera=$partes[0];
		
		if ($resto) {
			$nueva_longitud=strlen($partes[0])+3-$resto;
			$parte_entera=str_pad($partes[0],$nueva_longitud, " ", STR_PAD_LEFT);
		}
		
		$miles=str_split($parte_entera,3);
		$parte_entera=  implode(".", $miles);
		$parte_decimal=(isset($partes[1]) ? ",".$partes[1] : "");
		return trim($parte_entera.$parte_decimal);
	}
	
	
	/**
	 * 
	 * @param decimal|string $decimal
	 * @return string
	 */
	public static function decimal_punto_a_coma_y_miles($decimal) {
		
		$decimal=(string)$decimal;
		$decimal = self::decimal_punto_a_coma($decimal);
		return self::poner_punto_separador_miles($decimal);
		
	}
	
	
} // Fin de la clase

?>