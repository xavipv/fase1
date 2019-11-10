<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

(require _LIBR_ . '/phplot/phplot.php') or die("<p>Error al incluir la libreria <b>phplot.php</b></p>");

// 0 - Total. 1 - Ordinaria. 2 - Extraordinaria.
$tipodeuda = (isset($_POST['tipodeuda'])) ? $_POST['tipodeuda'] : 0;

// Fecha inicial y final de la deuda.
$fechaini = (isset($_POST['fechaini'])) ? $_POST['fechaini'] : '';
$fechafin = (isset($_POST['fechafin'])) ? $_POST['fechafin'] : '';

// Portal inicial y final.
$portalini = (isset($_POST['portalini'])) ? $_POST['portalini'] : '1';
$portalfin = (isset($_POST['portalfin'])) ? $_POST['portalfin'] : '15';

// 0 - Horizontal. 1 - Vertical
$tipografico = (isset($_POST['tipografico'])) ? $_POST['tipografico'] : 0;

// Obtiene los datos de las deudas.
$oDeudas = new Deudas();
$aDeudas = $oDeudas->getDeudas();   // array('fecha'=>array('portal'=>array('piso','letra','fase','ordinaria','extraordinaria','suma','fechaiso')...)...)

$fechaI = (!$fechaini) ? $oDeudas->getFechaMenor() : $fechaini;
$fechaF = (!$fechafin) ? $oDeudas->getFechaMayor() : $fechafin;

// Si la fecha inicial es mayor que la final, las gira.
if($fechaI > $fechaF) {
    $fechaX = $fechaI;
    $fechaI = $fechaF;
    $fechaF = $fechaX;
}

// Si el protal inicial es mayor que el final, los gira.
if($portalini > $portalfin) {
    $portalx = $portalini;
    $portalini = $portalfin;
    $portalfin = $portalx;
}

$aF = $oDeudas->getFechas();        // Obtiene las fechas de mayor a menor. Array('fechaBD' => 'fechaISO'...)
ksort($aF);                         // Ordena las fechas de menor a mayor.

// Filtra las fechas. Array('fechaBD' => 'fechaISO'...)
$aF1 = array();
foreach ($aF as $fBD => $fISO) {
    if($fBD >= $fechaI && $fBD <= $fechaF) {
        $aF1[$fBD] = $fISO;
    }
}

$aFechas = array_values($aF1);

// Numero de fechas que hay.
$imax = count($aFechas);

// Datos de las deudas filtrados por fechas y portales elegidos.
$aDeudasPortal = array();
foreach ($aDeudas as $fBD => $aApa) {
    // Comprueba que es una de las fechas seleccionadas.
    if(array_key_exists($fBD, $aF1)) {
        // $aApa --> array('codapar'=>array('portal','piso','letra','fase','ordinaria','extraordinaria','suma','fechaiso')
        foreach ($aApa as $iApa => $aDeu) {
            $por = $aDeu[0];    // Portal
            if($por >= $portalini && $por <= $portalfin) {
                // Apartamento.
                $aDeudasPortal[$iApa][0] = "$por-" . $aDeu[1] . $aDeu[2];
                
                // Deuda a guardar.
                switch ($tipodeuda) {
                    case 1:
                        $deuda = $aDeu[4];  // Ordinaria.
                        break;
                    case 2:
                        $deuda = $aDeu[5];  // Extraordianria.
                        break;
                    default:
                        $deuda = $aDeu[6];  // Total
                        break;
                }
                
                // Inicializa los datos.
                for($i=1; $i<=$imax; $i++) {
                    if(!isset($aDeudasPortal[$iApa][$i])) {
                        $aDeudasPortal[$iApa][$i] = 0;
                    }
                }
                // Pone la deuda en la fecha adecuada.
                $ind = array_search($aDeu[7], $aFechas) + 1;
                if($ind !== false) {
                    $aDeudasPortal[$iApa][$ind] = $deuda;
                }
            }
        }
    }
}

ksort($aDeudasPortal);     // Ordena los datos por numero de apartamento.

// Quita el codigo de los apartamentos de los arrays.
foreach ($aDeudasPortal as $aD) {
    $aGraph[] = $aD;
}

if(false) {
    echo "<pre>";
    echo "Tipo es $tipodeuda<br>";
    echo "Fecha inicial es $fechaini<br>";
    echo "Fecha final es $fechafin<br>";
    echo "Portal inicial es $portalini<br>";
    echo "Portal final es $portalfin<br>";
    echo "Tipo grafico es $tipografico<br>";

    print_r($aF1);
    print_r($aFechas);
    //print_r($aDeudas);
    print_r($aGraph);

    echo "</pre>";
    exit();
}

// Calcula el tamaño del grafico.
$ancho = count($aGraph) * 25 * $imax;
if($ancho < 800) {
    $ancho = 800;
}
$alto = 900;
//$incremento = 1000;
$horizontal = (!$tipografico) ? true : false;

switch ($tipodeuda) {
    case 1 : $titulo = "Evolución de la deuda ordinaria"; break;
    case 2 : $titulo = "Evolución de la deuda extraordinaria"; break;
    default: $titulo = "Evolución de la deuda total"; break;
}
$titulo .= ($portalini != $portalfin) ? " del portal $portalini al $portalfin" : " del portal $portalini";

$plot = ($horizontal) ? new PHPlot($ancho,$alto) : new PHPlot($alto, $ancho);   
$plot->SetImageBorderType('plain');
$plot->SetDataValues($aGraph);
$plot->SetLegend($aFechas);
$plot->SetPlotType('bars');
$plot->SetShading(0);
$plot->SetTitle(utf8_decode($titulo));



if($horizontal) {
    // Horizontal.
    if($ancho > 2000) {
        $plot->SetLegendPosition(0, 0, 'plot', 0, 0, 10, 0);
    }
    $plot->SetDataType('text-data');
    $plot->SetYTitle(utf8_decode('Deuda en euros'));
    $plot->SetYDataLabelPos('plotin');
    $plot->SetYDataLabelAngle(90);
    $plot->SetXTitle(utf8_decode('Apartamentos'));
    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');
    //$plot->SetYTickIncrement($incremento);
} else {
    // Vertical.
    $plot->SetDataType('text-data-yx');
    $plot->SetXTitle('Deuda en euros');
    $plot->SetXDataLabelPos('plotin');
    $plot->SetYTickPos('none');
    $plot->SetYTitle(utf8_decode('Apartamentos'));
    $plot->SetLegendReverse(true);
    //$plot->SetXDataLabelAngle(90);
    //$plot->SetXTickIncrement($incremento);
}
$plot->DrawGraph();
