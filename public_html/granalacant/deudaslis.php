<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

$fec = ( filter_input(INPUT_POST, 'fecha') ) ? filter_input(INPUT_POST, 'fecha') : filter_input(INPUT_GET, 'fecha');

if(!$fec) {
    $fec = $oJuntas->getUltimaJunta();
}

$oDeudas = new Deudas();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Listado de deudas"); ?>
    </head>
    <body onload="xajax_getListadoDeudas(xajax.getFormValues('frmdatos'));">
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <br />
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container" style="width: 80%">
            <div class="row">
                <div id="contenido" class="col-sm-12">
                    <div id="divcabecera">
                        <div id="divformulario">
                            <!-- Formulario para los datos -->
                            <form id="frmdatos" name="frmdatos" method="post" onsubmit="return false;">
                                <div class="form-group row">
                                    <h2 class="col-sm-10">Listado de deudas</h2>
                                    <div class="col-sm-2 text-right">
                                        <button class="btn btn-outline-success" style="cursor: pointer" id="imprimir" title="Pantalla completa" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'deudaslismax.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-fullscreen-enter"></span></button>
                                        <button class="btn btn-outline-success" style="cursor: pointer" id="imprimir" title="Imprimir en un PDF" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'deudasprint.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-print"></span></button>
                                    </div>
                                </div>
                                <div class="form-group row" data-animation="false" data-toggle="tooltip" data-placement="right" data-trigger="hover" title="Orden de los datos">
                                    <label for="tipo" class="col-sm-1 col-form-label text-right">Fecha:</label>
                                    <div class="col-sm-2"><?php echo f_getSelectAgrupadoFechas($oDeudas->getFechas(), 'fecha', "$fec", 'form-control', 'xajax_getListadoDeudas(xajax.getFormValues(\'frmdatos\'))', TRUE); ?></div>
                                    
                                    <div class="col-sm-2 offset-1">
                                        <div class="input-group">
                                            <input type="radio" class="form-check-input" id="ordenF" name="orden" value="0" onclick="$('#sumas').prop('disabled',!$(this).prop('checked')); xajax_getListadoDeudas(xajax.getFormValues('frmdatos'));" checked="checked">
                                            <label for="ordenF" class="form-check-label">Fechas</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input type="radio" class="form-check-input" id="ordenA" name="orden" value="1" onclick="$('#sumas').prop('disabled',$(this).prop('checked')); xajax_getListadoDeudas(xajax.getFormValues('frmdatos'));">
                                            <label for="ordenA" class="form-check-label">Apartamentos</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input type="radio" class="form-check-input" id="ordenS" name="orden" value="2" onclick="$('#sumas').prop('disabled',!$(this).prop('checked')); xajax_getListadoDeudas(xajax.getFormValues('frmdatos'));">
                                            <label for="ordenS" class="form-check-label">Suma deudas</label>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group row" data-animation="false" data-toggle="tooltip" data-placement="right" data-trigger="hover" title="Datos a filtrar">
                                    
                                    <div class="col-sm-2 offset-2">
                                        <input type="checkbox" class="form-check-input" id="ordin" name="ordin" onclick="xajax_getListadoDeudas(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="ordin" class="form-check-label">Deuda ordinaria</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="extra" name="extra" onclick="xajax_getListadoDeudas(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="extra" class="form-check-label">Deuda extraordinaria</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="total" name="total" onclick="xajax_getListadoDeudas(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="total" class="form-check-label">Total deuda</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="mayus" name="mayus"  onclick="xajax_getListadoDeudas(xajax.getFormValues('frmdatos'));">
                                        <label for="mayus" class="form-check-label">May&uacute;sculas</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="sumas" name="sumas"  onclick="xajax_getListadoDeudas(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="sumas" class="form-check-label">Sumas</label>
                                    </div>
                                </div>                                
                                <hr /><br />
                                <input id="datosdiv" name="datosdiv" type="hidden" value="">
                            </form>
                            
                        </div>
                    </div>
                    <div id="divbusqueda" class="listado"></div>
                </div>
            </div>    
        </div>
        <div id="ainicio" class=""><a href="#inicio" title="Ir al inicio" role="button" class="btn btn-outline-secondary"><span class="oi oi-arrow-thick-top"></span></a></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
