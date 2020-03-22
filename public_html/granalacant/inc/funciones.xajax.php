<?php
/**
 * Funciones de XAJAX.
 */

/**
 * Carga la librería de XAJAX.
 */
require_once _XAJX_;

$oPers = new Personas();
$oProps = new Propietarios();
$oApars = new Apartamentos();
$oAdmins = new Administraciones();
$oJuntas = new Juntas();
$oInfo = new Info();


/**
 * @var xajax Crea una instancia de XAJAX.
 */
$xajax = new xajax();

/**
 * Configura el XAJAX.
 */
$xajax->configure('characterEncoding', 'UTF-8');
$xajax->configure('javascript URI', _UXJX_);
$xajax->configure('debug', FALSE);

/**
 * Registra las funciones de XAJAX.
 */

$xajax->register(XAJAX_FUNCTION, 'buscar');
$xajax->register(XAJAX_FUNCTION, 'busquedaActas');
$xajax->register(XAJAX_FUNCTION, 'busquedaAyuda');
$xajax->register(XAJAX_FUNCTION, 'confirmarVotacionCambios');
$xajax->register(XAJAX_FUNCTION, 'deshacerDeuda');
$xajax->register(XAJAX_FUNCTION, 'eliminarActa');
$xajax->register(XAJAX_FUNCTION, 'eliminarAsistentes');
$xajax->register(XAJAX_FUNCTION, 'eliminarJunta');
$xajax->register(XAJAX_FUNCTION, 'eliminarPropiedad');
$xajax->register(XAJAX_FUNCTION, 'eliminarPropiedadPregunta');
$xajax->register(XAJAX_FUNCTION, 'eliminarPropietario');
$xajax->register(XAJAX_FUNCTION, 'eliminarPropietarioPregunta');
$xajax->register(XAJAX_FUNCTION, 'getActa');
$xajax->register(XAJAX_FUNCTION, 'getActaDatos');
$xajax->register(XAJAX_FUNCTION, 'getActaDatosForm');
$xajax->register(XAJAX_FUNCTION, 'getAsistentes');
$xajax->register(XAJAX_FUNCTION, 'getAsistentesJuntaSumas');
$xajax->register(XAJAX_FUNCTION, 'getCalculos');
$xajax->register(XAJAX_FUNCTION, 'getCantidadTotal');
$xajax->register(XAJAX_FUNCTION, 'getDatosCoeficientes');
$xajax->register(XAJAX_FUNCTION, 'getJuntasAnyos');
$xajax->register(XAJAX_FUNCTION, 'getJuntasListado');
$xajax->register(XAJAX_FUNCTION, 'getListadoApartamentos');
$xajax->register(XAJAX_FUNCTION, 'getListadoDeudas');
$xajax->register(XAJAX_FUNCTION, 'getListadoJunta');
$xajax->register(XAJAX_FUNCTION, 'getListadoPersonas');
$xajax->register(XAJAX_FUNCTION, 'getListadoPropietarios');
$xajax->register(XAJAX_FUNCTION, 'getPersonasIniciales');
$xajax->register(XAJAX_FUNCTION, 'getPresupuesto');
$xajax->register(XAJAX_FUNCTION, 'getPropietarios');
$xajax->register(XAJAX_FUNCTION, 'getRepresentantes');
$xajax->register(XAJAX_FUNCTION, 'getSecretario');
$xajax->register(XAJAX_FUNCTION, 'grabarApartamento');
$xajax->register(XAJAX_FUNCTION, 'grabarActa');
$xajax->register(XAJAX_FUNCTION, 'grabarAsistente');
$xajax->register(XAJAX_FUNCTION, 'grabarAsistenteMulti');
$xajax->register(XAJAX_FUNCTION, 'grabarDeuda');
$xajax->register(XAJAX_FUNCTION, 'grabarJunta');
$xajax->register(XAJAX_FUNCTION, 'grabarPersona');
$xajax->register(XAJAX_FUNCTION, 'grabarPropiedad');
$xajax->register(XAJAX_FUNCTION, 'grabarPropietario');
$xajax->register(XAJAX_FUNCTION, 'grabarVotacion');
$xajax->register(XAJAX_FUNCTION, 'reenviarFuncion');
$xajax->register(XAJAX_FUNCTION, 'setApartamentosDatosForm');
$xajax->register(XAJAX_FUNCTION, 'setAsistente');
$xajax->register(XAJAX_FUNCTION, 'setAsistenteMulti');
$xajax->register(XAJAX_FUNCTION, 'setDatosCoeficientes');
$xajax->register(XAJAX_FUNCTION, 'setDeudaDatosForm');
$xajax->register(XAJAX_FUNCTION, 'setJuntaDatosForm');
$xajax->register(XAJAX_FUNCTION, 'setPersonasDatosForm');
$xajax->register(XAJAX_FUNCTION, 'setPropiedadesPersonaDatosForm');
$xajax->register(XAJAX_FUNCTION, 'setPropietariosApartamentoDatosForm');
$xajax->register(XAJAX_FUNCTION, 'setTransformar');
$xajax->register(XAJAX_FUNCTION, 'setVotacionCabecera');
$xajax->register(XAJAX_FUNCTION, 'setVotacionDatosForm');

/**
 * Procesa las peticiones de XAJAX.
 */
$xajax->processRequest();

//--- GENERALES --------------------------------------------------------------//

/**
 * Realiza una busqueda segun la pagina en la que estemos.
 * 
 * @param string $pagina Nombre de la pagina.
 * @param string $texto Texto a buscar.
 * @return \xajaxResponse
 */
function buscar($pagina, $texto) {
    $response = new xajaxResponse();
    $html = "";
    switch ($pagina) {
        case 'personas.php' : $html = f_buscarPersonas($texto, "xajax_setPersonasDatosForm"); break;
        case 'apartamentos.php' : $html = f_buscarApartamentos($texto, "xajax_setApartamentosDatosForm"); break;
        case 'propietarios.php' : $html = f_buscarApartamentos($texto, "xajax_setPropietariosApartamentoDatosForm"); break;
        case 'propper.php' : $html = f_buscarPersonas($texto, "xajax_setPropiedadesPersonaDatosForm"); break;
        default: $html = ""; break;
    } 
    $response->assign("divbusqueda", "innerHTML", $html);
    return $response;
}

/**
 * Reenvia el control a la funcion adecuada segun la pagina de procedendia.
 * 
 * @param string $pagina Nombre de la pagina.
 * @param mixed $cod Codigo usado como primer parametro.
 * @param mixed $cod1 Codigo usado como segundo parametro (opcional).
 * @return \xajaxResponse
 */
