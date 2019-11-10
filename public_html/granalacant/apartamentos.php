<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

// Mira si hay datos recibidos.
$iApar = ( filter_input(INPUT_POST, 'apartamento') ) ? filter_input(INPUT_POST, 'apartamento') : filter_input(INPUT_GET, 'apartamento');

if(!$iApar) {
    $iApar = 1;
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Apartamentos"); ?>
    </head>
    <body onload="xajax_setApartamentosDatosForm(<?php echo $iApar; ?>);">
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <ul class="nav submenu">
                <li class="nav-item text-left col-sm-4">
                    <div id="submenu1" class="nav-link"><?php echo f_getApartamentosIniciales(); ?></div>
                </li>
                <li class="nav-item text-center col-sm-4">
                    <div id="submenu2" class="nav-link" style="font-weight: bold;">Apartamento nuevo</div>
                </li>
                <li class="nav-item text-right col-sm-4">
                    <div id="submenu2" class="nav-link"></div>
                </li>
            </ul>
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container col-sm-9">
            <div class="row">
                <div id="divlistado" class="col-sm-2 hidden-ms hidden-xs listado" style=""><?php echo f_getApartamentosListado(); ?></div>
                <div id="contenido" class="col-sm-10">
                    <div id="divcabecera">  
                        <div id="divformulario">
                            <h2>Apartamento</h2>
                            <form id="frmapartamento">
                                <div class="row">
                                    <div class="form-group col-sm-1">
                                        <label for="portal">Portal</label>
                                        <?php echo f_getSelectPortales(); ?>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="piso">Piso</label>
                                        <?php echo f_getSelectPisos(); ?>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="letra">Letra</label>
                                        <?php echo f_getSelectLetras(); ?>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="tipo">Tipo</label>
                                        <?php echo f_getSelectTipos(); ?>
                                    </div>
                                    <div  class="form-group col-sm-2">
                                        <label for="fase200">Coeficiente</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control solonumeros" id="fase200" name="fase200" value="" onkeyup="$('#fase100').val(($(this).val()/2).toFixed(5))" placeholder="Porcentaje">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                    <div  class="form-group col-sm-2">
                                        <label for="bloque">Bloque</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control solonumeros" id="bloque" name="bloque" value="" placeholder="Porcentaje">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                    <div  class="form-group col-sm-2">
                                        <label for="metros">Metros</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control solonumeros" id="metros" name="metros" value="" placeholder="Metros">
                                            <span class="input-group-addon">m2</span>
                                        </div>
                                    </div>
                                    <div  class="form-group col-sm-2">
                                        <label for="terraza">Terraza</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control solonumeros" id="terraza" name="terraza" value="" placeholder="Metros">
                                            <span class="input-group-addon">m2</span>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="form-group col-sm-1">
                                        <label for="codigo">C&oacute;digo</label>
                                        <input type="text" class="form-control solonumeros readonly" id="codigo" name="codigo" value="" title="" placeholder="C&oacute;digo" readonly="readonly" onfocus="setTimeout(function() { $('#registro').focus(); $('#registro').select(); }, 5);">
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="codigo">Finca</label>
                                        <input type="text" class="form-control solonumeros readonly" id="finca" name="finca" value="" title="" placeholder="Finca" readonly="readonly" onfocus="setTimeout(function() { $('#registro').focus(); $('#registro').select(); }, 5);">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="registro">Registro</label>
                                        <input type="text" class="form-control solonumeros" id="registro" name="registro" value="" title="" placeholder="Registro">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="catastral">Referencia catastral</label>
                                        <input type="text" class="form-control mayusculas" id="catastral" name="catastral" value="" onkeyup="$(this).val($(this).val().toUpperCase())" title="" placeholder="Ref. catastral">
                                    </div>
                                    <div  class="form-group col-sm-2">
                                        <label for="mecat">Metros catastro</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control solonumeros" id="mecat" name="mecat" value="" placeholder="Metros catastro">
                                            <span class="input-group-addon">m2</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="garajes">Garajes</label>
                                        <input type="text" class="form-control solonumeros readonly" id="garajes" name="garajes" value="" placeholder="Garajes" readonly="readonly" onfocus="setTimeout(function() { $('#portal').focus(); $('#portal').select(); }, 5);">
                                    </div>                                
                                </div>
                                <button class="btn btn-success col-sm-2" type="button" onclick="xajax_grabarApartamento(xajax.getFormValues('frmapartamento'))">Guardar</button>
                                <button class="btn btn-warning col-sm-2 float-right" type="button" onclick="xajax_setApartamentosDatosForm($('#codigo').val());">Restaurar</button>
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