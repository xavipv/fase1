<?php

/**
 * Clase Propiedad.
 */

/**
 * La clase Propiedad extiende a la clase Apartamento y la amplia con los datos de los propietarios.
 * Permite trabajar con los propietarios del apartamento.
 *
 * @author xavi
 */
class Propiedad extends Apartamento {
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Contiene todos los propietarios del apartamento actual. 
     * El array devuelto tiene como clave el codigo de la persona y como datos:
     * <ul>
     * <li>0 - Apellidos y nombe.</li>
     * <li>1 - Fecha en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha en formato DD-MM-YYYY.</li>
     * <li>3 - Orden.</li>
     * </ul>
     * 
     * @var array con formato array('codpers'=>array('nombre','date','fecha','orden)...)
     */
    private $aPropietarios;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase Apartamento.
     * 
     * @param int $cod Codigo del apartamento.
     */
    function __construct($cod = 0) {
        parent::__construct($cod);
        $this->cargarPropietarios();
    }
    
    //--- METODOS PRIVADOS ---------------------------------------------------//
    
    /**
     * Carga todos los propietarios del apartamento actual.
     */
    private function cargarPropietarios() {
        $aDa = array();
        $apa = $this->getCodigo();
        $res = Funciones::gEjecutarSQL("SELECT P.CODPERS,CONCAT(PE.APELLIDOS,' ',PE.NOMBRE) AS PERSONA,P.BAJA,DATE_FORMAT(P.BAJA,'%d-%m-%Y') AS FECHA,P.ORDEN FROM PROPIETARIOS P LEFT JOIN PERSONAS PE ON P.CODPERS=PE.CODPERS WHERE P.CODAPAR='$apa' ORDER BY IFNULL(P.BAJA,'9999-99-99') DESC,P.ORDEN");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $aDa[$aRow['CODPERS']] = array($aRow['PERSONA'], $aRow['BAJA'], $aRow['FECHA'], $aRow['ORDEN']);
        }
        $res->closeCursor();
        $this->aPropietarios = $aDa;
    }
    
    /**
     * Obtiene los propietarios de alta o de baja del apartamento actual.
     * 
     * @param boolean $alta Si es TRUE carga los propietarios de alta y si es FALSE carga los de baja.
     * @return array con los propietarios seleccionados.
     */
    private function obtenerPropietarios($alta=TRUE) {
        $aRes = array();
        $aPro = $this->aPropietarios;
        foreach ($aPro as $per => $aDat) {
            if(!$aDat[1] && $alta) {
                $aRes[$per] = $aDat;
            } elseif ($aDat[1] && !$alta) {
                $aRes[$per] = $aDat;
            }
        }
        return $aRes;
    }
    
    /**
     * Convierte una fecha de formato YYYY-MM-DD a DD-MM-YYYY o viceversa.
     * 
     * @param string $fecha Fecha a convertir.
     * @param boolean $base Indica si la fecha a convertir tiene el formato de base de datos o no.
     * @return string Fecha en el nuevo formato.
     */
    private function convertirISO_Base($fecha, $base) {
        $fec = $fecha;
        $sql = ($base) ? "SELECT DATE_FORMAT('$fecha','%d-%m-%Y') AS FECHA" : "SELECT STR_TO_DATE('$fecha','%d-%m-%Y') AS FECHA";
        $res = Funciones::gEjecutarSQL($sql);
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $fec = $aRow['FECHA'];
        }
        $res->closeCursor();
        return $fec;
    }
    