function reenviarFuncion($pagina, $cod, $cod1='') {
    $response = new xajaxResponse();
    switch ($pagina) {
        case 'personas.php' : $response->call("xajax_setPersonasDatosForm", $cod); break;
        case 'propper.php' : $response->call("xajax_setPropiedadesPersonaDatosForm", $cod); break;
        case 'apartamentos.php' : $response->call("xajax_setApartamentosDatosForm", $cod); break;
        case 'propietarios.php' : $response->call("xajax_setPropietariosApartamentoDatosForm", $cod); break;
        case 'juntas.php' : $response->call("xajax_setJuntaDatosForm", $cod, $cod); break;
        case 'deudas.php' : $response->script("if (js_comprobarBotones()) { xajax_setDeudaDatosForm('$cod'); }"); break;
        case 'asistentes.php' : $response->script("if (js_comprobarBotones()) { xajax_getAsistentes('$cod'); }"); break;
        case 'votaciones.php' : $response->call("xajax_setVotacionDatosForm", $cod, $cod1); break;
        case 'actas.php' : $response->call("xajax_getActa", $cod); break;
        case 'actasedit.php' : $response->call("xajax_getActaDatosForm", $cod); break;
    } 
    return $response;
}

//--- PERSONAS ---------------------------------------------------------------//

/**
 * Obtiene las iniciales de la personas.
 * 
 * @param string $id Identificador del elemento donde se pondran las iniciales.
 * @return \xajaxResponse
 */
function getPersonasIniciales($id) {
    $response = new xajaxResponse();
    $response->assign($id, "innerHTML", f_getPersonasIniciales());
    return $response;
}

/**
 * Rellena el formulario de la persona con sus datos.
 * 
 * @param int $cod Codigo de persona.
 * @return \xajaxResponse
 */
function setPersonasDatosForm($cod=0) {
    $response = new xajaxResponse();
    $oPer = new Persona($cod);
    
    $cdi = $oPer->getCodigo();
    $ape = $oPer->getApellidos();
    $nom = $oPer->getNombre();
    $ncm = ($ape) ? $oPer->getNombreCompleto() : "Nueva persona";
    $cor = $oPer->getCorreo();
    $env = ($oPer->getEnvios() == 'S') ? TRUE : FALSE;
    $tel = $oPer->getTelefono();
    $sex = $oPer->getSexo();
    $not = $oPer->getNotas();
    
    $response->assign("submenu2", "innerHTML", $ncm);
    $response->assign("codigo", "value", $cdi);
    $response->assign("apellidos", "value", $ape);
    $response->assign("nombre", "value", $nom);
    $response->assign("correo", "value", $cor);
    $response->assign("enviar", "checked", $env);
    $response->assign("telefono", "value", $tel);
    $response->assign("sexo", "value", $sex);
    $response->assign("notas", "value", $not);
    $response->script("$('#apellidos').focus()");
    return $response;
}

/**
 * Graba los datos de la persona y refresca el listado.
 * 
 * @param array $frm Datos del formulario.
 * @return \xajaxResponse
 */
function grabarPersona($frm) {
    $response = new xajaxResponse();
    $msg = f_grabarPersona($frm);
    $response->alert($msg);
    $response->assign("submenu1", "innerHTML", f_getPersonasIniciales());
    $response->assign("divlistado", "innerHTML", f_getPersonasListado());
    return $response;
}

//--- APARTAMENTOS -----------------------------------------------------------//

/**
 * Rellena el formulario del apartamento.
 * 
 * @param int $cod Codigo de apartamento.
 * @return \xajaxResponse
 */
function setApartamentosDatosForm($cod=1) {
    $response = new xajaxResponse();
    $oApa = new Apartamento($cod);
    
    $cdi = $oApa->getCodigo();
    $por = $oApa->getPortal();
    $pis = $oApa->getPiso();
    $let = $oApa->getLetra();
    //$fas = $oApa->getFase();
    $tip = $oApa->getTipo();
    $met = number_format($oApa->getMetros(), 2, '.', '');
    $ter = number_format($oApa->getTerraza(), 2, '.', '');
    //$cou = number_format($oApa->getCoeficiente(), 4, '.', '');
    $co2 = number_format($oApa->getCoeficienteFase(), 4, '.', '');
    //$co1 = number_format($oApa->getCoeficienteFase() / 2, 5, '.', '');
    $cob = number_format($oApa->getCoeficienteBloque(), 2, '.', '');
    $fin = $oApa->getFinca();
    $reg = number_format($oApa->getRegistro(), 0, '.', '');
    $cat = $oApa->getReferenciaCatastral();
    $mca = number_format($oApa->getMetrosCatastrales(), 2, '.', '');
    $gar = $oApa->getGarajesNumero();
    $apa = $oApa->getApartamento();
    $grs = f_getGarajesPlano($oApa);
    
    $response->assign("submenu2", "innerHTML", $apa);
    
    //$response->assign("fase", "value", $fas);
    $response->assign("portal", "value", $por);
    $response->assign("piso", "value", $pis);
    $response->assign("letra", "value", $let);
    $response->assign("tipo", "value", $tip);
    $response->assign("fase200", "value", $co2);
    $response->assign("bloque", "value", $cob);
    $response->assign("metros", "value", $met);
    $response->assign("terraza", "value", $ter);
    //$response->assign("urba", "value", $cou);
    //$response->assign("fase100", "value", $co1);
    
    $response->assign("codigo", "value", $cdi);
    $response->assign("finca", "value", $fin);
    $response->assign("registro", "value", $reg);
    $response->assign("catastral", "value", $cat);
    $response->assign("mecat", "value", $mca);
    $response->assign("garajes", "value", $gar);
    $response->assign("divbusqueda", "innerHTML", $grs);
    $response->script("$('#portal').focus()"); 
    
    return $response;
}

/**
 * Graba los datos del apartamento.
 * 
 * @param array $frm Datos del formulario.
 * @return \xajaxResponse
 */
function grabarApartamento($frm) {
    $response = new xajaxResponse();
    $msg = f_grabarApartamento($frm);
    
    $response->alert($msg);
    
    return $response;
}

//--- COEFICIENTES -----------------------------------------------------------//

/**
 * Rellena los metros y coeficientes de un apartamento.
 * 
 * @param int $cod Codigo del apartamento.
 * @param int $por Numero de portal.
 * @return \xajaxResponse
 */
function getDatosCoeficientes($cod, $por) {
    $response = new xajaxResponse();
    $oApa = new Apartamento($cod);
    
    $met = number_format($oApa->getMetros(), 2, '.', '');
    $ter = number_format($oApa->getTerraza(), 2, '.', '');
    //$cou = number_format($oApa->getCoeficiente(), 4, '.', '');
    $co2 = number_format($oApa->getCoeficienteFase(), 4, '.', '');
    //$co1 = number_format($oApa->getCoeficienteFase() / 2, 5, '.', '');
    $cob = number_format($oApa->getCoeficienteBloque(), 2, '.', '');
    
    $response->assign("me$cod", "value", $met);
    $response->assign("te$cod", "value", $ter);
    //$response->assign("cu$cod", "value", $cou);
    $response->assign("cf$cod", "value", $co2);
    //$response->assign("cr$cod", "value", $co1);
    $response->assign("cb$cod", "value", $cob);
    
    $response->script("js_sumarTodos($por)");
    
    return $response;
}

