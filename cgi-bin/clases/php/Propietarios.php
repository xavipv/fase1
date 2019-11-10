<?php

/**
 * Clase Propietarios.
 */

/**
 * Description of Propietarios1
 *
 * @author xavi
 */
class Propietarios {
    
    //--- CONSTANTES ---------------------------------------------------------//
    
    /**
     * Fecha en formato AAAA-MM-DD de la constitucion de la comunidad.
     */
    const FECHA_CONSTITUCION = "1984-08-24";
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Guarda los datos de los propietarios en un array cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Codigo del apartamento.</li>
     * <li>1 - Nombre del apartamento.</li>
     * <li>2 - Codigo de persona.</li>
     * <li>3 - Nombre de persona.</li>
     * <li>4 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>5 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>6 - Numero de orden.</li>
     * </ul>
     * 
     * @var array del tipo array('codapa','apartamento','codpers','persona','date','fecha','orden')
     */
    private $aPropietarios;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase.
     */
    public function __construct() {
        $this->cargar();
    }
    
    //--- METODOS PRIVADOS ---------------------------------------------------//
    
    /**
     * Carga los propietarios guardados en la base de datos.
     * Los datos se ordenan por:
     * <ul>
     * <li>Codigo de apartamento, ascendente.</li>
     * <li>Fecha de baja, descendente.</li>
     * <li>Orden, ascendente.</li>
     * </ul>
     */
    private function cargar() {
        $this->omision();
        $res = Funciones::gEjecutarSQL("SELECT PR.CODAPAR,CONCAT(A.PORTAL,'-',A.PISO,A.LETRA) AS APARTAMENTO,PR.CODPERS,CONCAT(P.APELLIDOS,' ',P.NOMBRE) AS PERSONA,PR.BAJA,DATE_FORMAT(PR.BAJA,'%d-%m-%Y') AS FECHA,PR.ORDEN FROM PROPIETARIOS PR LEFT JOIN APARTAMENTOS A ON PR.CODAPAR=A.CODAPAR LEFT JOIN PERSONAS P ON PR.CODPERS=P.CODPERS ORDER BY CODAPAR ASC,IFNULL(BAJA,'9999-99-99') DESC, ORDEN ASC");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $this->aPropietarios[] = array($aRow['CODAPAR'],$aRow['APARTAMENTO'],$aRow['CODPERS'],$aRow['PERSONA'],$aRow['BAJA'],$aRow['FECHA'],$aRow['ORDEN']);
        }
        $res->closeCursor();
    }
    
    /**
     * Carga los valores por omision.
     */
    private function omision() {
        $this->aPropietarios = array();
    }
    
    /**
     * Ordena el array de propietarios por lo siguiente:
     * <ul>
     * <li>Nombre de propietario, ascendente.</li>
     * <li>Fecha de baja, descendente.</li>
     * <li>Codigo de apartamento, ascendente.</li>
     * </ul>
     * 
     * @return array reordenado del tipo array del tipo array('codapa','apartamento','codpers','persona','date','fecha','orden')
     */
    private function ordenarPorPersonas() {
        $aProp = $this->aPropietarios;
        foreach ($aProp as $key => $aP) {
            $cap[$key] = $aP[0];
            $per[$key] = $aP[3];
            $fec[$key] = ($aP[4]) ? $aP[4] : '9999-99-99';
        }
        array_multisort($per, SORT_ASC, $fec, SORT_DESC, $cap, SORT_ASC, $aProp);
        return $aProp;
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    //--- PROPIETARIOS ---//
    
    /**
     * Obtiene todos los propietarios ordenados por apartamento o por nombre de persona.
     * Se devuelve un array cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Codigo del apartamento.</li>
     * <li>1 - Nombre del apartamento.</li>
     * <li>2 - Codigo de persona.</li>
     * <li>3 - Nombre de persona.</li>
     * <li>4 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>5 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>6 - Numero de orden.</li>
     * </ul>
     * 
     * @param boolean $bPer Si es TRUE ordena por los nombres de personas o si es FALSE por apartamentos.
     * @return array del tipo array('codapa','apartamento','codpers','persona','date','fecha','orden')
     */
    public function getPropietarios($bPer=FALSE) {
        return (!$bPer) ? $this->aPropietarios : $this->ordenarPorPersonas();
    }
    
    /**
     * Obtiene los propietarios que estan de alta ordenados por apartamento o por nombre de persona.
     * Se devuelve un array cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Codigo del apartamento.</li>
     * <li>1 - Nombre del apartamento.</li>
     * <li>2 - Codigo de persona.</li>
     * <li>3 - Nombre de persona.</li>
     * <li>4 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>5 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>6 - Numero de orden.</li>
     * </ul>
     * 
     * @param boolean $bPer Si es TRUE ordena por los nombres de personas o si es FALSE por apartamentos.
     * @return array del tipo array del tipo array('codapa','apartamento','codpers','persona','date','fecha','orden')
     */
    public function getPropietariosAlta($bPer=FALSE) {
        $aNueva = array();
        $aDatos = $this->getPropietarios($bPer);
        foreach ($aDatos as $aPropietario) {
            if (!$aPropietario[4]) {
                $aNueva[] = $aPropietario;
            }
        }
        return $aNueva;
    }
    
    /**
     * Obtiene los propietarios de los apartamentos entre dos fechas dadas y ordenados por apartamento o por nombre de persona.
     * Se devuelve un array cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Codigo del apartamento.</li>
     * <li>1 - Nombre del apartamento.</li>
     * <li>2 - Codigo de persona.</li>
     * <li>3 - Nombre de persona.</li>
     * <li>4 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>5 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>6 - Numero de orden.</li>
     * </ul>
     * 
     * @param date $fecha1 Fecha inicial, en cualquier formato.
     * @param date $fecha2 Fecha final, en cualquier formato.
     * @param boolean $bPer Si es TRUE ordena por los nombres de personas o si es FALSE por apartamentos.
     * @return array del tipo array del tipo array('codapa','apartamento','codpers','persona','date','fecha','orden')
     */
    public function getPropietariosEntreFechas($fecha1='', $fecha2='', $bPer=FALSE) {
        $aNueva = array();
        $date1 = ($fecha1) ? Funciones::gConvertirFecha($fecha1, TRUE) : "1984-08-24";  // Fecha constitucion de la comunidad.
        $date2 = ($fecha2) ? Funciones::gConvertirFecha($fecha2, TRUE) : date("Y-m-d"); // Fecha actual.
        if ($date1 > $date2) {
            $d = $date1;
            $date1 = $date2;
            $date2 = $d;
        }
        $orden = ($bPer) ? "PERSONA,IFNULL(PR.BAJA,'9999-99-99') DESC,PR.CODAPAR" : "PR.CODAPAR,IFNULL(PR.BAJA,'9999-99-99') DESC,PR.ORDEN";
        $res = Funciones::gEjecutarSQL("SELECT PR.CODAPAR,CONCAT(A.PORTAL,'-',A.PISO,A.LETRA) AS APARTAMENTO,PR.CODPERS,CONCAT(P.APELLIDOS,' ',P.NOMBRE) AS PERSONA,PR.BAJA,DATE_FORMAT(PR.BAJA,'%d-%m-%Y') AS FECHA,PR.ORDEN FROM PROPIETARIOS PR LEFT JOIN APARTAMENTOS A ON PR.CODAPAR=A.CODAPAR LEFT JOIN PERSONAS P ON PR.CODPERS=P.CODPERS WHERE IFNULL(PR.BAJA,'9999-99-99') >= '$date1' AND IFNULL((SELECT MAX(IFNULL(BAJA,'9999-99-99')) FROM PROPIETARIOS WHERE CODAPAR=PR.CODAPAR AND IFNULL(BAJA,'9999-99-99')<IFNULL(PR.BAJA,'9999-99-99')),'0000-00-00') <= '$date2' ORDER BY $orden");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $aNueva[] = array($aRow['CODAPAR'],$aRow['APARTAMENTO'],$aRow['CODPERS'],$aRow['PERSONA'],$aRow['BAJA'],$aRow['FECHA'],$aRow['ORDEN']);
        }
        $res->closeCursor();
        return $aNueva;
    }
    
    /**
     * Obtiene los datos de los propietarios en una fecha determinada.
     * Se devuelve un array cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Codigo del apartamento.</li>
     * <li>1 - Nombre del apartamento.</li>
     * <li>2 - Codigo de persona.</li>
     * <li>3 - Nombre de persona.</li>
     * <li>4 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>5 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>6 - Numero de orden.</li>
     * </ul>
     * 
     * @param date $fecha Fecha en cualquier formato.
     * @param boolean $bPer Si es TRUE ordena por los nombres de personas o si es FALSE por apartamentos.
     * @return array del tipo array('codapa','apartamento','codpers','persona','date','fecha','orden')
     */
    public function getPropietariosFecha($fecha='', $bPer=FALSE) {
        $aDatos = array();
        $date = Funciones::gConvertirFecha($fecha, TRUE);
        $orden = ($bPer) ? "PERSONA,IFNULL(PR.BAJA,'9999-99-99') DESC,PR.CODAPAR" : "PR.CODAPAR,IFNULL(PR.BAJA,'9999-99-99') DESC,PR.ORDEN";
        $res = Funciones::gEjecutarSQL("SELECT PR.CODAPAR,CONCAT(A.PORTAL,'-',A.PISO,A.LETRA) AS APARTAMENTO,PR.CODPERS,CONCAT(P.APELLIDOS,' ',P.NOMBRE) AS PERSONA,PR.BAJA,DATE_FORMAT(PR.BAJA,'%d-%m-%Y') AS FECHA,PR.ORDEN FROM PROPIETARIOS PR LEFT JOIN APARTAMENTOS A ON PR.CODAPAR=A.CODAPAR LEFT JOIN PERSONAS P ON PR.CODPERS=P.CODPERS WHERE IFNULL(PR.BAJA,'9999-99-99')=(SELECT MIN(IFNULL(BAJA,'9999-99-99')) FROM PROPIETARIOS WHERE CODAPAR=PR.CODAPAR AND IFNULL(BAJA,'9999-99-99')>'$date') ORDER BY $orden");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $aDatos[] = array($aRow['CODAPAR'],$aRow['APARTAMENTO'],$aRow['CODPERS'],$aRow['PERSONA'],$aRow['BAJA'],$aRow['FECHA'],$aRow['ORDEN']);
        }
        $res->closeCursor();
        return $aDatos;
    }
    
    //--- PROPIETARIOS APARTAMENTO ---//
    
    /**
     * Obtiene todos los propietarios que tiene y ha tenido un apartamento.
     * Se devuelve un array cuya clave es el <b>codigo de persona</b> y cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Nombre de persona.</li>
     * <li>1 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>3 - Numero de orden.</li>
     * </ul>
     * 
     * @param int $apa Codigo de apartamento.
     * @return array del tipo array('codpers'=>array('persona','date','fecha','orden')...)
     */
    public function getPropietariosApartamento($apa) {
        $aDatos = array();
        $aPro = $this->getPropietarios();
        foreach ($aPro as $aP) {
            if ($aP[0] == $apa) {
                $aDatos[$aP[2]] = array($aP[3],$aP[4],$aP[5],$aP[6]);
            }
        }
        return $aDatos;
    }
    
    /**
     * Obtiene los propietarios de un apartamento entre dos fechas dadas.
     * Se devuelve un array cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Codigo del apartamento.</li>
     * <li>1 - Nombre del apartamento.</li>
     * <li>2 - Codigo de persona.</li>
     * <li>3 - Nombre de persona.</li>
     * <li>4 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>5 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>6 - Numero de orden.</li>
     * </ul>
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha1 Fecha inicial, en cualquier formato.
     * @param date $fecha2 Fecha final, en cualquier formato.
     * @return array del tipo array del tipo array('codapa','apartamento','codpers','persona','date','fecha','orden')
     */
    public function getPropietariosApartamentoEntreFechas($apa, $fecha1='', $fecha2='') {
        $aNueva = array();
        $date1 = ($fecha1) ? Funciones::gConvertirFecha($fecha1, TRUE) : self::FECHA_CONSTITUCION;  // Fecha constitucion de la comunidad.
        $date2 = ($fecha2) ? Funciones::gConvertirFecha($fecha2, TRUE) : date("Y-m-d"); // Fecha actual.
        if ($date1 > $date2) {
            $d = $date1;
            $date1 = $date2;
            $date2 = $d;
        }
        $res = Funciones::gEjecutarSQL("SELECT PR.CODAPAR,CONCAT(A.PORTAL,'-',A.PISO,A.LETRA) AS APARTAMENTO,PR.CODPERS,CONCAT(P.APELLIDOS,' ',P.NOMBRE) AS PERSONA,PR.BAJA,DATE_FORMAT(PR.BAJA,'%d-%m-%Y') AS FECHA,PR.ORDEN FROM PROPIETARIOS PR LEFT JOIN APARTAMENTOS A ON PR.CODAPAR=A.CODAPAR LEFT JOIN PERSONAS P ON PR.CODPERS=P.CODPERS WHERE PR.CODAPAR='$apa' AND IFNULL(PR.BAJA,'9999-99-99') >= '$date1' AND IFNULL((SELECT MAX(IFNULL(BAJA,'9999-99-99')) FROM PROPIETARIOS WHERE CODAPAR=PR.CODAPAR AND IFNULL(BAJA,'9999-99-99')<IFNULL(PR.BAJA,'9999-99-99')),'0000-00-00')  <= '$date2' ORDER BY IFNULL(PR.BAJA,'9999-99-99') DESC,PR.ORDEN,PERSONA");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $aNueva[] = array($aRow['CODAPAR'],$aRow['APARTAMENTO'],$aRow['CODPERS'],$aRow['PERSONA'],$aRow['BAJA'],$aRow['FECHA'],$aRow['ORDEN']);
        }
        $res->closeCursor();
        return $aNueva;
    }
    
    /**
     * Obtiene los propietarios de un apartamento en una fecha determinada.
     * Se devuelve un array cuya clave es el <b>codigo de persona</b> y cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Nombre de persona.</li>
     * <li>1 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>3 - Numero de orden.</li>
     * </ul>
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return array del tipo array('codpers'=>array('persona','date','fecha','orden')...)
     */
    public function getPropietariosApartamentoFecha($apa, $fecha='') {
        $aDatos = array();
        $aPro = $this->getPropietariosFecha($fecha); // array('codapa','apartamento','codpers','persona','date','fecha','orden')
        foreach ($aPro as $aP) {
            if ($aP[0] == $apa) {
                $aDatos[$aP[2]] = array($aP[3],$aP[4],$aP[5],$aP[6]);
            }
        }
        return $aDatos;
    }
    
    /**
     * Obtiene los nombres de los propietarios de un apartamento en una fecha determinada.
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return array del tipo array('codpers'=>'nombre'...)
     */
    public function getNombresPropietariosApartamentoFecha($apa, $fecha='') {
        $aDatos = array();
        $aPro = $this->getPropietariosFecha($fecha, FALSE);
        foreach ($aPro as $aP) {
            if ($aP[0] == $apa) {
                $aDatos[$aP[2]] = $aP[3];
            }
        }
        return $aDatos;
    }
    
    /**
     * Obtiene los codigos de los propietarios de un apartamento en una fecha determinada.
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return array del tipo array(codper1, coper2...)
     */
    public function getCodigosPropietariosApartamentoFecha($apa, $fecha='') {
        return array_keys($this->getPropietariosApartamentoFecha($apa, $fecha));
    }
    
    /**
     * Obtiene el numero de propietarios que tiene o ha tenido un apartamento.
     * Se devuelve un array con los siguientes valores:
     * <ul>
     * <li>0 - Numero total de propietarios.</li>
     * <li>1 - Numero de propietarios de alta.</li>
     * <li>2 - Numero de propietarios de baja.</li>
     * </ul>
     * 
     * @param int $apa Codigo de apartamento.
     * @return array del tipo array(total, alta, baja)
     */
    public function getNumeroPropietarios($apa) {
        $tota = 0;
        $alta = 0;
        $baja = 0;
        $aPro = $this->getPropietariosApartamento($apa);
        foreach ($aPro as $aDatos) {
            $tota++;
            if ($aDatos[1]) {
                $baja++;    // Con fecha de baja.
            } else {
                $alta++;    // Sin fecha de baja.
            }
        }
        return array($tota, $alta, $baja);
    }
    
    /**
     * Obtiene el numero de propiedades totales, de alta y de baja que tienen todos los propietarios.
     * Se devuelve un array cuya clave es el <b>codigo de persona</b> y con los siguientes valores:
     * <ul>
     * <li>0 - Nombre del propietario.</li>
     * <li>1 - Numero total de propiedades.</li>
     * <li>2 - Numero de propiedades de alta.</li>
     * <li>3 - Numero de propiedades de baja.</li>
     * </ul>
     * 
     * @param boolean $bPer Si es TRUE ordena por los nombres de personas o si es FALSE por apartamentos.
     * @return array del tipo array(codper=>array(nombre,total, alta, baja)...)
     */
    public function getPropietariosNumeroPropiedades($bPer=FALSE) {
        $aDatos = array();
        $aProp = $this->getPropietarios($bPer);  // array('codapa','apartamento','codpers','persona','date','fecha','orden')
        foreach ($aProp as $aP) {
            $per = $aP[2];
            $nom = $aP[3];
            $aNum = $this->getNumeroPropiedades($per);
            $aDatos[$per] = array($nom, $aNum[0], $aNum[1], $aNum[2]);
        }
        return $aDatos;
    }
    
    //--- PROPIETARIO PRINCIPAL ---//
    
    /**
     * Obtiene el primer propietario de un apartamento en una fecha determinada.
     * Se devuelve un array cuya clave es el <b>codigo de persona</b> y cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Nombre de persona.</li>
     * <li>1 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>3 - Numero de orden.</li>
     * </ul>
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return array del tipo array('codpers'=>array('persona','date','fecha','orden'))
     */
    public function getPropietarioApartamentoFecha($apa, $fecha='') {
        $aPri = array();
        $aPro = $this->getPropietariosApartamentoFecha($apa, $fecha);
        foreach ($aPro as $key => $aDatos) {
            $aPri[$key] = $aDatos;
            return $aPri;   // Devuelve la primera persona.
        }
        return array();
    }
    
    /**
     * Obtiene el nombre del primer propietario de un apartamento en una fecha determinada.
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return array del tipo array('codpers'=>'nombre'...)
     */
    public function getNombrePropietarioApartamentoFecha($apa, $fecha='') {
        $aDatos = array();
        $aPro = $this->getPropietarioApartamentoFecha($apa, $fecha);
        $key = key($aPro);
        $nom = $aPro[$key][0];
        $aDatos[$key] = $nom;
        return $aDatos;
    }
    
    /**
     * Obtiene el codigo del primer propietario de un apartamento en una fecha determinada.
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return int Codigo de persona.
     */
    public function getCodigoPropietarioApartamentoFecha($apa, $fecha='') {
        return key($this->getPropietarioApartamentoFecha($apa, $fecha));    // Clave del primer propietario.
    }
    
    public function getNombreSimplePropietarioApartamentoFecha($apa, $fecha='') {
        return array_values($this->getPropietarioApartamentoFecha($apa, $fecha))[0][0];    
    }
    
    /**
     * Obtiene las propiedades del primer propietario de un apartamento en una fecha determinada.
     * Se devuelve un array cuya clave es el <b>codigo de apartamento</b> y cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Apartamento.</li>
     * <li>1 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>3 - Numero de orden.</li>
     * </ul>
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return array del tipo array('codapar'=>array('apartamento','date','fecha','orden')...)
     */
    public function getMisPropiedadesFecha($apa, $fecha) {
        $per = $this->getCodigoPropietarioApartamentoFecha($apa, $fecha);   // Codigo del primer propietario.
        return $this->getPropiedadesPersonaFecha($per, $fecha);
    }
    
    /**
     * Obtiene los codigos de las propiedades del primer propietario de un apartamento en una fecha determinada.
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return array del tipo array(codapa1, codapa2...)
     */
    public function getCodigosMisPropiedadesFecha($apa, $fecha) {
        return array_keys($this->getMisPropiedadesFecha($apa, $fecha));
    }


    //--- PROPIEDADES ---//
    
    /**
     * Obtiene todas las propiedades que tiene y ha tenido una persona.
     * Se devuelve un array cuya clave es el <b>codigo de apartamento</b> y cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Apartamento.</li>
     * <li>1 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>3 - Numero de orden.</li>
     * </ul>
     * 
     * @param int $per Codigo de persona.
     * @return array del tipo array('codapar'=>array('apartamento','date','fecha','orden')...)
     */
    public function getPropiedadesPersona($per) {
        $aDatos = array();
        $aPro = $this->getPropietarios(TRUE);
        foreach ($aPro as $aP) {
            if ($aP[2] == $per) {
                $aDatos[$aP[0]] = array($aP[1],$aP[4],$aP[5],$aP[6]);
            }
        }
        return $aDatos;
    }
    
    /**
     * Obtiene las propiedades de una persona en una fecha determinada.
     * Se devuelve un array cuya clave es el <b>codigo de apartamento</b> y cuyos valores son los siguientes:
     * <ul>
     * <li>0 - Apartamento.</li>
     * <li>1 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>3 - Numero de orden.</li>
     * </ul>
     * 
     * @param int $per Codigo de persona.
     * @param date $fecha Fecha en cualquier formato.
     * @return array del tipo array('codapar'=>array('apartamento','date','fecha','orden')...)
     */
    public function getPropiedadesPersonaFecha($per, $fecha='') {
        $aDatos = array();
        $aPro = $this->getPropietariosFecha($fecha);
        foreach ($aPro as $aP) {
            if ($aP[2] == $per) {
                $aDatos[$aP[0]] = array($aP[1],$aP[4],$aP[5],$aP[6]);
            }
        }
        return $aDatos;
    }
    
    /**
     * Obtiene los codigos de las propiedades de una persona en una fecha determinada.
     * 
     * @param int $per Codigo de persona.
     * @param date $fecha Fecha en cualquier formato.
     * @return array del tipo array(codapa1, codapa2...)
     */
    public function getCodigosPropiedadesPersonaFecha($per, $fecha='') {
        return array_keys($this->getPropiedadesPersonaFecha($per, $fecha));
    }
    
    /**
     * Obtiene el numero de propiedades que tiene o ha tenido una persona.
     * Se devuelve un array con los siguientes valores:
     * <ul>
     * <li>0 - Numero total de propiedades.</li>
     * <li>1 - Numero de propiedades de alta.</li>
     * <li>2 - Numero de propiedades de baja.</li>
     * </ul>
     * 
     * @param int $per Codigo de persona.
     * @return array del tipo array(total, alta, baja)
     */
    public function getNumeroPropiedades($per) {
        $tota = 0;
        $alta = 0;
        $baja = 0;
        $aPro = $this->getPropiedadesPersona($per);
        foreach ($aPro as $aDatos) {
            $tota++;
            if ($aDatos[1]) {
                $baja++;    // Con fecha de baja.
            } else {
                $alta++;    // Sin fecha de baja.
            }
        }
        return array($tota, $alta, $baja);
    }
    
    //--- REPRESENTANTES ---//
    
    /**
     * Obtiene los posibles representantes de un apartamento en una Junta.
     * Se obtienen todas las personas menos los propietarios del apartamento en la fecha indicada.
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return array Representantes en formato array('codpers'=>'nombre'...)
     */
    public function getRepresentantes($apa, $fecha) {
        $aRepr = array();
        $date = Funciones::gConvertirFecha($fecha, TRUE);
        $res = Funciones::gEjecutarSQL("SELECT CODPERS,CONCAT(APELLIDOS,' ',NOMBRE) AS NOM FROM PERSONAS WHERE CODPERS NOT IN (SELECT CODPERS FROM PROPIETARIOS WHERE CODAPAR='$apa' AND IFNULL(BAJA,'9999-99-99')=(SELECT MIN(IFNULL(BAJA,'9999-99-99')) FROM PROPIETARIOS WHERE CODAPAR='$apa' AND IFNULL(BAJA,'9999-99-99')>'$date')) ORDER BY APELLIDOS,NOMBRE,CODPERS");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $aRepr[$aRow['CODPERS']] = $aRow['NOM'];
        }
        $res->closeCursor();
        return $aRepr;
    }

    /**
     * Obtiene el ultimo representante, antes de una fecha, de un apartamento en una Junta.
     * 
     * @param int $apa Codigo de apartamento.
     * @param date $fecha Fecha en cualquier formato.
     * @return int Codigo del representante.
     */
    public function getUltimoRepresentante($apa, $fecha) {
        $repr = "";
        $date = Funciones::gConvertirFecha($fecha, TRUE);
        $res = Funciones::gEjecutarSQL("SELECT CODPERS FROM ASISTENTES WHERE REPRESENTADO='S' AND FECHA<='$date' AND CODAPAR='$apa' ORDER BY FECHA DESC LIMIT 1");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $repr = $aRow['CODPERS'];
        }
        $res->closeCursor();
        return $repr;
    }
}
