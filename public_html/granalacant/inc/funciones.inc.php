<?php

/**
 * Funciones generales.
 */

//--- GENERALES --------------------------------------------------------------//

/**
 * Comprueba si un fichero o una URL existen.
 * Si no existe, muestra un mensaje de error y detiene la ejecucion.
 * 
 * @param string $fich Nombre del fichero o de la URL.
 */
function f_comprobarFichero($fich) {
    if(substr($fich, 0, 4) == 'http') {
        // Es una URL.
        if(get_headers($fich)[0] == 'HTTP/1.1 404 Not Found') {
            die("<div class=\"alert alert-danger\" role=\"alert\">La URL <strong>$fich</strong> no se ha podido cargar.</div>");
        }
    } else {
        // Es un fichero.
        if(!file_exists($fich)) {
            die("<div class=\"alert alert-danger\" role=\"alert\">El fichero <strong>$fich</strong> no se ha podido cargar.</div>");
        }
    }
}

/**
 * Convierte las frases con la primera letra de cada palabra en mayuscula.
 * 
 * @param string $nom Frase a convertir.
 * @return string Frase convertida.
 */
function f_primeraMayuscula($nom) {
    //return ucwords(strtolower($nom)," \t\r\n\f\v-.");
    return mb_convert_case($nom, MB_CASE_TITLE, "UTF-8");
}

/**
 * Crea un select con los valores y textos iguales.
 * 
 * @param array $aDat Array del tipo array(v1, v2...).
 * @param string $id Identificador para el select.
 * @param string $sel Elemento seleccionado.
 * @param string $clase Clase para el select.
 * @param string $onch Funcion para 'onchange'.
 * @param boolean $bla Si es TRUE deja la primera opcion en blanco.
 * @return string Codigo HTML del select.
 */
function f_getSelectSimple($aDat, $id='', $sel='', $clase='', $onch='', $bla=FALSE) {
    $aNew = array();
    foreach ($aDat as $valor) {
        $aNew[$valor] = $valor;
    }
    return f_getSelect($aNew, $id, $sel, $clase, $onch, $bla);
}

/**
 * Crea un select con los valores y textos diferentes.
 * 
 * @param array $aDat Array del tipo array(k1=>v1, k2=>v2...).
 * @param string $id Identificador para el select.
 * @param string $sel Elemento seleccionado.
 * @param string $clase Clase para el select.
 * @param string $onch Funcion para 'onchange'.
 * @param boolean $bla Si es TRUE deja la primera opcion en blanco.
 * @return string Codigo HTML del select.
 */
function f_getSelect($aDat, $id='', $sel='', $clase='', $onch='' , $bla=FALSE) {
    $sI = ($id) ? "id=\"$id\" name=\"$id\"" : "";
    $sC = ($clase) ? "class=\"$clase\"" : "";
    $sA = ($onch) ? "onchange=\"$onch\"" : "";
    $s  = "<select $sI $sC $sA>"; 
    $s .= ($bla) ? "<option value=\"\"></option>" : "";
    foreach ($aDat as $key => $valor) {
        $se = ($sel == $key) ? "selected=\"selected\"" : "";
        $s .= "<option value=\"$key\" $se>$valor</option>";
    }
    return "$s</select>";
}

/**
 * Crea un select agrupado con los valores y textos diferentes.
 * 
 * @param array $aDat Array del tipo array(k1=>array(v1,gr1), k2=>(v2,gr2)...).
 * @param string $id Identificador para el select.
 * @param string $sel Elemento seleccionado.
 * @param string $clase Clase para el select.
 * @param string $onch Funcion para 'onchange'.
 * @param boolean $bla Si es TRUE deja la primera opcion en blanco.
 * @return string Codigo HTML del select.
 */
function f_getSelectAgrupado($aDat, $id='', $sel='', $clase='', $onch='' , $bla=FALSE) {
    $sI = ($id) ? "id=\"$id\" name=\"$id\"" : "";
    $sC = ($clase) ? "class=\"$clase\"" : "";
    $sA = ($onch) ? "onchange=\"$onch\"" : "";
    $s  = "<select $sI $sC $sA>"; 
    $s .= ($bla) ? "<option value=\"\"></option>" : "";
    $g  = "";
    foreach ($aDat as $key => $aVal) {
        if ($aVal[1] != $g) {
            $s .= (!$g) ? "<optgroup label=\"" . $aVal[1] . "\">" : "</optgroup><optgroup label=\"" . $aVal[1] . "\">";
            $g = $aVal[1];
        }
        $se = ($sel == $key) ? "selected=\"selected\"" : "";
        $s .= "<option value=\"$key\" $se>$aVal[0]</option>";
    }
    return "$s</optgroup></select>";
}

/**
 * Crea un select de fechas agrupadas por años.
 * El valor se da en formato YYYY-MM-DD y los textos aparecen como DD-MM-YYYY.
 * 
 * @param array $aDat Array del tipo array('date'=>'fecha'...)
 * @param string $id Identificador para el select.
 * @param string $sel Elemento seleccionado.
 * @param string $clase Clase para el select.
 * @param string $onch Funcion para 'onchange'.
 * @param boolean $bla Si es TRUE deja la primera opcion en blanco.
 * @return string Codigo HTML del select.
 */
function f_getSelectAgrupadoFechas($aDat, $id='', $sel='', $clase='', $onch='' , $bla=FALSE) {
    $sI = ($id) ? "id=\"$id\" name=\"$id\"" : "";
    $sC = ($clase) ? "class=\"$clase\"" : "";
    $sA = ($onch) ? "onchange=\"$onch\"" : "";
    $s  = "<select $sI $sC $sA>"; 
    $s .= ($bla) ? "<option value=\"\"></option>" : "";
    $g  = "";
    foreach ($aDat as $date => $fecha) {
        $any = substr($date, 0, 4);
        if ($any != $g) {
            $s .= (!$g) ? "<optgroup label=\"$any\">" : "</optgroup><optgroup label=\"$any\">";
            $g = $any;
        }
        $se = ($sel == $date) ? "selected=\"selected\"" : "";
        $s .= "<option value=\"$date\" $se>$fecha</option>";
    }
    return "$s</optgroup></select>";
}

//--- CABECERA Y PIE DE PAGINA -----------------------------------------------//

/**
 * Obtiene la cabecera de la pagina.
 * 
 * @param string $titulo Titulo de la pagina.
 * @return string Codigo HTML de la cabecera.
 */
function f_getCabeceraHTML($titulo) {
    return f_getMetas($titulo) . f_getEstilos();
}

/**
 * Obtiene las etiqueta META y el título para la página web.
 * 
 * @param string $titulo Nombre de la página.
 * @return string Código HTML de los META.
 */
function f_getMetas($titulo) {
    return "<meta charset=\"utf-8\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
            <title>$titulo</title>";
}

/**
 * Obtiene los enlaces a las página CSS para la página web.
 * 
 * @return string Código HTML de los enlaces.
 */
function f_getEstilos() {
    $css = array(
        'bootstrap.min.css', 
        'open-iconic-bootstrap.min.css', 
        'bootstrap-datepicker.min.css', 
        'trumbowyg.min.css', 
        'personal.css'
    );
    $txt = "";
    foreach ($css as $f) {
        $fich = _ULIB_ . "/css/$f";
        f_comprobarFichero($fich);
        $txt .= "<link href=\"" . _ULIB_ . "/css/$f\" rel=\"stylesheet\">";
    }
    return $txt;
}

/**
 * Obtiene las llamadas a los ficheros JavaScript.
 * 
 * @global /xajax $xajax Instancia de XAJAX.
 * @return string Código HTML para los scripts.
 */
function f_getScripts() {
    global $xajax;
    $js = array(
        'jquery-3.2.1.min.js', 
        'tether.min.js', 
        'bootstrap.min.js', 
        'bootstrap-datepicker.min.js', 
        'bootstrap-datepicker.es.min.js', 
        'trumbowyg.min.js', 
        'trumbowyg.es.min.js',
        'trumbowyg.preformatted.xavi.min.js',
        'personal.js'
    );
    $txt = "";
    foreach ($js as $f) {
        $fich = _ULIB_ . "/js/$f";
        f_comprobarFichero($fich);
        $txt .= "<script src=\"$fich\"></script>";
    }
    return $txt . $xajax->printJavascript();
} 

//--- MENU PRINCIPAL ---------------------------------------------------------//

/**
 * Obtiene las acciones a aplicar segun la pagina.
 * 
 * @global boolean $busqueda Si es TRUE se muestra la busqueda, si es FALSE no se muestra.
 * @param string $pagina Nombre de la pagina.
 * @return array Indica si el menu esta activo o no.
 */
function f_getMenuAcciones($pagina) {
    global $busqueda;
    $activo = array('','','','','','','','');
    switch ($pagina) {
        // Personas.
        case "personas.php": $activo[1] = "active"; $busqueda = TRUE; break;
        case "personaslis.php": $activo[1] = "active"; break;
        // Apartamentos.
        case "apartamentos.php": $activo[2] = "active"; $busqueda = TRUE; break;
        case "coeficientes.php": $activo[2] = "active"; break;
        case "apartamentoslis.php": $activo[2] = "active"; break;
        // Propietarios.
        case "propietarios.php": $activo[3] = "active"; $busqueda = TRUE; break;
        case "propper.php": $activo[3] = "active"; $busqueda = TRUE; break;
        case "propietarioslis.php": $activo[3] = "active"; break;
        // Juntas.
        case "juntas.php": $activo[4] = "active"; break;
        case "asistentes.php": $activo[4] = "active"; break;
        case "votaciones.php": $activo[4] = "active"; break;
        case "juntaslis.php": $activo[4] = "active"; break;
        // Actas.
        case "actas.php": $activo[5] = "active"; break;
        case "actasbuscar.php": $activo[5] = "active"; break;
        case "actasedit.php": $activo[5] = "active"; break;
        // Deudas
        case "deudas.php": $activo[6] = "active"; break;
        case "deudaslis.php": $activo[6] = "active"; break;
        case "deudapar.php": $activo[6] = "active"; break;
        case "graph_deudaport.php": $activo[6] = "active"; break;
        // Otros.
        case "calculos.php": $activo[7] = "active"; break;
        case "transformar.php": $activo[7] = "active"; break;
        // Inicio.
        default: $activo[0] = "active"; break;
    }
    return $activo;
}

//--- PERSONAS ---------------------------------------------------------------//

/**
 * Obtiene el listado de personas encontradas.
 * 
 * @global \Personas $oPers Instancia de Personas.
 * @param string $texto Texto a buscar.
 * @param string $func Nombre de la funcion a ejecutar cuando se haga clic.
 * @return string Codigo HTML del listado de personas encontradas.
 */
function f_buscarPersonas($texto, $func) {
    global $oPers;
    $html = ""; 
    if($texto) {
        $aDatos = $oPers->buscar($texto);
        $num = count($aDatos);
        $per = ($num == 1) ? "persona encontrada" : "personas encontradas";
        $html = "<h3>$num $per</h3><div style=\"display:table\">";
        
        foreach ($aDatos as $cod => $aNom) {
            $html .= "<div style=\"display:table-row\"><div class=\"listado-elem\" style=\"display:table-cell;text-align:right;width:10%\" onclick=\"$func($cod);\">$cod&nbsp;</div><div class=\"listado-elem\" style=\"display:table-cell;width:90%\" onclick=\"$func($cod);\">&nbsp;" . $aNom[0] . " " . $aNom[1] . "</div></div>";
        }
        $html .= "</div>";
    }
    return $html;
}

/**
 * Obtiene las iniciales de las personas.
 * 
 * @global \Personas $oPers Instancia de Personas.
 * @return string Codigo HTML de las iniciales.
 */
function f_getPersonasIniciales() {
    global $oPers;
    $aIni = $oPers->getIniciales();
    $sIni = "";
    foreach ($aIni as $ini) {
        $sIni .= "<a href=\"#ini$ini\">$ini</a>&nbsp;";
    }
    return $sIni;
}

/**
 * Obtiene un listado de todas las personas.
 * 
 * @global \Personas $oPers Instancia de Personas.
 * @return string Codigo HTML del listado de personas.
 */
function f_getPersonasListado() {
    global $pagina, $oPers;
    $aPer = $oPers->getNombresCompletos();
    $sIni = "";
    $sPer = "<div><a name=\"inicio\"></a></div>";
    foreach ($aPer as $cod => $nombre) {
        $ini = substr($nombre, 0, 1);
        $sPer .= ($ini != $sIni) ? "<div class=\"listado-tit\"><a name=\"ini$ini\" href=\"#inicio\">$ini</a></div>" : "";
        $sPer .= "<div class=\"listado-elem\" onclick=\"xajax_reenviarFuncion('$pagina', $cod);\" data-animation=\"false\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"$nombre\">$nombre</div>";
        $sIni = $ini;
    }
    return $sPer;
}

/**
 * Graba los datos de una persona.
 * 
 * @param array $frm Datos del formulario.
 * @return string Mensaje de error o de que todo ha ido bien.
 */
function f_grabarPersona($frm) {
    global $oPers;
    $cod = $frm['codigo'];
    $ape = $frm['apellidos'];
    $nom = $frm['nombre'];
    $cor = $frm['correo'];
    $env = (isset($frm['enviar'])) ? 'S' : 'N';
    $tel = $frm['telefono'];
    $sex = $frm['sexo'];
    $not = $frm['notas'];
    
    $oPer = new Persona($cod);
    $oPer->setApellidos($ape);
    $oPer->setNombre($nom);
    $oPer->setCorreo($cor);
    $oPer->setEnvios($env);
    $oPer->setTelefono($tel);
    $oPer->setSexo($sex);
    $oPer->setNotas($not);
    
    if ($oPer->grabar()) {
        $oPers->actualizar();   // Actualiza las personas.
        $msg = "Los datos se han guardado correctamente.";
    } else {
        $msg = "Error al guardar los datos.";
    }
    return $msg;
}

//--- APARTAMENTOS -----------------------------------------------------------//

/**
 * Busca apartamentos.
 * 
 * @global \Apartamentos $oApars Instancia de la clase apartamentos.
 * @param string $texto Texto a buscar.
 * @param string $func Nombre de la funcion a ejecutar cuando se haga clic.
 * @return string Listado con los apartamentos encontrados.
 */
function f_buscarApartamentos($texto, $func) { 
    global $oApars;
    $html = ""; 
    if($texto) {
        $aDatos = $oApars->buscar($texto);
        $num = count($aDatos);
        $apa = ($num == 1) ? "apartamento encontrado" : "apartamentos encontrados";
        $html = "<h3>$num $apa</h3><div style=\"display:table\">";
        foreach ($aDatos as $cod => $aNom) {
            $html .= "<div style=\"display:table-row\"><div class=\"listado-elem\" style=\"display:table-cell;text-align:right;width:10%\" onclick=\"$func($cod);\">$cod&nbsp;</div><div class=\"listado-elem\" style=\"display:table-cell;width:90%\" onclick=\"$func($cod);\">&nbsp;Portal " . $aNom[0] . "-" . $aNom[1] . $aNom[2] . "</div></div>";
        }
        $html .= "</div>";
    }
    return $html;
} 

/**
 * Obtiene una lista con los portales de los apartamentos.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @return string Codigo HTML de la lista.
 */
function f_getApartamentosIniciales() {
    global $oApars;
    $aIni = $oApars->getPortalesLista();
    $sIni = "";
    foreach ($aIni as $ini) {
        $sIni .= "<a href=\"#ini$ini\">$ini</a>&nbsp;";
    }
    return $sIni;
}

/**
 * Obtiene un listado con los apartamentos existentes.
 * 
 * @global string $pagina Nombre de la pagina.
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @return string Codigo HTML del listado.
 */
function f_getApartamentosListado() {
    global $pagina, $oApars;
    $aApar = $oApars->getNombresCompletos();
    $aPort = $oApars->getPortales();
    $sIni = "";
    $sApa = "<div><a name=\"inicio\"></a></div>";
    foreach ($aApar as $cod => $nombre) {
        $ini = $aPort[$cod];
        $sApa .= ($ini != $sIni) ? "<div class=\"listado-tit\"><a name=\"ini$ini\" href=\"#inicio\">$ini</a></div>" : "";
        $sApa .= "<div class=\"listado-elem\" onclick=\"xajax_reenviarFuncion('$pagina', $cod);\" title=\"$nombre\">$nombre</div>";
        $sIni = $ini;
    }
    return $sApa;
}

/**
 * Obtiene un select para elegir un portal.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param string $id Identificador del select.
 * @param int $sel Elemento seleccionado.
 * @param string $clase Clase CSS.
 * @param string $onch Funcion para el evento onChange.
 * @param boolean $bla Si es TRUE se deja una opcion en blanco, si es FALSE no se deja.
 * @return string Codigo HTML del select.
 */
function f_getSelectPortales($id='portal', $sel='', $clase='form-control', $onch='', $bla=FALSE) {
    global $oApars;
    $aDat = $oApars->getPortalesDistintos();
    return f_getSelectSimple($aDat, $id, $sel, $clase, $onch, $bla);
} 

/**
 * Obtiene un select para elegir un piso.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param string $id Identificador del select.
 * @param int $sel Elemento seleccionado.
 * @param string $clase Clase CSS.
 * @param string $onch Funcion para el evento onChange.
 * @param boolean $bla Si es TRUE se deja una opcion en blanco, si es FALSE no se deja.
 * @return string Codigo HTML del select.
 */
function f_getSelectPisos($id='piso', $sel='', $clase='form-control', $onch='', $bla=FALSE) {
    global $oApars;
    $aDat = $oApars->getPisosDistintos();
    return f_getSelectSimple($aDat, $id, $sel, $clase, $onch, $bla);
}

/**
 * Obtiene un select para elegir una letra.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param string $id Identificador del select.
 * @param int $sel Elemento seleccionado.
 * @param string $clase Clase CSS.
 * @param string $onch Funcion para el evento onChange.
 * @param boolean $bla Si es TRUE se deja una opcion en blanco, si es FALSE no se deja.
 * @return string Codigo HTML del select.
 */
function f_getSelectLetras($id='letra', $sel='', $clase='form-control', $onch='', $bla=FALSE) {
    global $oApars;
    $aDat = $oApars->getLetrasDistintas();
    return f_getSelectSimple($aDat, $id, $sel, $clase, $onch, $bla);
}

/**
 * Obtiene un select para elegir una fase.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param string $id Identificador del select.
 * @param int $sel Elemento seleccionado.
 * @param string $clase Clase CSS.
 * @param string $onch Funcion para el evento onChange.
 * @param boolean $bla Si es TRUE se deja una opcion en blanco, si es FALSE no se deja.
 * @return string Codigo HTML del select.
 */
function f_getSelectFases($id='fase', $sel='', $clase='form-control', $onch='', $bla=FALSE) {
    global $oApars;
    $aDat = $oApars->getFasesDistintas();
    return f_getSelectSimple($aDat, $id, $sel, $clase, $onch, $bla);
}

/**
 * Obtiene un select para elegir un tipo de apartamento.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param string $id Identificador del select.
 * @param int $sel Elemento seleccionado.
 * @param string $clase Clase CSS.
 * @param string $onch Funcion para el evento onChange.
 * @param boolean $bla Si es TRUE se deja una opcion en blanco, si es FALSE no se deja.
 * @param boolean $gen Si es TRUE incluye tipos genericos (A?, L?).
 * @return string Codigo HTML del select.
 */
function f_getSelectTipos($id='tipo', $sel='', $clase='form-control', $onch='', $bla=FALSE, $gen=FALSE) { 
    global $oApars;
    $aDat = $oApars->getTiposDistintos();
    if($gen) {
        $aTip = array();
        $sTip = "";
        foreach ($aDat as $tipo) {
            if(substr($sTip, 0, 1) != substr($tipo, 0, 1)) {
                $aTip[] = substr($tipo, 0, 1) . "?";
            }
            $sTip = $tipo;
            $aTip[] = $tipo;
        }
        $aDat = $aTip;
    }
    return f_getSelectSimple($aDat, $id, $sel, $clase, $onch, $bla);
}

/**
 * Obtiene el plano de las plazas de garaje.
 * 
 * @param \Apartamento $oApa Instancia de Apartamento.
 * @return string Codigo HTML del plano.
 */