/**
 * Graba los metros y coeficientes de un apartamento.
 * 
 * @param int $cod Codigo del apartamento.
 * @param int $met Metros cuadrados del apartamento.
 * @param int $ter Metros cuadrados de la terraza.
 * @param int $cof Coeficiente de fase.
 * @param int $cob Coeficiente de bloque.
 * @return \xajaxResponse
 */
function setDatosCoeficientes($cod, $met, $ter, $cof, $cob) {
    $response = new xajaxResponse();
    $msg = f_grabarCoeficientes($cod, $met, $ter, $cof, $cob);
    if(substr($msg, 0, 5) != "Error") {
        $response->script("$('#boton$cod').prop('disabled',true)");
    }
    $response->alert($msg);
    
    return $response;
}

//--- PROPIETARIOS -----------------------------------------------------------//

/**
 * Obtiene los propietarios de un apartamento y rellena los datos.
 * 
 * @param int $apa Codigo de apartamento.
 * @return \xajaxResponse
 */
function setPropietariosApartamentoDatosForm($apa) {
    $response = new xajaxResponse();
    $oApar = new Apartamento($apa);
    $response->assign("submenu2", "innerHTML", $oApar->getApartamento());
    $response->assign("frmpropietarios", "innerHTML", f_getPropietarios($apa));
    $response->script("js_calendario(true, false); js_redimensionar()");
    return $response;
}

/**
 * Graba los datos de un propietario.
 * 
 * @param int $apa Codigo de apartamento.
 * @param int $per Codigo de persona.
 * @param int $ord Orden.
 * @param date $baj Fecha de baja en cualquier formato.
 * @return \xajaxResponse
 */
function grabarPropietario($apa, $per, $ord, $baj) {
    $response = new xajaxResponse();
    if (f_grabarPropietario($apa, $per, $ord, $baj)) {
        // Correcto. Refresca los datos.
        $response->call("xajax_setPropietariosApartamentoDatosForm", $apa);
    } else {
        // Error, muestra un mensaje.
        $response->alert("Error al grabar los propietarios.");
    }
    return $response;
}

/**
 * Pide confirmacion antes de eliminar un propietario.
 * 
 * @param int $apa Codigo de apartamento.
 * @param int $per Codigo de persona.
 * @return \xajaxResponse
 */
function eliminarPropietarioPregunta($apa, $per) {
    $response = new xajaxResponse();
    $response->confirmCommands(1, "¿Seguro que quieres eliminar el propietario de este apartamento?");
    $response->call("xajax_eliminarPropietario", $apa, $per);
    return $response;
}

/**
 * Elimina el propietario de la base de datos.
 * 
 * @param int $apa Codigo de apartamento.
 * @param int $per Codigo de persona.
 * @return \xajaxResponse
 */
function eliminarPropietario($apa, $per) {
    $response = new xajaxResponse();
    if (f_eliminarPropietario($apa, $per)) {
        // Correcto. Refresca los datos.
        $response->call("xajax_setPropietariosApartamentoDatosForm", $apa);
    } else {
        // Error, muestra un mensaje.
        $response->alert("Error al eliminar el propietario.");
    }
    return $response;
}


//--- PROPIEDADES ------------------------------------------------------------//

/**
 * Obtiene las propiedades de una persona y rellena los datos.
 * 
 * @param int $per Codigo de persona.
 * @return \xajaxResponse
 */
function setPropiedadesPersonaDatosForm($per) {
    $response = new xajaxResponse();
    $oPer = new Persona($per);
    $response->assign("submenu2", "innerHTML", $oPer->getNombreCompleto());
    $response->assign("frmpropiedades", "innerHTML", f_getPropiedades($per));
    $response->script("js_calendario(true, false); js_redimensionar()");
    return $response;
}

/**
 * Graba los datos de una propiedad.
 * 
 * @param int $per Codigo de persona.
 * @param int $apa Codigo de apartamento.
 * @param int $ord Orden.
 * @param date $baj Fecha de baja en cualquier formato.
 * @return \xajaxResponse
 */
function grabarPropiedad($per, $apa, $ord, $baj) {
    $response = new xajaxResponse();
    if (f_grabarPropiedad($per, $apa, $ord, $baj)) {
        // Correcto. Refresca los datos.
        $response->call("xajax_setPropiedadesPersonaDatosForm", $per);
    } else {
        // Error, muestra un mensaje.
        $response->alert("Error al grabar las propiedades.");
    }
    return $response;
}

/**
 * Pide confirmacion antes de eliminar una propiedad.
 * 
 * @param int $per Codigo de persona.
 * @param int $apa Codigo de apartamento.
 * @return \xajaxResponse
 */
function eliminarPropiedadPregunta($per, $apa) {
    $response = new xajaxResponse();
    $response->confirmCommands(1, "¿Seguro que quieres eliminar la propiedad de este propietario?");
    $response->call("xajax_eliminarPropiedad", $per, $apa);
    return $response;
}

/**
 * Elimina una propiedad.
 * 
 * @param int $per Codigo de persona.
 * @param int $apa Codigo de apartamento.
 * @return \xajaxResponse
 */
function eliminarPropiedad($per, $apa) {
    $response = new xajaxResponse();
    if (f_eliminarPropiedad($per, $apa)) {
        // Correcto. Refresca los datos.
        $response->call("xajax_setPropiedadesPersonaDatosForm", $per);
    } else {
        // Error, muestra un mensaje.
        $response->alert("Error al eliminar la propiedad.");
    }
    return $response;
}

//--- JUNTAS - DATOS ---------------------------------------------------------//

/**
 * Pone una lista de años en los que ha habido juntas.
 * 
 * @param string $id Identificador del elemento donde se pondra la lista.
 * @return \xajaxResponse
 */
function getJuntasAnyos($id) {
    $response = new xajaxResponse();
    $response->assign($id, "innerHTML", f_getJuntasAnyos());
    return $response;
}

/**
 * Pone una lista con las fechas de las juntas guardadas.
 * 
 * @param string $id Identificador del elemento donde se pondra la lista.
 * @return \xajaxResponse
 */
function getJuntasListado($id) {
    $response = new xajaxResponse();
    $response->assign($id, "innerHTML", f_getJuntasListado());
    return $response;
}

/**
 * Pone los datos de una Junta.
 * 
 * @global Juntas $oJuntas Intancia de Juntas.
 * @param date $fecha Fecha de la Junta.
 * @param date $original Fecha original (vacio si es una Junta nueva).
 * @return \xajaxResponse
 */
