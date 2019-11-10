<?php
$pagina = basename(__FILE__);

// Paginas de '/includes' que se quieren a incluir en esta pagina.
$aIncludes = array('config.inc.php', 'funciones.inc.php', 'funciones.xajax.php');

// Carga las constantes.
(include(dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')) . "/cgi-bin/includes/constantes.inc.php")) or die("<p>Error al incluir <b>/cgi-bin/includes/constantes.inc.php</b></p>");

// Mira si hay datos recibidos.
$iPer = ( filter_input(INPUT_POST, 'persona') ) ? filter_input(INPUT_POST, 'persona') : filter_input(INPUT_GET, 'persona');
$onLo = ($iPer) ? "onload=\"xajax_setPersonasDatosForm($iPer)\"" : "";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php echo f_getCabeceraHTML("Personas"); ?>
    </head>
    <body <?php echo $onLo; ?>>
        <div id="cabecera">
            <!-- Barra de navegacion -->
            <?php include 'menu.php'; ?>
            <!-- Submenu -->
            <ul class="nav submenu">
                <li class="nav-item text-left col-sm-4">
                    <div id="submenu1" class="nav-link"><?php echo f_getPersonasIniciales(); ?></div>
                </li>
                <li class="nav-item text-center col-sm-4">
                    <div id="submenu2" class="nav-link" style="font-weight: bold;">Nueva persona</div>
                </li>
                <li class="nav-item text-right col-sm-4">
                    <div id="submenu3"><button type="button" class="btn btn-outline-success" onclick="xajax_setPersonasDatosForm(0);">Nueva persona</button></div>
                </li>
            </ul>
        </div>
        <!-- Contenido -->
        <div id="contenedor" class="container col-sm-9">
            <div class="row">
                <div id="divlistado" class="col-sm-3 hidden-ms hidden-xs listado"><?php echo f_getPersonasListado(); ?></div>
                <div id="contenido" class="col-sm-9">
                    <div id="divcabecera">
                        <h2>Persona</h2>
                        <form id="frmpersona">
                            <div class="row">
                                <div  class="form-group col-sm-2">
                                    <label for="codigo">C&oacute;digo</label>
                                    <input type="text" class="form-control" style="background-color: transparent" id="codigo" name="codigo" value="" placeholder="C&oacute;digo" readonly="readonly">
                                </div>
                                <div class="form-group col-sm-5">
                                    <label for="apellidos">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="" onkeyup="this.value=this.value.toUpperCase();" placeholder="Apellidos">
                                </div>
                                <div  class="form-group col-sm-5">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="" onkeyup="this.value=this.value.toUpperCase();" placeholder="Nombre">
                                </div>
                            </div> 
                            <div class="row">
                                <div  class="form-group col-sm-5">
                                    <label for="correo">Correo</label>
                                    <input type="email" class="form-control" id="correo" name="correo" value="" placeholder="Correo">
                                </div>
                                <div class="form-group col-sm-1">
                                    <label for="enviar">Enviar</label>
                                    <input type="checkbox" class="form-control" id="enviar" name="enviar">
                                </div>
                                <div  class="form-group col-sm-4">
                                    <label for="telefono">Tel&eacute;fono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="" placeholder="Tel&eacute;fono">
                                </div>
                                <div  class="form-group col-sm-2">
                                    <label for="sexo">Sexo</label>
                                    <select class="form-control" id="sexo" name="sexo">
                                        <option value=""></option>
                                        <option value="H" selected="selected">Hombre</option>
                                        <option value="M">Mujer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="notas">Notas</label>
                                    <textarea class="form-control" id="notas" name="notas" rows="3" value=""></textarea>
                                </div>
                            </div>
                            <button class="btn btn-success col-sm-2" type="button" onclick="xajax_grabarPersona(xajax.getFormValues('frmpersona'))">Guardar</button>
                            <button class="btn btn-warning col-sm-2 float-right" type="button" onclick="xajax_setPersonasDatosForm($('#codigo').val());">Restaurar</button>
                        </form>
                        <br />
                    </div>
                    <div id="divbusqueda" class="listado"></div>
                </div>
            </div>    
        </div>
        <div id="ainicio" class=""><a href="#inicio" title="Ir al inicio" role="button" class="btn btn-outline-secondary"><span class="oi oi-arrow-thick-top"></span></a></div>
        <!-- JavaScript -->
        <?php echo f_getScripts(); ?>
  </body>
</html>