    /**
     * Convierte una fecha de un formato a otro.
     * 
     * @param string $fecha Fecha a convertir.
     * @param boolean $base Si es TRUE se convierte a YYYY-MM-DD y si es FALSE a DD-MM-YYYY.
     * @return string Fecha en el formato indicado.
     */
    private function convertirFecha($fecha, $base=TRUE) {
        $nueva = $fecha;
        
        if($fecha && substr($fecha, 4, 1) == '-' && substr($fecha, 7 , 1) == '-') {
            // Tenemos una fecha del tipo YYYY-MM-DD
            $nueva = ($base) ? $fecha : $this->convertirISO_Base($fecha,TRUE);
            
        } elseif($fecha && substr($fecha, 2, 1) == '-' && substr($fecha, 5 , 1) == '-') {
            // Tenemos una fecha del tipo DD-MM-YYYY
            $nueva = (!$base) ? $fecha : $this->convertirISO_Base($fecha,FALSE);
        }
        return $nueva;
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    /**
     * Obtiene todos los propietarios del apartamento actual. 
     * El array devuelto tiene como clave el <b>codigo de la persona</b> y como datos:
     * <ul>
     * <li>0 - Apellidos y nombe.</li>
     * <li>1 - Fecha en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha en formato DD-MM-YYYY.</li>
     * <li>3 - Orden.</li>
     * </ul>
     * 
     * @return array con formato array('codpers'=>array('nombre','date','fecha','orden)...)
     */
    public function getPopietarios() {
        return $this->aPropietarios;
    }
    
    /**
     * Obtiene los propietarios de alta del apartamento actual. 
     * El array devuelto tiene como clave el <b>codigo de la persona</b> y como datos:
     * <ul>
     * <li>0 - Apellidos y nombe.</li>
     * <li>1 - Fecha en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha en formato DD-MM-YYYY.</li>
     * <li>3 - Orden.</li>
     * </ul>
     * 
     * @return array con formato array('codpers'=>array('nombre','date','fecha','orden)...)
     */
    public function getPropietariosAlta() {
        return $this->obtenerPropietarios(TRUE);
    }
    
    /**
     * Obtiene el primer propietario de alta del apartamento actual.
     * El array devuelto tiene como clave el <b>codigo de la persona</b> y como datos:
     * <ul>
     * <li>0 - Apellidos y nombe.</li>
     * <li>1 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>3 - Orden.</li>
     * </ul>
     * 
     * @return array con formato array('codpers'=>array('nombre','date','fecha','orden))
     */
    public function getPrimerPropietarioAlta() {
        $aPro = $this->getPropietariosAlta();
        reset($aPro);
        return current($aPro);
    }
    
    /**
     * Obtiene el codigo del primer propietario de alta del apartamento actual.
     * 
     * @return int Codigo de persona.
     */
    public function getPrimerPropietarioAltaCodigo() {
        $aPro = array_keys($this->getPropietariosAlta());
        return $aPro[0];
    }
    
    /**
     * Obtiene los propietarios de baja del apartamento actual. 
     * El array devuelto tiene como clave el <b>codigo de la persona</b> y como datos:
     * <ul>
     * <li>0 - Apellidos y nombe.</li>
     * <li>1 - Fecha de baja en formato YYYY-MM-DD.</li>
     * <li>2 - Fecha de baja en formato DD-MM-YYYY.</li>
     * <li>3 - Orden.</li>
     * </ul>
     * 
     * @return array con formato array('codpers'=>array('nombre','date','fecha','orden)...)
     */
    public function getPropietariosBaja() {
        return $this->obtenerPropietarios(FALSE);
    }
    
    /**
     * Indica si la persona indicada es o ha sido propietaria del apartamento actual.
     * 
     * @param int $per Codigo de persona.
     * @return boolean TRUE si es propietario o FALSE si no lo es.
     */
    public function esPropietario($per) {
        return array_key_exists($per, $this->aPropietarios);
        
    }
    
    /**
     * Indica si la persona indicada es propietaria del apartamento actual.
     * 
     * @param int $per Codigo de persona.
     * @return boolean TRUE si es propietario o FALSE si no lo es.
     */
    public function esPropietarioAlta($per) {
        $aPro = $this->aPropietarios;
        return (isset($aPro[$per]) && !$aPro[$per][1]) ? TRUE : FALSE;
    }
    
    /**
     * Asigna los datos de un propietario.
     * 
     * @param int $per Codigo de persona.
     * @param date $fec Fecha de baja en formato YYYY-MM-DD o DD-MM-YYYY.
     * @param int $ord Orden.
     */
    public function setPropietario($per, $fec, $ord) {  
        if($per) {
            $oPer = new Persona($per);
            $nomb = $oPer->getNombreCompleto();
            $date = $this->convertirFecha($fec, TRUE);
            $fech = $this->convertirFecha($fec, FALSE);
            $this->aPropietarios[$per] = array($nomb, $date, $fech, $ord);
        }
    }
    
    /**
     * Guarda los datos de los propietarios del apartamento.
     * 
     * @return boolean Devuelve TRUE si todo es correcto y FALSE si hay algun error.
     */
    public function grabarPropietarios() {
        $bRes = TRUE;
        $iApa = $this->getCodigo();
        $aPro = $this->aPropietarios;
        foreach ($aPro as $per => $aDatos) {
            $fec = (!$aDatos[1]) ? 'NULL' : "'" . $this->convertirFecha($aDatos[1], TRUE) . "'";
            $ord = $aDatos[3];
            $res = Funciones::gEjecutarSQL("REPLACE INTO PROPIETARIOS (CODAPAR,CODPERS,BAJA,ORDEN) VALUES ('$iApa','$per',$fec,'$ord')");
            $bRes = (!$res) ? FALSE : $bRes;
        }
        $this->cargarPropietarios();
        return $bRes;
    }
    
    /**
     * Elimina un propietario del apartamento.
     * 
     * @param int $per Codigo de persona.
     * @return boolean Devuelve TRUE si todo es correcto y FALSE si hay algun error.
     */
    public function eliminarPropietario($per) {
        $iApa = $this->getCodigo();
        $res = Funciones::gEjecutarSQL("DELETE FROM PROPIETARIOS WHERE CODAPAR='$iApa' AND CODPERS='$per'");
        if($res) {
            $this->cargarPropietarios();
            return TRUE;
        }
        return FALSE;
    }
}