function setJuntaDatosForm($fecha='', $original='') {
    //global $oJuntas;
    
    $response = new xajaxResponse();
    $fechaISO = Funciones::gConvertirFecha($fecha, FALSE);
    
    if ($original) {
        // Junta existente.
        $aDat = f_setJuntaDatosExistente($fechaISO);
        $aSum = f_getAsistentesSumas($original);
    } else {
        // Junta nueva.
        $aDat = f_setJuntaDatosNueva($fechaISO);
        $aSum = f_getAsistentesSumas();
    }
    // Pone los datos en el formulario. 
    if ($aDat[17]) {
        // array(0 $fec, 1 $ori, 2 $tip, 3 $con, 4 $hor, 5 $pre, 6 $vi1, 7 $vi2, 8 $vo1, 9 $vo2, 10 $vo3, 11 $vo4, 12 $sec, 13 $adm, 14 $not, 15 $tit, 16 $src, 17 $iok);
        $response->assign("titulo1", "innerHTML", $aDat[15]);    
        $response->assign("fecha", "value", $aDat[0]);
        $response->assign("fechaoriginal", "value", $aDat[1]);
        $response->assign("tipo", "value", $aDat[2]);
        $response->assign("convo", "value", $aDat[3]);
        $response->assign("hora", "value", $aDat[4]);
        $response->assign("presi", "value", $aDat[5]);
        $response->assign("vice1", "value", $aDat[6]);
        $response->assign("vice2", "value", $aDat[7]);
        $response->assign("vocal1", "value", $aDat[8]);
        $response->assign("vocal2", "value", $aDat[9]);
        $response->assign("vocal3", "value", $aDat[10]);
        $response->assign("vocal4", "value", $aDat[11]);
        $response->assign("secre", "value", $aDat[12]);
        $response->assign("admi", "value", $aDat[13]);
        $response->assign("notas", "value", $aDat[14]);
        $response->assign("asist", "value", $aSum[0]);
        $response->assign("repre", "value", $aSum[1]);
        $response->assign("votos", "value", $aSum[2]);
    } 
    if ($aDat[16]) { 
        $response->script($aDat[16]); 
    }
    return $response;
}

/**
 * Dando el codigo de una administracion selecciona su secretario.
 * 
 * @global Administraciones $oAdmins Instancia de Administraciones.
 * @param int $adm Codigo de administracion.
 * @return \xajaxResponse
 */
function getSecretario($adm) {
    global $oAdmins;
    $response = new xajaxResponse();
    $sec = $oAdmins->getAdministrador($adm);
    if ($sec) {
        $response->assign("secre", "value", $sec);
    }
    return $response;
}

/**
 * Graba los datos de una Junta.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @param array $frm Datos de la Junta.
 * @return \xajaxResponse
 */
function grabarJunta($frm) {
    $response = new xajaxResponse();
    $fec = $frm['fecha'];     
    if (f_grabarJunta($frm)) {
        $response->call("xajax_getJuntasAnyos", "submenu1");
        $response->call("xajax_getJuntasListado", "divlistado");
        $response->call("xajax_setJuntaDatosForm", $fec, $fec);
    } else {
        $response->alert("No se han podido grabar los datos de la Junta.");
    } 
    return $response;
}

/**
 * Elimina los datos de una Junta.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @param date $fecha Fecha de la Junta a eliminar.
 * @return \xajaxResponse
 */
function eliminarJunta($fecha) {
    global $oJuntas;
    $response = new xajaxResponse();
    $oJunta = new Junta($fecha);
    if ($oJunta->eliminar()) {
        $oJuntas->recargar();   // Recarga las Juntas.
        $fec = $oJuntas->getUltimaJunta();
        $response->call("xajax_getJuntasAnyos", "submenu1");
        $response->call("xajax_getJuntasListado", "divlistado");
        $response->call("xajax_setJuntaDatosForm", $fec, $fec);
        $response->script("js_eliminarAsistentes('$fecha')");
    }
    return $response;
}

//--- JUNTA - ASISTENTES -----------------------------------------------------//

/**
 * Pone los datos de los asistentes a una Junta.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @param date $dat Fecha de la Junta.
 * @return \xajaxResponse
 */
function getAsistentes($dat='') {
    global $oJuntas;
    $response = new xajaxResponse();
    $date = ($oJuntas->existeJunta($dat)) ? $dat : "";  // Mira si existe la fecha.
    $fecha = (!$date) ? f_getUltimaJunta() : $date;     // Saca los datos de la fecha o de la ultima junta.
    
    $response->assign("divcontenido", "innerHTML", f_getAsistentes($fecha));
    $response->call("xajax_getAsistentesJuntaSumas", $fecha);
    
    return $response;
}

/**
 * Obtiene un select con los propietarios de un apartamento.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @param date $dat Fecha.
 * @param int $apa Codigo de apartamento.
 * @param int $sel Persona seleccionada.
 * @return \xajaxResponse
 */
function getPropietarios($dat, $apa, $sel='') {
    global $oJuntas;
    $response = new xajaxResponse();
    $date = ($oJuntas->existeJunta($dat)) ? $dat : "";  // Mira si existe la fecha.
    $fecha = (!$date) ? f_getUltimaJunta() : $date;     // Saca los datos de la fecha o de la ultima junta.
    $onc = "xajax_setAsistenteMulti('$fecha', '$apa', $('#nombre$apa').val(), $('#voto$apa').prop('checked'), $('#repr$apa').prop('checked'), $('#multiples').prop('checked'));";
    $response->assign("selec$apa", "innerHTML", f_getPropietariosRepresentantes($apa, $fecha, $sel, 'N', $onc));
    if (!$sel) {
        $response->script("$('#boton$apa').prop('disabled',true)");
    } else {
        $response->script("$('#boton$apa').prop('disabled',false)");
    }
    return $response;
}

/**
 * Obtiene un select con los representantes de un apartamento.
 * Muestra todas las personas, menos los propietarios actuales.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @param date $dat Fecha.
 * @param int $apa Codigo de apartamento.
 * @param int $sel Persona seleccionada.
 * @return \xajaxResponse
 */
function getRepresentantes($dat, $apa, $sel='') {
    global $oJuntas, $oProps; //$oPers;
    $response = new xajaxResponse();
    
    $date = ($oJuntas->existeJunta($dat)) ? $dat : "";  // Mira si existe la fecha.
    $fecha = (!$date) ? f_getUltimaJunta() : $date;     // Saca los datos de la fecha o de la ultima junta.
    //$ult = (!$sel) ? $oPers->getUltimoRepresentante($apa, $fecha) : $sel;
    $ult = (!$sel) ? $oProps->getUltimoRepresentante($apa, $fecha) : $sel;
    $onc = "xajax_setAsistenteMulti('$fecha', '$apa', $('#nombre$apa').val(), $('#voto$apa').prop('checked'), $('#repr$apa').prop('checked'), $('#multiples').prop('checked'));";
    $response->assign("selec$apa", "innerHTML", f_getPropietariosRepresentantes($apa, $fecha, $ult, 'S', $onc));
    if (!$ult) {
        $response->script("$('#boton$apa').prop('disabled',true)");
        $response->assign("voto$apa", "checked", FALSE);
    } else {
        $response->script("$('#boton$apa').prop('disabled',false)");
        $response->assign("voto$apa", "checked", TRUE);
    }
    $response->script("xajax_setAsistenteMulti('$fecha', '$apa', '$ult', $('#voto$apa').prop('checked'), $('#repr$apa').prop('checked'), $('#multiples').prop('checked'));");
    return $response;
}

