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
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Asistentes"); ?>
    </head>
    <body onload="xajax_getAsistentes('<?php echo $fec; ?>')">
        <a name="iniciopagina"></a>
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <ul class="nav submenu">
                <li class="nav-item text-left col-sm-8">
                    <div id="submenu1" class="nav-link"><?php echo f_getJuntasAnyos(); ?></div>
                </li>
                <li class="nav-item text-right col-sm-4">
                    <div id="submenu2" class="nav-link"><?php echo f_getApartamentosIniciales(); ?></div>
                </li>
            </ul>
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container col-sm-10">
            <div class="row">
                <div id="divlistado" class="col-sm-1 hidden-ms hidden-xs listado"><?php echo f_getJuntasListado(); ?></div>
                <div id="contenido"  class="col-sm-11">
                    <div id="divcabecera">
                        <div id="btnfechair"></div>
                        <table class="table table-sm" style="width:100%; border-bottom: 1px solid lightgray; margin: 0; padding: 0">
                            <tbody id="datosvot">
                            <tr>
                                <td colspan="2" style="width:25%"><input type="checkbox" id="multiples" checked="checked" onclick="js_eliminarTooltips(this.checked);" title="Marcar usuario con varios apartamentos."><label class="form-check-label" for="multiples">Sincronizar</label></td>
                                <th class="text-right align-middle" style="width:15%">Apart.</th>
                                <th class="text-right align-middle" style="width:15%">Personas</th>
                                <th class="text-right align-middle" style="width:15%">Con&nbsp;voto</th>
                                <th class="text-right align-middle" style="width:15%">Sin&nbsp;voto</th>
                                <th class="text-right align-middle" style="width:15%">Coeficiente</th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-right">Asistentes</th>
                                <td id="asiapa" class="text-right">0</td>
                                <td id="asiper" class="text-right">0</td>
                                <td id="asisin" class="text-right">0</td>
                                <td id="asicon" class="text-right">0</td>
                                <td id="asi200" class="text-right">0%</td>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-right">Representados</th>
                                <td id="repapa" class="text-right">0</td>
                                <td id="repper" class="text-right">0</td>
                                <td id="repsin" class="text-right">0</td>
                                <td id="repcon" class="text-right">0</td>
                                <td id="rep200" class="text-right">0%</td>
                            </tr>
                            </tbody>
                            <tr>
                                <th class="text-left"><input type="hidden" id="oculto" name="oculto" value="N"><button class="btn btn-default btn-sm btn-block" onclick="if($('#oculto').val() === 'N'){ $('#oculto').val('S'); $('#datosvot').hide(); $('#caret').removeClass('oi-caret-top').addClass('oi-caret-bottom');  } else { $('#oculto').val('N'); $('#datosvot').show(); $('#caret').removeClass('oi-caret-bottom').addClass('oi-caret-top'); }; js_redimensionar();" title="Mostrar/Ocultar cabecera"><span id="caret" class="oi oi-caret-top"></span></button></th>
                                <th class="text-right">Sumas</th>
                                <th id="sumapa" class="text-right">0</th>
                                <th id="sumper" class="text-right">0</th>
                                <th id="sumsin" class="text-right">0</th>
                                <th id="sumcon" class="text-right">0</th>
                                <th id="sum200" class="text-right">0%</th>
                            </tr>
                        </table>
                    </div>
                    <div id="divcontenido" class="listado"></div>
                </div>
            </div>    
        </div>
        <div id="aportales" class=""><button type="button" class="btn btn-outline-primary" data-toggle="popover">Portales</button></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