function f_getGarajesPlano($oApa) {
    $aGar = $oApa->getGarajesApartamento();
    $aGarCorte = array(21,42,67,92,117);    // Números de las plazas de garaje donde termina cada fila.
    $aGarDoble = array(67,117);             // Números de las plazas de garaje con separación doble entre filas.
    $iGarFase2 = 68;                        // Primer número de plaza de garaje de la Fase II.
    $iGarMaxim = 117;                       // Número máximo de plazas de garaje.

    $f = "";
    $s = "";
    $gar = "<h4>Aparcamientos</h4>";
    for($p=1; $p<= $iGarMaxim; $p++) {
        if (in_array($p, $aGar)) {
            $s .= "<div class=\"divgarajesel\">$p</div>";
        } elseif ($p < $iGarFase2) {
            $s .= "<div class=\"divgaraje1\">$p</div>";
        } else {
            $s .= "<div class=\"divgaraje2\">$p</div>";
        }
        if (in_array($p, $aGarCorte)) {
            $f = (in_array($p, $aGarDoble)) ? "$s<div class=\"divvacio\">&nbsp;</div><div class=\"divvacio\">&nbsp;</div>$f" : "$s<div class=\"divvacio\">&nbsp;</div>$f";
            $s = "";
        }
    }
    $gar .= "$f<br><br><div class=\"divgaraje1\">&nbsp;</div>&nbsp;Fase I<br><br><div class=\"divgaraje2\">&nbsp;</div>&nbsp;Fase II";
    return $gar;
}

/**
 * Obtiene los datos de los coeficientes de los apartamentos.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @return string Codigo HTML de los apartamentos.
 */
function f_getDatosApartamentos() {
    global $oApars;
    // array(portal,piso,letra,fase,tipo,finca,metros,terraza,coefurb,coeffase,coefbloq)
    // array('portal','piso','letra','tipo','metros','terraza','coeficiente','coef.blo','finca','numreg','catastral','metros cat','numgar')
    $aApars = $oApars->getApartamentos();
    
    $iPortal = 1; 
    $pap = 0; $pm2 = 0; $pt2 = 0; $pcf = 0; $pcb = 0;
    $tap = 0; $tm2 = 0; $tt2 = 0; $tcf = 0; $tcb = 0;
    
    $sTabla  = "<a name=\"iniciopagina\"></a><table class=\"table table-sm\" style=\"width:100%\">";
    $sTabla .= "<tr><th class=\"text-center\" colspan=\"10\"><a name=\"ini$iPortal\" href=\"#iniciopagina\">Portal $iPortal</a></th></tr>"
            .  "<tr><th colspan=\"2\" class=\"text-center\">Apartamento</th><th class=\"text-center\">Superficie</th><th class=\"text-center\">Terraza</th><th class=\"text-center\">Coeficiente</th><th class=\"text-center\">Escalera</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
    
    // Recorre los apartamentos.
    foreach ($aApars as $cod => $aDat) {
        // Comprueba si hay un nuevo portal.
        if($aDat[0] != $iPortal) {
            $sTabla .= f_getCoeficientesNuevoPortal($iPortal, $pap, $pm2, $pt2, $pcf, $pcb);
            $iPortal = $aDat[0]; $pap = 0; $pm2 = 0; $pt2 = 0; $pcf = 0; $pcb = 0;
            // Cabecera del portal.
            $sTabla .= "<tr class=\"active\"><th class=\"text-center\" colspan=\"8\"><a name=\"ini$iPortal\" href=\"#iniciopagina\">Portal $iPortal</a></th></tr>"
                    .  "<tr><th colspan=\"2\" class=\"text-center\">Apartamento</th><th class=\"text-center\">Superficie</th><th class=\"text-center\">Terraza</th><th class=\"text-center\">Coeficiente</th><th class=\"text-center\">Escalera</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
        }
        // Realiza las sumas.
        $pap++; $pm2 += $aDat[4]; $pt2 += $aDat[5]; $pcf += $aDat[6]; $pcb += $aDat[7];
        $tap++; $tm2 += $aDat[4]; $tt2 += $aDat[5]; $tcf += $aDat[6]; $tcb += $aDat[7];
        
        // Fila de datos. f_getCoeficientesNuevoApartamento($cod, $por, $pis, $let, $tip, $met, $ter, $cof, $cob)
        $sTabla .= f_getCoeficientesNuevoApartamento($cod, $aDat[0], $aDat[1], $aDat[2], $aDat[3], $aDat[4], $aDat[5], $aDat[6], $aDat[7]);
    }
    // Suma portal. f_getCoeficientesNuevoPortal($por, $pap, $pm2, $pt2, $pcf, $pcb)
    $sTabla .= f_getCoeficientesNuevoPortal($aDat[0], $pap, $pm2, $pt2, $pcf, $pcb);
    
    // Suma total. f_getCoeficientesNuevaUrbanizacion($tap, $tm2, $tt2, $tcf, $tcb)
    $sTabla .= f_getCoeficientesNuevaUrbanizacion($tap, $tm2, $tt2, $tcf, $tcb);
    return "<a name=\"iniciolista\">&nbsp;</a>$sTabla</table><a name=\"finallista\">&nbsp;</a>";
}

/**
 * Obtiene el formulario para los coeficientes de un apartamento.
 * 
 * @param int $cod Codigo de apartamento.
 * @param int $por Numero de portal.
 * @param string $pis Numero de piso.
 * @param string $let Letra.
 * @param string $tip Tipo de apartamento.
 * @param int $met Metros cuadrados del piso.
 * @param int $ter Metros cuadrados de la terraza.
 * @param int $cof Coeficiente fase.
 * @param int $cob Coeficiente bloque.
 * @return string Codigo HTML de los datos.
 */
function f_getCoeficientesNuevoApartamento($cod, $por, $pis, $let, $tip, $met, $ter, $cof, $cob) {
    $o1 = "onchange=\"$('#boton$cod').prop('disabled',false);\"";
    $o2 = "onkeyup=\"js_sumar($por, this.id);\"";
    //$o3 = "onkeyup=\"$('#cr$cod').val(($(this).val()/2).toFixed(5)); js_sumar($por, this.id);\"";
    
    $i1 = f_getCoeficientesInput("me$cod", number_format($met,2,'.',''), "m2", "$o1 $o2", TRUE);
    $i2 = f_getCoeficientesInput("te$cod", number_format($ter,2,'.',''), "m2", "$o1 $o2", TRUE);
    $i3 = f_getCoeficientesInput("cf$cod", number_format($cof,4,'.',''), "%", "$o1 $o2", TRUE);
    $i4 = f_getCoeficientesInput("cb$cod", number_format($cob,2,'.',''), "%", "$o1 $o2", TRUE);
    $b1 = "<button type=\"button\" class=\"btn btn-warning\" onclick=\"xajax_getDatosCoeficientes($cod, $por);$('#boton$cod').prop('disabled',true);\"  title=\"Deshacer\"><span class=\"oi oi-loop-circular\" aria-hidden=\"true\"></span></button>";
    $b2 = "<button type=\"button\" class=\"btn btn-success\" id=\"boton$cod\" onclick=\"xajax_setDatosCoeficientes('$cod', $('#me$cod').val(), $('#te$cod').val(), $('#cf$cod').val(), $('#cb$cod').val());\" disabled=\"disabled\" title=\"Guardar\"><span class=\"oi oi-hard-drive\" aria-hidden=\"true\"></span></button>";
    
    //return "<tr><td style=\"width:10%\" class=\"align-middle\"><input type=\"hidden\" id=\"po$cod\" value=\"$por\">Portal $por-$pis$let</td><td style=\"width:4%\" class=\"align-middle\">$tip</td><td style=\"width:13%\">$i1</td><td style=\"width:13%\">$i2</td><td style=\"width:13%\">$i3</td><td style=\"width:13%\">$i4</td><td style=\"width:4%\">$b1</td><td style=\"width:4%\">$b2</td></tr>"; 
    return "<tr><td class=\"align-middle\"><input type=\"hidden\" id=\"po$cod\" value=\"$por\">Portal $por-$pis$let</td><td class=\"align-middle\">$tip</td><td>$i1</td><td>$i2</td><td>$i3</td><td>$i4</td><td>$b1</td><td>$b2</td></tr>"; 
}

/**
 * Obtiene el formulario las sumas de los datos del portal.
 * 
 * @param int $por Numero de portal.
 * @param int $pap Suma de apartamentos del portal.
 * @param int $pm2 Suma de metros cuadrados de los apartamentos.
 * @param int $pt2 Suma de metros cuadrados de las terrazas.
 * @param int $pcf Suma de los coeficientes de fase.
 * @param int $pcb Suma de los coeficientes de los bloques.
 * @return string Codigo HTML de los datos.
 */
function f_getCoeficientesNuevoPortal($por, $pap, $pm2, $pt2, $pcf, $pcb) {
    $i1 = f_getCoeficientesInput("pme$por", number_format($pm2,2,'.',''), "m2", "", FALSE);
    $i2 = f_getCoeficientesInput("pte$por", number_format($pt2,2,'.',''), "m2", "", FALSE);
    $i3 = f_getCoeficientesInput("pcf$por", number_format($pcf,4,'.',''), "%", "", FALSE);
    $i4 = f_getCoeficientesInput("pcb$por", number_format($pcb,2,'.',''), "%", "", FALSE);

    return "<tr><th class=\"align-middle\">Portal $por</th><th class=\"align-middle\">$pap</th><th>$i1</th><th>$i2</th><th>$i3</th><th>$i4</th><th>&nbsp;</th><th>&nbsp;</th></tr>"; 
}

/**
 * Obtiene el formulario las sumas de los datos totales.
 * 
 * @param int $tap Suma de apartamentos de la urbanizacion.
 * @param int $tm2 Suma de metros cuadrados de los apartamentos.
 * @param int $tt2 Suma de metros cuadrados de las terrazas.
 * @param int $tcf Suma de los coeficientes de fase.
 * @param int $tcb Suma de los coeficientes de los bloques.
 * @return string Codigo HTML de los datos.
 */
function f_getCoeficientesNuevaUrbanizacion($tap, $tm2, $tt2, $tcf, $tcb) {
    $i1 = f_getCoeficientesInput("tme", number_format($tm2,2,'.',''), "m2", "", FALSE);
    $i2 = f_getCoeficientesInput("tte", number_format($tt2,2,'.',''), "m2", "", FALSE);
    $i3 = f_getCoeficientesInput("tcf", number_format($tcf,4,'.',''), "%", "", FALSE);
    $i4 = f_getCoeficientesInput("tcb", number_format($tcb,2,'.',''), "%", "", FALSE);
        
    return "<tr><th class=\"align-middle\">Urbanizaci&oacute;n</th><th class=\"align-middle\">$tap</th><th>$i1</th><th>$i2</th><th>$i3</th><th>$i4</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
}

/**
 * Obtiene un INPUT para los coeficientes.
 * 
 * @param string $id Identificador.
 * @param type $val Valor.
 * @param string $sim Simbolo.
 * @param string $on Funciones on.
 * @param string $act Activado o no.
 * @return string Codigo HTML del INPUT.
 */
function f_getCoeficientesInput($id, $val, $sim, $on='', $act=TRUE) {
    $readonly = (!$act) ? "readonly=\"readonly\"" : "";
    return "<div class=\"input-group\">
                <input type=\"text\" id=\"$id\" name=\"$id\" class=\"form-control solonumeros\" value=\"$val\" $on $readonly>
                <div class=\"input-group-addon\">$sim</div>
            </div>";
}

/**
 * Graba los datos del apartamento.
 * 
 * @param array $frm Datos del formulario.
 * @return string Mensaje de error o de todo correcto.
 */
function f_grabarApartamento($frm) {
    $cod = $frm['codigo'];
    $por = $frm['portal'];
    $pis = $frm['piso'];
    $let = $frm['letra'];
    //$fas = $frm['fase'];
    $tip = $frm['tipo'];
    $met = $frm['metros'];
    $ter = $frm['terraza'];
    //$cou = $frm['urba'];
    $co2 = $frm['fase200'];
    $cob = $frm['bloque'];
    $reg = $frm['registro'];
    $cat = $frm['catastral'];
    $mca = $frm['mecat'];

    $oApa = new Apartamento($cod);
    $oApa->setPortal($por);
    $oApa->setPiso($pis);
    $oApa->setLetra($let);
    //$oApa->setFase($fas);
    $oApa->setTipo($tip);
    $oApa->setMetros($met);
    $oApa->setTerraza($ter);
    //$oApa->setCoeficiente($cou);
    $oApa->setCoeficienteFase($co2);
    $oApa->setCoeficienteBloque($cob);
    $oApa->setRegistro($reg);
    $oApa->setReferenciaCatastral($cat);
    $oApa->setMetrosCatastrales($mca);
    
    return ($oApa->grabar()) ? "Los datos del apartamento se han guardado correctamente." : "Error al guardar los datos del apartamento.";
}

/**
 * Graba los datos de los metros cuadrados y los coeficientes del apartamento.
 * 
 * @param int $cod Codigo del apartamento.
 * @param int $met Metros cuadrados.
 * @param int $ter Metros cuadrados de terraza.
 * @param int $cof Coeficiente de fase.
 * @param int $cob Coeficiente de bloque.
 * @return string Mensaje de error o de todo correcto.
 */
function f_grabarCoeficientes($cod, $met, $ter, $cof, $cob) {
    $oApa = new Apartamento($cod);
    $oApa->setMetros($met);
    $oApa->setTerraza($ter);
    $oApa->setCoeficienteFase($cof);
    $oApa->setCoeficienteBloque($cob);
    return ($oApa->grabar()) ? "Los metros y coeficientes del apartamento se han guardado correctamente." : "Error al guardar los metros y coeficientes del apartamento.";
}

//--- PROPIETARIOS -----------------------------------------------------------//

/**
 * Obtiene los propietarios de un apartamento.
 * 
 * @param int $cod Codigo de apartamento.
 * @return string Codigo HTML de los propietarios.
 */
function f_getPropietarios($cod) { 
    $oPro = new Propiedad($cod);
    $aPro = $oPro->getPopietarios();
    $tabla = "<table class=\"table table-sm\" style=\"width:100%\"><tr><th>&nbsp;</th><th>Nombre del propietario</th><th>Orden</th><th>Fecha baja</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
    foreach ($aPro as $per => $aDat) {
        $nom = $aDat[0];
        $dat = $aDat[1];
        $fec = $aDat[2];
        $ord = $aDat[3];
        $tabla .= f_getPropietario($cod, $per, $nom, $dat, $fec, $ord);
    }
    $tabla .= f_getPropietarioNuevo($aPro, $cod);
    
    return "$tabla</table>";
}

/**
 * Obtiene los datos de un  propietario del apartamento.
 * 
 * @param int $cod Codigo del apartamento.
 * @param int $per Codigo de persona.
 * @param string $nom Nombre del propietario.
 * @param date $dat Fecha de baja en formato YYYY-MM-DD.
 * @param date $fec Fecha de baja en formato DD-MM-YYYY.
 * @param int $ord Numero de orden.
 * @return string Codigo HTML del propietario.
 */
function f_getPropietario($cod, $per, $nom, $dat, $fec, $ord) {
    if($dat) {
        // Propietario de baja.
        $clase = "dangercolor";
        $verpe = "btn-outline-danger";
        $boton = "btn-danger";
    } else {
        // Propietario de alta.
        $clase = "";
        $verpe = "btn-outline-info";
        $boton = "btn-success";
    }
    $on = "$('#boton$per').prop('disabled',false)";
    $gr = f_getPropietarioBoton($cod, $per);
    
    return "<tr><td class=\" align-middle\" style=\"width:5%\" title=\"Ver persona $per\"><a class=\"btn $verpe\" href=\"personas.php?persona=$per\" role=\"button\"><span class=\"oi oi-eye\"></span></a></td>
            <td class=\"align-middle $clase\" style=\"width:60%\">$nom</td>
            <td class=\"align-middle\" style=\"width:10%\">" . f_getSelectSimple(array(0,1,2,3,4,5,6,7,8,9), "orden$per", $ord, "form-control $clase", $on) . "</td>
            <td class=\"align-middle\" style=\"width:15%\"><input type=\"text\" id=\"fecha$per\" name=\"fecha$per\" class=\"form-control text-center calendario $clase\" style=\"background-color:transparent\" readonly=\"readonly\" value=\"$fec\"></td>
            <td class=\"align-middle text-right\" style=\"width:5%\"><button type=\"button\" id=\"boton$per\" onclick=\"$gr\" class=\"btn $boton\" title=\"Grabar\" disabled=\"disabled\"><span class=\"oi oi-hard-drive\"></span></button></td>
            <td class=\"align-middle\" style=\"width:5%\"><button type=\"button\" id=\"borra$per\" onclick=\"xajax_eliminarPropietarioPregunta($cod, $per)\" class=\"btn btn-danger\" title=\"Eliminar\"><span class=\"oi oi-trash\"></span></button></td></tr>";
}

/**
 * Obtiene el formulario para seleccionar un nuevo propietario.
 * 
 * @global \Personas $oPers Instancia de Personas.
 * @param array $aPro Datos de los propietarios.
 * @param int $apa Codigo de apartamento.
 * @return string Codigo HTML del formulario.
 */
function f_getPropietarioNuevo($aPro, $apa) {
    global $oPers;
    
    // Lista de todas las personas menos los propietarios.
    $aPers = $oPers->getPersonasExcluyendo($aPro, TRUE);
    $on = "if($('#nombre0').val() == '') { $('#boton0').prop('disabled',true); } else { $('#boton0').prop('disabled',false); }";
    $gr = f_getPropietarioBoton($apa, 0);
    
    return "<tr><th>&nbsp;</th><th>Nuevo propietario</th><th>Orden</th><th>Fecha baja</th><th>&nbsp;</th><th>&nbsp;</th></tr>
            <tr><td class=\"align-middle\" title=\"Nuevo propietario\"><div class=\"btn btn-outline-success\"><span class=\"oi oi-person\"></span></div></td>
            <td class=\"align-middle\">" . f_getSelect($aPers, "nombre0", "", "form-control", $on, TRUE) . "</td>
            <td class=\"align-middle\">" . f_getSelectSimple(array(0,1,2,3,4,5,6,7,8,9), "orden0", 0, "form-control", $on) . "</td>
            <td class=\"align-middle\"><input type=\"text\" id=\"fecha0\" name=\"fecha0\" class=\"form-control text-center calendario\" style=\"background-color:transparent\" readonly=\"readonly\" value=\"\"></td>
            <td class=\"align-middle text-right\"><button type=\"button\" id=\"boton0\" onclick=\"$gr\" class=\"btn btn-outline-success\" title=\"Grabar\" disabled=\"disabled\"><span class=\"oi oi-hard-drive\"></span></button></td>
            <td class=\"align-middle\">&nbsp;</td></tr>";
}

/**
 * Obtiene el contenido para el boton de grabar.
 * 
 * @param int $apa Codigo de apartamento.
 * @param int $per Codigo de persona.
 * @return string Propiedades del boton.
 */
function f_getPropietarioBoton($apa, $per) {
    $perso = ($per) ? $per : "$('#nombre$per').val()";
    return "xajax_grabarPropietario($apa, $perso, $('#orden$per').val(), $('#fecha$per').val())";
}

/**
 * Graba los datos de un propietario.
 * 
 * @param int $apa Codigo de apartamento.
 * @param int $per Codigo de persona.
 * @param int $ord Numero de orden.
 * @param date $baj Fecha de baja.
 * @return boolean Devuelve TRUE si todo es correcto o FALSE si falla algo.
 */
function f_grabarPropietario($apa, $per, $ord, $baj) {
    $oPro = new Propiedad($apa);
    $oPro->setPropietario($per, $baj, $ord);
    return $oPro->grabarPropietarios();
}

/**
 * Elimina los datos de un propietario.
 * 
 * @param int $apa Codigo de apartamento.
 * @param int $per Codigo de persona.
 * @return boolean Devuelve TRUE si todo es correcto o FALSE si falla algo.
 */
function f_eliminarPropietario($apa, $per) {
    $oPro = new Propiedad($apa);
    return $oPro->eliminarPropietario($per);
}