/**
 * Obtiene las sumas de los asistentes a una Junta.
 * 
 * @param date $fecha Fecha de la Junta.
 * @return \xajaxResponse
 */
function getAsistentesJuntaSumas($fecha) {
    $response = new xajaxResponse();
    $oAsis = new Asistentes($fecha);
    $aSumas = $oAsis->getSumas();   // array('prop' => array('propietarios', 'distintos', 'con voto', 'sin voto', 'coeficiente'), 'repr' => array('representados', 'distintos', 'con voto', 'sin voto', 'coeficiente'))
    $aProps = $aSumas['prop'];
    $aReprs = $aSumas['repr'];
    $response->assign("btnfechair", "innerHTML", "<a class=\"btn btn-outline-primary\" onclick=\"return js_comprobarBotones();\" href=\"juntas.php?fecha=$fecha\" role=\"button\" title=\"Ir a datos de la Junta\"><span class=\"oi oi-calendar\"></span>&nbsp;&nbsp;" . Funciones::gConvertirFecha($fecha, FALSE) . "</a>");
    
    $response->assign("asiapa", "innerHTML", $aProps[0]);
    $response->assign("asiper", "innerHTML", $aProps[1]);
    $response->assign("asisin", "innerHTML", $aProps[2]);
    $response->assign("asicon", "innerHTML", $aProps[3]);
    //$response->assign("asiurb", "innerHTML", number_format($aProps[4],4) . "%");
    $response->assign("asi200", "innerHTML", number_format($aProps[4],4) . "%");
    //$response->assign("asi100", "innerHTML", number_format($aProps[5]/2,5) . "%");
    
    $response->assign("repapa", "innerHTML", $aReprs[0]);
    $response->assign("repper", "innerHTML", $aReprs[1]);
    $response->assign("repsin", "innerHTML", $aReprs[2]);
    $response->assign("repcon", "innerHTML", $aReprs[3]);
    //$response->assign("repurb", "innerHTML", number_format($aReprs[4],4) . "%");
    $response->assign("rep200", "innerHTML", number_format($aReprs[4],4) . "%");
    //$response->assign("rep100", "innerHTML", number_format($aReprs[5]/2,5) . "%");
    
    $response->assign("sumapa", "innerHTML", $aProps[0] + $aReprs[0]);
    $response->assign("sumper", "innerHTML", $aProps[1] + $aReprs[1]);
    $response->assign("sumsin", "innerHTML", $aProps[2] + $aReprs[2]);
    $response->assign("sumcon", "innerHTML", $aProps[3] + $aReprs[3]);
    //$response->assign("sumurb", "innerHTML", (number_format($aProps[4],4) + number_format($aReprs[4],4)) . "%");
    $response->assign("sum200", "innerHTML", (number_format($aProps[4],4) + number_format($aReprs[4],4)) . "%");
    //$response->assign("sum100", "innerHTML", (number_format($aProps[5]/2,5) + number_format($aReprs[5]/2,4)) . "%");
    
    return $response;
}

/**
 * Pone los datos de un asistente a una Junta.
 * 
 * @param date $fecha Fecha de la Junta.
 * @param int $apar Codigo de apartamento.
 * @return \xajaxResponse
 */
function setAsistente($fecha, $apar) {
    $response = new xajaxResponse();
    $oAsi = new Asistente($fecha, $apar);
    $vot = ($oAsi->getVoto() == 'S') ? true : false;
    $rep = ($oAsi->getRepresentado() == 'S') ? true : false;
    $per = $oAsi->getPersona();
    $onc = "xajax_setAsistenteMulti('$fecha', '$apar', $('#nombre$apar').val(), $('#voto$apar').prop('checked'), $('#repr$apar').prop('checked'), $('#multiples').prop('checked'));";
    $response->assign("voto$apar", "checked", $vot);
    $response->assign("repr$apar", "checked", $rep);
    $response->assign("selec$apar", "innerHTML", f_getPropietariosRepresentantes($apar, $fecha, $per, $oAsi->getRepresentado(), $onc));
    $response->assign("boton$apar", "disabled", TRUE); 
    $response->script("xajax_setAsistenteMulti('$fecha', '$apar', $('#nombre$apar').val(), $('#voto$apar').prop('checked'), $('#repr$apar').prop('checked'), $('#multiples').prop('checked'));");
    return $response;
}

/**
 * Sincroniza los datos de un propietario con varios apartamentos.
 * 
 * @global Propietarios $oPers Instancia de Propietarios.
 * @param date $fecha Fecha de la Junta.
 * @param int $apar Codigo de apartamento.
 * @param int $per Codigo de persona (propietario o representante).
 * @param boolean $vot Indica si tiene voto.
 * @param boolean $rep Indica si esta representado.
 * @param boolean $mul Indica si esta activada la sincronizacion.
 * @return \xajaxResponse
 */
function setAsistenteMulti($fecha, $apar, $per, $vot, $rep, $mul) {
    //global $oPers;
    global $oProps;
    $response = new xajaxResponse();
    $tool = "";
    $repr = ($rep) ? 'S' : 'N';
    
    if ($mul) {
        // Esta activado el multiple, puede tener mas apartamentos.
        //$aProps = $oPers->getMisPropiedades($apar, $fecha); // Propiedades del dueño del apartamento array('codapar'=>array('apartamento','orden')...)
        $aProps = $oProps->getMisPropiedadesFecha($apar, $fecha); //array('codapar'=>array('apartamento','date','fecha','orden')...)
        foreach ($aProps as $codapar => $aDatos) {
            if ($codapar != $apar) {
                $tool .= (!$tool) ? "Sincronizado con " . $aDatos[0] : ", " . $aDatos[0];
                $onCh = "xajax_setAsistenteMulti('$fecha', '$codapar', $('#nombre$codapar').val(), $('#voto$codapar').prop('checked'), $('#repr$codapar').prop('checked'), $('#multiples').prop('checked'));";
                $response->assign("voto$codapar", "checked", $vot);
                $response->assign("repr$codapar", "checked", $rep);
                $response->assign("selec$codapar", "innerHTML", f_getPropietariosRepresentantes($codapar, $fecha, $per, $repr, $onCh));
                $response->script("$('#boton$codapar').prop('disabled', $('#boton$apar').prop('disabled'))");
            }
        }
    }
    if ($tool) {
        $response->script("js_existeTooltip('$apar', '$tool')");
    }
    return $response;
}

/**
 * Graba los datos de un asistente a una Junta con varios apartamentos a su nombre.
 * 
 * @global Propietarios $oPers Instancia de Propietarios.
 * @param date $fecha Fecha de la Junta.
 * @param int $apar Codigo de apartamento.
 * @param int $per Codigo de persona.
 * @param boolean $rep Indica si esta representado.
 * @param boolean $vot Indica si tiene voto.
 * @param boolean $mul Indica si esta activada la sincronizacion.
 * @return \xajaxResponse
 */
