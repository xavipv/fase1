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

// Contador de campos a mostrar. Se inicia a 4 porque el Apartamento, € General, € Bloque y Cuota siempre se mostraran.
$c = 4; 
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Presupuesto"); ?>
    </head>
    <body onload="xajax_getPresupuesto(xajax.getFormValues('frmdatos'));">
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
                    <h1>Cuotas mensuales seg&uacute;n presupuesto</h1>
                </div>
                <div class="col-sm-12 text-center">
                    <table class="table table-sm text-right">
                        <tr><th colspan="10" class="text-left">Presupuestos de los portales:</th>
                        <?php
                        $g = $_POST['general'];         // Presupuesto general.
                        $s = 0;
                        for($i=0; $i<=14; $i++) 
                        {
                            $p = $i + 1;                // Portal.
                            $e = $_POST['portal' . $p]; // Euros
                            $s += $e;                   // Suma.
                            echo ($i % 5 == 0) ? "</tr><tr>" : "";
                            echo "<td>Portal&nbsp;$p:</td><td class=\"font-weight-bold\">" . number_format($e, 2, ",", ".") . "&euro;</td>";
                        }
                        ?>
                        </tr>
                        <tr><th colspan="10" class="text-left">Datos del presupuesto:</th></tr>
                        <tr>
                            <td colspan="3">Presupuesto&nbsp;general:</td><td class="font-weight-bold"><?php echo number_format($g, 2, ",", "."); ?>&euro;</td>
                            <td colspan="2">Suma&nbsp;portales:</td><td class="font-weight-bold"><?php echo number_format($s, 2, ",", "."); ?>&euro;</td>
                            <td colspan="2">Total&nbsp;presupuesto:</td><td class="font-weight-bold"><?php echo number_format(($g + $s), 2, ",", "."); ?>&euro;</td>
                        </tr>
                    </table>
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
