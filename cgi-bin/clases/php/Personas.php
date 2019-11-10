<?php

/**
 * Clase Peronas.
 */

/**
 * La clase Personas guarda el contenido de todas las personas.
 * Permite obtener datos y realizar busquedas en las personas.
 *
 * @author xavi
 */
class Personas {
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Contiene los datos de todas las personas guardadas en la base de datos.
     * El array tiene como clave el <b>codigo de la persona</b> y como datos:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Sexo.</li>
     * <li>3 - Codigo de usuario.</li>
     * <li>4 - Correo electronico.</li>
     * <li>5 - Enviar correos.</li>
     * <li>6 - Telefono.</li>
     * <li>7 - Notas</li>
     * </ul>
     * 
     * @var array del tipo array('cod'=>array(0 apellidos,1 nombre,2 sexo,3 codusu,4 correo,5 envios,6 telefono,7 notas)...)
     */
    private $aDatosPersonas;
    
    /**
     * Contiene el orden que se usara para ordenar las personas.
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Codigo.</li>
     * </ul>
     * 
     * @var int Tipo de orden. 
     */
    private $orden;
    
    /**
     * Filtrar por sexo hombre.
     * 
     * @var string S/N. 
     */
    private $filtroHombre;
    
    /**
     * Filtrar por sexo mujer.
     * 
     * @var string S/N.  
     */
    private $filtroMujer;
    
    /**
     * Filtrar por sexo otro.
     * 
     * @var string S/N.  
     */
    private $filtroOtro;
    
    /**
     * Fitrar por los que tienen correo.
     * 
     * @var string S/N.  
     */
    private $filtroCorreo;
    
    /**
     * Filtrar por los que tienen activos los envios.
     * 
     * @var string S/N.  
     */
    private $filtroEnvios;
    
    /**
     * Filtrar por lo que tienen telefonos.
     * 
     * @var string S/N.  
     */
    private $filtroTelefono;
    
    /**
     * Filtrar por los que tienen notas.
     * 
     * @var string S/N.  
     */
    private $filtroNotas;
    
    /**
     * Filtar por los que son propietarios.
     * 
     * @var string S/N.  
     */
    private $filtroPropietario;
    
    /**
     * Contiene los datos de las personas que han sido filtradas.
     * El array tiene como clave el codigo de la persona y como datos:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Sexo.</li>
     * <li>3 - Codigo de usuario.</li>
     * <li>4 - Correo electronico.</li>
     * <li>5 - Enviar correos.</li>
     * <li>6 - Telefono.</li>
     * <li>7 - Notas</li>
     * </ul>
     * 
     * @var array del tipo array('cod'=>array(0 apellidos,1 nombre,2 sexo,3 codusu,4 correo,5 envios,6 telefono,7 notas)...)
     */
    private $aFiltradas;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase Personas.
     * El orden de las personas puede ser:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Codigo.</li>
     * </ul>
     * 
     * @param int $ord Tipo de orden 0|1|2.
     */
    function __construct($ord=0) {
        $this->cargarPersonas($ord);
    }
    
    //--- METODOS PRIVADOS Y PROTEGIDOS --------------------------------------//
    
