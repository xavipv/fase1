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
        <?php echo f_getCabeceraHTML("Actas"); ?>
    </head>
    <body onload="$('#buscar').focus(); xajax_busquedaAyuda()">
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <form id="frmbusqueda">
                <ul class="nav submenu">
                    <li class="nav-item text-center col-sm-6">
                        <div id="submenu1" class="nav-link">
                            <div class="input-group">
                                <div class="input-group-addon"><span class="oi oi-magnifying-glass"></span></div>
                                <input type="text" id="buscar" name="buscar" class="form-control" value="" onchange="if( $(this).val().length > 2){ $('#bobuscar').prop('disabled',false); }else{ $('#bobuscar').prop('disabled',true); }"  onkeyup="if( $(this).val().length > 2){ $('#bobuscar').prop('disabled',false); }else{ $('#bobuscar').prop('disabled',true); }" placeholder="Palabras a buscar">
                            </div>
                        </div>
                    </li>
                    <li class="nav-item text-center col-sm-4">
                        <div id="submenu2" class="nav-link">
                            <?php echo f_getSelect(array(0=>'Busqueda en lenguaje natural', 1=>'Busqueda booleana', 2=>'Busqueda literal'), "tipo", "", "form-control", "xajax_busquedaAyuda(this.value)"); ?>
                        </div>
                    </li>
                    <li class="nav-item text-center col-sm-2">
                        <div id="submenu3" class="nav-link">
                            <button id="bobuscar" class="btn btn-block btn-success" disabled="disabled" onclick="xajax_busquedaActas($('#buscar').val(),$('#tipo').val());return false;">Buscar actas</button>
                        </div>
                    </li>
                </ul>
            </form>
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container listadowrap col-sm-11"></div>
        <div id="ainicio" class=""><a href="#inicio" title="Ir al inicio" role="button" class="btn btn-outline-secondary"><span class="oi oi-arrow-thick-top"></span></a></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