//--- PROPIEDADES ------------------------------------------------------------//

/**
 * Obtiene un listado de propietarios.
 * 
 * @global string $pagina Nombre de la pagina.
 * @global \Propietarios $oProps Instancia de Propietarios.
 * @return string Codigo HTML del listado.
 */
function f_getPropietariosListado() {
    global $pagina, $oProps; //$oPers;
    $aPer = $oProps->getPropietariosNumeroPropiedades(TRUE);
    $sIni = "";
    $sPer = "<div><a name=\"inicio\"></a></div>";
    foreach ($aPer as $per => $aDatos) {
        $nombre = $aDatos[0];
        $numpro = $aDatos[1];
        $numalt = $aDatos[2];
        $numbaj = $aDatos[3];
        $cla = f_getPropietariosClase($numpro, $numalt, $numbaj);
        $ini = substr($nombre, 0, 1);
        $sPer .= ($ini != $sIni) ? "<div class=\"listado-tit\"><a name=\"ini$ini\" href=\"#inicio\">$ini</a></div>" : "";
        $sPer .= "<div class=\"$cla\" onclick=\"xajax_reenviarFuncion('$pagina', $per);\" data-animation=\"false\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"$nombre ($numpro)\">$nombre</div>";
        $sIni = $ini;
    }
    return $sPer;
}

/**
 * Obtiene la clase CSS aplicable a un propietario.
 * <ul>
 * <li>listado-elem-des Sin propiedades.</li>
 * <li>listado-elem Tiene alguna propiedad de alta.</li>
 * <li>listado-elem-baja Tiene propiedades, pero de baja todas.</li>
 * </ul>
 * 
 * @param int $num Numero de propiedades.
 * @param int $alt Numero de propiedades de alta.
 * @param int $baj Numero de propiedades de baja.
 * @return string Clase CSS.
 */
function f_getPropietariosClase($num, $alt, $baj) {
    if (!$num) {
        // No tiene propiedades.
        $cla = "listado-elem-des";
    } elseif ($alt) {
        // Tiene alguna propiedad de alta.
        $cla = "listado-elem";
    } elseif ($baj) {
        // Tiene propiedades, pero de baja.
        $cla = "listado-elem-baja";
    }
    return $cla;
}

/**
 * Obtiene las propiedades de una persona.
 * 
 * @global \Propietarios $oProps Instancia de Propietarios.
 * @param int $per Codigo de persona.
 * @return string Codigo HTML de las propiedades.
 */
function f_getPropiedades($per) { 
    global $oProps;
    $aPro = $oProps->getPropiedadesPersona($per);
    $tabla = "<table class=\"table table-sm\" width=\"100%\"><tr><th>&nbsp;</th><th>Propiedad</th><th>Orden</th><th>Fecha baja</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
    foreach ($aPro as $apa => $aDat) {
        $nom = $aDat[0];
        $dat = $aDat[1];
        $fec = $aDat[2];
        $ord = $aDat[3];
        $tabla .= f_getPropiedad($per, $apa, $nom, $dat, $fec, $ord);
    }
    $tabla .= f_getPropiedadNueva($aPro, $per);
    
    return "$tabla</table>"; 
}

/**
 * Obtiene el formulario para una propiedad.
 * 
 * @param int $per Codigo de persona.
 * @param int $apa Codigo de apartamento.
 * @param string $nom Nombre del propietario.
 * @param date $dat Fecha de baja en formato YYYY-MM-DD.
 * @param date $fec Fecha de baja en formato DD-MM-YYYY.
 * @param int $ord Numero de orden.
 * @return string Codigo HTML del formulario.
 */
function f_getPropiedad($per, $apa, $nom, $dat, $fec, $ord) {
    if($dat) {
        // Propiedad de baja.
        $clase = "dangercolor";
        $verpe = "btn-outline-danger";
        $boton = "btn-danger";
    } else {
        // Propiedad de alta.
        $clase = "";
        $verpe = "btn-outline-info";
        $boton = "btn-success";
    }
    $on = "$('#boton$apa').prop('disabled',false)";
    $gr = f_getPropiedadBoton($per, $apa);
    
    return "<tr><td class=\"align-middle\" width=\"5%\" title=\"Ver apartamento $apa\"><a class=\"btn $verpe\" href=\"apartamentos.php?apartamento=$apa\" role=\"button\"><span class=\"oi oi-eye\"></span></a></td>
            <td class=\"align-middle $clase\" width=\"60%\">$nom</td>
            <td class=\"align-middle\" width=\"10%\">" . f_getSelectSimple(array(0,1,2,3,4,5,6,7,8,9), "orden$apa", $ord, "form-control $clase", $on) . "</td>
            <td class=\"align-middle\" width=\"15%\"><input type=\"text\" id=\"fecha$apa\" name=\"fecha$apa\" class=\"form-control text-center calendario $clase\" style=\"background-color:transparent\" readonly=\"readonly\" value=\"$fec\"></td>
            <td class=\"align-middle text-right\" width=\"5%\"><button type=\"button\" id=\"boton$apa\" onclick=\"$gr\" class=\"btn $boton\" title=\"Grabar\" disabled=\"disabled\"><span class=\"oi oi-hard-drive\"></span></button></td>
            <td class=\"align-middle\" width=\"5%\"><button type=\"button\" id=\"borra$apa\" onclick=\"xajax_eliminarPropiedadPregunta($per, $apa)\" class=\"btn btn-danger\" title=\"Eliminar\"><span class=\"oi oi-trash\"></span></button></td></tr>";
}

/**
 * Obtiene el formulario para elegir una propiedad nueva.
 * 
 * @param array $aPro Nombres de los propietarios.
 * @param int $per Codigo de persona.
 * @return string Codigo HTML del formulario.
 */
function f_getPropiedadNueva($aPro, $per) {
    // Lista de todas las personas menos los propietarios.
    $aApar = f_getApartamentosConPortal($aPro);
    $on = "if($('#nombre0').val() == '') { $('#boton0').prop('disabled',true); } else { $('#boton0').prop('disabled',false); }";
    $gr = f_getPropiedadBoton($per, 0);
    
    return "<tr><th>&nbsp;</th><th>Nueva propiedad</th><th>Orden</th><th>Fecha baja</th><th>&nbsp;</th><th>&nbsp;</th></tr>
            <tr><td class=\"align-middle\" title=\"Nueva propiedad\"><div class=\"btn btn-outline-success\"><span class=\"oi oi-home\"></span></div></td>
            <td class=\"align-middle\">" . f_getSelectAgrupado($aApar, "nombre0", "", "form-control", $on, TRUE) . "</td>
            <td class=\"align-middle\">" . f_getSelectSimple(array(0,1,2,3,4,5,6,7,8,9), "orden0", 0, "form-control", $on) . "</td>
            <td class=\"align-middle\"><input type=\"text\" id=\"fecha0\" name=\"fecha0\" class=\"form-control text-center calendario\" style=\"background-color:transparent\" readonly=\"readonly\" value=\"\"></td>
            <td class=\"align-middle text-right\"><button type=\"button\" id=\"boton0\" onclick=\"$gr\" class=\"btn btn-outline-success\" title=\"Grabar\" disabled=\"disabled\"><span class=\"oi oi-hard-drive\"></span></button></td>
            <td class=\"align-middle\">&nbsp;</td></tr>";
}

/**
 * Obtiene una lista de propiedades.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param array $aPro Nombres de los propietarios.
 * @return string Codigo HTML del formulario.
 */
function f_getApartamentosConPortal($aPro) {
    global $oApars;
    $aDat  = array();
    $aApar = $oApars->getApartamentosExcluyendo($aPro, FALSE);
    foreach ($aApar as $apa => $aDatos) {
        $aDat[$apa] = array("Portal " . $aDatos[0] . "-" . $aDatos[1] . $aDatos[2], "Portal " . $aDatos[0]);
    }
    return $aDat;
}

/**
 * Obtiene el boton para grabar.
 * 
 * @param int $per Codigo de persona.
 * @param int $apa Codigo de apartamento.
 * @return string Valor del boton.
 */
function f_getPropiedadBoton($per, $apa) {
    $apart = ($apa) ? $apa : "$('#nombre$apa').val()";
    return "xajax_grabarPropiedad($per, $apart, $('#orden$apa').val(), $('#fecha$apa').val())";
}

/**
 * Graba los datos de una propiedad.
 * 
 * @param int $per Codigo de persona.
 * @param int $apa Codigo de apartamento.
 * @param int $ord Numero de orden.
 * @param date $baj Fecha de baja.
 * @return boolean Devuelve TRUE si todo es correcto o FALSE si ha fallado algo.
 */
function f_grabarPropiedad($per, $apa, $ord, $baj) {
    $oPro = new Propietario($per);
    $oPro->setPropiedad($apa, $baj, $ord);
    return $oPro->grabarPropiedades();
}

/**
 * Elimina una propiedad.
 * 
 * @param int $per Codigo de persona.
 * @param int $apa Codigo de apartamento.
 * @return boolean Devuelve TRUE si todo es correcto o FALSE si ha fallado algo.
 */
function f_eliminarPropiedad($per, $apa) {
    $oPro = new Propietario($per);
    return $oPro->eliminarPropiedad($apa);
}

//--- JUNTAS - DATOS ---------------------------------------------------------//

/**
 * Obtiene los años de las juntas.
 * 
 * @return string Codigo HTML de los años de las juntas.
 */
function f_getJuntasAnyos() {
    $oJuntas = new Juntas();
    $aAnys = $oJuntas->getJuntasAnyos();
    $sAnys = "";
    foreach ($aAnys as $any) {
        $sAnys .= "<a href=\"#any$any\">$any</a>&nbsp;";
    }
    return $sAnys;
}

/**
 * Obtiene un listado con las fechas de las juntas.
 * 
 * @global string $pagina Nombre de la pagina actual.
 * @return string Codigo HTML de las juntas.
 */
function f_getJuntasListado() {
    global $pagina;
    $oJuntas = new Juntas();
    $aJuntas = $oJuntas->getJuntas();
    $sJuntas = "<div><a name=\"inicio\"></a></div>";
    $sAny = "";
    
    foreach ($aJuntas as $date => $aJunta) {
        $any   = substr($date, 0, 4);
        $fecha = $aJunta[0];
        $tipo  = $aJunta[1];
        if ($tipo == 'O') {
            $clase = "";
        } elseif ($tipo == "I") {
            $clase = "";
        } else {
            $clase = "";
        }
        $sJuntas .= ($any != $sAny) ? "<div class=\"listado-tit\"><a name=\"any$any\" href=\"#inicio\">$any</a></div>" : "";
        $sJuntas .= "<div class=\"listado-elem $clase\" onclick=\"xajax_reenviarFuncion('$pagina', '$fecha');\">$fecha</div>";
        $sAny = $any;
    } 
    return $sJuntas;
}

/**
 * Obtiene la ultima Junta guardada.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @return date Fecha de la ultima Junta.
 */
function f_getUltimaJunta() {
    global $oJuntas;
    return $oJuntas->getUltimaJunta();
}

/**
 * Obtiene los datos de una Junta existente.
 * 
 * @param date $fecha Fecha de la Junta.
 * @return array Datos obtenidos del tipo array($fec, $ori, $tip, $con, $hor, $pre, $vi1, $vi2, $vo1, $vo2, $vo3, $vo4, $sec, $adm, $not, $tit, $src, $iok)
 */
function f_setJuntaDatosExistente($fecha) {
    // Obtiene los datos de la Junta.
    $oJunta = new Junta($fecha);
    $fec = $fecha;
    $ori = $oJunta->getFecha();
    $tip = $oJunta->getTipo();
    $con = $oJunta->getConvocatoria();
    $hor = $oJunta->getHora();
    $pre = $oJunta->getPresidente();
    $vi1 = $oJunta->getVicepresidente1();
    $vi2 = $oJunta->getVicepresidente2();
    $vo1 = $oJunta->getVocal1();
    $vo2 = $oJunta->getVocal2();
    $vo3 = $oJunta->getVocal3();
    $vo4 = $oJunta->getVocal4();
    $sec = $oJunta->getSecretario();
    $adm = $oJunta->getAdministracion();
    $not = $oJunta->getNotas();
    $tit = "Junta del $fec";
    $src = "$('.calendario').datepicker('destroy');$('#fecha').css('backgroundColor','transparent');$('#boasistentes').show(); $('#botongrabar').show(); $('#boeliminar').show();";
    $iok = 1;
    return array($fec, $ori, $tip, $con, $hor, $pre, $vi1, $vi2, $vo1, $vo2, $vo3, $vo4, $sec, $adm, $not, $tit, $src, $iok);
}

/**
 * Obtiene los datos para una Junta nueva.
 * Si la fecha de la nueva junta no existe, obtiene los datos principales de la junta anterior.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @param date $fecha Fecha para la nueva Junta.
 * @return array Datos por omision del tipo array($fec, $ori, $tip, $con, $hor, $pre, $vi1, $vi2, $vo1, $vo2, $vo3, $vo4, $sec, $adm, $not, $tit, $src, $iok)
 */
function f_setJuntaDatosNueva($fecha) {
    global $oJuntas;
    
    if ($oJuntas->existeJunta($fecha)) {
        // En la nueva fecha ya existe una Junta.
        $fec = "";
        $ori = "";
        $tip = "";
        $con = "";
        $hor = "";
        $pre = "";
        $vi1 = "";
        $vi2 = "";
        $vo1 = "";
        $vo2 = "";
        $vo3 = "";
        $vo4 = "";
        $sec = "";
        $adm = "";
        $not = "";
        $tit = "";
        $src = "$('#fecha').css('backgroundColor','#F8D7DA'); $('#botongrabar').hide(); $('#boasistentes').hide(); $('#boeliminar').hide();";
        $iok = 0;
    } else {
        // En la nueva fecha no existe ninguna otra Junta. Busca los datos de la Junta anterior.
        $aDatos = $oJuntas->getJuntaAnterior($fecha);
        $dat = Funciones::gConvertirFecha($fecha, TRUE);
        $fec = $fecha;
        $ori = "";
        $tip = (date("m", strtotime($dat)) == "08") ? "O" : "E";
        $con = "2";
        $hor = "10:00";
        $pre = $aDatos[2];
        $vi1 = $aDatos[3];
        $vi2 = $aDatos[4];
        $vo1 = $aDatos[5];
        $vo2 = $aDatos[6];
        $vo3 = $aDatos[7];
        $vo4 = $aDatos[8];
        $sec = $aDatos[9];
        $adm = $aDatos[10];
        $not = "";
        $tit = "Nueva Junta";
        $src = "$('#fecha').css('backgroundColor','#dff9df'); $('#botongrabar').show(); $('#boasistentes').hide(); $('#boeliminar').hide();";
        $iok = 1;
    }
    return array($fec, $ori, $tip, $con, $hor, $pre, $vi1, $vi2, $vo1, $vo2, $vo3, $vo4, $sec, $adm, $not, $tit, $src, $iok);
}

/**
 * Obtiene el numero de asistentes, representados y votos de una Junta.
 * 
 * @param date $fecha Fecha de la Junta.
 * @return array Sumas del tipo array(sumasis, sumrepr, sumvotos)
 */
function f_getAsistentesSumas($fecha='') {
    $aSumas = array(0, 0, 0);
    if ($fecha) {
        // Se trata de una Junta.
        $oAsis = new Asistentes($fecha);
        $aSumas = $oAsis->getSumas();   // array('prop' => array('propietarios', 'distintos', 'con voto', 'sin voto'), 'repr' => array('representados', 'distintos', 'con voto', 'sin voto'))
        $aProps = $aSumas['prop'];
        $aReprs = $aSumas['repr'];
        $aSumas[0] = $aProps[0] + $aReprs[0];
        $aSumas[1] = $aReprs[0];
        $aSumas[2] = $aProps[2] + $aReprs[2];
    } 
    return $aSumas;
}

/**
 * Graba los datos de una Junta.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @param array $frm Datos del formulario.
 * @return boolean Devuelve TRUE si todo es correcto o FALSE si falla algo.
 */
function f_grabarJunta($frm) {
    global $oJuntas;
    
    $bok = FALSE;
    $fec = $frm['fecha'];
    $tip = $frm['tipo'];
    $con = $frm['convo'];
    $hor = $frm['hora'];
    $pre = $frm['presi'];
    $vi1 = $frm['vice1'];
    $vi2 = $frm['vice2'];
    $vo1 = $frm['vocal1'];
    $vo2 = $frm['vocal2'];
    $vo3 = $frm['vocal3'];
    $vo4 = $frm['vocal4'];
    $sec = $frm['secre'];
    $adm = $frm['admi'];
    $not = $frm['notas'];
    
    if ($fec) {
        $oJunta = new Junta($fec);
        $oJunta->setTipo($tip);
        $oJunta->setConvocatoria($con);
        $oJunta->setHora($hor);
        $oJunta->setPresidente($pre);
        $oJunta->setVicepresidente1($vi1);
        $oJunta->setVicepresidente2($vi2);
        $oJunta->setVocal1($vo1);
        $oJunta->setVocal2($vo2);
        $oJunta->setVocal3($vo3);
        $oJunta->setVocal4($vo4);
        $oJunta->setSecretario($sec);
        $oJunta->setAdministracion($adm);
        $oJunta->setNotas($not);      
        if ($oJunta->grabar()) {
            $oJuntas->recargar();   // Recarga las Juntas.
            $bok = TRUE;
        }
    }
    return $bok;
}

//--- JUNTA - ASISTENTES -----------------------------------------------------//

/**
 * Obtiene los asistentes a una Junta.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param date $fecha Fecha de la Junta.
 * @return string Codigo HTML de los asistentes.
 */
