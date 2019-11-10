<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("C&aacute;lculos"); ?>
    </head>
    <body onload="$('#cuota').focus();">
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
                                <h2 class="col-sm-10">C&aacute;lculo del total</h2>
                                <div class="col-sm-2 text-right">
                                    <button class="btn btn-outline-success" style="cursor: pointer" id="imprimir" disabled="true" title="Pantalla completa" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'calctotallismax.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-fullscreen-enter"></span></button>
                                    <button class="btn btn-outline-success" style="cursor: pointer" id="imprimirpdf" disabled="true" title="Imprimir en un PDF" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'calctotalprint.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-print"></span></button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cuota" class="col-sm-2 col-form-label text-right">Cuota mensual</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="cuota" name="cuota" value="" placeholder="Cantidad" onclick="$(this).select();" onkeyup="js_cantidadTotal()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="portal" class="col-sm-1 col-form-label text-right">Portal</label>
                                <div class="form-group col-sm-1">
                                    <?php echo f_getSelectPortales('portal', '1', 'form-control', 'js_cantidadTotal()'); ?>
                                </div>
                                <label for="piso" class="col-sm-1 col-form-label text-right">Piso</label>
                                <div class="form-group col-sm-1"> 
                                    <?php echo f_getSelectPisos('piso', '1', 'form-control', 'js_cantidadTotal()'); ?>
                                </div>
                                <label for="letra" class="col-sm-1 col-form-label text-right">Letra</label>
                                <div class="form-group col-sm-1">
                                    <?php echo f_getSelectLetras('letra', 'A', 'form-control', 'js_cantidadTotal()'); ?>
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
