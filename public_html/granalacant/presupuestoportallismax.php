<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include ("../lib/const.php")) or (die("Error al incluir <b>../lib/const.php"));

// Array con los campos del formulario que se pueden elegir para ser mostrados o no.
$campos = array('codigo','metros','coeficientes','sumas');

// Factor para calcular el ancho del listado a mostrar. Maximo el 100%. Campos seleccionables + 4 fijos + 1 por doble coeficientes.
$factor = intval(100 / (count($campos) + 5));

// Contador de campos a mostrar. Se inicia a 3 porque el Apartamento, A pagar y Cuota siempre se mostraran.
$c = 3; 

$cantidad = $_POST['cantidad'];
$portal = $_POST['portal'];
switch($_POST['tipocoef']) {
    case 1: $tipocoef = "Coeficiente general aplicado al portal"; break;
    case 2: $tipocoef = "Coeficiente general"; break;
    case 3: $tipocoef = "Coeficiente de las plazas de garaje"; break;
    default: $tipocoef = "Coeficiente del portal"; break;
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Presupuesto"); ?>
    </head>
    <body onload="xajax_getPresupuestoPortal(xajax.getFormValues('frmdatos'));">
        <!-- Recupera los datos del formulario -->
        <form id="frmdatos" name="frmdatos">
            <?php 
            foreach ($_POST as $campo => $valor) { 
                if ($campo != "datosdiv") {
                    // Si es un campo del array le suma 1.
                    $c += (in_array($campo, $campos)) ? 1 : 0;
                    $c += ($campo == "coeficientes") ? 1 : 0;   // Agrega uno mas si son coeficientes.
                ?>
                <input type="hidden" id="<?php echo $campo; ?>" name="<?php echo $campo; ?>" value="<?php echo $valor; ?>" />
            <?php } } 
            $porcien = $c * $factor; ?>
        </form>
        <div id="contenedor" class="container" style="width: <?php echo "$porcien%"; ?>; max-width:100%">
            <div class="row">
                <div id="contenido" class="col-sm-12 text-center">
                    <h1>Presupuesto portal <?php echo $portal; ?></h1>
                    <hr>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-5 text-left">Total presupuesto: <b><?php echo number_format($cantidad, 2, ",", "."); ?>&nbsp;&euro;</b></div>
                        <div class="col-sm-7 text-left">Coeficiente: <b><?php echo $tipocoef; ?></b></div>
                    </div>
                </div>
            </div>
            <hr>
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