function grabarAsistenteMulti($fecha, $apar, $per, $rep, $vot, $mul) {
    //global $oPers;
    global $oProps;
    $response = new xajaxResponse();
    //$aProps = ($mul) ? array_keys($oPers->getMisPropiedades($apar, $fecha)) : array($apar);
    
    $aProps = ($mul) ? $oProps->getCodigosMisPropiedadesFecha($apar, $fecha) : array($apar);
    foreach ($aProps as $codapar) {
        $response->call("xajax_grabarAsistente", $fecha, $codapar, $per, $rep, $vot);
    }
    return $response;
}

/**
 * Graba los datos de un asistente a una Junta.
 * 
 * @param date $fecha Fecha de la Junta.
 * @param int $apar Codigo de apartamento.
 * @param int $per Codigo de persona.
 * @param boolean $rep Indica si esta representado.
 * @param boolean $vot Indica si tiene voto.
 * @return \xajaxResponse
 */
function grabarAsistente($fecha, $apar, $per, $rep, $vot) {
    $response = new xajaxResponse();
    if ($fecha && $apar) {
        $oAsis = new Asistente($fecha, $apar);
        $repr = ($rep) ? 'S' : 'N';
        $voto = ($vot) ? 'S' : 'N';
        $oAsis->setPersona($per);
        $oAsis->setRepresentado($repr);
        $oAsis->setVoto($voto);
        if ($oAsis->grabar()) {  
            $response->assign("boton$apar", "disabled", TRUE);
            $response->call("xajax_getAsistentesJuntaSumas", $fecha);
        } else {
            $response->alert("Error al grabar el asistente a la Junta General.");
        } 
    }
    return $response;
}

/**
 * Elimina los datos de todos los asistentes a una Junta.
 * 
 * @param date $fecha Fecha de la Junta.
 * @return \xajaxResponse
 */
function eliminarAsistentes($fecha) {
    $response = new xajaxResponse();
    if ($fecha) {
        $oAsis = new Asistentes($fecha);
        $fec = $oAsis->getFechaISO();
        if ($oAsis->eliminar()) {
            $response->alert("Se han eliminado los asistentes a la Junta del día $fec.");
        } else {
            $response->alert("Error al eliminar los asistentes a la Junta del día $fec..");
        }
    }
    return $response;
}

//--- VOTACIONES -------------------------------------------------------------//

/**
 * Pide confirmacion antes de poner nuevos datos de las votaciones.
 * 
 * @param date $fecha Fecha de la votacion.
 * @param int $num Numero de votacion.
 * @return \xajaxResponse
 */
function confirmarVotacionCambios($fecha, $num=1) {
    $response = new xajaxResponse();
    $response->confirmCommands(1, "Todos los cambios no guardados se perderán. ¿Seguro que quieres continuar?");
    $response->call("xajax_setVotacionDatosForm", $fecha, $num);
    return $response;
}

/**
 * Rellena los datos de las votaciones.
 * 
 * @global Apartamentos $oApars Instancia de Apartamentos.
 * @param date $fecha Fecha de la votacion.
 * @param int $num Numero de votacion.
 * @return \xajaxResponse
 */
function setVotacionDatosForm($fecha, $num=1) {
    global $oApars;
    $response = new xajaxResponse();
    $script = f_convertirArrayJS($oApars->getCoeficientes());
    $response->assign("scripts", "innerHTML", $script);
    $response->call("xajax_setVotacionCabecera", $fecha, $num);
    $response->assign("divcontenido", "innerHTML", f_getAsistentesVotacion($fecha, $num));
    return $response;
}

/**
 * Rellena los datos de la cabecera de las votaciones.
 * 
 * @param date $fecha Fecha de la votacion.
 * @param int $num Numero de votacion.
 * @return \xajaxResponse
 */
function setVotacionCabecera($fecha, $num=1) {
    $response = new xajaxResponse();
    $oVot = new Votacion($fecha, $num);
    $aSum = $oVot->getSumas();
    
    $response->assign("fechainicial", "value", $oVot->getFechaISO());
    $response->assign("fecha", "value", $oVot->getFechaISO());
    $response->assign("votacioninicial", "value", $oVot->getVotacion());
    $response->assign("selecvotacion", "innerHTML", f_getSelectNumVotaciones($fecha, $oVot->getVotacion()));
    //$response->assign("votacion", "value", $oVot->getVotacion());
    $response->assign("opcion1", "value", $oVot->getOpcion1());
    $response->assign("opcion2", "value", $oVot->getOpcion2());
    $response->assign("opcion3", "value", $oVot->getOpcion3());
    $response->assign("opcion4", "value", $oVot->getOpcion4());
    $response->assign("textvot", "innerHTML", $oVot->getTexto());
    
    $response->assign("sumasis", "value", number_format($aSum['asis'][0],0));
    $response->assign("sumvota", "value", number_format($aSum['asis'][1],0));
    $response->assign("sumnovoto", "value", number_format($aSum['asis'][2],0));
    $response->assign("sumpres", "value", number_format($aSum['asis'][3],0));
    
    $response->assign("votos1", "value", number_format($aSum['opci'][0],0));
    $response->assign("votos2", "value", number_format($aSum['opci'][1],0));
    $response->assign("votos3", "value", number_format($aSum['opci'][2],0));
    $response->assign("votos4", "value", number_format($aSum['opci'][3],0));
    
    $response->assign("coe1", "value", number_format($aSum['urba'][0],4));
    $response->assign("coe2", "value", number_format($aSum['urba'][1],4));
    $response->assign("coe3", "value", number_format($aSum['urba'][2],4));
    $response->assign("coe4", "value", number_format($aSum['urba'][3],4));
    
    $response->assign("cof1", "value", number_format($aSum['fase'][0],4));
    $response->assign("cof2", "value", number_format($aSum['fase'][1],4));
    $response->assign("cof3", "value", number_format($aSum['fase'][2],4));
    $response->assign("cof4", "value", number_format($aSum['fase'][3],4));
    
    $response->assign("cor1", "value", number_format($aSum['fase'][0]/2,5));
    $response->assign("cor2", "value", number_format($aSum['fase'][1]/2,5));
    $response->assign("cor3", "value", number_format($aSum['fase'][2]/2,5));
    $response->assign("cor4", "value", number_format($aSum['fase'][3]/2,5));
    
    $response->assign("sumvotos", "value", $aSum['opci'][0]+$aSum['opci'][1]+$aSum['opci'][2]+$aSum['opci'][3]);
    $response->assign("sumcoe", "value", number_format($aSum['urba'][0]+$aSum['urba'][1]+$aSum['urba'][2]+$aSum['urba'][3],4));
    $response->assign("sumcof", "value", number_format($aSum['fase'][0]+$aSum['fase'][1]+$aSum['fase'][2]+$aSum['fase'][3],4));
    $response->assign("sumcor", "value", number_format(($aSum['fase'][0]/2)+($aSum['fase'][1]/2)+($aSum['fase'][2]/2)+($aSum['fase'][3]/2),5));
    
    $response->script("js_calendario(false, true)");
    
    return $response;
}

