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
        <?php echo f_getCabeceraHTML("Apartamentos"); ?>
    </head>
    <body>
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <ul class="nav submenu">
                <li class="nav-item text-left col-sm-4">
                    <div id="submenu1" class="nav-link"><?php echo f_getApartamentosIniciales(); ?></div>
                </li>
                <li class="nav-item text-center col-sm-4">
                    <div id="submenu2" class="nav-link" style="font-weight: bold;">Metros y coeficientes de los apartamentos</div>
                </li>
                <li class="nav-item text-right col-sm-4">
                    <div id="submenu2" class="nav-link"></div>
                </li>
            </ul>
        </div>
        <div id="contenedor" class="container col-sm-10">
            <div id="divlistado" class="listado"><?php echo f_getDatosApartamentos(); ?></div>
        </div>
        <div id="aportales" class=""><button type="button" class="btn btn-outline-primary" data-toggle="popover" data-container="divformularioasis">Portales</button></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
