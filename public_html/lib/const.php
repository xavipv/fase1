<?php

// Incluye el documento de constantes.
$rutaCons = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php";
//$rutaCons = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . "/fase1/cgibin/includes/constantes.inc.php";

(include($rutaCons)) or die("<p>Error al incluir <b>$rutaCons</b></p>");