/**
 * Graba los datos de la votacion y muestra el resultado.
 * 
 * @param array $frm Datos del formulario.
 * @return \xajaxResponse
 */
function grabarVotacion($frm) {
    $response = new xajaxResponse();
    if (f_grabarVotacion($frm)) {
        // Actualiza los datos.
        $response->assign("submenu1", "innerHTML", f_getVotacionesAnyos());
        $response->assign("divlistado", "innerHTML", f_getVotacionesListado());
        $response->assign("selecvotacion", "innerHTML", f_getSelectNumVotaciones($frm['fecha'], $frm['votacion']));
        $msg = "Los datos de la votación se han guardado correctamente.";
    } else {
        $msg = "ERROR: no se han podido guardar los datos de la votación.";
    }
    $response->alert($msg); 
    return $response;
}

//--- JUNTAS - DEUDORES ------------------------------------------------------//

/**
 * Pone los datos de las deudas en una fecha determinada.
 * Como fechas de referencia se usan las de las Juntas.
 * 
 * @param date $fecha Fecha en cualquier formato.
 * @return \xajaxResponse
 */
function setDeudaDatosForm($fecha) {
    $response = new xajaxResponse();
    $response->assign("divcontenido", "innerHTML", f_getDeudas($fecha));
    $response->assign("btnfechair", "innerHTML", "<div class=\"btn btn-outline-primary\" title=\"Fecha de la deuda\"><span class=\"oi oi-calendar\"></span>&nbsp;&nbsp;" . Funciones::gConvertirFecha($fecha, FALSE) . "</div>");
    $response->script("js_soloNumeros(); js_sumarDeudas();");
    return $response;
}

/**
 * Graba los datos de la deuda del apartamento en la fecha indicada.
 * 
 * @param date $fecha Fecha en cualquier formato.
 * @param int $apar Codigo del apartamento.
 * @param int $ordin Deuda ordinaria.
 * @param int $extra Deuda extraordinaria.
 * @return \xajaxResponse
 */
function grabarDeuda($fecha, $apar, $ordin, $extra) {
    $response = new xajaxResponse();
    $oDeuda = new Deuda($fecha, $apar);
    $oDeuda->setOrdinaria($ordin);
    $oDeuda->setExtraordinaria($extra);
    
    // Si los valores de la deuda son 0, lo que hace es eliminarla.
    if (intval($ordin) == 0 && intval($extra) == 0) {
        if ($oDeuda->eliminar()) {
            $response->assign("divlistado", "innerHTML", f_getDeudasListado());
            $response->script("$('#boton$apar').prop('disabled',true)");
        } else {
            $response->alert("Error, no se ha podido eliminar la deuda del $fecha para el apartamento $apar.");
        }
    } else {
        if ($oDeuda->grabar()) {
            $response->assign("divlistado", "innerHTML", f_getDeudasListado());
            $response->script("$('#boton$apar').prop('disabled',true)");
        } else {
            $response->alert("Error, no se han podido grabar los datos de la deuda del $fecha para el apartamento $apar.");
        }
    }
    return $response;
}

/**
 * Deshace los cambios realizados y deja la deuda original del apartamento en una fecha determinada.
 * 
 * @param int $por Numero de portal.
 * @param int $apa Codigo de apartamento.
 * @param int $ord Deuda ordinaria original.
 * @param int $ext Deuda extraordinaria original.
 * @return \xajaxResponse
 */
function deshacerDeuda($por, $apa, $ord, $ext) {
    $response = new xajaxResponse();
    $response->assign("or$apa", "value", number_format($ord,2,".",""));
    $response->assign("ex$apa", "value", number_format($ext,2,".",""));
    $response->script("js_sumar($por,'or$apa'); $('#boton$apa').prop('disabled',true)");
    return $response;
}

//--- ACTAS VISUALIZAR -------------------------------------------------------//

/**
 * Muestra el contenido de un acta.
 * 
 * @param date $fecha Fecha en cualquier formato.
 * @return \xajaxResponse
 */
function getActa($fecha='') {
    $response = new xajaxResponse();
    $datos = f_getDatosActa($fecha);
    $response->assign("actapdf", "href", "/actas/" . f_getNombreActaPDF($fecha));
    $response->assign("aedit", "href", "actasedit.php?fecha=$fecha");
    $response->assign("divcontenido", "innerHTML", $datos);
    $response->assign("datosdiv", "value", $datos);
    return $response;
}

//--- ACTAS BUSCAR -----------------------------------------------------------//

/**
 * Muestra la ayuda para cada tipo de busqueda diferente.
 * 
 * @param int $tipo Tipo de busqueda: 0 - Natural, 1 - Booleana, 2 - Literal.
 * @return \xajaxResponse
 */
function busquedaAyuda($tipo=0) {
    $response = new xajaxResponse();
    switch ($tipo) {
        case 1:
            $tit = "B&uacute;squeda booleana";
            $txt = "<p>Permite usar modificadores en la busqueda. Los operadores disponibles son:</p>
                    <table class=\"table table-sm\">
                    <tr><th>+</th><td>La palabra tiene que estar presente.</td><td>+palabra</td></tr>
                    <tr><th>-</th><td>La palabra no tiene que estar presente.</td><td>-palabra</td></tr>
                    <tr><th>nada</th><td>La palabra es opcional.</td><td>palabra</td></tr>
                    <tr><th>&gt;</th><td>La palabra tiene mayor relevancia.</td><td>&gt;palabra</td></tr>
                    <tr><th>&lt;</th><td>La palabra tiene menor relevancia.</td><td>&lt;palabra</td></tr>
                    <tr><th>()</th><td>Permite agrupar expresiones.</td><td>(palabra)</td></tr>
                    <tr><th>~</th><td>Negaci&oacute;n. Similar a '-', pero no excluye.</td><td>~palabra</td></tr>
                    <tr><th>*</th><td>Comod&iacute;n para uno o m&aacute;s caracteres. Se pone al final.</td><td>palabra*</td></tr>
                    <tr><th>&quot; &quot;</th><td>Busca literalmente.</td><td>&quot;palabra&quot;</td></tr>
                    </table>
                    <p>No se tienen en cuenta las min&uacute;sculas y may&uacute;sculas.</p>";
            break;
        case 2:
            $tit = "B&uacute;squeda literal";
            $txt = "<p>Busca la palabra exacta o la frase tal y como se ha puesto.</p>
                    <p>No se tienen en cuenta las min&uacute;sculas y may&uacute;sculas.</p>";
            break;
        default:
            $tit = "B&uacute;squeda en lenguaje natural";
            $txt = "<p>Realiza una busqueda lo m&aacute;s aproximada al lenguaje natural y devuelve los resultados ordenados por la relevancia de
                    los resultados obtenidos, mayor relevancia significa que se han encontrado m&aacute;s palabas en el texto.</p>
                    <p>No se tienen en cuenta las min&uacute;sculas y may&uacute;sculas.</p>";           
            break;
    }
    $texto = "<div class=\"alert alert-light\" role=\"alert\"><h4>$tit</h4><hr />$txt</div>";
    $response->assign("contenedor", "innerHTML", $texto);
    return $response;
}

