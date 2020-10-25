<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include ("../lib/const.php")) or (die("Error al incluir <b>../lib/const.php"));

// Array con los campos del formulario que se pueden elegir para ser mostrados o no.
$campos = array('coeur','coef200','coef100','coeblo','coegar','codigo','fase','metros','sumas','dife');

// Factor para calcular el ancho del listado a mostrar. Maximo el 100%.
$factor = intval(100 / (count($campos) + 4));

// Contador de campos a mostrar. Se inicia a 1 porque el Apartamento siempre se mostrara.
$c = 1; 
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Cálculo cuotas"); ?>
    </head>
    <body onload="xajax_getCalculos(xajax.getFormValues('frmdatos'));">
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
                    <h1>Listado de cuotas mensuales</h1>
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