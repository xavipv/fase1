<?php
/**
 * Carga una clase de forma automática.
 * Los ficheros que guardan la clase tendrán el mismo nombre que la clase más la extensión '.php'.
 * Por ejemplo, la clase 'Conexion' se guarda en el fichero 'Conexion.php'.
 * 
 * @param string $clase Nombre de la clase.
 */
function __autoload($clase) {
    if ($clase) {
        $sFile = $clase . ".php";
        if (!recorrerDirectorios(__DIR__, $sFile)) {
            die("<p>ERROR: No se ha encontrado la clase $clase ($sFile)</p>\n"); 
            exit;
        }
    }
}

/**
 * Recorre el direcctorio y los subdirectorios de clases para buscar una clase dada.
 * 
 * @param string $sRuta Directorio de clases.
 * @param string $sClase Nombre del fichero de clases.
 * @return boolean Será TRUE si encuentra la clase y FALSE en caso contrario.
 */
function recorrerDirectorios($sRuta, $sClase) {
    $bRes = FALSE;
    $dir = (substr($sRuta, -1) == "/") ? $sRuta : "$sRuta/";
    $d = dir($dir);
    while (FALSE !== ($r = $d->read())) {
        // Clase que buscamos.
        $sRutaClase = $dir.$sClase;
        if ($r != "." && $r != ".." && !is_dir($dir.$r) && is_file($dir.$r) && (mb_strtolower($dir.$r) == mb_strtolower($sRutaClase)))  {
            include_once "$dir$r"; // Incluye la clase.
            return TRUE;
        } elseif ($r != "." && $r != ".." && is_dir($dir.$r)) {
            $bRes = recorrerDirectorios($dir.$r, $sClase);
        }
        if ($bRes) {
            break;
        }
    }
    return $bRes;
}
