<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include ("../lib/const.php")) or (die("Error al incluir <b>../lib/const.php"));

$fec = ( filter_input(INPUT_POST, 'fecha') ) ? filter_input(INPUT_POST, 'fecha') : filter_input(INPUT_GET, 'fecha');

if(!$fec) {
    $oActas = new Actas();
    $fec = $oActas->getUltimaActa();
}

// Nombre del acta en PDF.
$actaPDF = f_getNombreActaPDF($fec);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Actas"); ?>
    </head>
    <body onload="xajax_getActa('<?php echo $fec; ?>')">
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <ul class="nav submenu">
                <li class="nav-item text-left col-sm-10">
                    <div id="submenu1" class="nav-link"><?php echo f_getActasAnyos(); ?></div>
                </li>
                <li class="nav-item text-right col-sm-2">
                    <div id="submenu3">
                        <a id="actapdf" href="<?php echo _ACTA_ . '/' . $actaPDF; ?>" target="_blank" role="button" title="Ver en PDF" class="btn btn-outline-success"><span class="oi oi-eye"></span></a>
                        <a id="aedit" href="actasedit.php?fecha=<?php echo $fec; ?>" role="button" title="Editar" class="btn btn-outline-success"><span class="oi oi-pencil"></span></a>
                        <button class="btn btn-outline-success" style="cursor: pointer" id="imprimir" title="Imprimir en un PDF" onclick="$('#frmdatos').submit();"><span class="oi oi-print"></span></button>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container col-sm-11">
            <div class="row">
                <div id="divlistado" class="col-sm-1 hidden-ms hidden-xs listado" style=""><?php echo f_getActasListado(); ?></div>
                <div id="contenido" class="col-sm-11">
                    <div id="divcontenido" class="listadowrap"></div>
                </div>
            </div>
        </div>
        <div id="ainicio" class=""><a href="#inicioacta" title="Ir al inicio" role="button" class="btn btn-outline-secondary"><span class="oi oi-arrow-thick-top"></span></a></div>
        <form id="frmdatos" name="frmdatos" method="post" action="actasprint.php" target="_blank">
            <input id="datosdiv" name="datosdiv" type="hidden" value="">
        </form>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