/**
 * Pone los resultados de la busqueda.
 * 
 * @param string $lista Lista de palabras a buscar.
 * @param int $tipo Tipo de busqueda: 0 - Natural, 1 - Booleana, 2 - Literal.
 * @return \xajaxResponse
 */
function busquedaActas($lista, $tipo=0) {
    $response = new xajaxResponse();
    $response->assign("contenedor", "innerHTML", f_getActasDatosBusqueda($lista, $tipo));
    return $response;
}

//--- ACTAS EDITAR -----------------------------------------------------------//

/**
 * Pone todos los datos de los listados y del acta para editar.
 * 
 * @param date $fecha Fecha en cuarquier formato.
 * @return \xajaxResponse
 */
function getActaDatos($fecha='') {
    $response = new xajaxResponse();
    $response->assign("submenu1", "innerHTML", f_getActasAnyos());
    $response->assign("divlistado", "innerHTML", f_getActasListado());
    $response->call("xajax_getActaDatosForm", $fecha);
    return $response;
}

/**
 * Muestra los datos de un acta para poder editarla.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @param date $fecha Fecha en cuarquier formato.
 * @return \xajaxResponse
 */
function getActaDatosForm($fecha='') {
    global $oJuntas;
    $response = new xajaxResponse();
    $oJunta = new Junta();
    $tipo = $oJunta->getTipos()[$oJuntas->getTipoJunta($fecha)];
    $fISO = ($fecha) ? Funciones::gConvertirFecha($fecha, FALSE) : date("d-m-Y");
    $titu = ($fecha) ? "Acta de la Junta $tipo del $fISO" : "Acta nueva";
    $response->assign("titulo", "innerHTML", $titu);
    $response->assign("fechainicial", "value", $fecha);
    $response->assign("fecha", "value", $fISO);
    $response->assign("aver", "href", "actas.php?fecha=$fecha");
    $response->assign("divcontenido", "innerHTML", f_getActa($fecha));
    $response->script("$('.calendario').datepicker('destroy');");
    $response->script("js_calendario(false, true)");
    $response->script("js_editor()");
    return $response;
}

/**
 * Graba los datos del acta.
 * 
 * @param array $frm Datos del formulario del acta.
 * @return \xajaxResponse
 */
function grabarActa($frm) {
    $response = new xajaxResponse();
    $oActas = new Actas();
    // Si se ha cambiado la fecha, comprueba que no exista otra acta con esa fecha.
    $fini = Funciones::gConvertirFecha($frm['fechainicial'], FALSE); 
    $fnew = Funciones::gConvertirFecha($frm['fecha'], FALSE);          
    if($fini <> $fnew && $oActas->existeActa($fnew)) {
        $response->alert("Ya hay un acta que tiene la nueva fecha indicada: '$fnew'.");
        $response->script("$('#fecha').focus()");
    } else {
        if (f_grabarActa($frm)) {
            // Se ha grabado bien, refresca todos los datos.
            $msg = "Los datos del acta se han guardado correctamente.";
            $response->call("xajax_getActaDatos", $fnew);
        } else {
            $msg = "ERROR: no se han podido grabar los datos del acta ($fini / $fnew).";
        }
        $response->alert($msg);
    }
    return $response;
}

/**
 * Elimina un acta.
 * 
 * @param date $fecha Fecha en cualquier formato.
 * @return \xajaxResponse
 */
function eliminarActa($fecha) {
    $response = new xajaxResponse();
    $oActa = new Acta($fecha);
    if ($oActa->eliminar()) {
        // Se ha eliminado bien, refresca todos los datos.
        $oActas = new Actas();
        $response->call("xajax_getActaDatos", $oActas->getUltimaActa());
    } else {
        $response->alert("ERROR: no se ha podido eliminar el acta ($fecha).");
    }
    return $response;
}

//--- CALCULOS Y LISTADOS ----------------------------------------------------//

/**
 * Obtiene un listado de personas con los datos seleccionados.
 * 
 * @param array $frm Datos del formulario.
 * @return \xajaxResponse
 */
function getListadoPersonas($frm) {
    $response = new xajaxResponse();
    $datos = f_getListadoPersonas($frm);
    $response->assign("divbusqueda", "innerHTML", $datos);
    $response->assign("datosdiv", "value", $datos);
    return $response;
}

/**
 * Obtiene un listado de apartamentos con los datos seleccionados.
 * 
 * @param array $frm Datos del formulario.
 * @return \xajaxResponse
 */
function getListadoApartamentos($frm) {
    $response = new xajaxResponse();
    $datos = f_getListadoApartamentos($frm);
    $response->assign("divbusqueda", "innerHTML", $datos);
    $response->assign("datosdiv", "value", $datos);
    return $response;
}

function getListadoPropietarios($frm) {
    $response = new xajaxResponse();
    $datos = f_getListadoPropietarios($frm);
    $response->assign("divbusqueda", "innerHTML", $datos);
    $response->assign("datosdiv", "value", $datos);
    return $response;
}

function getListadoJunta($frm) {
    $response = new xajaxResponse();
    $datos = f_getListadoJunta($frm);
    $response->assign("divbusqueda", "innerHTML", $datos);
    $response->assign("datosdiv", "value", $datos);
    return $response;
}

function getListadoDeudas($frm) {
    $response = new xajaxResponse();
    $datos = f_getListadoDeudas($frm);
    $response->assign("divbusqueda", "innerHTML", $datos);
    $response->assign("datosdiv", "value", $datos);
    return $response;
}

/**
 * Calcula las cuotas a pagar cada mes.
 * 
 * @param array $frm Datos del formulario.
 * @return \xajaxResponse
 */
function getCalculos($frm) {
    $response = new xajaxResponse();
    $datos = f_getCalculos($frm);
    $response->assign("divbusqueda", "innerHTML", $datos);
    $response->assign("datosdiv", "value", $datos);
    return $response;
}

/**
 * Calcula la cantidad total a partir de una cuota.
 * 
 * @param array $frm Datos del formulario.
 * @return \xajaxResponse
 */
function getCantidadTotal($frm) {
    $response = new xajaxResponse();
    $datos = f_getCantidadTotal($frm);
    $response->assign("divbusqueda", "innerHTML", $datos);
    $response->assign("datosdiv", "value", $datos);
    return $response;
}

/**
 * Calcula la cuota a pagar segun un presupuesto dado.
 * 
 * @param array $frm Datos del formulario.
 * @return \xajaxResponse
 */
function getPresupuesto($frm) {
    $response = new xajaxResponse();
    $datos = f_getPresupuesto($frm);
    $response->assign("divbusqueda", "innerHTML", $datos);
    $response->assign("datosdiv", "value", $datos);
    return $response;
}