    /**
     * Carga los datos de las personas guardadas en la base de datos.
     * Se guardan en el array 'aDatosPersonas' de la clase.
     * Los tipos de orden permitidos son:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Codigo.</li>
     * </ul>
     * 
     * @param int $ord Tipo de orden 0|1|2.
     */
    private function cargarPersonas($ord) {
        switch ($ord) {
            case 1  : $sOrd = "NOMBRE,APELLIDOS,CODPERS"; break;
            case 2  : $sOrd = "CODPERS"; break;
            default : $sOrd = "APELLIDOS,NOMBRE,CODPERS"; $ord = 0; break;
        }
        $this->cargarPersonasOmision($ord);
        $rRes = Funciones::gEjecutarSQL("SELECT CODPERS,APELLIDOS,NOMBRE,SEXO,CODUSU,CORREO,ENVIOS,TELEFONO,NOTAS FROM PERSONAS ORDER BY $sOrd");
        while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
            $this->aDatosPersonas[$aRow['CODPERS']] = array($aRow['APELLIDOS'],$aRow['NOMBRE'],$aRow['SEXO'],$aRow['CODUSU'],$aRow['CORREO'],$aRow['ENVIOS'],$aRow['TELEFONO'],$aRow['NOTAS']);
        }
        $rRes->closeCursor();
        $this->aFiltradas = $this->aDatosPersonas;
    }
    
    /**
     * Carga los datos por omision.
     * 
     * @param int $ord Tipo de orden 0|1|2.
     */
    private function cargarPersonasOmision($ord) {
        $this->aDatosPersonas = array();
        $this->orden = $ord;
        $this->aFiltradas = array();
        $this->filtroHombre = '';
        $this->filtroMujer = '';
        $this->filtroOtro = '';
        $this->filtroCorreo = '';
        $this->filtroEnvios = '';
        $this->filtroTelefono = '';
        $this->filtroNotas = '';
        $this->filtroPropietario = '';
    }
    
    /**
     * Obtiene el numero de propiedades que tiene una persona.
     * 
     * @param int $per Codigo de persona.
     * @return int Numero de propiedades.
     */
    private function numeroPropiedades($per) {
        $num = 0;
        $res = Funciones::gEjecutarSQL("SELECT COUNT(*) AS TOT FROM PROPIETARIOS WHERE CODPERS='$per' AND BAJA IS NULL");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $num = $aRow['TOT'];
        }
        $res->closeCursor();
        return $num;
    }
    
    /**
     * Filtra las personas por los filtros indicados.
     */
    private function filtrarPersonas() {
        $aFil = array();
        $aPer = $this->aDatosPersonas;
        $fHom = $this->filtroHombre;
        $fMuj = $this->filtroMujer;
        $fOtr = $this->filtroOtro;        
        $fCor = $this->filtroCorreo;        
        $fEnv = $this->filtroEnvios;
        $fTel = $this->filtroTelefono;
        $fNot = $this->filtroNotas;
        $fPro = $this->filtroPropietario;
        
        // array('cod'=>array(0 apellidos, 1 nombre, 2 sexo, 3 codusu, 4 correo, 5 envios, 6 telefono, 7 notas)...)
        foreach ($aPer as $per => $aPersona) {
            $bOK = TRUE;
            if ($fHom == 'S' || $fMuj == 'S' || $fOtr == 'S') {
                $bOK = (($fHom == 'S' && $aPersona[2] == 'H') || ($fMuj == 'S' && $aPersona[2] == 'M') || ($fOtr == 'S' && $aPersona[2] == '')) ? TRUE : FALSE;
            }
            if ($fCor == 'S') {
                $bOK = ($fCor == 'S' && !$aPersona[4]) ? FALSE : $bOK;
            }
            if ($fEnv == 'S') {
                $bOK = ($fEnv == 'S' && !$aPersona[5]) ? FALSE : $bOK;
            }
            if ($fTel == 'S') {
                $bOK = ($fTel == 'S' && !$aPersona[6]) ? FALSE : $bOK;
            }
            if ($fNot == 'S') {
                $bOK = ($fNot == 'S' && !$aPersona[7]) ? FALSE : $bOK;
            }
            if ($fPro == 'S') {
                $bOK = (!$this->esPropietario($per)) ? FALSE : $bOK;
            }
            if ($bOK) {
                $aFil[$per] = $aPersona;
            }
        }
        $this->aFiltradas = $aFil;
    }
    
    /**
     * Busca personas en la base de datos.
     * Se devuelve un array cuyas claves son los <b>codigos de personas</b> encontradas y como contenido:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * </ul>
     * 
     * @param string $buscar Datos a buscar.
     * @return array con las personas encontradas del tipo array('codpers'=>array('apellidos','nombre')...)
     */
    private function buscarPersonas($buscar) {
        $aBus = array();
        if($buscar) {
            $dato = Funciones::gCodificar($buscar);
            $rRes = Funciones::gEjecutarSQL("SELECT CODPERS,APELLIDOS,NOMBRE FROM PERSONAS WHERE STRCMP(CODPERS,'$dato')=0 OR APELLIDOS LIKE '%$dato%' OR NOMBRE LIKE '%$dato%' ORDER BY APELLIDOS,NOMBRE,CODPERS");
            while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
                $aBus[$aRow['CODPERS']] = array($aRow['APELLIDOS'],$aRow['NOMBRE']);
            }
            $rRes->closeCursor(); 
        }
        return $aBus;
    }
    
    /**
     * Obtiene un dato concreto de todas las personas.
     * Los numeros de datos pueden ser:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Sexo.</li>
     * <li>3 - Codigo de usuario.</li>
     * <li>4 - Correo electronico.</li>
     * <li>5 - Enviar correos.</li>
     * <li>6 - Telefono.</li>
     * <li>7 - Notas</li>
     * </ul>
     * 
     * @param int $num Numero de dato.
     * @return array del tipo array('cod'=>'dato'...)
     */
    private function getDatos($num) {
        $aDat = array();
        $aPer = $this->aDatosPersonas;
        foreach ($aPer as $cod => $aDatos) {
            $aDat[$cod] = $aDatos[$num];
        }
        return $aDat;
    }
    
    /**
     * Obtiene los datos de personas filtrando por otro grupo de personas.
     * El array devuelto tiene como clave el codigo de persona y como datos,
     * si $nom es TRUE, el nombre completo, pero si es FALSE los datos completos:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Sexo.</li>
     * <li>3 - Codigo de usuario.</li>
     * <li>4 - Correo electronico.</li>
     * <li>5 - Enviar correos.</li>
     * <li>6 - Telefono.</li>
     * <li>7 - Notas</li>
     * </ul>
     * 
     * @param array $aPers Personas a incluir o excluir.
     * @param boolean $bExc Si es TRUE se excluyen, si el FALSE se incluyen solo estas personas.
     * @param boolean $bNom Si es FALSE obtiene todos los datos si es TRUE obtiene solo el nombre.
     * @return array con los datos de las personas.
     */
    private function getPersonasIncExc($aPers, $bExc=TRUE, $bNom=FALSE) {
        $aDat = array();
        $aPer = $this->aDatosPersonas;
        foreach ($aPer as $per => $aPersona) {
            if($bExc && !array_key_exists($per, $aPers)) {
                $aDat[$per] = (!$bNom) ? $aPersona : $aPersona[0] . " " . $aPersona[1];
            } elseif (!$bExc && array_key_exists($per, $aPers)) {
                $aDat[$per] = (!$bNom) ? $aPersona : $aPersona[0] . " " . $aPersona[1];
            }
        }
        return $aDat;
    }
    
    /**
     * Arregla el valor para un filtro permitiendo solo S, N o vacio.
     * 
     * @param string $fil Cadena a filtrar.
     * @return string Cadena filtrada.
     */
    private function arreglarFiltro($fil) {
        if ($fil == 'S' || $fil == 's') {
            $fil = 'S';
        } elseif ($fil == 'N' || $fil == 'n') {
            $fil = 'N';
        } else {
            $fil = '';
        }
        return $fil;
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    /**
     * Actualiza los datos de las personas.
     */
    public function actualizar() {
        $ord = $this->orden;
        $this->cargarPersonas($ord);
    }
    
    /**
     * Obtiene los datos de todas las personas.
     * El array tiene como clave el <b>codigo de la persona</b> y como datos:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Sexo.</li>
     * <li>3 - Codigo de usuario.</li>
     * <li>4 - Correo electronico.</li>
     * <li>5 - Enviar correos.</li>
     * <li>6 - Telefono.</li>
     * <li>7 - Notas</li>
     * </ul>
     * 
     * @return array del tipo array('cod'=>array(apellidos,nombre,sexo,codusu,correo,envios,telefono,notas)...)
     */
    public function getPersonas() {
        return $this->aDatosPersonas;
    }
    
    /**
     * Obtiene los datos de todas las personas incluidas en un grupo.
     * El array devuelto tiene como clave el codigo de persona y como datos,
     * si $nom es TRUE, el nombre completo, pero si es FALSE los datos completos:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Sexo.</li>
     * <li>3 - Codigo de usuario.</li>
     * <li>4 - Correo electronico.</li>
     * <li>5 - Enviar correos.</li>
     * <li>6 - Telefono.</li>
     * <li>7 - Notas</li>
     * </ul>
     * 
     * @param array $aInc Grupo de personas a incluir.
     * @param boolean $bNom Si es FALSE obtiene todos los datos si es TRUE obtiene solo el nombre.
     * @return array con los datos de las personas.
     */
    public function getPersonasIncluyendo($aInc, $bNom=FALSE) {
        return $this->getPersonasIncExc($aInc, FALSE, $bNom);
    }
    
    /**
     * Obtiene los datos de todas las personas excluyendo las indicadas.
     * El array devuelto tiene como clave el codigo de persona y como datos,
     * si $nom es TRUE, el nombre completo, pero si es FALSE los datos completos:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Sexo.</li>
     * <li>3 - Codigo de usuario.</li>
     * <li>4 - Correo electronico.</li>
     * <li>5 - Enviar correos.</li>
     * <li>6 - Telefono.</li>
     * <li>7 - Notas</li>
     * </ul>
     * 
     * @param array $aExc Grupo de personas a excluir.
     * @param boolean $bNom Si es FALSE obtiene todos los datos si es TRUE obtiene solo el nombre.
     * @return array con los datos de las personas.
     */
    public function getPersonasExcluyendo($aExc, $bNom=FALSE) {
        return $this->getPersonasIncExc($aExc, TRUE, $bNom);
    }
    
    /**
     * Obtiene los apellidos de todas las personas.
     * @return array('cod'=>'apellidos'...)
     */
    public function getApellidos() {
        return $this->getDatos(0);
    }
    
    /**
     * Obtiene los apellidos y nombres de las personas.
     * Segun el orden asignado se obtendran los apellidos y nombres o los nombres y apellidos.
     * 
     * @param string $sep Separador entre los apellidos y el nombre.
     * @return array del tipo array('cod'=>'apellidos nombre'...)
     */
    public function getNombresCompletos($sep='') {
        $aDat = array();
        $aPer = $this->aDatosPersonas;
        $orden = $this->orden;
        foreach ($aPer as $cod => $aDatos) {
            $aDat[$cod]= ($orden == 1) ? $aDatos[1] . "$sep " . $aDatos[0] : $aDatos[0] . "$sep " . $aDatos[1];
        }
        return $aDat;
    }
    
    /**
     * Obtiene los apellidos y nombre de una persona.
     * Segun el orden asignado se obtendran los apellidos y nombre o el nombre y apellidos.
     * 
     * @param int $per Codigo de persona.
     * @param string $sep Separador entre los apellidos y el nombre.
     * @return string Apellidos y nombre o nombre y apellidos.
     */
    public function getNombreCompleto($per, $sep='') {
        $aPer = $this->aDatosPersonas;
        $orden = $this->orden;
        $aDatos = $aPer[$per];
        return ($orden == 1) ? $aDatos[1] . "$sep " . $aDatos[0] : $aDatos[0] . "$sep " . $aDatos[1];
    }
    
    /**
     * Obtiene las distintas iniciales de todos los apellidos.
     * 
     * @return array del tipo array(ini1, ini2...)
     */
    public function getIniciales() {
        $aIni = array();
        $aApe = $this->getApellidos();
        $sIni = "";
        foreach ($aApe as $Apellido) {
            $ini = substr($Apellido, 0, 1);
            if($ini != $sIni) {
                $aIni[] = $ini;
            }
            $sIni = $ini;
        }
        return $aIni;
    }
    
    /**
     * Asigna el orden para el listado de personas.
     * Si el tipo de orden no es el mismo que habia, se reordenara la lista para ajustarla al nuevo orden.
     * Los tipos de orden permitidos son:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Codigo.</li>
     * </ul>
     * 
     * @param int $ord Tipo de orden 0|1|2.
     */
    public function setOrden($ord) {
        $orden = ($ord != 1 && $ord != 2) ? 0 : $ord;
        if ($orden != $this->orden) {
            $this->cargarPersonas($orden);
        }
    }
    
    /**
     * Obtiene el tipo de orden que se esta usando.
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Codigo.</li>
     * </ul>
     * 
     * @param boolean $bNom Si es TRUE obtiene el texto y si es FALSE el numero.
     * @return @return mixed Tipo de orden 0|1|2 o con texto Apellidos|Nombre|Codigo.
     */
    public function getOrden($bNom=FALSE) {
        $aOrden = array('Apellidos', 'Nombre', 'C&oacute;digo');
        return ($bNom) ? $aOrden[$this->orden] : $this->orden;
    }
    
    /**
     * Obtiene los nombres de las personas que coinciden con el criterio de busqueda.
     * Se devuelve un array cuyas claves son los <b>codigos de personas</b> encontradas y como contenido:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * </ul>
     * 
     * @param string $busqueda Cadena a buscar.
     * @return array del tipo array('cod'=>array('apellidos','nombre')...)
     */
    public function buscar($busqueda) {
        return $this->buscarPersonas($busqueda);
    }
    
    /**
     * Asigna el filtro por sexo hombre.
     * 
     * @param string $fil Filtro S/N.
     */
    public function setFiltroSexoHombre($fil='') {
        $this->filtroHombre = $this->arreglarFiltro($fil);
        $this->filtrarPersonas();
    }
    
    /**
     * Asigna el filtro por sexo mujer.
     * 
     * @param string $fil Filtro S/N.
     */
    public function setFiltroSexoMujer($fil='') {
        $this->filtroMujer = $this->arreglarFiltro($fil);
        $this->filtrarPersonas();
    }
    
    /**
     * Asigna el filtro por sexo otro.
     * 
     * @param string $fil Filtro S/N.
     */
    public function setFiltroSexoOtro($fil='') {
        $this->filtroOtro = $this->arreglarFiltro($fil);
        $this->filtrarPersonas();
    }
    
    /**
     * Asigna el filtro de personas con correo.
     * 
     * @param string $fil Filtro S/N.
     */
    public function setFiltroCorreo($fil='') {
        $this->filtroCorreo = $this->arreglarFiltro($fil);
        $this->filtrarPersonas();
    }
    
    /**
     * Asigna el filtro de envios activados.
     * 
     * @param string $fil Filtro S/N.
     */
    public function setFiltroEnvios($fil='') {
        $this->filtroEnvios = $this->arreglarFiltro($fil);
        $this->filtrarPersonas();
    }
    
    /**
     * Asigna el filtro de personas con telefono.
     * 
     * @param string $fil Filtro S/N.
     */
    public function setFiltroTelefono($fil='') {
        $this->filtroTelefono = $this->arreglarFiltro($fil);
        $this->filtrarPersonas();
    }
    
    /**
     * Asigna el filtro de personas con notas.
     * 
     * @param string $fil Filtro S/N.
     */
    public function setFiltroNotas($fil='') {
        $this->filtroNotas = $this->arreglarFiltro($fil);
        $this->filtrarPersonas();
    }
    
    /**
     * Asigna el filtro de propietarios.
     * 
     * @param string $fil Filtro S/N.
     */
    public function setFiltroPropietario($fil='') {
        $this->filtroPropietario = $this->arreglarFiltro($fil);
        $this->filtrarPersonas();
    }
    
    /**
     * Obtiene los datos de las personas filtradas.
     * El array tiene como clave el <b>codigo de la persona</b> y como datos:
     * <ul>
     * <li>0 - Apellidos.</li>
     * <li>1 - Nombre.</li>
     * <li>2 - Sexo.</li>
     * <li>3 - Codigo de usuario.</li>
     * <li>4 - Correo electronico.</li>
     * <li>5 - Enviar correos.</li>
     * <li>6 - Telefono.</li>
     * <li>7 - Notas</li>
     * </ul>
     * 
     * @return array del tipo array('cod'=>array(apellidos,nombre,sexo,codusu,correo,envios,telefono,notas)...)
     */
    public function getFiltradas() {
        return $this->aFiltradas;
    }
    
    /**
     * Indica si una persona es propietaria o no.
     * 
     * @param int $per Codigo de persona.
     * @return boolean Devuelve TRUE si es propietario o FALSE si no lo es.
     */
    public function esPropietario($per) {
        return ($this->numeroPropiedades($per) > 0) ? TRUE : FALSE;
    }
}
