<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

$oDeudas = new Deudas();
$aFechas = $oDeudas->getFechas();
$fFechaMay = $oDeudas->getFechaMayor()[0];
$fFechaMen = $oDeudas->getFechaMenor()[0];

$oAparts = new Apartamentos();
//$aPortales = $oAparts->getPortalesDistintos();
$aPortales = $oAparts->getPortalesLista();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Listado de deudas"); ?>
    </head>
    <body>
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
                            <form id="frmdatos" method="post" target="_blank" action="graph_deudapar.php" onsubmit="return false;">
                                <div class="form-group row">
                                    <h2 class="col-sm-12">Evoluci&oacute;n de la deuda por apartamento</h2>
                                </div>
                                <div class="form-group row" data-animation="false" data-toggle="tooltip" data-placement="right" data-trigger="hover" title="Deuda">
                                    <label for="tipodeuda" class="col-sm-2 col-form-label text-right">Tipo deuda:</label>
                                    <div class="col-sm-2"><?php echo f_getSelect(array('Total', 'Ordinaria', 'Extraordinaria'), 'tipodeuda', '', 'form-control'); ?></div>
                                    
                                    <label for="fechaini" class="col-sm-2 col-form-label text-right">Fecha inicial:</label>
                                    <div class="col-sm-2"><?php echo f_getSelectAgrupadoFechas($oDeudas->getFechas(), 'fechaini', $fFechaMen, 'form-control', 'xajax_getListadoDeudas(xajax.getFormValues(\'frmdatos\'))', TRUE); ?></div>
                                    
                                    <label for="fechafin" class="col-sm-2 col-form-label text-right">Fecha final:</label>
                                    <div class="col-sm-2"><?php echo f_getSelectAgrupadoFechas($oDeudas->getFechas(), 'fechafin', $fFechaMay, 'form-control', 'xajax_getListadoDeudas(xajax.getFormValues(\'frmdatos\'))', TRUE); ?></div>
                                </div>
                                <hr />
                                <div class="form-group row" data-animation="false" data-toggle="tooltip" data-placement="right" data-trigger="hover" title="Portales">
                                    <label for="portalini" class="col-sm-2 col-form-label text-right">Portal inicial:</label>
                                    <div class="col-sm-2"><?php echo f_getSelectPortales('portalini', '1'); ?></div>
                                    
                                    <label for="portalfin" class="col-sm-2 col-form-label text-right">Portal final:</label>
                                    <div class="col-sm-2"><?php echo f_getSelectPortales('portalfin', '26'); ?></div>
                                    
                                    <label for="tipografico" class="col-sm-2 col-form-label text-right">Tipo de gr&aacute;fico:</label>
                                    <div class="col-sm-2"><?php echo f_getSelect(array('Horizontal', 'Vertical'), 'tipografico', '', 'form-control'); ?></div>
                                </div>                               
                                <hr /><br />
                                <div class="form-group row">
                                    <button class="btn btn-success offset-sm-3 col-sm-2" type="button" id="botongrafico" onclick="document.getElementById('frmdatos').submit();">Mostrar gr&aacute;fico</button>
                                    <button class="btn btn-warning offset-sm-2 col-sm-2" type="button" id="botongrafico" onclick="document.getElementById('frmdatos').reset();">Resetear datos</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        <div id="ainicio" class=""><a href="#inicio" title="Ir al inicio" role="button" class="btn btn-outline-secondary"><span class="oi oi-arrow-thick-top"></span></a></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
