<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

// Array con los campos del formulario que se pueden elegir para ser mostrados o no.
$campos = array('codigo','finca','registro','tipo','fase','metros','terraza','garajes','urban','fase200','fase100','bloque');

// Factor para calcular el ancho del listado a mostrar. Maximo el 100%.
$factor = intval(100 / count($campos));

// Contador de campos a mostrar. Se inicia a 1 porque el Apartamento siempre se mostrara.
$c = 1; 
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Listado apartamentos"); ?>
    </head>
    <body onload="xajax_getListadoApartamentos(xajax.getFormValues('frmdatos'));">
        <!-- Recupera los datos del formulario -->
        <form id="frmdatos" name="frmdatos">
            <?php 
            foreach ($_POST as $campo => $valor) { 
                if ($campo != "datosdiv") {
                    // Si es un campo del array le suma 1.
                    $c += (in_array($campo, $campos)) ? 1 : 0;
                ?>
                <input type="hidden" id="<?php echo $campo; ?>" name="<?php echo $campo; ?>" value="<?php echo $valor; ?>" />
            <?php } } 
            $porcien = $c * $factor; ?>
        </form>
        <div id="contenedor" class="container" style="width: <?php echo "$porcien%"; ?>; max-width:100%">
            <div class="row">
                <div id="contenido" class="col-sm-12 text-center">
                    <h1>Listado de apartamentos</h1>
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