function f_getAsistentes($fecha) { 
    global $oApars;
    $oAsist = new Asistentes($fecha);
    
    $aApars = $oApars->getApartamentos();
    $aAsist = $oAsist->getAsistentes();
    $portal = 1;
    $flechaI = "<a href=\"#ini1\"><span class=\"oi oi-data-transfer-upload\"></span></a>";
    $flechaF = "<a href=\"#final\"><span class=\"oi oi-data-transfer-download\"></span></a>";
    $flecha1 = "<a href=\"#ini2\"><span class=\"oi oi-arrow-thick-bottom\" role=\"button\"></span></a>";
    $flecha2 = "<a href=\"#ini1\"><span class=\"oi oi-arrow-thick-top\" role=\"button\"></span></a>";
    $tabla  = "<table class=\"table table-sm\" style=\"width:100%\"><tr style=\"background-color:#F5F5F5\"><th class=\"align-middle\">&nbsp;<a name=\"ini$portal\">Portal $portal</a></th><th class=\"align-middle\">Voto</th><th class=\"align-middle\">Repr.</th><th class=\"align-middle\">Asistente a la Junta General</th><th class=\"align-middle text-center\">$flecha1&nbsp;&nbsp;$flecha2</th><th class=\"align-middle text-center\">$flechaF&nbsp;&nbsp;$flechaI</th></tr>";
    
    foreach ($aApars as $apa => $aApartamento) {
        if ($portal != $aApartamento[0]) {
            $portal = $aApartamento[0];
            $p1 = $portal + 1;
            $p2 = $portal - 1;
            $flecha1 = ($portal != 26) ? "<a href=\"#ini$p1\"><span class=\"oi oi-arrow-thick-bottom\" role=\"button\"></span></a>" : "<a href=\"#ini26\"><span class=\"oi oi-arrow-thick-bottom\" role=\"button\"></span></a>";
            $flecha2 = "<a href=\"#ini$p2\"><span class=\"oi oi-arrow-thick-top\" role=\"button\"></span></a>";
            $tabla .= "<tr style=\"background-color:#F5F5F5;\"><th class=\"align-middle\">&nbsp;<a name=\"ini$portal\">Portal $portal</a></th><th class=\"align-middle\">Voto</th><th class=\"align-middle\">Repr.</th><th class=\"align-middle\">Asistente a la Junta General</th><th class=\"align-middle text-center\">$flecha1&nbsp;&nbsp;$flecha2</th><th class=\"align-middle text-center\">$flechaF&nbsp;&nbsp;$flechaI</th></tr>";
        }
        
        if (isset($aAsist[$apa])) {
            $asis = $aAsist[$apa][1];   // Codigo de persona
            $repr = $aAsist[$apa][3];   // Representante S/N
            $voto = $aAsist[$apa][4];   // Voto S/N   
        } else {
            $asis = "";
            $repr = "N";
            $voto = "N";
        }
        
        $chk1 = ($voto == "S") ? "checked=\"checked\"" : "";
        $chk2 = ($repr == "S") ? "checked=\"checked\"" : "";
        $onCh = "xajax_setAsistenteMulti('$fecha', '$apa', $('#nombre$apa').val(), $('#voto$apa').prop('checked'), $('#repr$apa').prop('checked'), $('#multiples').prop('checked'));";
        $onCl = "if(this.checked){ xajax_getRepresentantes('$fecha',$apa); } else { xajax_getPropietarios('$fecha', $apa); }";
        $sele = f_getPropietariosRepresentantes($apa, $fecha, $asis, $repr, $onCh);
        $tabla .= "<tr><td class=\"align-middle col-sm-1\" style=\"width:10%\"><div id=\"apartamento$apa\">&nbsp;" . $aApartamento[0] . "-" . $aApartamento[1] . $aApartamento[2] . "</div></td>
                   <td class=\"align-middle col-sm-1\" style=\"width:5%\"><input type=\"checkbox\" id=\"voto$apa\" name=\"voto$apa\" $chk1 onchange=\"$onCh\"></td>
                   <td class=\"align-middle col-sm-1\" style=\"width:5%\"><input type=\"checkbox\" id=\"repr$apa\" name=\"repr$apa\" $chk2 onchange=\"$onCh $onCl\" onclick=\"\"></td>
                   <td class=\"align-middle col-sm-7\" style=\"width:70%\" id=\"selec$apa\">$sele</td>
                   <td class=\"align-middle col-sm-1 text-center\" style=\"width:5%\"><button class=\"btn btn-warning\" type=\"button\" onclick=\"xajax_setAsistente('$fecha','$apa')\" title=\"Deshacer\"><span class=\"oi oi-loop-circular\"></span></button></td>
                   <td class=\"align-middle col-sm-1 text-center\" style=\"width:5%\"><button id=\"boton$apa\" class=\"btn btn-success\" type=\"button\" onclick=\"xajax_grabarAsistenteMulti('$fecha', '$apa', $('#nombre$apa').val(), $('#repr$apa').prop('checked'), $('#voto$apa').prop('checked'), $('#multiples').prop('checked'))\" title=\"Guardar\" disabled=\"disabled\"><span class=\"oi oi-hard-drive\"></span></button></td></tr>";
    }
    return "<a name=\"iniciolista\">&nbsp;</a>$tabla</table><a name=\"finallista\">&nbsp;</a>";
}

/**
 * Obtiene un select con los nombres de los propietarios o los representantes.
 * 
 * @global \Propietarios $oProps Instancia de Propietarios.
 * @param int $apa Codigo de apartamento.
 * @param date $fecha Fecha de la Junta.
 * @param int $sel Codigo de la persona a seleccionar.
 * @param string $repr Representado 'S' o propietario 'N'.
 * @param string $onCh Cadena para el evento onchange del select.
 * @return string Codigo HTML del select.
 */
function f_getPropietariosRepresentantes($apa, $fecha, $sel='', $repr='N', $onCh='') {
    global $oProps;
    $aPro = ($repr == 'S') ? $oProps->getRepresentantes($apa, $fecha) : $oProps->getNombresPropietariosApartamentoFecha($apa, $fecha);
    $func = "$('#boton$apa').prop('disabled',false); if(!this.value){ $('#voto$apa').prop('checked',false); } else { $('#voto$apa').prop('checked',true); }; $onCh";
    return f_getSelect($aPro, "nombre$apa", $sel, "form-control", $func, TRUE);
}

//--- VOTACIONES -------------------------------------------------------------//

/**
 * Obtiene una lista con los años en los que ha habido votaciones.
 * 
 * @return string Lista con los años de las votaciones.
 */
function f_getVotacionesAnyos() {
    $oVots = new Votaciones();
    $aAnys = $oVots->getVotacionesAnyos();
    $sAnys = "";
    foreach ($aAnys as $any) {
        $sAnys .= "<a href=\"#any$any\">$any</a>&nbsp;";
    }
    return $sAnys;
}

/**
 * Obtiene un listado con las fechas de todas las votaciones que ha habido.
 * 
 * @global string $pagina Nombre de pagina.
 * @return string Codigo HTML con las fechas de la votaciones.
 */
function f_getVotacionesListado() {
    global $pagina;
    $oVots = new Votaciones();
    $aVots = $oVots->getVotaciones();
    $sVots = "<div><a name=\"inicio\"></a></div>";
    $sAny = "";
    $dFec = "";
    foreach ($aVots as $date => $aVota) {
        $any   = substr($date, 0, 4);
        $sVots .= ($any != $sAny) ? "<div class=\"listado-tit\"><a name=\"any$any\" href=\"#inicio\">$any</a></div>" : "";
        foreach ($aVota as $numvo => $fecha) {
            $sVots .= ($date != $dFec) ? "<div class=\"\">$fecha</div>" : "";
            $sVots .= "<div class=\"listado-elem\" onclick=\"xajax_reenviarFuncion('$pagina', '$fecha', '$numvo');\">Votaci&oacute;n $numvo</div>";
            $dFec = $date;
        }
        $sAny = $any;
    } 
    return $sVots;
}

/**
 * Obtiene una lista de propietarios para realizar una votacion.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @global \Personas $oPers Instancia de Personas.
 * @param date $fecha Fecha de la votacion.
 * @param int $num Numero de votacion.
 * @return string Codigo HTML de la votacion.
 */
function f_getAsistentesVotacion($fecha, $num=1) {
    global $oApars, $oProps; //$oPers;
    
    $aApars = $oApars->getApartamentos();
    $oVots = new Votaciones();
    $oVotx = new Votacion($fecha, $num);
    $oVota = (!$oVots->existeVotacion($fecha, $num)) ? f_cargarAsistentesJunta($oVotx) : $oVotx;
    
    $opc1 = ($oVota->getOpcion1()) ? $oVota->getOpcion1() : "Opc. 1";
    $opc2 = ($oVota->getOpcion2()) ? $oVota->getOpcion2() : "Opc. 2";
    $opc3 = ($oVota->getOpcion3()) ? $oVota->getOpcion3() : "Opc. 3";
    $opc4 = ($oVota->getOpcion4()) ? $oVota->getOpcion4() : "Opc. 4";
    
    $portal = "";
    $tabla  = "<table id=\"tablavot\" class=\"table table-sm\">";
    
    // Recorre los apartamentos de la urbanizacion.
    foreach ($aApars as $apa => $aApartamento) {
        
        // Cabecera para un nuevo portal.
        if ($portal != $aApartamento[0]) {
            $portal = $aApartamento[0];
            $tabla .= f_cabeceraTablaAsistentes($portal, $opc1, $opc2, $opc3, $opc4);
        }
        
        $aProp = $oProps->getNombrePropietarioApartamentoFecha($apa, $fecha);   // array('codpers'=>array('persona','date','fecha','orden'))
        $iProp = $oProps->getCodigoPropietarioApartamentoFecha($apa, $fecha);   // codpers
        $aApPr = $oProps->getCodigosPropiedadesPersonaFecha($iProp, $fecha);    // array(apa1,apa2...)
        
        $sApPr = "[" . implode(",", $aApPr) . "]";                      // Codigos en formato [1,2,3]
        
        // Datos de la votacion.
        $aVoto = $oVota->getVoto($apa); // array('asis','vota','pres','res1','res2','res3','res4')
        
        $ch1 = ($aVoto[0] == 'S') ? "checked=\"checked\"" : ""; // Asiste.
        $ch2 = ($aVoto[1] == 'S') ? "checked=\"checked\"" : ""; // Tiene voto.
        $ch3 = ($aVoto[2] == 'S') ? "checked=\"checked\"" : ""; // Presente.
        
        $op1 = ($aVoto[3] == 'S') ? "checked=\"checked\"" : ""; // Resultado 1.
        $op2 = ($aVoto[4] == 'S') ? "checked=\"checked\"" : ""; // Resultado 2.
        $op3 = ($aVoto[5] == 'S') ? "checked=\"checked\"" : ""; // Resultado 3.
        $op4 = ($aVoto[6] == 'S') ? "checked=\"checked\"" : ""; // Resultado 4.
        
        $bch = ($aVoto[0] != 'S') ? "disabled=\"disabled\"" : "";
        $bop = ($aVoto[0] != 'S' || $aVoto[1] != 'S' || $aVoto[2] != 'S') ? "disabled=\"disabled\"" : "";
        
        $nom = $aProp[$iProp];
        $onc = "js_marcarOpc(this.id, $apa, $sApPr, this.checked);";
        $onk = f_nombresApartamentosMultiples($apa, $aApPr);
        
        // Fila con los datos de la votacion de un apartamento.
        $tabla .= f_datosTablaAsistentes($apa, $aApartamento, $nom, $onc, $onk, $ch1, $ch2, $ch3, $bch, $sApPr, $op1, $op2, $op3, $op4, $bop);
    }
    return "<a name=\"iniciolista\">&nbsp;</a>$tabla</table><a name=\"finallista\">&nbsp;</a>";
}

/**
 * Crea una llamada a JavaScript para mostrar un tool-tip con los nombres de los apartamentos de un propietario.
 * Se muestran los nombres de sus apartamentos, menos el actual.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param int $apa Codigo del apartamento actual.
 * @param array $aCods Codigos de apartamentos.
 * @return string Llamada a la funcion que muestra los tool-tip.
 */
function f_nombresApartamentosMultiples($apa, $aCods) {
    global $oApars;
    $aNombres = array();
    if(count($aCods) > 1) {
        // Tiene varios apartamentos.
        foreach ($aCods as $codigo) {
            if ($codigo != $apa) {
                // No incluye el apartamento actual.
                $aNombres[] = $oApars->getNombreCompleto($codigo);
            }
        }
        $sNombres = implode(", ", $aNombres);
        $onk = "if($('#sincro').prop('checked') == true) { js_existeTooltip($apa, 'Sincronizado con $sNombres'); }";
    } else {
        $onk = "";
    }
    return $onk;
}

/**
 * Obtiene la cabecera de portal en la lista de una votacion.
 * 
 * @param int $portal Numero de portal.
 * @param string $opc1 Texto de la opcion 1.
 * @param string $opc2 Texto de la opcion 2.
 * @param string $opc3 Texto de la opcion 3.
 * @param string $opc4 Texto de la opcion 4.
 * @return string Codigo HTML de la cabecera.
 */
function f_cabeceraTablaAsistentes($portal, $opc1, $opc2, $opc3, $opc4) {
    return "<tr id=\"portal$portal\" style=\"background-color:#F5F5F5\">
                <th class=\"align-middle\">&nbsp;<a name=\"ini$portal\">Portal $portal</a></th>
                <th class=\"align-middle\">Propietario</th>
                <th class=\"align-middle text-center\">Asi.</th>
                <th class=\"align-middle text-center\">Vot</th>
                <th class=\"align-middle text-center\">Pre</th>
                <th id=\"titop1$portal\" class=\"align-middle text-center\" title=\"$opc1\">" . substr($opc1, 0, 8) . "</th>
                <th id=\"titop2$portal\" class=\"align-middle text-center\" title=\"$opc2\">" . substr($opc2, 0, 8) . "</th>
                <th id=\"titop3$portal\" class=\"align-middle text-center\" title=\"$opc3\">" . substr($opc3, 0, 8) . "</th>
                <th id=\"titop4$portal\" class=\"align-middle text-center\" title=\"$opc4\">" . substr($opc4, 0, 8) . "</th></tr>";
}

/**
 * Obtiene el contenido de lo que ha votado un apartamento en la lista de una votacion.
 * 
 * @param int $apa Codigo del apartamento.
 * @param array $aApartamento Datos del apartamento.
 * @param string $nom Nombre del propietario.
 * @param string $onc Llamada a la funcion que se ejecuta al hacer clic.
 * @param string $onk Llamada a la funcion de sincronizacion.
 * @param string $ch1 Estado de la seleccion de la casilla de asistencia.
 * @param string $ch2 Estado de la seleccion de la casilla de puede votar.
 * @param string $ch3 Estado de la seleccion de la casilla de presencia.
 * @param string $bch Estado de activacion de las casillas.
 * @param string $sApPr Lista de los codigos de todos los apartamentos de los cuales es propietario.
 * @param string $op1 Estado de seleccion de la opcion 1.
 * @param string $op2 Estado de seleccion de la opcion 2.
 * @param string $op3 Estado de seleccion de la opcion 3.
 * @param string $op4 Estado de seleccion de la opcion 4.
 * @param boolean $bop Estado de activacion de las opciones.
 * @return string Codigo HTML de la informacion sobre la votacion del apartamento.
 */
function f_datosTablaAsistentes($apa, $aApartamento, $nom, $onc, $onk, $ch1, $ch2, $ch3, $bch, $sApPr, $op1, $op2, $op3, $op4, $bop) {
    return "<tr id=\"fila$apa\"><td id=\"apartamento$apa\" class=\"align-middle\" style=\"width:10%\">" . $aApartamento[0] . "-" . $aApartamento[1] . $aApartamento[2] . "</td>
                <td class=\"align-middle\" style=\"width:35%\">$nom</td>
                <td class=\"align-middle text-center\" style=\"width:5%\"><input type=\"checkbox\" id=\"asis$apa\" name=\"asis[$apa]\" onclick=\"$onc $onk\" $ch1></td>
                <td class=\"align-middle text-center\" style=\"width:5%\"><input type=\"checkbox\" id=\"vota$apa\" name=\"vota[$apa]\" onclick=\"$onc\" $ch2 $bch></td>    
                <td class=\"align-middle text-center\" style=\"width:5%\"><input type=\"checkbox\" id=\"pres$apa\" name=\"pres[$apa]\" onclick=\"$onc\" $ch3 $bch></td>
                <td class=\"align-middle text-center\" style=\"width:10%\"><input type=\"radio\" id=\"res1$apa\" name=\"opciones[$apa]\" onclick=\"js_sincronizar($sApPr,1)\" value=\"1\" $op1 $bop></td>
                <td class=\"align-middle text-center\" style=\"width:10%\"><input type=\"radio\" id=\"res2$apa\" name=\"opciones[$apa]\" onclick=\"js_sincronizar($sApPr,2)\" value=\"2\" $op2 $bop></td>
                <td class=\"align-middle text-center\" style=\"width:10%\"><input type=\"radio\" id=\"res3$apa\" name=\"opciones[$apa]\" onclick=\"js_sincronizar($sApPr,3)\" value=\"3\" $op3 $bop></td>
                <td class=\"align-middle text-center\" style=\"width:10%\"><input type=\"radio\" id=\"res4$apa\" name=\"opciones[$apa]\" onclick=\"js_sincronizar($sApPr,4)\" value=\"4\" $op4 $bop></td></tr>";
}

/**
 * Carga los votos de los asistentes a una votacion.
 * 
 * @param \Votacion $oVota Instancia de Votacion.
 * @return \Votacion Instancia con los votos cargados.
 */
function f_cargarAsistentesJunta($oVota) {
    $fecha = $oVota->getFecha();
    $oAsis = new Asistentes($fecha);
    $aAsis = $oAsis->getAsistentes();   // Asistentes: array('codapar' => array('apart','codpers','nombre','repre','voto')...)
    foreach ($aAsis as $apa => $aDat) {
        $vot = $aDat[4];    // Voto S/N.
        $oVota->setVoto($apa, 'S', $vot, 'S', 'N', 'N', 'N', 'N'); 
    } 
    return $oVota;
}

/**
 * Convierte el formato de un array de PHP en el formato para JavaScript.
 * 
 * @param array $aDatos Datos a transformar.
 * @return string Datos transformados como arrays de JavaScript.
 */
function f_convertirArrayJS($aDatos) {
    $sDatos = "var coeficienteJS = new Array();";
    foreach ($aDatos as $iApar => $aCoef) {
        $sCoef = implode(",", $aCoef);
        $sDatos .= "coeficienteJS[$iApar] = [$sCoef];";
    }
    return $sDatos;
}

/**
 * Obtiene el Select para elegir el numero de la votacion.
 * 
 * @param date $fecha Fecha de la votacion.
 * @param int $vot Numero de votacion seleccionada.
 * @return Codigo HTML para el Select del numero de votacion.
 */
function f_getSelectNumVotaciones($fecha, $vot=1) {
    $oVots = new Votaciones();
    $num   = $oVots->getUltimaVotacion($fecha) + 1;
    $aNum  = array();
    for($i=1; $i<=$num; $i++) {
        $aNum[$i] = $i;
    }
    return f_getSelectSimple($aNum, "votacion", $vot, "form-control form-control-sm", "js_cambiarNumeroVotacion($('#votacion').val(), $('#votacioninicial').val())");
}

/**
 * Graba los datos de una votacion.
 * 
 * @param array $frm Datos del formulario de la votacion.
 * @return boolean Devuelve TRUE si todo ha ido bien o FALSE en caso contrario.
 */
function f_grabarVotacion($frm) {
    $fec = $frm['fecha'];
    $num = $frm['votacion'];
    $txt = $frm['textvot'];
    $op1 = $frm['opcion1'];
    $op2 = $frm['opcion2'];
    $op3 = $frm['opcion3'];
    $op4 = $frm['opcion4'];
    $aAs = $frm['asis'];
    $aVo = $frm['vota'];
    $aPr = $frm['pres'];
    $aOp = $frm['opciones'];
    
    $oVot = new Votacion($fec, $num);
    $oVot->setTexto($txt);
    $oVot->setOpcion1($op1);
    $oVot->setOpcion2($op2);
    $oVot->setOpcion3($op3);
    $oVot->setOpcion4($op4);
    
    for($a=1; $a<=373; $a++) {
        $asi = ($aAs[$a]) ? 'S' : 'N';
        if ($asi == 'S') {
            $vot = ($aVo[$a]) ? 'S' : 'N';
            $pre = ($aPr[$a]) ? 'S' : 'N';
            $re1 = ($aOp[$a] == 1) ? 'S' : 'N';
            $re2 = ($aOp[$a] == 2) ? 'S' : 'N';
            $re3 = ($aOp[$a] == 3) ? 'S' : 'N';
            $re4 = ($aOp[$a] == 4) ? 'S' : 'N';
            $oVot->setVoto($a, $asi, $vot, $pre, $re1, $re2, $re3, $re4);
        }
    }
    return $oVot->grabar();
}

//--- JUNTAS - DEUDAS --------------------------------------------------------//

/**
 * Obtiene un listado con las fechas de las Juntas distinguiendo las que tienen deudas.
 * 
 * @global string $pagina Nombre de la pagina.
 * @return string Codigo HTML del listado de fechas.
 */
function f_getDeudasListado() {
    global $pagina;
    
    // Obtiene las fechas de las deudas.
    $oDeudas = new Deudas();
    $aFechas = array_keys($oDeudas->getFechas());
    
    // Obtiene las fechas de las Juntas.
    $oJuntas = new Juntas();
    $aJuntas = $oJuntas->getJuntas();
    $sJuntas = "<div><a name=\"inicio\"></a></div>";
    $sAny = "";
    
    foreach ($aJuntas as $date => $aJunta) {
        $any   = substr($date, 0, 4);
        $fecha = $aJunta[0];
        if (!in_array($date, $aFechas)) {
            $clase = "baja";
        } else {
            $clase = "";
        }
        $sJuntas .= ($any != $sAny) ? "<div class=\"listado-tit\"><a name=\"any$any\" href=\"#inicio\">$any</a></div>" : "";
        $sJuntas .= "<div class=\"listado-elem $clase\" onclick=\"xajax_reenviarFuncion('$pagina', '$fecha');\">$fecha</div>";
        $sAny = $any;
    } 
    return $sJuntas;
}

/**
 * Obtiene los datos de las deudas de los apartamentos en una fecha determinada.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @global \Propietarios $oProps Instancia de Propietarios.
 * @param data $fecha Fecha en cualquier formato.
 * @return string Codigo HTML de las deudas.
 */
