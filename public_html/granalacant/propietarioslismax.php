<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

// Factor para calcular el ancho del listado a mostrar. Maximo el 100%.
$factor = intval(100 / 3);

// Contador de campos a mostrar. Se inicia a 2 que son los campos a mostrar por omision.
$c = 2; 
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Listado propietarios"); ?>
    </head>
    <body onload="xajax_getListadoPropietarios(xajax.getFormValues('frmdatos'));">
        <!-- Recupera los datos del formulario -->
        <form id="frmdatos" name="frmdatos">
            <?php 
            foreach ($_POST as $campo => $valor) { 
                if ($campo != "datosdiv") {
                    if ($campo == "verque") {
                        $c = ($valor == 1) ? 3 : 2;
                    }
                ?>
                <input type="hidden" id="<?php echo $campo; ?>" name="<?php echo $campo; ?>" value="<?php echo $valor; ?>" />
            <?php } } 
            $porcien = $c * $factor; ?>
        </form>
        <div id="contenedor" class="container" style="width: <?php echo "$porcien%"; ?>; max-width:100%">
            <div class="row">
                <div id="contenido" class="col-sm-12 text-center">
                    <h1>Listado de propietarios</h1>
                </div>
            </div>
            
            <div class="row">
                <div id="contenido" class="col-sm-12">
                    <div id="divbusqueda"></div>
                </div>
            </div>
        </div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
    </body>
</html>