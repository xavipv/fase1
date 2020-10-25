<?php
/**
 * Define las constantes de usuario.
 */

// Si es true muestra las constantes y finaliza la ejecucion.
$DEBUG = false;

// El directorio ROOT es el mismo que DOCS?
$RootIsDocs = false;    // true o false

// Las paginas están directamente en el raiz o en un subdirectorio?
$SubdirRoot = '';       // Subdirectorio en la forma '/subdir'

// Directorios
defined('_DOCS_') or define('_DOCS_', filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . $SubdirRoot);
if (!$RootIsDocs) {
    defined('_ROOT_') or define('_ROOT_', dirname(_DOCS_));
} else {
    defined('_ROOT_') or define('_ROOT_', _DOCS_ );
}
defined('_CGIB_') or define('_CGIB_', _ROOT_ . '/cgi-bin');
defined('_CLAS_') or define('_CLAS_', _CGIB_ . '/clases');
defined('_INCL_') or define('_INCL_', _CGIB_ . '/includes');
defined('_LIBR_') or define('_LIBR_', _DOCS_ . '/lib');

// Ficheros
defined('_BASE_') or define('_BASE_', _INCL_ . '/datosbase.ini');
defined('_AUTC_') or define('_AUTC_', _CLAS_ . '/php/autocarga.php');
defined('_XAJX_') or define('_XAJX_', _LIBR_ . '/xajax/xajax_core/xajax.inc.php');

// URLs
defined('_USRV_') or define('_USRV_', 'http://' . filter_input(INPUT_SERVER, 'HTTP_HOST') . $SubdirRoot);
defined('_ULIB_') or define('_ULIB_', _USRV_ . '/lib');
defined('_ACTA_') or define('_ACTA_', _USRV_ . '/actas');
defined('_UXJX_') or define('_UXJX_', _ULIB_ . '/xajax');

/**
 * Comprueba que las rutas o URLs de las constantes de usuario son correctas.
 * Si hay alguna incorrecta se detendra la ejecucion y se mostrara el error.
 */
foreach (get_defined_constants(true)['user'] as $c => $v) {
    if(substr($v, 0, 4) == 'http') {
        // Es una URL.
        if(get_headers($v)[0] == 'HTTP/1.1 404 Not Found') {
            die("<div class=\"alert alert-danger\" role=\"alert\">Error en la constante <strong>$c</strong>: no se encuentra la URL <strong>$v</strong></div>");
        }
    } else {
        // Es un fichero.
        if(!file_exists($v)) {
            die("<div class=\"alert alert-danger\" role=\"alert\">Error en la constante <strong>$c</strong>: no se encuentra el  <strong>$v</strong></div>");
        }
    }
    if ($DEBUG) {
        echo "<b>$c</b> = $v<br />";
    }
}

if ($DEBUG) {    
    die("<br />[FIN debug]");
}

// Autocarga para las clases.
include _AUTC_;

// Comprueba los includes de la pagina
if(isset($aIncludes)) {
    foreach ($aIncludes as $inc) {
        $inc = "inc/$inc";
        if(is_file($inc)) {
            include_once $inc;
        } else {
            die("<p>ERROR: No se encuentra el fichero <b>$inc</b></p>");
        }
    }
}