function f_getDeudas($fecha) {
    global $oApars, $oProps;
    $oDeudas = new Deudas();
    
    $aApars = $oApars->getApartamentos();
    $papart = 0; $pdeuda = 0; $pordin = 0; $pextra = 0;
    $tapart = 0; $tdeuda = 0; $tordin = 0; $textra = 0;
    $portal = "";
    $tabla  = "<table class=\"table table-sm\">";
    
    foreach ($aApars as $apa => $aApartamento) {
        
        if ($aApartamento[0] != $portal) {
            // Nuevo portal.
            if ($portal) {
                // No es el primer portal, pone las sumas.
                $tabla .= f_getDeudasSuma("portal", $portal, $papart, $pdeuda, $pordin, $pextra);
            }
            // Pone el titulo del portal.
            $tabla .= "<tr id=\"titp" . $aApartamento[0] . "\"><th class=\"align-middle\">&nbsp;<a name=\"ini$aApartamento[0]\">Portal $aApartamento[0]</a></th><th class=\"align-middle\" colspan=\"3\">Propietario</th><th class=\"align-middle text-center\">Ordinaria</th><th class=\"align-middle text-center\">Extraordinaria</th><th class=\"align-middle text-center\">Suma</th><th>&nbsp;</th></tr>";
            $portal = $aApartamento[0];
            $papart = 0; $pdeuda = 0; $pordin = 0; $pextra = 0;
        }
        
        // Datos del apartamento.
        $on1 = "onkeyup=\"js_sumar($portal, this.id);\" onchange=\"js_formatear(this.id, 2);$('#boton$apa').prop('disabled',false);\" onfocus=\"$('#ultimofoco').val($(this).attr('id'));\"";
        $pro = $oProps->getNombreSimplePropietarioApartamentoFecha($apa, $fecha);   // Propietario en esa fecha.
        $aDe = $oDeudas->getDeudaFechaApartamento($fecha, $apa);                    // array('ordinaria', 'extraordinaria')
        $ord = number_format($aDe[0], 2, ".", "");   
        $ext = number_format($aDe[1], 2, ".", ""); 
        $sum = number_format($aDe[0] + $aDe[1], 2, ".", ",");
        $papart++; $pdeuda += ($sum > 0) ? 1 : 0; $pordin += $ord; $pextra += $ext;
        $tapart++; $tdeuda += ($sum > 0) ? 1 : 0; $tordin += $ord; $textra += $ext;
        $tabla .= "<tr id=\"fila$apa\"><td class=\"align-middle\" style=\"width:10%\"><div id=\"apartamento$apa\">&nbsp;" . $aApartamento[0] . "-" . $aApartamento[1] . $aApartamento[2] . "</div></td>
                   <td class=\"align-middle\" colspan=\"3\" style=\"width:35%\">$pro</td>
                   <td class=\"align-middle\" style=\"width:15%\">" . f_getCoeficientesInput("or$apa", $ord, "€", $on1, TRUE). "</td>
                   <td class=\"align-middle\" style=\"width:15%\">" . f_getCoeficientesInput("ex$apa", $ext, "€", $on1, TRUE). "</td>
                   <td class=\"align-middle\" style=\"width:15%\">" . f_getCoeficientesInput("su$apa", $sum, "€", "tabindex=\"-1\"", FALSE). "</td>
                   <td class=\"align-middle text-right\" style=\"width:10%\"><button class=\"btn btn-warning\" type=\"button\" onclick=\"xajax_deshacerDeuda($portal, $apa, $ord, $ext)\" title=\"Deshacer\"><span class=\"oi oi-loop-circular\"></span></button>&nbsp;
                   <button id=\"boton$apa\" class=\"btn btn-success\" type=\"button\" onclick=\"xajax_grabarDeuda('$fecha','$apa',$('#or$apa').val(),$('#ex$apa').val())\" title=\"Guardar\" disabled=\"disabled\"><span class=\"oi oi-hard-drive\"></span></button></td></tr>";
    }
    $tabla .= f_getDeudasSuma("portal", $portal, $papart, $pdeuda, $pordin, $pextra);
    $tabla .= f_getDeudasSuma("total", "", $tapart, $tdeuda, $tordin, $textra);
    return "<a name=\"iniciolista\">&nbsp;</a>$tabla</table><a name=\"finallista\">&nbsp;</a>";
}

/**
 * Obtiene las sumas de las deudas.
 * 
 * @param string $tipo Tipo de suma a realizar: portal, total.
 * @param string $valor Valor del portal.
 * @param int $apar Codigo de apartamento.
 * @param int $deuda Numero de deudores.
 * @param int $ordin Deuda ordinaria.
 * @param int $extra Deuda extraordinaria.
 * @return string Codigo HTML de las sumas.
 */
function f_getDeudasSuma($tipo, $valor, $apar, $deuda, $ordin, $extra) {
    $let = substr($tipo, 0, 1);
    $ord = number_format($ordin, 2, ".", ",");   
    $ext = number_format($extra, 2, ".", ","); 
    $sum = number_format($ordin + $extra, 2, ".", ",");
    $por = number_format($deuda * 100 / $apar, 2, ".", ",");
    $idp = ($tipo === "portal") ? "sump$valor" : "sumtotal";
    return "<tr id=\"$idp\"><td class=\"align-middle text-center\" id=\"${let}ap$valor\">$apar</td>
            <td class=\"align-middle text-right\">Deudores $tipo $valor:</td>
            <td class=\"align-middle text-center\" id=\"${let}de$valor\">$deuda</td>
            <td class=\"align-middle\" id=\"${let}po$valor\">($por %)</td>
            <td class=\"align-middle\">" . f_getCoeficientesInput("${let}or$valor", $ord, "€", "tabindex=\"-1\"", FALSE). "</td>
            <td class=\"align-middle\">" . f_getCoeficientesInput("${let}ex$valor", $ext, "€", "tabindex=\"-1\"", FALSE). "</td>
            <td class=\"align-middle\">" . f_getCoeficientesInput("${let}su$valor", $sum, "€", "tabindex=\"-1\"", FALSE). "</td>
            <td class=\"align-middle\">&nbsp;</td></tr>";
}

//--- ACTAS VISUALIZAR -------------------------------------------------------//

/**
 * Obtiene los años de las actas.
 * 
 * @return string Codigo HTML de la lista de años.
 */
function f_getActasAnyos() {
    $oActas = new Actas();
    $aAnys = $oActas->getActasAnyos();
    $sAnys = "";
    foreach ($aAnys as $any) {
        $sAnys .= "<a href=\"#any$any\">$any</a>&nbsp;";
    }
    return $sAnys;
}

/**
 * Obtiene un listado con las fechas de las actas.
 * 
 * @global string $pagina Nombre de la pagina actual.
 * @return string Codigo HTML del listado.
 */
function f_getActasListado() {
    global $pagina;
    $oActas = new Actas();
    $aActas = $oActas->getActas();
    $sActas = "<div><a name=\"inicio\"></a></div>";
    $sAny = "";
    
    foreach ($aActas as $date => $aActa) {
        $any   = substr($date, 0, 4);
        $fecha = $aActa[0];
        $sActas .= ($any != $sAny) ? "<div class=\"listado-tit\"><a name=\"any$any\" href=\"#inicio\">$any</a></div>" : "";
        $sActas .= "<div class=\"listado-elem\" onclick=\"xajax_reenviarFuncion('$pagina', '$fecha');\">$fecha</div>";
        $sAny = $any;
    } 
    return $sActas;
}

/**
 * Obtiene los datos del acta.
 * 
 * @global Juntas $oJuntas Instancia de Juntas.
 * @param date $fecha Fecha del acta.
 * @return string Codigo HTML del acta.
 */
function f_getDatosActa($fecha) {
    global $oJuntas;
    $oJunta = new Junta();
    $oActa = new Acta($fecha);
    $feISO = Funciones::gConvertirFecha($fecha, FALSE);
    // Titulo principal.
    $aTipo = $oJunta->getTipos();
    $sTipo = $aTipo[$oJuntas->getTipoJunta($fecha)];
    $html = "<a name=\"inicioacta\">&nbsp;</a><br /><h1>Acta de la Junta General $sTipo del $feISO</h1>";
    // Indice del acta.
    $html .= f_getDatosActaIndice($oActa->getTitulos()) . "<hr />";
    // Cuerpo del acta.
    $html .= "<div class=\"cuerpoacta\">" . f_getDatosActaCuerpo($oActa->getPuntos()) . "</div>";
    return $html;
}

/**
 * Obtiene el indice de los puntos del acta.
 * 
 * @param array $aIndi Puntos del acta.
 * @return string Codigo HTML del indice.
 */
function f_getDatosActaIndice($aIndi) {
    $html = "";
    if ($aIndi && count($aIndi) > 0) {
        foreach ($aIndi as $iPun => $sTit) {
            $html .= "<div class=\"indice\"><a href=\"#punto$iPun\">$sTit</a></div>";
        }
    }
    return $html;
}

/**
 * Obtiene el cuerpo del acta.
 * 
 * @param array $aPuntos array del tipo array('pun'=>array(punto, titulo, array('apa'=>array(apart, subtit, texto)...))...)
 * @return string Codigo HTML del cuerpo del acta.
 */
function f_getDatosActaCuerpo($aPuntos) {
    $html = "";
    foreach ($aPuntos as $iPun => $aPunto) {
        $sPun = $aPunto[0];
        $sTit = $aPunto[1];
        $aAps = $aPunto[2];
        $html .= "<h2><a name=\"punto$iPun\"></a>";
        $html .= ($sPun) ? "$sPun.- $sTit</h2>" : "$sTit</h2>";
        $html .= f_getDatosActaApartados($aAps);
    }
    return $html;
}

/**
 * Obtiene los apartados del acta.
 * 
 * @param array $aAps del tipo array('apa'=>array(apart, subtit, texto)...)
 * @return string Codigo HTML de los apartados.
 */
function f_getDatosActaApartados($aAps) {
    $html = "";
    foreach ($aAps as $aApa) {
        $sApa = $aApa[0];
        $sSub = $aApa[1];
        $sTxt = $aApa[2];
        $apar = ($sApa) ? "$sApa.- " : "";
        $subt = ($sSub) ? "<h3>$apar$sSub</h3>" : "";
        $html .= $subt . $sTxt;
    }
    return $html;
}

/**
 * Obtiene el nombre del archivo PDF para el acta.
 * 
 * @param date $fec Fecha.
 * @return string Cadena con el nombre del archivo PDF.
 */
function f_getNombreActaPDF($fec) {
    $oJunta = new Junta($fec);
    $fecha = $oJunta->getFecha();
    $tipo  = $oJunta->getTipo(FALSE);
    return "Acta_$fecha${tipo}_es.pdf";
}

//--- ACTAS BUSCAR -----------------------------------------------------------//

/**
 * Obtiene un panel de resultados de la busqueda de palabras en las actas.
 * 
 * @param string $lista Lista de palabras a buscar.
 * @param int $tipo Tipo de busqueda: 0 - Natural, 1 - Booleana, 2 - Literal.
 * @return string Codigo HTML del resultado.
 */
function f_getActasDatosBusqueda($lista, $tipo) {
    $oActas = new Actas();
    switch ($tipo) {
        // array(0 - fecha, 1 - codpun, 2 - punto, 3 - titulo, 4 - codapa, 5 - apartado, 6 - subtit, 7 - texto, 8 - relevancia)
        case 1 : $aBus = $oActas->busquedaBooleana($lista); break;
        case 2 : $aBus = $oActas->busquedaLiteral($lista); break;
        default: $aBus = $oActas->busquedaNatural($lista); break;
    }
    $html = "<h3><a name=\"inicio\"></a>Encontradas " . count($aBus) . " actas para <em>$lista</em></h3><div id=\"accordion\" role=\"tablist\">"; 
    foreach ($aBus as $aPunto) {
        $fecha = $aPunto[0];
        $feISO = Funciones::gConvertirFecha($fecha, FALSE);
        $iPunt = $aPunto[1];
        $sPunt = $aPunto[2];
        $sTitu = ($aPunto[3]) ? Funciones::gRemarcar($lista, $aPunto[3]) : "";
        $iApar = $aPunto[4];
        $sApar = $aPunto[5];
        $sSubt = ($aPunto[6]) ? Funciones::gRemarcar($lista, $aPunto[6]) : "";
        $sText = ($aPunto[7]) ? Funciones::gRemarcar($lista, $aPunto[7]) : "";
        $iRele = number_format($aPunto[8],2);
        $apart = ($sApar) ? "$sApar.- " : "";
        $subti = ($sSubt) ? "<h5>$apart$sSubt</h5>" : "";
        
        $html .= "<div class=\"card\"><div class=\"card-header\" role=\"tab\" id=\"c$fecha$iPunt$iApar\" title=\"Relevancia $iRele\"><div class=\"row\">
                  <div class=\"col-sm-1\"><a data-toggle=\"collapse\" href=\"#p$fecha$iPunt$iApar\" role=\"button\" aria-expanded=\"true\" aria-controls=\"p$fecha$iPunt$iApar\">$feISO</a></div>
                  <div class=\"col-sm-10\"><a data-toggle=\"collapse\" href=\"#p$fecha$iPunt$iApar\" role=\"button\" aria-expanded=\"true\" aria-controls=\"p$fecha$iPunt$iApar\">$sPunt.- $sTitu</a></div>
                  <div class=\"col-sm-1 text-right\"><a href=\"actas.php?fecha=$fecha\" role=\"button\" class=\"btn btn-sm btn-outline-primary\" title=\"Ver acta del d&iacute;a $feISO\"><span class=\"oi oi-eye\"></span></a></div></div></div>
                  <div id=\"p$fecha$iPunt$iApar\" class=\"collapse\" role=\"tabpanel\" aria-labelledby=\"c$fecha$iPunt$iApar\" data-parent=\"#accordion\">
                  <div class=\"card-body\">$subti$sText</div></div></div>";
    }
    return "$html</div>";
}

//--- ACTAS EDITAR -----------------------------------------------------------//

/**
 * Obtiene los datos de un acta para editarlos.
 * 
 * @param date $fecha Fecha del acta.
 * @return string Codigo HTML del acta.
 */
function f_getActa($fecha='') {
    $html = "<a name=\"inicioacta\"></a><div id=\"accordion\" role=\"tablist\">";
    $oActa = new Acta($fecha);
    $aPunt = $oActa->getPuntos();
    $iNuev = 0;
    foreach ($aPunt as $iPun => $aPunto) {
        $sPun = $aPunto[0];
        $sTit = $aPunto[1];
        $aAps = $aPunto[2];
        $html .= "<div class=\"card\">" . f_getActaCabecera($iPun, $sPun, $sTit) . f_getActaApartados($iPun, $aAps) . "</div>";
        $iNuev = $iPun + 1;
    }
    // Punto vacio.
    $html .= "<div class=\"card\">" . f_getActaCabecera($iNuev, '', '') . f_getActaApartados($iNuev, array()) . "</div>";
    
    return "$html</div>";
}

/**
 * Obtiene los aparatados de un acta.
 * 
 * @param int $iPun Punto del acta.
 * @param array $aAps Apartados del punto.
 * @return string Codigo HTML de los apartados.
 */
function f_getActaApartados($iPun, $aAps) {
    $html .= "<div id=\"p$iPun\" class=\"collapse\" role=\"tabpanel\" aria-labelledby=\"c$iPun\" data-parent=\"#accordion\"><div class=\"card-body\">";
    $iNue = 0;
    foreach ($aAps as $iApa => $aApa) {
        $sApa = $aApa[0];
        $sSub = $aApa[1];
        $sTxt = $aApa[2];
        $html .= f_getActaCuerpo($iPun, $iApa, $sApa, $sSub, $sTxt);
        $iNue = $iApa + 1;
    }
    // Apartado vacio.
    $html .= f_getActaCuerpo($iPun, $iNue, '', '', '');
        
    return "$html</div></div>";
}

/**
 * Obtiene la cabecera del punto del acta.
 * 
 * @param int $iPun Codigo del punto.
 * @param string $sPun Numero del punto.
 * @param string $sTit Titulo del punto.
 * @return string Codigo HTML de la cabecera.
 */
function f_getActaCabecera($iPun, $sPun, $sTit) {
    return "<div class=\"card-header\" role=\"tab\" id=\"c$iPun\">
                <div class=\"row\">
                    <div class=\"col-sm-1\"><input type=\"text\" id=\"pun$iPun\" name=\"punt[$iPun]\" class=\"form-control\" value=\"$sPun\" placeholder=\"Punto\"></div>
                    <div class=\"col-sm-10\"><input type=\"text\" id=\"tit$iPun\" name=\"tit[$iPun]\" class=\"form-control\" value=\"$sTit\" placeholder=\"T&iacute;tulo\"></div>
                    <div class=\"col-sm-1 text-right\"><a data-toggle=\"collapse\" href=\"#p$iPun\" class=\"btn btn-outline-primary\" role=\"button\" aria-expanded=\"true\" aria-controls=\"p$iPun\"><span class=\"oi oi-caret-bottom\"></span></a></div>
                </div>
            </div>";
}

/**
 * Obtiene el cuerpo del punto del acta.
 * 
 * @param int $iPun Codigo del punto.
 * @param int $iApa Codigo del apartado.
 * @param string $sApa Numero del apartado.
 * @param string $sSub Titulo del apartado.
 * @param string $sTxt Texto de apartado.
 * @return string Codigo HTML del cuerpo.
 */
function f_getActaCuerpo($iPun, $iApa, $sApa, $sSub, $sTxt) {
    return "<div class=\"row\">
                <div class=\"col-sm-1\"><input type=\"text\" id=\"apa$iPun-$iApa\" name=\"apa[$iPun][$iApa]\" class=\"form-control\" value=\"$sApa\" placeholder=\"Apartado\"></div>
                <div class=\"col-sm-11\"><input type=\"text\" id=\"sub$iPun-$iApa\" name=\"sub[$iPun][$iApa]\" class=\"form-control\" value=\"$sSub\" placeholder=\"Subt&iacute;tulo\"></div>
            </div>
            <div class=\"row\">
                <div class=\"col-sm-12\"><textarea id=\"txt$iPun-$iApa\" name=\"txt[$iPun][$iApa]\" class=\"editor form-control\">$sTxt</textarea></div>
            </div>";
}

/**
 * Graba los datos del acta.
 * 
 * @param array $frm Datos del formulario.
 * @return boolean Devuelve TRUE si el correcto o FASE si ha fallado algo.
 */
function f_grabarActa($frm) {
    $fini = $frm['fechainicial'];   // Fecha inicial del acta.
    $fnew = $frm['fecha'];          // Fecha actual a guardar.
    
    $oActa = new Acta($fini);       // Crea el acta inicial.
    $oActa->setFecha($fnew);        // Guarda la nueva fecha.
    
    $aPun = $frm['punt'];           // Array con los puntos del acta.
    $aTit = $frm['tit'];            // Array con los titulos de los puntos.

    // Recorre los puntos.
    foreach ($aPun as $iPun => $sPun) {
        $sTit = $aTit[$iPun];
        if ($sTit) {
            // Si hay un titulo guardamos y seguimos con los apartados.
            $oActa->setPuntoTxt($iPun, $sPun);  // Guarda el numero o letra del punto.
            $oActa->setTitulo($iPun, $sTit);    // Guarda el titulo del punto.
            
            $aApa = $frm['apa'][$iPun];         // Numeros de apartado del punto actual.
            $aSub = $frm['sub'][$iPun];         // Subtitulos del punto actual.
            $aTxt = $frm['txt'][$iPun];         // Textos del punto actual.
            // Recorre los apartados.
            foreach ($aApa as $iApa => $sApa) {
                $sSub = $aSub[$iApa];   // Subtitulo del apartado actual.
                $sTxt = $aTxt[$iApa];   // Texto del apartado actual.
                
                $oActa->setApartadoTxt($iPun, $iApa, $sApa);    // Guarda el numero o letra del apartado.
                $oActa->setSubtitulo($iPun, $iApa, $sSub);      // Guarda el subtitulo del apartado.
                $oActa->setTexto($iPun, $iApa, $sTxt);          // Guarda el texto del apartado.
            }
        }
    }
    return $oActa->grabar();
}

