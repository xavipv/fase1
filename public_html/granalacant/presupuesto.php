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
        <?php echo f_getCabeceraHTML("Presupuesto"); ?>
    </head>
    <body onload="//$('#cantidad').focus();">
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
                                <h2 class="col-sm-10">C&aacute;lculo de presupuesto</h2>
                                <div class="col-sm-2 text-right">
                                    <button class="btn btn-outline-success" style="cursor: pointer" id="imprimir" disabled="true" title="Pantalla completa" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'presupuestolismax.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-fullscreen-enter"></span></button>
                                    <button class="btn btn-outline-success" style="cursor: pointer" id="imprimirpdf" disabled="true" title="Imprimir en un PDF" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'presupuestoprint.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-print"></span></button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">General:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="general" name="general" value="" placeholder="General" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 1:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span id="repetir" name="repetir" class="input-group-addon" style="display: none; cursor: pointer" onclick="js_copiarPresupuestos();"><span class="oi oi-arrow-thick-right"></span></span>
                                        <input type="text" class="form-control solonumeros" id="portal1" name="portal1" value="" placeholder="Portal 1" onclick="$(this).select();" onkeyup="js_presupuesto(); if($(this).val()>0){$('#repetir').show();}else{$('#repetir').hide();}">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 2:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal2" name="portal2" value="" placeholder="Portal 2" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 3:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal3" name="portal3" value="" placeholder="Portal 3" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div> 
                            </div>
                            <div class="form-group row">
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 4:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal4" name="portal4" value="" placeholder="Portal 4" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 5:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal5" name="portal5" value="" placeholder="Portal 5" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 6:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal6" name="portal6" value="" placeholder="Portal 6" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 7:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal7" name="portal7" value="" placeholder="Portal 7" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div> 
                            </div>
                            <div class="form-group row">
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 8:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal8" name="portal8" value="" placeholder="Portal 8" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 9:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal9" name="portal9" value="" placeholder="Portal 9" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 10:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal10" name="portal10" value="" placeholder="Portal 10" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 11:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal11" name="portal11" value="" placeholder="Portal 11" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div> 
                            </div>
                            <div class="form-group row">
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 12:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal12" name="portal12" value="" placeholder="Portal 12" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 13:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal13" name="portal13" value="" placeholder="Portal 13" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 14:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal14" name="portal14" value="" placeholder="Portal 14" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div>
                                <label for="cantidad" class="col-sm-1 col-form-label text-right">Portal 15:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control solonumeros" id="portal15" name="portal15" value="" placeholder="Portal 15" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                        <span class="input-group-addon">€</span>
                                    </div>
                                </div> 
                            </div>
                            <hr />
                            <div class="form-group row">
                                <label for="meses" class="col-sm-1 col-form-label text-right">Meses:</label>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control solonumeros" id="meses" name="meses" value="12" placeholder="Meses" onclick="$(this).select();" onkeyup="js_presupuesto()">
                                </div>
                                <div class="col-sm-2 text-center offset-1">
                                    <input type="checkbox" class="form-check-input" id="codigo" name="codigo"  onclick="js_presupuesto()">
                                    <label for="codigo" class="form-check-label">C&oacute;digo</label>
                                </div>
                                <div class="col-sm-2 text-center"">
                                    <input type="checkbox" class="form-check-input" id="metros" name="metros"  onclick="js_presupuesto()">
                                    <label for="metros" class="form-check-label">Metros</label>
                                </div>
                                <div class="col-sm-2 text-center"">
                                    <input type="checkbox" class="form-check-input" id="coeficientes" name="coeficientes" onclick="js_presupuesto()" checked="checked">
                                    <label for="coeficientes" class="form-check-label">Coeficientes</label>
                                </div>
                                <div class="col-sm-2 text-center"">
                                    <input type="checkbox" class="form-check-input" id="sumas" name="sumas"  onclick="js_presupuesto()" checked="checked">
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
