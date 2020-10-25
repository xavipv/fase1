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
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Actas"); ?>
    </head>
    <body onload="xajax_getActaDatosForm('<?php echo $fec; ?>');">
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
                        <button type="button" class="btn btn-outline-success" onclick="xajax_getActaDatosForm()">Nueva acta</button>
                        <a id="aver" href="actas.php?fecha=<?php echo $fec; ?>" role="button" class="btn btn-outline-success" onclick=""><span class="oi oi-eye"></span></a>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Contenido -->
        
            <div id="contenedor" class="container col-sm-11">
                <div class="row">
                    <div id="divlistado" class="col-sm-1 hidden-ms hidden-xs listado" style=""><?php echo f_getActasListado(); ?></div>
                    <div id="contenido" class="col-sm-11">
                        <form id="frmacta" onsubmit="return false;">
                            <div id="divcabecera">
                                <h2 id="titulo">Acta nueva</h2>
                                <div class="row">
                                    <label for="fecha" class="col-sm-1 col-form-label text-right">Fecha:</label>
                                    <div class="col-sm-2">
                                        <input type="hidden" id="fechainicial" name="fechainicial" value="<?php echo $fec; ?>">
                                        <input type="text" id="fecha" name="fecha" class="calendario form-control" style="background-color: transparent;" readonly="readonly">
                                    </div> 
                                    <div class="col-sm-1 offset-6">
                                        <button id="bograbar" class="btn btn-block btn-outline-success" onclick="xajax_grabarActa(xajax.getFormValues('frmacta'))" title="Grabar los datos"><span class="oi oi-circle-check"></span></button>
                                    </div>
                                    <div class="col-sm-1">
                                        <button id="bograbar" class="btn btn-block btn-outline-warning" onclick="js_deshacerActa($('#fechainicial').val())" title="Restaurar datos originales"><span class="oi oi-loop-circular"></span></button>
                                    </div>
                                    <div class="col-sm-1">
                                        <button id="bograbar" class="btn btn-block btn-outline-danger" onclick="js_eliminarActa($('#fechainicial').val())" title="Eliminar el acta actual"><span class="oi oi-circle-x"></span></button>
                                    </div> 
                                </div>
                                <br />
                            </div>
                            <div id="divcontenido" class="listado"></div>
                        </form>
                    </div>
                </div>
            </div>
        
        <div id="ainicio" class=""><a href="#inicioacta" title="Ir al inicio" role="button" class="btn btn-outline-secondary"><span class="oi oi-arrow-thick-top"></span></a></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
