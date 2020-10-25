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
        <?php echo f_getCabeceraHTML("Listado de apartamentos"); ?>
    </head>
    <body onload="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));">
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
                                    <h2 class="col-sm-10">Listado de apartamentos</h2>
                                    <div class="col-sm-2 text-right">
                                        <button class="btn btn-outline-success" style="cursor: pointer" id="imprimir" title="Pantalla completa" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'apartamentoslismax.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-fullscreen-enter"></span></button>
                                        <button class="btn btn-outline-success" style="cursor: pointer" id="imprimir" title="Imprimir en un PDF" onclick="$('#frmdatos').attr('target', '_blank'); $('#frmdatos').attr('action', 'apartamentosprint.php'); $('#frmdatos').attr('onsubmit', 'return true'); $('#frmdatos').submit();"><span class="oi oi-print"></span></button>
                                    </div>
                                </div>
                                <div class="form-group row" data-animation="false" data-toggle="tooltip" data-placement="right" data-trigger="hover" title="Datos a filtrar">
                                    <label for="tipo" class="col-sm-1 col-form-label text-right">De portal:</label>
                                    <div class="col-sm-1"><?php echo f_getSelectPortales('portal1', '1', 'form-control', 'js_rangoPortales();xajax_getListadoApartamentos(xajax.getFormValues(\'frmdatos\'))', FALSE); ?></div>
                                    <label for="tipo" class="col-sm-1 col-form-label text-right">A portal:</label>
                                    <div class="col-sm-1"><?php echo f_getSelectPortales('portal2', '15', 'form-control', 'js_rangoPortales();xajax_getListadoApartamentos(xajax.getFormValues(\'frmdatos\'))', FALSE); ?></div>
                                    <label for="tipo" class="col-sm-1 col-form-label text-right">Tipo:</label>
                                    <div class="col-sm-1"><?php echo f_getSelectTipos('contipo', '', 'form-control', 'xajax_getListadoApartamentos(xajax.getFormValues(\'frmdatos\'))', TRUE, TRUE); ?></div>
                                    <div class="col-sm-2 offset-sm-2">
                                        <input type="checkbox" class="form-check-input" id="congaraje" name="congaraje" onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));">
                                        <label for="congaraje" class="form-check-label">Con garaje</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="conterraza" name="conterraza" onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));">
                                        <label for="conterraza" class="form-check-label">Con terraza</label>
                                    </div>
                                    
                                </div>
                                <hr />
                                <div class="form-group row" data-animation="false" data-toggle="tooltip" data-placement="right" data-trigger="hover" title="Datos a mostrar">
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="codigo" name="codigo"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="codigo" class="form-check-label">C&oacute;digo</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="finca" name="finca"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="finca" class="form-check-label">Finca</label>
                                    </div>
                                    
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="tipo" name="tipo"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="tipo" class="form-check-label">Tipo</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="metros" name="metros"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="metros" class="form-check-label">Metros</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="terraza" name="terraza"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="terraza" class="form-check-label">Terraza</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="fase200" name="fase200"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="fase200" class="form-check-label">Coeficiente</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="bloque" name="bloque"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="bloque" class="form-check-label">Coef. bloque</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="registro" name="registro"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="registro" class="form-check-label">Registro</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="refcat" name="refcat"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="refcat" class="form-check-label">Ref. catastral</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="metcat" name="metcat"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="metcat" class="form-check-label">Metros cat.</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="garajes" name="garajes"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="garajes" class="form-check-label">Garajes</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-check-input" id="sumas" name="sumas"  onclick="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));" checked="checked">
                                        <label for="sumas" class="form-check-label">Sumas</label>
                                    </div>
                                </div>
                                <hr />
                                <input id="datosdiv" name="datosdiv" type="hidden" value="">
                            </form>
                            <br />
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
