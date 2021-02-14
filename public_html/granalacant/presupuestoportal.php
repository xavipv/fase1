<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include ("../lib/const.php")) or (die("Error al incluir <b>../lib/const.php"));

// Tipo de coeficientes a elegir.
$aTipoCoef = array(array("Coeficiente de portal","Portal"), array("Coeficiente general por portal","Portal"), array("Coeficiente general","General"), array("Coeficiente plazas de garaje","General"));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Presupuesto"); ?>
    </head>
    <body onload="$('#cantidad').focus();">
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
                        <!-- Formulario para los datos -->
                        <form id="frmdatos" name="frmdatos" method="post" onsubmit="return false;"> 
                            <div class="form-group row">
                                <h2 class="col-sm-10">Presupuesto de un portal</h2>
                                <div class="col-sm-2 text-right">
                                <!-- MODIFICAR ---->
                                    <button class="btn btn-outline-success" style="cursor: pointer" id="imprimir" disabled="true" title="Pantalla completa" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'presupuestoportallismax.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-fullscreen-enter"></span></button>
                                    <button class="btn btn-outline-success" style="cursor: pointer" id="imprimirpdf" disabled="true" title="Imprimir en un PDF" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'presupuestoportalprint.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-print"></span></button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Cantidad:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="cantidad" name="cantidad" value="" placeholder="Cantidad" onclick="$(this).select();" onkeyup="js_presupuestoportal()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="meses" class="col-sm-1 col-form-label text-right">Meses:</label>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control solonumeros" id="meses" name="meses" value="12" placeholder="Meses" onclick="$(this).select();" onkeyup="js_presupuestoportal()">
                                </div>
                                <label for="portal" class="col-sm-1 col-form-label text-right">Portal:</label>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <?php echo f_getSelectPortales('portal', '1', 'form-control', 'js_presupuestoportal()'); ?>
                                    </div>
                                </div>
                                <label for="tipocoef" class="col-sm-1 col-form-label text-right">Coeficiente:</label>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <?php echo f_getSelectAgrupado($aTipoCoef, 'tipocoef', '0', 'form-control', 'js_presupuestoportal()'); ?>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-3 text-center">
                                    <input type="checkbox" class="form-check-input" id="codigo" name="codigo"  onclick="js_presupuestoportal()">
                                    <label for="codigo" class="form-check-label">C&oacute;digo</label>
                                </div>
                                <div class="col-sm-3 text-center"">
                                    <input type="checkbox" class="form-check-input" id="metros" name="metros"  onclick="js_presupuestoportal()">
                                    <label for="metros" class="form-check-label">Metros</label>
                                </div>
                                <div class="col-sm-3 text-center"">
                                    <input type="checkbox" class="form-check-input" id="coeficientes" name="coeficientes" onclick="js_presupuestoportal()" checked="checked">
                                    <label for="coeficientes" class="form-check-label">Coeficientes</label>
                                </div>
                                <div class="col-sm-3 text-center"">
                                    <input type="checkbox" class="form-check-input" id="sumas" name="sumas"  onclick="js_presupuestoportal()" checked="checked">
                                    <label for="sumas" class="form-check-label">Sumas</label>
                                </div>
                            </div>
                            <hr />
                            <input id="datosdiv" name="datosdiv" type="hidden" value="">
                        </form>
                        <br />
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
