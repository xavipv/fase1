<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

$fec = ( filter_input(INPUT_POST, 'fecha') ) ? filter_input(INPUT_POST, 'fecha') : filter_input(INPUT_GET, 'fecha');

if(!$fec) {
    $oVots = new Votaciones();
    $fec = $oVots->getUltimaFechaVotacion();
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Votaciones"); ?>
    </head>
    <body onload="xajax_setVotacionDatosForm('<?php echo $fec; ?>')">
        <a name="iniciopagina"></a>
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <ul class="nav submenu" style="border:1px solid lightblue;padding: 3px 6px">
                <li class="nav-item text-left col-sm-8">
                    <div id="submenu1" class="nav-link"><?php echo f_getVotacionesAnyos(); ?></div>
                </li>
                <li class="nav-item text-right col-sm-4">
                    <div id="submenu2" class="nav-link"><?php echo f_getApartamentosIniciales(); ?></div>
                </li>
            </ul>
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container col-sm-10">
            <div class="row">
                <div id="divlistado" class="col-sm-1 hidden-ms hidden-xs listado"><?php echo f_getVotacionesListado(); ?></div>
                <div id="contenido" class="col-sm-11">
                    <form id="frmvotacion" name="frmvotacion" onsubmit="return false;">
                        <div id="divcabecera">
                            <table class="table table-sm" style="width:100%; border-bottom: 1px solid lightgray; font-size: 0.9em; margin: 0">
                                <tbody id="datosvot">
                                    <tr>
                                        <th class="text-left align-middle" colspan="2">Opciones para la votaci&oacute;n</th>
                                        <th class="text-right align-middle">Votos</th>
                                        <th class="text-right align-middle">Coeficientes</th>
                                        <th class="text-right align-middle">&nbsp;</th>
                                        <th class="text-right align-middle">&nbsp;</th>
                                        <th class="text-right align-middle">&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <td class="text-left" colspan="2"><input type="text" id="opcion1" name="opcion1" class="form-control form-control-sm" value="Sí" onkeyup="js_ponerEtiquetas(1);"></td>
                                        <td class="text-right"><input type="text" id="votos1" name="votos1" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></td>
                                        <td class="text-right"><input type="text" id="cof1" name="cof1" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></td>
                                        <th class="text-right">Asistentes</th>
                                        <td class="text-right"><input type="text" id="sumasis" name="sumasis" class="form-control form-control-sm solonumeros" value="No" readonly="readonly"></td>
                                        <td class="text-right"><button class="btn btn-primary btn-sm btn-block" onclick="js_ocultarFilas(false)" title="Mostrar todos los apartamentos"><span class="oi oi-task"></span></button></td>

                                    </tr>
                                    <tr>
                                        <td class="text-left" colspan="2"><input type="text" id="opcion2" name="opcion2" class="form-control form-control-sm" value="No" onkeyup="js_ponerEtiquetas(2);"></td>
                                        <td class="text-right"><input type="text" id="votos2" name="votos2" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></td>
                                        <td class="text-right"><input type="text" id="cof2" name="cof2" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></td>
                                        <th class="text-right">Con&nbsp;voto</th>
                                        <td class="text-right"><input type="text" id="sumvota" name="sumvota" class="form-control form-control-sm solonumeros" value="" readonly="readonly"></td>
                                        <td class="text-right"><button class="btn btn-danger btn-sm btn-block" onclick="js_ocultarFilas(true)" title="Mostrar solo los asistentes"><span class="oi oi-task"></span></button></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" colspan="2"><input type="text" id="opcion3" name="opcion3" class="form-control form-control-sm" value="Abstención" onkeyup="js_ponerEtiquetas(3);"></td>
                                        <td class="text-right"><input type="text" id="votos3" name="votos3" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></td>
                                        <td class="text-right"><input type="text" id="cof3" name="cof3" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></td>
                                        <th class="text-right">Sin&nbsp;voto</th>
                                        <td class="text-right"><input type="text" id="sumnovoto" name="sumnovoto" class="form-control form-control-sm solonumeros" value="" readonly="readonly"></td>
                                        <td class="text-right"><button class="btn btn-warning btn-sm btn-block" onclick="xajax_confirmarVotacionCambios($('#fecha').val(), $('#votacion').val());" title="Deshacer"><span class="oi oi-loop-circular"></span></button></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" colspan="2"><input type="text" id="opcion4" name="opcion4" class="form-control form-control-sm" value="" onkeyup="js_ponerEtiquetas(4);"></td>
                                        <td class="text-right"><input type="text" id="votos4" name="votos4" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></td>
                                        <td class="text-right"><input type="text" id="cof4" name="cof4" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></td>
                                        <th class="text-right">Presentes</th>
                                        <td class="text-right"><input type="text" id="sumpres" name="sumpres" class="form-control form-control-sm solonumeros" value="" readonly="readonly"></td>
                                        <td class="text-right"><button class="btn btn-success btn-sm btn-block" onclick="xajax_grabarVotacion(xajax.getFormValues('frmvotacion'));" title="Guardar"><span class="oi oi-hard-drive"></span></button></td>
                                    </tr>
                                </tbody>
                                    <tr>
                                        <td class="text-left" style="width: 25%" title="Junta"><input type="hidden" id="fechainicial" value=""><input type="text" id="fecha" name="fecha" class="form-control form-control-sm calendario" style="background-color:transparent" value="" readonly="readonly"></td>
                                        <td class="text-left" style="width: 25%" title="Votaci&oacute;n"><input type="hidden" id="votacioninicial" value=""><div id="selecvotacion"></div></td>
                                        <th class="text-right" style="width: 10%"><input type="text" id="sumvotos" name="sumvotos" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></th>
                                        <th class="text-right" style="width: 10%"><input type="text" id="sumcof" name="sumcof" class="form-control form-control-sm solonumeros" value="0" readonly="readonly"></th>
                                        <th class="text-right" style="width: 10%">Sincronizar</th>
                                        <td class="text-center" style="width: 10%"><input type="checkbox" id="sincro" name="sincro" checked="checked"></td>
                                        <td class="text-right" style="width: 10%"><input type="hidden" id="oculto" name="oculto" value="N"><button class="btn btn-default btn-sm btn-block" onclick="if($('#oculto').val() === 'N'){ $('#oculto').val('S'); $('#datosvot').hide(); $('#datosvot1').hide(); $('#caret').removeClass('oi-caret-top').addClass('oi-caret-bottom');  } else { $('#oculto').val('N'); $('#datosvot').show(); $('#datosvot1').show(); $('#caret').removeClass('oi-caret-bottom').addClass('oi-caret-top'); }; js_redimensionar();" title="Mostrar/Ocultar cabecera"><span id="caret" class="oi oi-caret-top"></span></button></td>
                                    </tr>
                                <tbody id="datosvot1">
                                    <tr>
                                        <td colspan="9"><textarea id="textvot" name="textvot" class="form-control form-control-sm" rows="1"></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="divcontenido" class="listado"></div>
                    </form>
                </div>
            </div>    
        </div>
        <div id="aportales" class=""><button type="button" class="btn btn-outline-primary" data-toggle="popover" data-container="divformularioasis">Portales</button></div>
        <!-- <div id="ainicio" class=""><a href="#iniciolis" title="Ir al inicio" role="button" class="btn btn-outline-secondary"><span class="oi oi-arrow-thick-top"></span></a></div>
        <!-- JavaScript -->
        <script id="scripts" type="text/javascript"></script>
        <?php echo f_getScripts(); ?>
  </body>
</html>
