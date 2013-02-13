<?php
namespace core;
/* CGI.php 
 * ver. 121024
 */

/*
 * Este fichero es clase, que es una librería con cuatro funciones: request(), get(), post() y cookie().
 */

/**
 * Contiene funciones para tratar la información recibida desde el navegador web 
 * mediante HTTP, utilizando las variables predefinidas de php que forman parte
 * del Common Gateway Interface (CGI).
 */
class CGI {
    
    /**
     * request($indice) devuelve el contenido de un índice del array $_REQUEST[$indice] si existe esa entrada y no es una cadena vacía.
     * request() devuelve el array $_REQUEST (se llama a la función sin parámetros).
     * Devuelve un null si $indice no existe en $_REQUEST o si existe y es una cadena vacía, de longitud cero strlen($_REQUEST[$indice])==0
     * Devuelve un string que será el contenido de la entrada $_REQUEST[$indice]
     * Devuelve un array que sera el propio $_REQUEST si se llama sin parámetros
     * Si se le pasa un parámetro no string devuelve un <b>Fatal error</b>
     *
     * @param null|string $indice
     * @return null|string|array
     */
    public static function request($indice=null) {
            $resultado=null; // Variable auxiliar para preparar y almacenar el resultado que retornará la función
            if ($indice===null) {
                    // $indice === null es decir se ha invocado sin parámetros: request()
                    // Usanmos === para descartar otros valores equivalentes a null como 0 "" o "0" o false
                    $resultado=$_REQUEST; // Retorna el array $_REQUEST con todos sus elementos
            }	
            elseif (is_string($indice)) { // Solo aceptamos índices alfanuméricos para el array $_REQUEST
                            if ( isset($_REQUEST[$indice]) ) { // Existe el indice $indice en el array 
                                    if (strlen($_REQUEST[$indice])) // El contenido de ese elemento de $_REQUEST es una cadena de longitud 1 o mayor
                                            $resultado=$_REQUEST[$indice]; // string

                            }
            }
            else { // Si el programador llama a la función con un parámetro distinto de tipo string, se le genera un Fatal error.
                    throw new Exception("Error: request(\$indice=$indice) \$indice debe ser string.\n");
            }
            return ($resultado);
    }

    /**
     * get($indice) devuelve el contenido de un índice del array $_GET[$indice] si existe esa entrada y no es una cadena vacía.
     * get() devuelve el array $_GET (se llama a la función sin parámetros).
     * Devuelve un null si $indice no existe en $_GET o si existe y es una cadena vacía, de longitud cero strlen($_GET[$indice])==0
     * Devuelve un string que será el contenido de la entrada $_GET[$indice]
     * Devuelve un array que sera el propio $_GET si se llama sin parámetros
     * Si se le pasa un parámetro no string devuelve un <b>Fatal error</b>
     *
     * @param null|string $indice
     * @return null|string|array
     */
    public static function get($indice=null) {
            $resultado=null; // Variable auxiliar para preparar y almacenar el resultado que retornará la función
            if ($indice===null) {
                    // $indice === null es decir se ha invocado sin parámetros: get()
                    // Usanmos === para descartar otros valores equivalentes a null como 0 "" o "0" o false
                    $resultado=$_GET; // Retorna el array $_GET con todos sus elementos
            }	
            elseif (is_string($indice)) { // Solo aceptamos índices alfanuméricos para el array $_GET
                    if ( isset($_GET[$indice]) ) { // Existe el indice $indice en el array 
                            if (strlen($_GET[$indice])) // El contenido de ese elemento de $_GET es una cadena de longitud 1 o mayor
                                    $resultado=$_GET[$indice]; // string

                    }
            }
            else { // Si el programador llama a la función con un parámetro distinto de tipo string, se le genera un Fatal error.
                    throw new Exception("Error: get(\$indice=$indice) \$indice debe ser string.\n");
            }
            return ($resultado);
    }

    /**
     * post($indice) devuelve el contenido de un índice del array $_POST[$indice] si existe esa entrada y no es una cadena vacía.
     * post() devuelve el array $_POST (se llama a la función sin parámetros).
     * Devuelve un null si $indice no existe en $_POST o si existe y es una cadena vacía, de longitud cero strlen($_POST[$indice])==0
     * Devuelve un string que será el contenido de la entrada $_POST[$indice]
     * Devuelve un array que sera el propio $_POST si se llama sin parámetros
     * Si se le pasa un parámetro no string devuelve un <b>Fatal error</b>
     *
     * @param null|string $indice
     * @return null|string|array
     */
    public static function post($indice=null) {
            $resultado=null; // Variable auxiliar para preparar y almacenar el resultado que retornará la función
            if ($indice===null) {
                    // $indice === null es decir se ha invocado sin parámetros: post()
                    // Usanmos === para descartar otros valores equivalentes a null como 0 "" o "0" o false
                    $resultado=$_POST; // Retorna el array $_POST con todos sus elementos
            }	
            elseif (is_string($indice)) { // Solo aceptamos índices alfanuméricos para el array $_POST
                    if ( isset($_POST[$indice]) ) { // Existe el indice $indice en el array 
                            if (strlen($_POST[$indice])) // El contenido de ese elemento de $_POST es una cadena de longitud 1 o mayor
                                    $resultado=$_POST[$indice]; // string

                    }
            }
            else { // Si el programador llama a la función con un parámetro distinto de tipo string, se le genera un Fatal error.
                    throw new Exception("Error: post(\$indice=$indice) \$indice debe ser string.\n");
            }
            return ($resultado);
    }

    /**
     * cookie($indice) devuelve el contenido de un índice del array $_COOKIE[$indice] si existe esa entrada y no es una cadena vacía.
     * cookie() devuelve el array $_COOKIE (se llama a la función sin parámetros).
     * Devuelve un null si $indice no existe en $_COOKIE o si existe y es una cadena vacía, de longitud cero strlen($_COOKIE[$indice])==0
     * Devuelve un string que será el contenido de la entrada $_COOKIE[$indice]
     * Devuelve un array que sera el propio $_COOKIE si se llama sin parámetros
     * Si se le pasa un parámetro no string devuelve un <b>Fatal error</b>
     *
     * @param null|string $indice
     * @return null|string|array
     */
    public static function cookie($indice=null) {
            $resultado=null; // Variable auxiliar para preparar y almacenar el resultado que retornará la función
            if ($indice===null) {
                    // $indice === null es decir se ha invocado sin parámetros: cookie()
                    // Usanmos === para descartar otros valores equivalentes a null como 0 "" o "0" o false
                    $resultado=$_COOKIE; // Retorna el array $_COOKIE con todos sus elementos
            }	
            elseif (is_string($indice)) { // Solo aceptamos índices alfanuméricos para el array $_COOKIE
                    if ( isset($_COOKIE[$indice]) ) { // Existe el indice $indice en el array 
                            if (strlen($_COOKIE[$indice])) // El contenido de ese elemento de $_COOKIE es una cadena de longitud 1 o mayor
                                    $resultado=$_COOKIE[$indice]; // string

                    }
            }
            else { // Si el programador llama a la función con un parámetro distinto de tipo string, se le genera un Fatal error.
                    throw new Exception("Error: cookie(\$indice=$indice) \$indice debe ser string.");
            }
            return ($resultado);
    }

	public static function method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

}