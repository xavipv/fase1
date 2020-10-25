<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include ("../lib/const.php")) or (die("Error al incluir <b>../lib/const.php"));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("C&aacute;lculos"); ?>
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
                                <h2 class="col-sm-10">C&aacute;lculo de cuotas mensuales</h2>
                                <div class="col-sm-2 text-right">
                                    <button class="btn btn-outline-success" style="cursor: pointer" id="imprimir" disabled="true" title="Pantalla completa" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'calculoslismax.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-fullscreen-enter"></span></button>
                                    <button class="btn btn-outline-success" style="cursor: pointer" id="imprimirpdf" disabled="true" title="Imprimir en un PDF" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'calculosprint.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-print"></span></button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cantidad" class="col-sm-1 col-form-label">A pagar</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="cantidad" name="cantidad" value="" placeholder="Cantidad" onclick="$(this).select();" onkeyup="js_cuotasMensuales()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="meses" class="col-sm-1 col-form-label text-right">Meses</label>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control solonumeros" id="meses" name="meses" value="1" placeholder="Meses" onclick="$(this).select();" onkeyup="js_cuotasMensuales()">
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <input type="checkbox" class="form-check-input" id="codigo" name="codigo"  onclick="js_cuotasMensuales()">
                                    <label for="codigo" class="form-check-label">C&oacute;digo</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="checkbox" class="form-check-input" id="metros" name="metros"  onclick="js_cuotasMensuales()" checked="checked">
                                    <label for="metros" class="form-check-label">Metros</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="checkbox" class="form-check-input" id="coef200" name="coef200" onclick="js_cuotasMensuales()" checked="checked">
                                    <label for="coef200" class="form-check-label">Coeficientes</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="checkbox" class="form-check-input" id="coeblo" name="coeblo" onclick="js_cuotasMensuales()">
                                    <label for="coeblo" class="form-check-label">Bloques</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="checkbox" class="form-check-input" id="coegar" name="coegar"  onclick="js_cuotasMensuales()">
                                    <label for="coegar" class="form-check-label">Garajes</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="checkbox" class="form-check-input" id="sumas" name="sumas"  onclick="js_cuotasMensuales()" checked="checked">
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
