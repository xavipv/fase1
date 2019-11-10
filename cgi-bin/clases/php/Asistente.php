<?php

/**
 * Clase Asistente.
 */

/**
 * La clase Asistente permite interactuar con los datos de un asistente a una Junta.
 * Permite agregar y obtener datos de los asistentes o sus representados.
 *
 * @author xavi
 */
class Asistente {
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Fecha de la Junta General.
     * 
     * @var date Fecha del tipo YYYY-MM-DD. 
     */
    private $fecha;
    
    /**
     * Codigo del apartamento al que se representa.
     * 
     * @var int Codigo de apartamento. 
     */
    private $apartamento;
    
    /**
     * Codigo de la persona que asiste a la Junta.
     * 
     * @var int Codigo de persona. 
     */
    private $persona;
    
    /**
     * Indica si es el propietario o esta representado.
     * 
     * @var string Sera 'S' si esta representado o 'N' si es el propietario. 
     */
    private $representado;
    
    /**
     * Indica si puede votar en la Junta.
     * 
     * @var string Sera 'S' si tiene voto o 'N' si no puede votar. 
     */
    private $voto;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase.
     * 
     * @param date $fecha Fecha de la Junta en cualquier formato.
     * @param int $apar Apartamento al que se representa.
     */
    public function __construct($fecha, $apar) {
        if ($fecha && $apar) {
            $this->cargarAsistente(Funciones::gConvertirFecha($fecha, TRUE), $apar);
        }
    }
    
    //--- METODOS PRIVADOS ---------------------------------------------------//
    
    /**
     * Carga los datos del asistente.
     * 
     * @param date $fecha Fecha en formato YYYY-MM-DD.
     * @param int $apar Codigo de apartamento.
     */
    private function cargarAsistente($fecha, $apar) { 
        $this->cargarAsistenteOmision($fecha, $apar);
        if($fecha && $apar) {
            $rRes = Funciones::gEjecutarSQL("SELECT CODPERS,REPRESENTADO,VOTO FROM ASISTENTES WHERE FECHA='$fecha' AND CODAPAR='$apar'");
            while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
                $this->fecha = $fecha;
                $this->apartamento = $apar;
                $this->persona = $aRow['CODPERS'];
                $this->representado = $aRow['REPRESENTADO'];
                $this->voto = $aRow['VOTO'];
            }
            $rRes->closeCursor(); 
        }
    }
    
    /**
     * Carga los datos por omision de un asistente.
     * 
     * @param date $fecha Fecha en formato YYYY-MM-DD.
     * @param int $apar Codigo de apartamento.
     */
    private function cargarAsistenteOmision($fecha, $apar) {
        $this->fecha = $fecha;
        $this->apartamento = $apar;
        $this->persona = 0;
        $this->representado = '';
        $this->voto = '';
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    /**
     * Indica si ya existe un asistente representando a un apartamento en una Junta.
     * 
     * @param date $fecha Fecha de la Junta en cualquier formato.
     * @param int $apar Codigo de apartamento.
     * @param int $per Codigo de persona.
     * @return boolean Devuelve TRUE si ya existe o FALSE si no existe.
     */
    public function existeAsistente($fecha, $apar, $per) { 
        $num = 0;
        if($fecha && $apar) {
            $date = Funciones::gConvertirFecha($fecha, TRUE);
            $rRes = Funciones::gEjecutarSQL("SELECT COUNT(*) AS NUM FROM ASISTENTES WHERE FECHA='$date' AND CODAPAR='$apar' AND CODPERS='$per'");
            while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
                $num = $aRow['NUM'];
            }
            $rRes->closeCursor(); 
        }
        return ($num) ? TRUE : FALSE;
    }
    
    /**
     * Obtiene la fecha de la Junta.
     * 
     * @return date en formato YYYY-MM-DD.
     */
    public function getFecha() {
        return $this->fecha;
    }
    
    /**
     * Obtiene el codigo del apartamento.
     * 
     * @return int Codigo del apartamento.
     */
    public function getApartamento() {
        return $this->apartamento;
    }
    
    /**
     * Asigna el codigo de la persona.
     * 
     * @param int $per Codigo de persona.
     */
    public function setPersona($per) {
        $this->persona = (!$per) ? 0 : $per;
    }
    
    /**
     * Obtiene el codigo de la persona.
     * 
     * @return int $per Codigo de la persona.
     */
    public function getPersona() {
        return $this->persona;
    }
    
    /**
     * Asigna si el asistente es el propietario o esta representado.
     * 
     * @param string $rep Si es el propietario 'N' y si esta reprsentado 'S'.
     */
    public function setRepresentado($rep='N') {
        $this->representado = (strtoupper($rep) == 'S') ? 'S' : 'N';
    }
    
    /**
     * Obtiene si el asistente es el propietario o esta representado.
     * 
     * @return string Si es el propietario 'N' y si esta reprsentado 'S'.
     */
    public function getRepresentado() {
        return $this->representado;
    }
    
    /**
     * Asigna si el asistente tiene derecho a voto o no.
     * 
     * @param string $vot Si tiene derecho a voto 'S', en caso contrario 'N'.
     */
    public function setVoto($vot='S') {
        $this->voto = (strtoupper($vot) == 'N') ? 'N' : 'S';
    }
    
    /**
     * Obtiene si el asistente tiene derecho a voto o no.
     * 
     * @return string Si tiene derecho a voto 'S', en caso contrario 'N'.
     */
    public function getVoto() {
        return $this->voto;
    } 
    
    /**
     * Graba los datos del asistente.
     * 
     * @return boolean Devuelve TRUE si todo ha sido correcto o FALSE si ha habido algun fallo.
     */
    public function grabar() {
        $fec = $this->fecha;
        $apa = $this->apartamento;
        $per = $this->persona;
        $rep = $this->representado;
        $vot = $this->voto;
        
        if (!$per) {
            // Eliminar asistencia.
            return Funciones::gEjecutarSQL("DELETE FROM ASISTENTES WHERE FECHA='$fec' AND CODAPAR='$apa'");
        } else {
            // Modificar asistencia.
            return Funciones::gEjecutarSQL("REPLACE INTO ASISTENTES (FECHA,CODAPAR,CODPERS,REPRESENTADO,VOTO) VALUES ('$fec','$apa','$per','$rep','$vot')");
        }
        return FALSE;
    }
}