//--- CALCULOS Y LISTADOS ----------------------------------------------------//

//--- LISTADO DE PERSONAS ---//

/**
 * Obtiene el listado de personas.
 * 
 * @global \Personas $oPers Instancia de Personas.
 * @param array $frm Datos del formulario.
 * @return string Codigo HTML del listado.
 */
function f_getListadoPersonas($frm) {
    global $oPers;
     
    $aEnv = array('S'=>'S&iacute;', 'N'=>'No');
    $aSexo = array('H'=>'Hombre', 'M'=>'Mujer');
    $fTit = f_getListadoPersonasTitulo($frm);
    // Asigna el orden.
    $ord = $frm['orden'];
    $oPers->setOrden($ord);
    $sOrd = strtolower($oPers->getOrden(TRUE));
    // Asigna los filtros.
    $filPr = (isset($frm['propietarios'])) ? 'S' : 'N';
    $filCo = (isset($frm['concorreo'])) ? 'S' : 'N';
    $filTe = (isset($frm['contelefono'])) ? 'S' : 'N';
    $filHo = (isset($frm['hombres'])) ? 'S' : 'N';
    $filMu = (isset($frm['mujeres'])) ? 'S' : 'N';
    $filOt = (isset($frm['otros'])) ? 'S' : 'N';
    $oPers->setFiltroPropietario($filPr);
    $oPers->setFiltroCorreo($filCo);
    $oPers->setFiltroTelefono($filTe);
    $oPers->setFiltroSexoHombre($filHo);
    $oPers->setFiltroSexoMujer($filMu);
    $oPers->setFiltroSexoOtro($filOt);
    $aPers = $oPers->getFiltradas();
    $fPer = "";
    $num = 0;
    foreach ($aPers as $per => $aPersona) {
        // array('cod'=>array(0 apellidos, 1 nombre, 2 sexo, 3 codusu, 4 correo, 5 envios, 6 telefono, 7 notas)...)
        $fPer .= "<tr>";
        $fPer .= (isset($frm['codigo'])) ? "<td>$per</td>" : "";
        $fPer .= "<td>" . $oPers->getNombreCompleto($per) . "</td>";
        $fPer .= (isset($frm['correo'])) ? "<td>" . $aPersona[4] . "</td>" : "";
        $fPer .= (isset($frm['enviar'])) ? "<td>" . $aEnv[$aPersona[5]] . "</td>" : "";
        $fPer .= (isset($frm['telefono'])) ? "<td>" . $aPersona[6] . "</td>" : "";
        $fPer .= (isset($frm['sexo'])) ? "<td>" . $aSexo[$aPersona[2]] . "</td>" : "";
        $fPer .= "</tr>";
        $fPer .= (isset($frm['notas']) && $aPersona[7]) ? f_getListadoPersonasNotas($frm, $aPersona[7]) : "";
        $num++;
    }
    return "<h4><a name=\"inicio\"></a>Listado de $num personas ordenado por $sOrd. <span style=\"font-size:0.8em\"><em>Filtro: " . f_getListadoPersonasFiltros($frm) . "</em>.</span></h4><table class=\"table table-condensed table-ultra\">$fTit$fPer</table>";
}

/**
 * Obtiene la fila con los titulos del listado.
 * 
 * @param array $frm Datos del formulario.
 * @return string Codigo HTML de la fila.
 */
function f_getListadoPersonasTitulo($frm) {
    $fila = "<tr>";
    $fila .= (isset($frm['codigo'])) ? "<th style=\"width: 6%;\">C&oacute;digo</th>" : "";
    $fila .= "<th style=\"width: 45%;\">Nombre</th>";
    $fila .= (isset($frm['correo'])) ? "<th style=\"width: 25%;\">Correo</th>" : "";
    $fila .= (isset($frm['enviar'])) ? "<th style=\"width: 5%;\">Enviar</th>" : "";
    $fila .= (isset($frm['telefono'])) ? "<th style=\"width:13%;\">Tel&eacute;fono</th>" : "";
    $fila .= (isset($frm['sexo'])) ? "<th style=\"width: 6%;\">Sexo</th>" : "";
    $fila .= "</tr>";
    return $fila;
}

/**
 * Obtiene la fila de notas.
 * 
 * @param array $frm Datos del formulario.
 * @param string $nota Notas.
 * @return string Codigo HTML de la fila.
 */
function f_getListadoPersonasNotas($frm, $nota) {
    $col = 1;
    $col += (isset($frm['correo'])) ? 1 : 0;
    $col += (isset($frm['enviar'])) ? 1 : 0;
    $col += (isset($frm['telefono'])) ? 1 : 0;
    $col += (isset($frm['sexo'])) ? 1 : 0;
    if (isset($frm['codigo'])) {
        $fila = "<tr><td>&nbsp;</td><td colspan=\"$col\">$nota</td></tr>";
    } else {
        $fila = "<tr><td colspan=\"$col\" style=\"padding-left:50px;\">$nota</td></tr>";
    }
    return $fila;
}

/**
 * Obtiene una lista con los filtros que se estan usando en el listado.
 * 
 * @param array $frm Datos del formulario.
 * @return string Lista de filtros.
 */
function f_getListadoPersonasFiltros($frm) {
    $aF = array();
    $filtros = "";
    if (isset($frm['propietarios'])) {
        $aF[] = 'propietarios';
    }
    if (isset($frm['concorreo'])) {
        $aF[] = 'correo';
    }
    if (isset($frm['contelefono'])) {
        $aF[] = 'tel&eacute;fono';
    }
    if (isset($frm['hombres'])) {
        $aF[] = 'hombres';
    }
    if (isset($frm['mujeres'])) {
        $aF[] = 'mujeres';
    }
    if (isset($frm['otros'])) {
        $aF[] = 'otros';
    }
    if (count($aF)) {
        $filtros = implode(', ', $aF);
    } else {
        $filtros = "ninguno";
    }
    return $filtros;
}

//--- LISTADO DE APARTAMENTOS ---//

/**
 * Obtiene el listado de apartamentos con los datos indicados.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param array $frm Datos del formulario.
 * @return string Codigo HTML del listado de apartamentos.
 */
function f_getListadoApartamentos($frm) {
    global $oApars;
    
    $fTit = f_getListadoApartamentosTitulo($frm);
    // Asignar filtros.
    $tipo = $frm['contipo'];                            // Tipo de apartamento.
    $por1 = $frm['portal1'];                            // Portal inicial.
    $por2 = $frm['portal2'];                            // Portal final.
    $cong = (isset($frm['congaraje'])) ? 'S' : 'N';     // Con garaje.
    $cont = (isset($frm['conterraza'])) ? 'S' : 'N';    // Con terraza.
    // Asignar filtros
    $oApars->setFiltroTipo($tipo);
    $oApars->setFiltroPortalIni($por1);
    $oApars->setFiltroPortalFin($por2);
    $oApars->setFiltroGarajes($cong);
    $oApars->setFiltroTerrazas($cont);
    
    // Inicializa las sumas.
    $bloApa = 0; $bloMe2 = 0; $bloTer = 0; $bloCof = 0; $bloCob = 0; $bloMca = 0; $bloGar = 0;
    $sumApa = 0; $sumMe2 = 0; $sumTer = 0; $sumCof = 0; $sumCob = 0; $sumMca = 0; $sumGar = 0;
    
    //array('codapar'=>array('portal','piso','letra','tipo','metros','terraza','coeficiente','coef.blo','finca','numreg','catastral','metros cat','numgar')...)
    $aApars = $oApars->getFiltrados();
    $portal = "";
    $fApa = "";
    foreach ($aApars as $apa => $aApartamento) {
        //array('0 portal','1 piso','2 letra','3 tipo','4 metros','5 terraza','6 coeficiente','7 coef.blo','8 finca','9 numreg','10 catastral','11 metros cat','12 numgar')
        
        // Mira si hay cambio de portal.
        if($portal != $aApartamento[0] && $frm['sumas']) {
            // Pone las sumas.
            $fSumb = f_getListadoApartamentosSumas($frm, "Portal $portal: ", $bloApa, $bloMe2, $bloTer, $bloCof, $bloCob, $bloMca, $bloGar);
            $bloApa = 0; $bloMe2 = 0; $bloTer = 0; $bloCof = 0; $bloCob = 0; $bloMca = 0; $bloGar = 0;
            $fApa .= ($portal) ? $fSumb : "";
            $ttt = ($portal && $portal < 15) ? $fTit : "";
            $portal = $aApartamento[0];
        }
        
        //array('0 portal','1 piso','2 letra','3 tipo','4 metros','5 terraza','6 coeficiente','7 coef.blo','8 finca','9 numreg','10 catastral','11 metros cat','12 numgar')
        $fApa .= "$ttt<tr>"; $ttt = "";
        $fApa .= (isset($frm['codigo'])) ? "<td>$apa</td>" : "";
        $fApa .= (isset($frm['finca'])) ? "<td>" . $aApartamento[8] ."</td>" : "";
        $fApa .= "<td>" . $aApartamento[0] . "-" .$aApartamento[1] . $aApartamento[2] . "</td>";
        $bloApa++; $sumApa++;
        
        $fApa .= (isset($frm['tipo'])) ? "<td>" . $aApartamento[3] ."</td>" : "";
        if (isset($frm['metros'])) {
            $fApa .= "<td class=\"text-right\">" . number_format($aApartamento[4],2,',','.') ."</td>";
            $bloMe2 += $aApartamento[4]; $sumMe2 += $aApartamento[4];
        }
        if (isset($frm['terraza'])) {
            $fApa .= (intval($aApartamento[5])) ? "<td class=\"text-right\">" . number_format($aApartamento[5],2,',','.') ."</td>" : "<td>&nbsp;</td>";
            $bloTer += $aApartamento[5]; $sumTer += $aApartamento[5];
        }
        if (isset($frm['fase200'])) {
            $fApa .= "<td class=\"text-right\">" . number_format($aApartamento[6],4,',','.') ."</td>";
            $bloCof += $aApartamento[6]; $sumCof += $aApartamento[6];
        }
        if (isset($frm['bloque'])) {
            $fApa .= "<td class=\"text-right\">" . number_format($aApartamento[7],2,',','.') ."</td>";
            $bloCob += $aApartamento[7]; $sumCob += $aApartamento[7];
        }
        $fApa .= (isset($frm['registro'])) ? "<td class=\"text-center\">" . $aApartamento[9] ."</td>" : "";
        $fApa .= (isset($frm['refcat'])) ? "<td class=\"text-center\">" . $aApartamento[10] ."</td>" : "";
        if (isset($frm['metcat'])) {
            $fApa .= "<td class=\"text-right\">" . number_format($aApartamento[11],2,',','.') ."</td>";
            $bloMca += $aApartamento[11]; $sumMca += $aApartamento[11];
        }
        if (isset($frm['garajes'])) {
            $iGar = $oApars->getNumeroGarajes($apa);
            $fApa .= ($iGar) ? "<td class=\"text-right\">$iGar&nbsp;</td>" : "<td>&nbsp;</td>";
            $bloGar += $iGar; $sumGar += $iGar;
        }
        $fApa .= "</tr>";
    }
    // Sumas del ultimo portal, ultima fase y totales.
    $fApa .= ($frm['sumas']) ? f_getListadoApartamentosSumas($frm, "Portal $portal: ", $bloApa, $bloMe2, $bloTer, $bloCof, $bloCob, $bloMca, $bloGar) : "";
    $fApa .= ($frm['sumas']) ? f_getListadoApartamentosSumas($frm, "Total: ", $sumApa, $sumMe2, $sumTer, $sumCof, $sumCob, $sumMca, $sumGar) : "";
    return "<h4><a name=\"inicio\"></a>Listado de $sumApa apartamentos. <span style=\"font-size:0.8em\"><em>Filtros: " . f_getListadoApartamentosFiltros($frm) . "</em>.</span></h4><table class=\"table table-condensed table-ultra\">$fTit$fApa</table>";
}

/**
 * Obtiene las sumas de los apartamentos.
 * 
 * @param array $frm Datos del formulario.
 * @param string $txt Texto para el inicio de la fila.
 * @param int $apa Suma de apartamentos.
 * @param int $me2 Suma de metros cuadrados de los apartamento.
 * @param int $ter Suma de metros cuadrados de las terrazas.
 * @param int $cof Suma de los coeficientes de la fase.
 * @param int $cob Suma de los coeficientes de los bloques.
 * @param int $mca Suma de los metros catastrales.
 * @param int $gar Suma del numero de garajes.
 * @return string Codigo HTML de la fila de sumas.
 */
function f_getListadoApartamentosSumas($frm, $txt, $apa, $me2, $ter, $cof, $cob, $mca, $gar) {
    
    $nCol = 1;
    $nCol += (isset($frm['codigo'])) ? 1 : 0;
    $nCol += (isset($frm['finca'])) ? 1 : 0;
    
    $fTit = "<tr><th colspan=\"$nCol\" class=\"clara\">$txt$apa</th>";
    $fTit .= (isset($frm['tipo'])) ? "<th class=\"clara\">&nbsp;</th>" : "";
    $fTit .= (isset($frm['metros'])) ? "<th class=\"text-right clara\">" . number_format($me2,2,',','.') . "</th>" : "";
    $fTit .= (isset($frm['terraza'])) ? "<th class=\"text-right clara\">" . number_format($ter,2,',','.') . "</th>" : "";
    $fTit .= (isset($frm['fase200'])) ? "<th class=\"text-right clara\">" . number_format($cof,4,',','.') . "</th>" : "";
    
    // La suma de coeficientes y cuotas de portales solo se pone en la suma de portales.
    if (substr($txt, 0, 1) == "P") {
        $fTit .= (isset($frm['bloque'])) ? "<th class=\"text-right clara\">" . number_format($cob,2,',','.') . "</th>" : "";
    } else {
        $fTit .= (isset($frm['bloque'])) ? "<th class=\"clara\">&nbsp;</th>" : "";
    }
    $fTit .= (isset($frm['registro'])) ? "<th class=\"text-center clara\">&nbsp;</th>" : "";
    $fTit .= (isset($frm['refcat'])) ? "<th class=\"text-center clara\">&nbsp;</th>" : "";
    $fTit .= (isset($frm['metcat'])) ? "<th class=\"text-right clara\">" . number_format($mca,2,',','.') . "</th>" : "";
    
    $fTit .= (isset($frm['garajes'])) ? "<th class=\"text-right clara\">$gar&nbsp;</th>" : "";
    return "$fTit</tr>";
}

/**
 * Obtiene un fila con los titulos del listado de apartamentos.
 * 
 * @param array $frm Datos del formulario.
 * @return string Codigo HTML de la fila de titulos.
 */
function f_getListadoApartamentosTitulo($frm) {
    $fila = "<tr>";
    $fila .= (isset($frm['codigo'])) ? "<th>C&oacute;digo</th>" : "";
    $fila .= (isset($frm['finca'])) ? "<th>Finca</th>" : "";
    $fila .= "<th>Apart.</th>";
    $fila .= (isset($frm['tipo'])) ? "<th>Tipo</th>" : "";
    $fila .= (isset($frm['metros'])) ? "<th class=\"text-right\">Metros</th>" : "";
    $fila .= (isset($frm['terraza'])) ? "<th class=\"text-right\">Terraza</th>" : "";
    $fila .= (isset($frm['fase200'])) ? "<th class=\"text-right\">Coef.</th>" : "";
    $fila .= (isset($frm['bloque'])) ? "<th class=\"text-right\">Bloque</th>" : "";
    $fila .= (isset($frm['registro'])) ? "<th class=\"text-center\">Registro</th>" : "";
    $fila .= (isset($frm['refcat'])) ? "<th class=\"text-center\">Ref.&nbsp;catastral</th>" : "";
    $fila .= (isset($frm['metcat'])) ? "<th class=\"text-right\">Metros&nbsp;cat</th>" : "";
    $fila .= (isset($frm['garajes'])) ? "<th class=\"text-right\">Gara.</th>" : "";
    $fila .= "</tr>";
    return $fila;
}

/**
 * Obtiene una cadena con los filtros aplicados al listado de apartamentos.
 * 
 * @param string $frm Datos del formulario.
 * @return string Cadena con los filtros aplicados.
 */
function f_getListadoApartamentosFiltros($frm) {
    $aF = array();
    $filtros = "";
    
    $aF[] = "del portal " . $frm['portal1'] . " al " . $frm['portal2'];
    if ($frm['contipo']) {
        $aF[] = 'apartamento tipo ' . $frm['contipo'];
    }
    if (isset($frm['congaraje'])) {
        $aF[] = 'con garaje';
    }
    if (isset($frm['conterraza'])) {
        $aF[] = 'con terraza';
    }
    if (count($aF)) {
        $filtros = implode(', ', $aF);
    } else {
        $filtros = "ninguno";
    }
    return $filtros;
}

//--- LISTADOS DE PROPIETARIOS ---//

/**
 * Obtiene el listado de propietarios.
 * 
 * @param array $frm Datos del formulario.
 * @return string Codigo HTML del listado.
 */
function f_getListadoPropietarios($frm) {
    
    $int = (isset($frm['intervalo']) && $frm['intervalo'] == 0) ? FALSE : TRUE; // Fechas puntuales : Intervalo completo.
    $din = ($frm['fechaini']) ? $frm['fechaini'] : '24-08-1984';
    $dfi = ($frm['fechafin']) ? $frm['fechafin'] : date('d-m-Y');
    $ver = ($frm['verpor'] == 1) ? TRUE : FALSE;    // Por personas : Por apartamentos.
    $que = ($frm['verque'] == 1) ? TRUE : FALSE;    // Tambien bajas : Solo actuales.
    $dis = ($frm['distintos']) ? TRUE : FALSE;      // Solo distintos : Todos.
    $may = ($frm['mayusculas']) ? TRUE : FALSE;     // Mayusculas : Minusculas.
    $lis = ($int) ? f_getListadoPropietariosCompleto($din, $dfi, $ver, $que, $may) : f_getListadoPropietariosFechas($din, $dfi, $ver, $dis, $may);
    return f_getListadoPropietariosTitulo($int, $din, $dfi, $ver, $que, $dis) . $lis;
}

/**
 * Obtiene el titulo para el listado de propietarios.
 * 
 * @param boolean $int Fechas puntuales (false) o intervalo completo de fechas (true).
 * @param date $din Fecha inicial o primera.
 * @param date $dfi Fecha final o segunda.
 * @param boolean $ver Mostrar por personas (true) o por apartamentos (false).
 * @param boolean $que Mostrar tambien las bajas (true) o solo los propietarios actuales (false).
 * @param boolean $dis Mostrar solo propietarios distintos (true) o todos (false).
 * @return string Codigo HTML del listado.
 */
function f_getListadoPropietariosTitulo($int, $din, $dfi, $ver, $que, $dis) {
    $ti0 = ($int) ? "Listado de propietarios entre el d&iacute;a $din y el $dfi " : "Listado de propietarios el d&iacute;a $din y el d&iacute;a $dfi ";
    $tit = ($que) ? $ti0 : "Listado de propietarios actuales ";
    $tit .= ($ver) ? "ordenado por personas. " : "ordenado por apartamentos. ";
    $tit .= ($dis) ? "Solo los distintos." : "";
    return "<h4><a name=\"inicio\"></a>$tit</h4>";
}

/**
 * Obtiene el listado de propietarios entre dos fechas.
 * 
 * @global \Propietarios $oProps Instancia de Propietarios.
 * @param date $din Fecha inicial o primera.
 * @param date $dfi Fecha final o segunda.
 * @param boolean $ver Mostrar por personas (true) o por apartamentos (false).
 * @param boolean $que Mostrar tambien las bajas (true) o solo los propietarios actuales (false).
 * @param boolean $may Mostrar los nombres en mayusculas (true) o no (false).
 * @return string Codigo HTML del listado.
 */
