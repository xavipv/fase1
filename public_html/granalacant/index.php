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
        <?php echo f_getCabeceraHTML("Inicio"); ?>
    </head>
    <body>
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
        </div>
        <!-- Contenido -->
        <div class="contenedor">
            <div id="divcabecera" class="text-center col-sm-12" style="padding: 50px">
                <h1>Gestión de datos de Gran Alacant Fase I</h1>
                <hr>
            </div>
            <div id="divcontenido" class="text-center col-sm-12 listado">
                <ul class="list-group col-sm-4" style="margin:auto">
                    <li class="list-group-item active"><b><?php echo $oInfo->getNombreBD(); ?></b></li>
                    <?php
                        foreach ($oInfo->getNombreTablasBD() as $tabla) {
                            echo "<li class=\"list-group-item\">$tabla</li>";
                        }
                    ?>
                </ul>
            </div> 
        </div>
        
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
