<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Pruebas"); ?>
    </head>
    <body>
        <div id="contenedor" class="container">
            <div>
                <?php
                $orden = 2; 
                $o = array("por fechas", "por apartamentos", "por suma de deudas");
                $oDeudas = new Deudas();
                $oDeudas->setOrden($orden);
                $aDeudas = $oDeudas->getFiltradas();  
                ?>
                <p>Ordenado por <?php echo $o[$orden]; ?></p>
                <table class="table table-sm">
                <?php
                $t = "";
                foreach ($aDeudas as $aDatos) {
                    $t .= "<tr>";
                    for($i=0; $i<10; $i++) {
                        $t .= "<td>" . $aDatos[$i] . "</td>";
                    }
                    $t .= "</tr>";
                }
                echo $t;
                ?>
                </table>
            </div>
        </div>
            
        
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>