function f_getListadoPropietariosCompleto($din, $dfi, $ver, $que, $may) {
    global $oProps;
    //array('0 codapa','1 apartamento','2 codpers','3 persona','4 date','5 fecha','6 orden')
    $aPro = ($que) ? $oProps->getPropietariosEntreFechas($din, $dfi, $ver) : $oProps->getPropietariosAlta($ver);
    $html = "<table class=\"table table-hover table-condensed table-ultra\">";
    $dini = "";
    $cuer = "";
    $cini = "";
    foreach ($aPro as $aD) {
        $nomb = ($may) ? $aD[3] : f_primeraMayuscula($aD[3]);
        $dato = ($ver) ? $nomb : $aD[1];   // persona : apartamento.
        $inic = ($ver) ? "<th style=\"width:50%;\">Propietarios - " . substr($aD[3], 0, 1) . "</th><th style=\"width:50%;\">Apartamentos</th>" : "<th style=\"width:10%;\">Portal " . strstr($aD[1], '-', true) . "&nbsp;</th><th style=\"width:90%;\">Propietarios</th>";
        if ($dato != $dini) {
            $html .= ($dini) ? "<tr><td>$dini</td><td>$cuer</td></tr>" : "";
            $cuer  = ($dini) ? "" : $cuer;
            $dini = $dato;
        }
        if ($inic != $cini) {
            $html .= "<tr>$inic</tr>";
            $cini = $inic;
        }
        $nuevo = ($cuer) ? " &mdash; " : ""; 
        $nuevo .= ($ver) ? $aD[1] : $nomb;
        $nuevo .= ($aD[5]) ? " (" . $aD[5] . ")" : "";
        $clase = ($aD[5]) ? "class=\"baja\"" : "";
        $cuer .= "<span $clase>$nuevo</span>";
    }
    return "$html<tr><td>$dato</td><td>$cuer</td></tr></table>";
}

/**
 * Obtiene el listado de propietarios para dos fechas puntuales.
 * 
 * @global \Propietarios $oProps Instancia de Propietarios.
 * @param date $din Fecha inicial o primera.
 * @param date $dfi Fecha final o segunda.
 * @param boolean $ver Mostrar por personas (true) o por apartamentos (false).
 * @param boolean $dis Mostrar solo propietarios distintos (true) o todos (false).
 * @param boolean $may Mostrar los nombres en mayusculas (true) o no (false).
 * @return string Codigo HTML del listado.
 */
function f_getListadoPropietariosFechas($din, $dfi, $ver, $dis, $may) {
    global $oProps;
    $aPr1 = f_getListadoPropietariosAgrupar($oProps->getPropietariosFecha($din, $ver), $ver, $may);
    $aPr2 = f_getListadoPropietariosAgrupar($oProps->getPropietariosFecha($dfi, $ver), $ver, $may);
    $aPro = f_getListadoPropietariosUnir($aPr1, $aPr2, $ver);
    $html = "<table class=\"table table-hover table-condensed table-ultra\">";
    $cini = "";
    foreach ($aPro as $aP) {
        $inic = ($ver) ? "<tr><th style=\"width:34%;\">Propietarios - " . substr($aP[0], 0, 1) . "</th><th style=\"width:33%;\">Apartamentos el $din</th><th style=\"width:33%;\">Apartamentos el $dfi</th></tr>" : "<tr><th style=\"width:10%;\">Portal " . strstr($aP[0], '-', true) . "&nbsp;</th><th style=\"width:45%;\">Propietarios el $din</th><th style=\"width:45%;\">Propietarios el $dfi</th></tr>";
        if ($inic != $cini) {
            $html .= $inic;
            $cini = $inic;
        }
        $html .= ($dis && $aP[1] == $aP[2]) ? "" : "<tr><td>" . $aP[0] . "</td><td>" . $aP[1] . "</td><td>" . $aP[2] . "</td></tr>";
    }
    return "$html</table>";
}

/**
 * Agrupa los nombres de los propietarios.
 * 
 * @param array $aPro Datos de los propietarios.
 * @param boolean $ver Mostrar por personas (true) o por apartamentos (false).
 * @param boolean $may Mostrar los nombres en mayusculas (true) o no (false).
 * @return array con los datos agrupados.
 */
function f_getListadoPropietariosAgrupar($aPro, $ver, $may) {
    //array('0 codapa','1 apartamento','2 codpers','3 persona','4 date','5 fecha','6 orden')
    $aDatos = array();
    $t = "";
    $i = "";
    $k = 0;
    foreach ($aPro as $aD) {
        $key = ($ver) ? $aD[2] : $aD[0];                        // Por Persona : Por Apartamento.
        $nom = ($may) ? $aD[3] : f_primeraMayuscula($aD[3]);    // NOMBRE : Nombre.
        $dat = ($ver) ? $aD[1] : $nom;                          // Nombre : Apartamento.
        
        if ($k != $key) {
            if ($k != 0) {
                $aDatos[$k] = array($i, $t);
                $t = "";
            }
            $k = $key;
        }
        $n  = ($t) ? " &mdash; $dat" : " $dat"; 
        $n .= ($aD[5]) ? " (" . $aD[5] . ")" : "";
        $c  = ($aD[5]) ? "class=\"baja\"" : "";
        $t .= "<span $c>$n</span>";
        $i  = ($ver) ? $nom : $aD[1];                          // Apartamento : Nombre.
    }
    $aDatos[$k] = array($i, $t);    // codigo => array(descrip, propietarios o apartamentos)
    return $aDatos;
}

/**
 * Unde datos de los propietarios.
 * 
 * @param array $aPr1 Datos de propietarios.
 * @param array $aPr2 Datos de propietarios.
 * @param boolean $ver Mostrar por personas (true) o por apartamentos (false).
 * @return array con los datos unidos y ordenados.
 */
function f_getListadoPropietariosUnir($aPr1, $aPr2, $ver) {
    $aNueva = array();
    foreach ($aPr1 as $key => $aPro) {
        $aNueva[$key][0] = $aPro[0];
        $aNueva[$key][1] = $aPro[1];
        $aAux[$key] = ($ver) ? $aPro[0] : $key; // Nombre : Codapar
    }
    foreach ($aPr2 as $key => $aPro) {
        $aNueva[$key][0] = $aPro[0];
        $aNueva[$key][2] = $aPro[1];
        $aAux[$key] = ($ver) ? $aPro[0] : $key; // Nombre : Codapar
    }
    array_multisort($aAux, SORT_ASC, $aNueva);
    return $aNueva;
}

//--- LISTADO DE JUNTAS ---//

/**
 * Obtiene el listado de los asistentes a una Junta.
 * 
 * @param array $frm Datos del formulario.
 * @return string Codigo HTML del listado.
 */
function f_getListadoJunta($frm) {
    // Datos del formulario.
    $fecha = $frm['fecha'];                             // Fecha de la Junta.
    $orden = $frm['orden'];                             // 0 - Apartamentos. 1 - Propietarios. 2 - Representantes.
    $bF200 = (isset($frm['fase200'])) ? TRUE : FALSE;   // Mostrar coeficiente fase 200%.
    $bBloq = (isset($frm['bloque'])) ? TRUE : FALSE;    // Mostrar coeficiente bloque.
    $bSuma = (isset($frm['sumas'])) ? TRUE : FALSE;     // Mostrar sumas (solo cuando ordena por apartamentos).
    $bVoto = (isset($frm['votos'])) ? TRUE : FALSE;     // Mostrar si tiene voto o no.
    $bMayu = (isset($frm['mayus'])) ? TRUE : FALSE;     // Mostrar los nombres en mayusculas.
    
    $tap = 0; $tre = 0; $tpr = 0; $tcf = 0; $tcb = 0; $tvo = 0; $tvn = 0;
    $pap = 0; $pre = 0; $ppr = 0; $pcf = 0; $pcb = 0; $pvo = 0; $pvn = 0;
    
    // Obtiene los datos de los asistentes a la Junta.
    $oAsis = new Asistentes($fecha, $orden);
    $feISO = Funciones::gConvertirFecha($fecha, FALSE);
    $html = "<table class=\"table table-condensed table-ultra\">" . f_getListadoJuntaTitulo($feISO, $orden, $bF200, $bBloq, $bVoto);
    $portal = "";
    $fase = "";
    foreach ($oAsis->getAsistentes() as $aAsis) {
        // array('codapar'=>array('0 apartamento','1 codpers','2 nombre','3 repre','4 voto','5 coefi','6 bloque','7 propietario','8 representante')...)
        
        $nom = ($bMayu) ? $aAsis[2] : f_primeraMayuscula($aAsis[2]);
        $pro = ($bMayu) ? $aAsis[7] : f_primeraMayuscula($aAsis[7]);
        $por = strstr($aAsis[0], '-', TRUE);
        
        if ($por != $portal) {
            // Pone las sumas del portal.
            $html .= ($bSuma) ? f_getListadoJuntaSumas("Portal", $portal, $bF200, $bBloq, $bVoto, $pap, $pre, $ppr, $pcf, $pcb, $pvo, $pvn) : "";
            $pap = 0; $pre = 0; $ppr = 0; $pcf = 0; $pcb = 0; $pvo = 0; $pvn = 0;
            $portal = $por;
        }
        
        // Apartamento.
        $html .= "<tr><td>" . $aAsis[0] . "</td>";
        $tap++; $pap++;
        
        // Propietario y representante.
        if ($aAsis[3] == 'S') {
            // Representado.
            $html .= "<td class=\"baja\">$pro</td><td>$nom</td>";
            $tre++; $pre++;
        } else {
            // Propietario.
            $html .= "<td>$nom</td><td>&nbsp;</td>";
            $tpr++; $ppr++;
        }
        
        $clasvot = ($aAsis[4] == 'S') ? "" : "baja";
        
        // Coeficiente fase.
        if ($bF200) {
            $html .= "<td class=\"text-right $clasvot\">" . number_format($aAsis[5],4,',','.') . "</td>";
            $tcf += $aAsis[5]; $fcf += $aAsis[5]; $pcf += $aAsis[5];
        }
        
        // Coeficiente bloque.
        if ($bBloq) {
            $html .= "<td class=\"text-right $clasvot\">" . number_format($aAsis[6],2,',','.') . "</td>";
            $tcb += $aAsis[6]; $pcb += $aAsis[6];
        }
        
        if ($bVoto) {
            // Con voto.
            if ($aAsis[4] == 'S') {
                // Con voto.
                $html .= "<td class=\"text-center\">S&iacute;</td></tr>";
                $tvo++; $pvo++;
            } else {
                // Sin voto.
                $html .= "<td class=\"text-center\">No</td></tr>";
                $tvn++; $pvn++;
            }
        } else {
            $html .= "</tr>";
        }
    }
    // Sumas finales.
    $html .= ($bSuma) ? f_getListadoJuntaSumas("Portal", $portal, $bF200, $bBloq, $bVoto, $pap, $pre, $ppr, $pcf, $pcb, $pvo, $pvn) : "";
    $html .= f_getListadoJuntaSumas("", "Total", $bF200, $bBloq, $bVoto, $tap, $tre, $tpr, $tcf, $tcb, $tvo, $tvn);
    return "$html</table>";
}

/**
 * Obtiene el titulo del listado de los asistentes a una Junta.
 * 
 * @param date $fec Fecha en formato DD-MM-YYYY.
 * @param int $ord Orden de los datos: 0, 1 o 2.
 * @param boolean $bur Ver coeficiente urbanizacion.
 * @param boolean $bf2 Ver coeficiente fase 200%.
 * @param boolean $bf1 Ver coeficiente fase 100%.
 * @param boolean $bbl Ver coeficiente bloque.
 * @param boolean $bvo Ver si puede votar o no.
 * @return string Codigo HTML del titulo.
 */
function f_getListadoJuntaTitulo($fec, $ord, $bf2, $bbl, $bvo) {
    
    $tit = "<h4>Listado de asistentes a la Junta del $fec ordenados por ";
    switch ($ord) {
        case 1 : $tit .= "nombre de los propietarios."; break;
        case 2 : $tit .= "nombre de los representantes."; break;
        default: $tit .= "apartamentos.</h4>"; break;
    }
    
    $c = 8; $p1 = 10; $p2 = 25; $p3 = 25;
    if(!$bf2) {
        $p2 += 5;
        $p3 += 5;
        $c--;
    }
    if(!$bbl) {
        $p1 += 1;
        $p2 += 2;
        $p3 += 2;
        $c--;
    }
    if(!$bvo) {
        $p1 += 1;
        $p2 += 2;
        $p3 += 2;
        $c--;
    }
    if($c > 5) {
        $t1 = "Apart.";
        $t4 = "Coef.";
        $t5 = "Blo.";
    } else {
        $t1 = "Apartamento";
        $t4 = "Coeficientes";
        $t5 = "Bloque";
    }
    
    $tit .= "<tr><th style=\"width: $p1%\">$t1</th><th style=\"width: $p2%\">Propietario</th><th style=\"width: $p3%\">Representante</th>";
    $tit .= ($bf2) ? "<th style=\"width: 10%\" class=\"text-right\">$t4</th>" : "";
    $tit .= ($bbl) ? "<th style=\"width: 5%\" class=\"text-right\">$t5</th>" : "";
    $tit .= ($bvo) ? "<th style=\"width: 5%\" class=\"text-center\">Voto</th>" : "";
    $tit .= "</tr>";
    return $tit;
}

/**
 * Obtiene las sumas de los asistentes a la Junta.
 * 
 * @param string $tipo Tipo de suma.
 * @param string $nom Numero de bloque, fase o total.
 * @param boolean $bf2 Ver coeficiente fase.
 * @param boolean $bbl Ver coeficiente bloque.
 * @param boolean $bvo Ver si puede votar o no.
 * @param int $ap Suma de apartamentos.
 * @param int $re Suma de representados.
 * @param int $pr Suma de propietarios.
 * @param int $cf Suma de coeficiente de fase.
 * @param int $cb Suma de coeficiente de bloque.
 * @param int $vo Suma de votos si.
 * @param int $vn Suma de votos no.
 * @return string Codigo HTML de las sumas.
 */
function f_getListadoJuntaSumas($tipo, $nom, $bf2, $bbl, $bvo, $ap, $re, $pr, $cf, $cb, $vo, $vn) {
    $tit = "";
    if ($nom) {
        $tit .= "<tr><th class=\"clara\">$tipo $nom:&nbsp;$ap</th><th class=\"clara\">$pr</th><th class=\"clara\">$re</th>";
        $tit .= ($bf2) ? "<th class=\"clara text-right\">" . number_format($cf, 4, ',', '.') . "</th>" : "";
        $tit .= ($bbl) ? "<th class=\"clara text-right\">" . number_format($cb, 2, ',', '.') . "</th>" : "";
        $tit .= ($bvo) ? "<th class=\"clara text-center\">$vo&nbsp;|&nbsp;$vn</th></tr>" : "</tr>";
    }
    return $tit;
}

//--- LISTADO DE DEUDAS ---//

/**
 * Crea el listado de deudas segun las opciones seleccionadas.
 * 
 * @global \Propietarios $oProps Instancia de Propietarios.
 * @param array $frm Datos del formulario.
 * @return string Codigo HTML del formulario.
 */
function f_getListadoDeudas($frm) {
    global $oProps;
    
    // Datos del formulario.
    $fecha = $frm['fecha'];                         // Fecha de la deuda.
    $orden = $frm['orden'];                         // 0 - Fechas. 1 - Apartamentos. 2 - Deudas.
    $ordin = (isset($frm['ordin'])) ? TRUE : FALSE; // Mostrar la deuda ordinaria.
    $extra = (isset($frm['extra'])) ? TRUE : FALSE; // Mostrar la deuda extraordinaria.
    $total = (isset($frm['total'])) ? TRUE : FALSE; // Mostrar el total de la deuda.
    $bMayu = (isset($frm['mayus'])) ? TRUE : FALSE; // Mostrar los nombres en mayusculas.
    $bSuma = (isset($frm['sumas'])) ? TRUE : FALSE; // Mostrar sumas (solo cuando ordena por fechas).
    
    // Columnas.
    $c  = 9;
    $c -= ($ordin) ? 0 : 1;
    $c -= ($extra) ? 0 : 1;
    $c -= ($total) ? 0 : 1;
    
    $tap = 0; $tor = 0; $tex = 0; $tsu = 0;
    //$fap = 0; $for = 0; $fex = 0; $fsu = 0;
    $pap = 0; $por = 0; $pex = 0; $psu = 0;
    
    // Obtiene los datos de las deudas.
    $oDeu = new Deudas();
    $oDeu->setOrden($orden);
    $oDeu->setFiltro($fecha);
    $sSumDeu = $oDeu->getSumasFechas();
    $html = "<a name=\"inicio\"></a><table class=\"table table-condensed table-ultra\">";
    $portal = "";
    //$fase = "";
    $dato = "";
    foreach ($oDeu->getFiltradas() as $aDeuda) {
        // array('0 fecha','1 apartamento','2 portal','3 piso','4 letra','5 ordinaria','6 extraordinaria','7 suma','8 fechaiso')
        
        // Fecha.
        $fec = $aDeuda[0];
        $iso = $aDeuda[8];
        // Apartamento.
        $cod = $aDeuda[1];
        $apa = $aDeuda[2] . "-" . $aDeuda[3] . $aDeuda[4];
        //$fas = $aDeuda[5];
        // Nombre del propietario.
        $nom = $oProps->getNombreSimplePropietarioApartamentoFecha($cod, $fec);
        $pro = ($bMayu) ? $nom : f_primeraMayuscula($nom);
        // Deudas.
        $ord = $aDeuda[5];
        $ext = $aDeuda[6];
        $tot = $aDeuda[7];
         
        if(!$dato) {
            $aSum = $sSumDeu[$fec];
        }

        switch ($orden) {
            case 1 :    // Ordenado por apartamentos -------------------------//
                if ($dato != $cod) {
                    $html .= f_getListadoDeudasTituloApartamentos($apa, $ordin, $extra, $total);
                    $dato = $cod;
                }
                $aSum = $sSumDeu[$fec];
                $html .= "<tr><td>$iso</td><td>$pro</td>";
                break;
            case 2 :    // Ordenado por fechas y suma de deudas --------------//
                if ($dato != $fec) {
                    $html .= f_getListadoDeudasSumaPortal("t", $dato, $ordin, $extra, $total, $bSuma, $tap, $tor, $tex, $tsu, $aSum);
                    $tap = 0; $tor = 0; $tex = 0; $tsu = 0;
                    $html .= "<tr><th colspan=\"$c\" class=\"text-center\">Deudas del $iso</th></tr>";
                    $html .= f_getListadoDeudasTituloDeudas($ordin, $extra, $total);
                    $dato = $fec;
                    $aSum = $sSumDeu[$fec];
                }
                $html .= "<tr><td>$apa</td><td>$pro</td>";
                break;
            default:    // Ordenado por fechas -------------------------------//
                if ($dato != $fec) {
                    // Cambia de fecha.
                    $html .= f_getListadoDeudasSumaPortal("p", $portal, $ordin, $extra, $total, $bSuma, $pap, $por, $pex, $psu, $aSum);
                    //$html .= f_getListadoDeudasSumaPortal("f", $fase, $ordin, $extra, $total, $bSuma, $fap, $for, $fex, $fsu, $aSum);
                    $html .= f_getListadoDeudasSumaPortal("t", $dato, $ordin, $extra, $total, $bSuma, $tap, $tor, $tex, $tsu, $aSum);
                    $html .= "<tr><th colspan=\"$c\" class=\"text-center\">Deudas del $iso</th></tr>";
                    $tap = 0; $tor = 0; $tex = 0; $tsu = 0;
                    $dato = $fec; $portal = ""; //$fase = "";
                    //$aSum = $oDeu->getSumas($fec);
                    $aSum = $sSumDeu[$fec];
                }
                if ($portal != $aDeuda[2]) {
                    // Cambia de portal.
                    $html .= f_getListadoDeudasSumaPortal("p", $portal, $ordin, $extra, $total, $bSuma, $pap, $por, $pex, $psu, $aSum);
                    /* if ($fase != $fas) {
                        $html .= f_getListadoDeudasSumaPortal("f", $fase, $ordin, $extra, $total, $bSuma, $fap, $for, $fex, $fsu, $aSum);
                        $fap = 0; $for = 0; $fex = 0; $fsu = 0;
                        $fase = $fas;
                    } */
                    $html .= f_getListadoDeudasTituloPortal($aDeuda[2], $ordin, $extra, $total);
                    $pap = 0; $por = 0; $pex = 0; $psu = 0;
                    $portal = $aDeuda[2];
                }
                $html .= "<tr><td class=\"text-left\">$apa</td><td>$pro</td>";
                break;
        }
        $p1 = ($aSum[0]) ? $ord * 100 / $aSum[0] : 0;
        $p2 = ($aSum[1]) ? $ext * 100 / $aSum[1] : 0;
        $p3 = ($aSum[2]) ? $tot * 100 / $aSum[2] : 0;
        $html .= ($ordin) ? "<td class=\"text-right\">" . number_format($ord, 2, ",", ".") . " €</td><td class=\"text-right\">" . number_format($p1, 3, ",", ".") . "%</td>" : "";
        $html .= ($extra) ? "<td class=\"text-right\">" . number_format($ext, 2, ",", ".") . " €</td><td class=\"text-right\">" . number_format($p2, 3, ",", ".") . "%</td>" : "";
        $html .= ($total) ? "<td class=\"text-right\">" . number_format($tot, 2, ",", ".") . " €</td><td class=\"text-right\">" . number_format($p3, 3, ",", ".") . "%</td>" : "";

        $html .= "</tr>";
        
        $pap++; $por += $ord; $pex += $ext; $psu += $tot;
        //$fap++; $for += $ord; $fex += $ext; $fsu += $tot;
        $tap++; $tor += $ord; $tex += $ext; $tsu += $tot;
    }
    // Sumas finales.
    $html .= f_getListadoDeudasSumaPortal("p", $portal, $ordin, $extra, $total, $bSuma, $pap, $por, $pex, $psu, $aSum);
    //$html .= f_getListadoDeudasSumaPortal("f", $fase, $ordin, $extra, $total, $bSuma, $fap, $for, $fex, $fsu, $aSum);
    $html .= f_getListadoDeudasSumaPortal("t", "total", $ordin, $extra, $total, $bSuma, $tap, $tor, $tex, $tsu, $aSum);
    return "$html</table>";
}

