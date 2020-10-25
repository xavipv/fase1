<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include ("../lib/const.php")) or (die("Error al incluir <b>../lib/const.php"));

$fec = ( filter_input(INPUT_POST, 'fecha') ) ? filter_input(INPUT_POST, 'fecha') : filter_input(INPUT_GET, 'fecha');

if(!$fec) {
    $fec = $oJuntas->getUltimaJunta();
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Deudas"); ?>
    </head>
    <body onload="xajax_setDeudaDatosForm('<?php echo $fec; ?>')">
        <a name="iniciopagina"></a>
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <ul class="nav submenu">
                <li class="nav-item text-left col-sm-8">
                    <div id="submenu1" class="nav-link"><?php echo f_getJuntasAnyos(); ?></div>
                </li>
                <li class="nav-item text-right col-sm-4">
                    <div id="submenu2" class="nav-link"><?php echo f_getApartamentosIniciales(); ?></div>
                </li>
            </ul>
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container col-sm-10">
            <div class="row">
                <div id="divlistado" class="col-sm-1 hidden-ms hidden-xs listado"><?php echo f_getDeudasListado(); ?></div>
                <div id="contenido"  class="col-sm-11">
                    <div id="divcabecera">
                        <div id="btnfechair"></div>
                        <table class="table table-sm" style="width:100%; border-bottom: 1px solid lightgray; margin: 0; padding: 0">
                            <tr>
                                <td class="align-middle text-center" style="width: 25%">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" onchange="js_ocultarNoDeudores($(this).prop('checked'));" value="" id="vernodeudores">
                                        <input type="hidden" id="ultimofoco" value="">
                                        <label class="form-check-label" for="vernodeudores">Ver solo deudores</label>
                                    </div>
                                </td>
                                <th class="text-right align-middle" style="width: 15%">Deudores</th>
                                <th class="text-right align-middle" style="width: 15%">Ordinaria</th>
                                <th class="text-right align-middle" style="width: 15%">Extraordinaria</th>
                                <th class="text-right align-middle" style="width: 15%">Total</th>
                                <th class="text-right align-middle" style="width: 15%">Porcentaje</th>
                            </tr>
                            <tr>
                                <th class="text-right align-middle">Sumas:</th>
                                <td id="tdapar" class="text-right align-middle">0</td>
                                <td id="tdordi" class="text-right align-middle">0.00 €</td>
                                <td id="tdextr" class="text-right align-middle">0.00 €</td>
                                <td id="tdsuma" class="text-right align-middle">0.00 €</td>
                                <td id="tdporc" class="text-right align-middle">0.00 %</td>
                            </tr>
                        </table>
                    </div>
                    <div id="divcontenido" class="listado"></div>
                </div>
            </div>    
        </div>
        <div id="aportales" class=""><button type="button" class="btn btn-outline-primary" data-toggle="popover">Portales</button></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
