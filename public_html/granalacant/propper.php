<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

// Mira si hay datos recibidos.
$iPer = ( filter_input(INPUT_POST, 'persona') ) ? filter_input(INPUT_POST, 'persona') : filter_input(INPUT_GET, 'persona');
if(!$iPer) {
    // Busca la primera persona de la lista.
    $aPers = array_keys($oPers->getPersonas());
    $iPer = $aPers[0];
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Propietarios"); ?>
    </head>
    <body onload="xajax_setPropiedadesPersonaDatosForm(<?php echo $iPer; ?>);">
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <ul class="nav submenu">
                <li class="nav-item text-left col-sm-4">
                    <div id="submenu1" class="nav-link"><?php echo f_getPersonasIniciales(); ?></div>
                </li>
                <li class="nav-item text-center col-sm-4">
                    <div id="submenu2" class="nav-link" style="font-weight: bold;">Propiedades por persona</div>
                </li>
                <li class="nav-item text-right col-sm-4">
                    <div id="submenu2" class="nav-link"></div>
                </li>
            </ul>
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container col-sm-9">
            <div class="row">
                <div id="divlistado" class="col-sm-3 hidden-ms hidden-xs listado" style=""><?php echo f_getPropietariosListado(); ?></div>
                <div id="contenido" class="col-sm-9">
                    <div id="divcontenido">
                        <div id="divcabecera">
                            <h2>Propiedades</h2>
                            <form id="frmpropiedades"></form>
                            <br />
                        </div>
                        <div id="divbusqueda" class="listado"></div>
                    </div>
                </div>
            </div>    
        </div>
        <div id="ainicio" class=""><a href="#inicio" title="Ir al inicio" role="button" class="btn btn-outline-secondary"><span class="oi oi-arrow-thick-top"></span></a></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