/**
 * Obtiene los titulos de los deudores por apartamentos.
 * 
 * @param string $apart Apartamento.
 * @param int $ordin Deuda ordinaria.
 * @param int $extra Deuda extraordinaria.
 * @param int $total Deuda total.
 * @return string Codigo HTML del titulo.
 */
function f_getListadoDeudasTituloApartamentos($apart, $ordin, $extra, $total) {
    $p = f_getCalcularAnchos($ordin, $extra, $total);
    $html .= "<tr><td class=\"tit text-left\" width=\"" . $p[0] . "%\">Portal $apart</td><td class=\"tit\" width=\"" . $p[1] . "%\">Propietario</td>";
    $html .= ($ordin) ? "<td width=\"" . $p[2] . "%\" class=\"tit text-right\">Ordinaria</td><td width=\"" . $p[3] . "%\" class=\"tit text-right\">%</td>" : "";
    $html .= ($extra) ? "<td width=\"" . $p[2] . "%\" class=\"tit text-right\">Extraordin.</td><td width=\"" . $p[3] . "%\" class=\"tit text-right\">%</td>" : "";
    $html .= ($total) ? "<td width=\"" . $p[2] . "%\" class=\"tit text-right\">Total deuda</td><td width=\"" . $p[3] . "%\" class=\"tit text-right\">%</td>" : "";
    $html .= "</tr>";
    return $html;
}

/**
 * Obtiene los titulos de los deudores por fechas.
 * 
 * @param int $portal Numero del portal.
 * @param int $ordin Deuda ordinaria.
 * @param int $extra Deuda extraordinaria.
 * @param int $total Deuda total.
 * @return string Codigo HTML del titulo.
 */
function f_getListadoDeudasTituloPortal($portal, $ordin, $extra, $total) {
    if ($portal) {
        $p = f_getCalcularAnchos($ordin, $extra, $total);
        $html .= "<tr><td width=\"" . $p[0] . "%\" class=\"tit text-left\">Portal $portal</td><td width=\"" . $p[1] . "%\" class=\"tit\">Propietario</td>";
        $html .= ($ordin) ? "<td width=\"" . $p[2] . "%\" class=\"tit text-right\">Ordinaria</td><td width=\"" . $p[3] . "%\" class=\"tit text-right\">%</td>" : "";
        $html .= ($extra) ? "<td width=\"" . $p[2] . "%\" class=\"tit text-right\">Extraordin.</td><td width=\"" . $p[3] . "%\" class=\"tit text-right\">%</td>" : "";
        $html .= ($total) ? "<td width=\"" . $p[2] . "%\" class=\"tit text-right\">Total deuda</td><td width=\"" . $p[3] . "%\" class=\"tit text-right\">%</td>" : "";
        $html .= "</tr>";
    }
    return $html;
}

/**
 * Obtiene los titulos de los deudores por cantidad de deuda.
 * 
 * @param int $ordin Deuda ordinaria.
 * @param int $extra Deuda extraordinaria.
 * @param int $total Deuda total.
 * @return string Codigo HTML del titulo.
 */
function f_getListadoDeudasTituloDeudas($ordin, $extra, $total) {
    $p = f_getCalcularAnchos($ordin, $extra, $total);
    $html .= "<tr><td width=\"" . $p[0] . "%\" class=\"tit text-left\">Apartamento</td><td width=\"" . $p[1] . "%\" class=\"tit\">Propietario</td>";
    $html .= ($ordin) ? "<td width=\"" . $p[2] . "%\" class=\"tit text-right\">Ordinaria</td><td width=\"" . $p[3] . "%\" class=\"tit text-right\">%</td>" : "";
    $html .= ($extra) ? "<td width=\"" . $p[2] . "%\" class=\"tit text-right\">Extraordin.</td><td width=\"" . $p[3] . "%\" class=\"tit text-right\">%</td>" : "";
    $html .= ($total) ? "<td width=\"" . $p[2] . "%\" class=\"tit text-right\">Total deuda</td><td width=\"" . $p[3] . "%\" class=\"tit text-right\">%</td>" : "";
    $html .= "</tr>";
    return $html;
}

/**
 * Calcula los anchos de las columnas del listado de deudas.
 * 
 * @param int $ordin Deuda ordinaria.
 * @param int $extra Deuda extraordinaria.
 * @param int $total Deuda total.
 * @return array con los anchos del tipo array(0 portal, 1 propietario, 2 valor deuda, 3 porcentaje deuda)
 */
function f_getCalcularAnchos($ordin, $extra, $total) {
    $po = 14; $pr = 29; $d1 = 10; $d2 = 8;
    $pr += (!$ordin) ? 19 : 0;
    $pr += (!$extra) ? 19 : 0;
    $pr += (!$total) ? 19 : 0;
    return array($po, $pr, $d1, $d2);
}

/**
 * Obtiene las sumas de las deudas por portal, fase o total.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param string $tipo Tipo de suma p o t.
 * @param string $portal Numero de portal.
 * @param int $ordin Deuda ordinaria.
 * @param int $extra Deuda extraordinaria.
 * @param int $total Deuda total.
 * @param boolean $bSuma Indica si se muestran las sumas o no.
 * @param int $pap Numero de apartamentos.
 * @param int $por Total de deuda ordinaria.
 * @param int $pex Total de deuda extraordinaria.
 * @param int $psu Total de suma de deudas.
 * @param array $aSum Suma ordinaria, extraordinaria y total.
 * @return string Codigo HTML de las sumas.
 */
function f_getListadoDeudasSumaPortal($tipo, $portal, $ordin, $extra, $total, $bSuma, $pap, $por, $pex, $psu, $aSum=null) {
    global $oApars;
    if ($bSuma && $portal) {
        switch ($tipo) {
            case "p": $num = $oApars->getNumApartamentosPortal($portal); $t = "portal"; break;          // Portal.
            default : $num = $oApars->getNumApartamentosPortal(); $t = "total"; $portal = ""; break;    // Total.
        }
        $prc = ($num) ? $pap * 100 / $num : 0;
        $pro = ($aSum[0]) ? $por * 100 / $aSum[0] : 0;
        $pre = ($aSum[1]) ? $pex * 100 / $aSum[1] : 0;
        $prt = ($aSum[2]) ? $psu * 100 / $aSum[2] : 0;
        $html .= "<tr><td class=\"text-left\">Suma $t $portal:&nbsp;<span class=\"negrita\">$num</span></td><td class=\"negrita text-left\">$pap deudores (" . number_format($prc, 2, ",", ".") . " %)</td>";
        $html .= ($ordin) ? "<td class=\"negrita text-right\">" . number_format($por, 2, ",", ".") . " €</td><td class=\"negrita text-right\">" . number_format($pro, 3, ",", ".") . " %</td>" : "";
        $html .= ($extra) ? "<td class=\"negrita text-right\">" . number_format($pex, 2, ",", ".") . " €</td><td class=\"negrita text-right\">" . number_format($pre, 3, ",", ".") . " %</td>" : "";
        $html .= ($total) ? "<td class=\"negrita text-right\">" . number_format($psu, 2, ",", ".") . " €</td><td class=\"negrita text-right\">" . number_format($prt, 3, ",", ".") . " %</td>" : "";
        $html .= "</tr>";
    }
    return $html;
}

//--- CALCULO DE CUOTA MENSUAL ---//

/**
 * Calcula la cuota mensual para pagar una cantidad determinada en los meses indicados.
 * 
 * @global \Apartamentos $oApars Instancia de Apartamentos.
 * @param array $frm Datos del formulario.
 * @return string Codigo HTML del resultado de los calculos.
 */
function f_getCalculos($frm) {
    global $oApars;
    $can = $frm['cantidad'];
    $mes = $frm['meses'];
    $meses = ($mes == 1) ? "$mes mes" : "$mes meses";
    
    // Inicializa las sumas.
    $bloApa = 0; $bloMe2 = 0; $bloCof = 0; $bloEuf = 0; $bloCob = 0; $bloEub = 0; $bloCog = 0; $bloEug = 0;
    $sumApa = 0; $sumMe2 = 0; $sumCof = 0; $sumEuf = 0; $sumCob = 0; $sumEub = 0; $sumCog = 0; $sumEug = 0;
    
    $portal = "";
    $fApa = "";
    $aAp = $oApars->getApartamentos();
    foreach ($aAp as $apa => $aApar) {
        //array('0 portal','1 piso','2 letra','3 tipo','4 metros','5 terraza','6 coeficiente','7 coef.blo','8 finca','9 numreg','10 refcat','11 metcat','12 numgar')
        
        // Mira si hay cambio de portal.
        if($portal != $aApar[0] && $frm['sumas']) {
            // Pone las sumas.
            $fSumb = f_getCalculosSumas($frm, "Portal $portal: ", $bloApa, $bloMe2, $bloCof, $bloEuf, $bloCob, $bloEub, $bloCog, $bloEug);
            $bloApa = 0; $bloMe2 = 0; $bloCof = 0; $bloEuf = 0; $bloCob = 0; $bloEub = 0; $bloCog = 0; $bloEug = 0;
            $fApa .= ($portal) ? $fSumb : "";
            $portal = $aApar[0];
            $fApa .= f_getCalculosTitulo($aApar[0], $frm);
        }
        
        // Inicia la fila del apartamento.
        $fApa .= "<tr>";
        
        // Codigo del apartamento.
        if (isset($frm['codigo'])) {
            $fApa .= "<td>$apa</td>";
        }
        
        // Nombre del apartamento. Se muestra siempre.
        $fApa .= "<td>" . $aApar[0] . "-" .$aApar[1] . $aApar[2] . "</td>";
        $bloApa++; $sumApa++;
        
        // Metros cuadrados.
        if(isset($frm['metros'])) {
            $fApa .= "<td class=\"text-right\">" . number_format($aApar[4],2,',','.') . " m<sup>2</sup></td>";
            $bloMe2 += $aApar[4]; $sumMe2 += $aApar[4];
        }
        
        // Coeficiente fase
        if(isset($frm['coef200'])) {
            $fApa .= "<td class=\"text-right\">" . number_format($aApar[6],4,',','.') . " %</td>";
            $bloCof += $aApar[6]; $sumCof += $aApar[6];
        }
        
        // Cuota fase.
        if(isset($frm['coef200']) || isset($frm['coef100'])) {
            $cuotaf = ($aApar[6] * $can) / ($mes * 100);
            $fApa .= "<td class=\"text-right successcolor\">" . number_format($cuotaf,2,',','.') . " €</td>";
            $bloEuf += $cuotaf; $sumEuf += $cuotaf; 
        }
        
        // Coeficiente escalera.
        if(isset($frm['coeblo'])) {
            $cuotab = ($aApar[7] * $can) / ($mes * 100);
            $fApa .= "<td class=\"text-right\">" . number_format($aApar[7],2,',','.') . " %</td><td class=\"text-right successcolor\">" . number_format($cuotab,2,',','.') . " €</td>";
            $bloCob += $aApar[7]; $bloEub += $cuotab; $sumCob += $aApar[7]; $sumEub += $cuotab;
        }
        
        // Coeficiente garajes 100%
        if(isset($frm['coegar'])) {
            $oApar = new Apartamento($apa);
            $coega = $oApar->getGarajesCoeficiente();
            $cuotag = ($coega) ? ($coega * $can) / ($mes * 100) : 0;
            $fApa .= ($cuotag) ? "<td class=\"text-right\">" . number_format($coega,4,',','.') . " %</td><td class=\"text-right successcolor\">" . number_format($cuotag,2,',','.') . " €</td>" : "<td>&nbsp;</td><td>&nbsp;</td>";
            $bloCog += $coega; $bloEug += $cuotag; $sumCog += $coega; $sumEug += $cuotag;
        }
        
        // Cierra las filas.
        $fApa .= "</tr>";
    }
    // Sumas del ultimo portal, ultima fase y totales.
    $fApa .= ($frm['sumas']) ? f_getCalculosSumas($frm, "Portal $portal: ", $bloApa, $bloMe2, $bloCof, $bloEuf, $bloCob, $bloEub, $bloCog, $bloEug) : "";
    $fApa .= ($frm['sumas']) ? f_getCalculosSumas($frm, "Total: ", $sumApa, $sumMe2, $sumCof, $sumEuf, $sumCob, $sumEub, $sumCog, $sumEug) : "";
    return "<h4><a name=\"inicio\"></a>Cuotas mensuales para pagar la cantidad de " . number_format($can,2,',','.') . " € en un plazo de $meses.</h4><table class=\"table table-condensed table-ultra\">$fApa</table>";
}

/**
 * Obtiene los titulos del portal.
 * 
 * @param int $por Numero de portal.
 * @param array $frm Datos del formulario.
 * @return string Codigo HTML del titulo.
 */
function f_getCalculosTitulo($por, $frm) {
    $fTit = "<tr>";
    $fTit .= (isset($frm['codigo'])) ? "<th style=\"width: 16%\" colspan=\"2\">Portal $por</th>" : "<th style=\"width: 16%\">Portal $por</th>";
    $fTit .= (isset($frm['metros'])) ? "<th style=\"width: 12%\" class=\"text-right\">Metros</th>" : "";
    $fTit .= (isset($frm['coef200'])) ? "<th style=\"width: 12%\" class=\"text-right\">Coeficiente</th><th style=\"width: 12%\" class=\"text-right\">Cuota</th>" : "";
    $fTit .= (isset($frm['coeblo'])) ? "<th style=\"width: 12%\" class=\"text-right\">Bloque</th><th style=\"width: 12%\" class=\"text-right\">Cuota</th>" : "";
    $fTit .= (isset($frm['coegar'])) ? "<th style=\"width: 12%\" class=\"text-right\">Garaje</th><th style=\"width: 12%\" class=\"text-right\">Cuota</th>" : "";
    return "$fTit</tr>";    
}

/**
 * Realiza las sumas de los calculos seleccionados.
 * 
 * @param array $frm Datos del formulario.
 * @param string $txt Texto de las sumas.
 * @param int $apa Numero de apartamentos.
 * @param int $me2 Metros cuadrados.
 * @param int $cof Coeficiente fase.
 * @param int $euf Cuota fase.
 * @param int $cob Coeficiente bloque.
 * @param int $eub Cuota bloque.
 * @param int $cog Coeficiente garajes.
 * @param int $eug Cuota garajes.
 * @return string Codigo HTML de las sumas.
 */
function f_getCalculosSumas($frm, $txt, $apa, $me2, $cof, $euf, $cob, $eub, $cog, $eug) {
    $fTit = "<tr>";
    $fTit .= (isset($frm['codigo'])) ? "<td colspan=\"2\" class=\"tit text-left\">$apa</td>" : "<td class=\"tit text-left\">$apa</td>";
    $fTit .= (isset($frm['metros'])) ? "<td class=\"tit text-right\">" . number_format($me2,2,',','.') . " m<sup>2</sup></td>" : "";
    $fTit .= (isset($frm['coef200'])) ? "<td class=\"tit text-right\">" . number_format($cof,4,',','.') . " %</td><td class=\"tit text-right successcolor\">" . number_format($euf,2,',','.') . " €</td>" : "";

    // La suma de coeficientes y cuotas de portales solo se pone en la suma de portales.
    if (substr($txt, 0, 1) == "P") {
        $fTit .= (isset($frm['coeblo'])) ? "<td class=\"tit text-right\">" . number_format($cob,2,',','.') . " %</td><td class=\"tit text-right successcolor\">" . number_format($eub,2,',','.') . " €</td>" : "";
    } else {
        $fTit .= (isset($frm['coeblo'])) ? "<td class=\"tit\">&nbsp;</td><td class=\"tit\">&nbsp;</td>" : "";
    }
    $fTit .= (isset($frm['coegar'])) ? "<td class=\"tit text-right\">" . number_format($cog,4,',','.') . " %</td><td class=\"tit text-right successcolor\">" . number_format($eug,2,',','.') . " €</td>" : "";
    return "$fTit</tr>";
}


function f_getCantidadTotal($frm) {
    global $oApars;
    $cuo = $frm['cuota'];
    $por = $frm['portal'];
    $pis = $frm['piso'];
    $let = $frm['letra'];
    $cod = $oApars->existe($por, $pis, $let);
    
    // Comprueba si el apartamento existe.
    if(!$cod) {
        // Mensaje de que no existe el apartamento.
        $resul = "El apartamento seleccionado no existe.";
        
    } else {
        // Obtiene los datos del apartamento: array('portal','piso','letra','tipo','metros','terraza','coeficiente','coef.blo','finca','numreg','catastral','metros cat','numgar')
        $aApar = $oApars->getApartamento($cod);
        $met = $aApar[4];
        $mte = $aApar[5];
        $coe = $aApar[6];
        $cob = $aApar[7];
        
        // Calcula los totales.
        $tog = $cuo * 100 / $coe;  // Total general.
        $tob = $cuo * 100 / $cob;  // Total bloque.
        
        // Numeros con formato.
        $fmet = number_format($met, 0, ",", ".");
        $fmte = number_format($mte, 0, ",", ".");
        $fcoe = number_format($coe, 4, ",", ".");
        $fcob = number_format($cob, 2, ",", ".");
        $ftog = number_format($tog, 2, ",", ".");
        $ftob = number_format($tob, 2, ",", ".");
        
        $resul = "<h4><a name=\"inicio\">Apartamento $por-$pis$let</a></h4><br />
                 <table class=\"table\" style=\"width:60%;margin:auto\"> 
                 <tr><th class=\"text-right\" style=\"width:30%\">Metros cuadrados</th><td class=\"text-right\" style=\"width:20%\">$fmet m<sup>2</sup></td><th class=\"text-right\" style=\"width:30%\">Metros terraza</th><td class=\"text-right\" style=\"width:20%\">$fmte m<sup>2</sup></td></tr>
                 <tr><th class=\"text-right\">Coeficiente general</th><td class=\"text-right\">$fcoe %</td><th class=\"text-right\">Total general</th><td class=\"text-right\"><span class=\"alert alert-success\">$ftog €</span></td></tr>
                 <tr><th class=\"text-right\">Coeficiente escalera</th><td class=\"text-right\">$fcob %</td><th class=\"text-right\">Total escalera</th><td class=\"text-right\"><span class=\"alert alert-info\">$ftob €</span></td></tr></table>";
                 
    }
    return $resul;
}