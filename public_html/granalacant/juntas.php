<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

$fec = ( filter_input(INPUT_POST, 'fecha') ) ? filter_input(INPUT_POST, 'fecha') : filter_input(INPUT_GET, 'fecha');

if(!$fec) {
    $fec = Funciones::gConvertirFecha($oJuntas->getUltimaJunta(), FALSE);
} 
// Junta vacia.
$oJ = new Junta();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Juntas"); ?>
    </head>
    <body onload="xajax_setJuntaDatosForm('<?php echo $fec; ?>', '<?php echo $fec; ?>')">
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <ul class="nav submenu">
                <li class="nav-item text-left col-sm-10">
                    <div id="submenu1" class="nav-link"><?php echo f_getJuntasAnyos(); ?></div>
                </li>
                <li class="nav-item text-right col-sm-2">
                    <div id="submenu3"><button type="button" onclick="xajax_setJuntaDatosForm('<?php echo date('d-m-Y'); ?>'); js_calendario(false, true);" class="btn btn-outline-success">Nueva junta</button></div>
                </li>
            </ul>
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container col-sm-10">
            <div class="row">
                <div id="divlistado" class="col-sm-1 hidden-ms hidden-xs listado" style=""><?php echo f_getJuntasListado(); ?></div>
                <div id="contenido" class="col-sm-11">
                    <div id="divcontenido">
                        <h2 id="titulo1">Juntas</h2>
                        <hr />
                        <form id="frmjunta">
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="fecha">Fecha</label>
                                    <input type="text" class="form-control calendario" style="background-color: transparent" id="fecha" name="fecha" value="<?php echo $fec; ?>" title="" placeholder="Fecha" readonly="readonly">
                                    <input type="hidden" id="fechaoriginal" name="fechaoriginal" value="<?php echo $fec; ?>">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="tipo">Tipo</label>
                                    <?php echo f_getSelect($oJ->getTipos(), "tipo", "E", "form-control"); ?>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="convo">Convocatoria</label>
                                    <?php echo f_getSelect($oJ->getConvocatorias(), "convo", "2", "form-control"); ?>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="hora">Hora</label>
                                    <input type="time" class="form-control" id="hora" name="hora" value="10:00" placeholder="Hora">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="presi">Presidente</label>
                                    <?php echo f_getSelect($oPers->getNombresCompletos(), "presi", "", "form-control", "", TRUE); ?>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="asist">Asistentes</label>
                                    <input type="text" class="form-control solonumeros" id="asist" name="asist" value="0" placeholder="Asistentes" readonly="readonly">
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="repre">Representados</label>
                                    <input type="text" class="form-control solonumeros" id="repre" name="repre" value="0" placeholder="Representados" readonly="readonly">
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="votos">Votos</label>
                                    <input type="text" class="form-control solonumeros" id="votos" name="votos" value="0" placeholder="Votos" readonly="readonly">
                                </div>
                                
                                <div class="form-group col-sm-6">
                                    <label for="vice1">Vicepresidente 1</label>
                                    <?php echo f_getSelect($oPers->getNombresCompletos(), "vice1", "", "form-control", "", TRUE); ?>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="vice2">Vicepresidente 2</label>
                                    <?php echo f_getSelect($oPers->getNombresCompletos(), "vice2", "", "form-control", "", TRUE); ?>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="vocal1">Vocal 1</label>
                                    <?php echo f_getSelect($oPers->getNombresCompletos(), "vocal1", "", "form-control", "", TRUE); ?>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="vocal2">Vocal 2</label>
                                    <?php echo f_getSelect($oPers->getNombresCompletos(), "vocal2", "", "form-control", "", TRUE); ?>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="vocal3">Vocal 3</label>
                                    <?php echo f_getSelect($oPers->getNombresCompletos(), "vocal3", "", "form-control", "", TRUE); ?>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="vocal4">Vocal 4</label>
                                    <?php echo f_getSelect($oPers->getNombresCompletos(), "vocal4", "", "form-control", "", TRUE); ?>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="secre">Secretario</label>
                                    <?php echo f_getSelect($oPers->getNombresCompletos(), "secre", "", "form-control", "", TRUE); ?>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="admi">Administraci&oacute;n</label>
                                    <?php echo f_getSelect($oAdmins->getNombresAdministraciones(), "admi", "", "form-control", "xajax_getSecretario(this.value)", TRUE); ?>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="fase">Notas</label>
                                    <textarea id="notas" name="notas" rows="3" value="" class="form-control"></textarea>
                                </div>
                            </div> 
                            <button class="btn btn-success col-sm-2" type="button" id="botongrabar" onclick="xajax_grabarJunta(xajax.getFormValues('frmjunta'))">Guardar</button>
                            <button class="btn btn-primary col-sm-2 offset-1" type="button" id="boasistentes" onclick="$(location).attr('href', 'asistentes.php?fecha=' + $('#fecha').val())">Asistentes</button>
                            <button class="btn btn-warning col-sm-2 offset-1" type="button" onclick="xajax_setJuntaDatosForm($('#fechaoriginal').val(), $('#fechaoriginal').val());">Restaurar</button>
                            <button class="btn btn-danger col-sm-2 float-right" type="button" id="boeliminar" onclick="js_eliminarJunta($('#fecha').val())">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>    
        </div>
        <div id="ainicio" class=""><a href="#inicio" title="Ir al inicio" role="button" class="btn btn-outline-secondary"><span class="oi oi-arrow-thick-top"></span></a></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
