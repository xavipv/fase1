<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include ("../lib/const.php")) or (die("Error al incluir <b>../lib/const.php"));

(require _LIBR_ . '/phplot/phplot.php') or die("<p>Error al incluir la libreria <b>phplot.php</b></p>");

// Obtiene los datos de las deudas en el ultima fecha guardada.
$oDeudas = new Deudas();
$aFechaMax = $oDeudas->getFechaMayor();
$aDeudas = $oDeudas->getDeudaFecha($aFechaMax[0]);
ksort($aDeudas);

// Agrupa la deuda total por portales.
$aDeudaPortal = array();
for($p=1; $p<=15; $p++) {
    // Inicializa el portal.
    $aDeudaPortal[$p-1][0] = "P-$p";
    $aDeudaPortal[$p-1][1] = 0;
    foreach ($aDeudas as $aApar) {
        if($aApar[0] == $p) {
            // Apartamento del portal, le suma la deuda total.
            $aDeudaPortal[$p-1][1] += $aApar[6];
        }
    }
}

if(false) {
    echo "<pre>";
    print_r($aFechaMax);
    print_r($aDeudaPortal);
    print_r($aDeudas);
    echo "</pre>";
    exit();
}

$titulo = 'Deuda por portales el día ' . $aFechaMax[1];

$plot = new PHPlot(1600, 900);
$plot->SetImageBorderType('plain');
$plot->SetPlotType('pie');
$plot->SetDataType('text-data-single');
$plot->SetDataValues($aDeudaPortal);
$plot->SetTitle(utf8_decode($titulo));
$plot->SetShading(0);

function mycallback($str)
{
    list($percent, $label) = explode(' ', $str, 2);
    return sprintf('%s (%.1f%%)', $label, $percent);
}
$plot->SetPieLabelType(array('percent', 'label'), 'custom', 'mycallback');

foreach ($aDeudaPortal as $i => $datos) {
    //echo "<pre>";print_r($datos);
    $x = ($i > 8) ? "" : " ";
    $n = number_format($datos[1], 2, ',', '.');
    $y = str_repeat(" ", 9 - strlen($n));
    $d = $datos[0] . ": $x$y$n"; 
    $plot->SetLegend($d);
    //$plot->SetLegend(implode(": ", $datos));
}
$plot->SetLegendPixels(20, 20);
$plot->SetLegendStyle(right, left);

$plot->DrawGraph();