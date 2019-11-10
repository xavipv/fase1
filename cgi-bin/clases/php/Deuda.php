<?php

/**
 * Clase Deuda.
 */

/**
 * La clase Deuda gestiona la deuda de un apartamento en una fecha determinada.
 *
 * @author xavi
 */
class Deuda {
    
    /**
     * Fecha de la deuda.
     * 
     * @var date en formato YYYY-MM-DD. 
     */
    private $fecha;
    
    /**
     * Codigo del apartamento.
     * 
     * @var int Codigo de apartamento. 
     */
    private $codapar;
    
    /**
     * Valor de la deuda ordinaria.
     * 
     * @var int Deuda ordinaria. 
     */
    private $ordinaria;
    
    /**
     * Valor de la deuda extraordinaria.
     * 
     * @var int Deuda extraordinaria. 
     */
    private $extraordinaria;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase.
     * 
     * @param date $fecha Fecha en cualquier formato.
     * @param int $codapar Codigo de apartamento.
     */
    public function __construct($fecha, $codapar) {
        $this->cargar(Funciones::gConvertirFecha($fecha, TRUE), $codapar);
    }
    
    /**
     * Carga los datos de la deuda.
     * 
     * @param date $fecha Fecha en formato YYYY-MM-DD.
     * @param int $codapar Codigo de apartamento.
     */
    private function cargar($fecha, $codapar) { 
        $this->cargarOmision($fecha, $codapar);
        if($fecha) {
            $rRes = Funciones::gEjecutarSQL("SELECT FECHA,CODAPAR,ORDINARIA,EXTRAORDINARIA FROM DEUDAS WHERE FECHA='$fecha' AND CODAPAR='$codapar'");
            while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
                $this->fecha = $aRow['FECHA'];
                $this->codapar = $aRow['CODAPAR'];
                $this->ordinaria = $aRow['ORDINARIA'];
                $this->extraordinaria = $aRow['EXTRAORDINARIA'];
            }
            $rRes->closeCursor(); 
        }
    }
    
    /**
     * Carga los datos por omision de una deuda.
     * 
     * @param date $fecha Fecha en formato YYYY-MM-DD.
     * @param int $codapar Codigo de apartamento.
     */
    private function cargarOmision($fecha, $codapar) {
        $this->fecha = $fecha;
        $this->codapar = $codapar;
        $this->ordinaria = 0;
        $this->extraordinaria = 0;
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    /**
     * Obtiene la fecha de la deuda.
     * 
     * @return date Fecha en formato YYYY-MM-DD.
     */
    public function getFecha() {
        return $this->fecha;
    }
    
    /**
     * Obtiene la fecha de la deuda.
     * 
     * @return date Fecha en formato DD-MM-YYYY.
     */
    public function getFechaISO() {
        return Funciones::gConvertirFecha($this->fecha, FALSE);
    }
    
    /**
     * Obtiene el codigo del apartamento.
     * 
     * @return int Codigo del apartamento.
     */
    public function getApartamento() {
        return $this->codapar;
    }
    
    /**
     * Asigna la deuda ordinaria.
     * 
     * @param int $eur Deuda ordinaria.
     */
    public function setOrdinaria($eur) {
        $this->ordinaria = $eur;
    }
    
    /**
     * Obtiene el valor de la deuda ordinaria.
     * 
     * @return int Deuda ordinaria.
     */
    public function getOrdinaria() {
        return $this->ordinaria;
    }
    
    /**
     * Asigna la deuda extraordinaria.
     * 
     * @param int $eur Deuda extraordinaria.
     */
    public function setExtraordinaria($eur) {
        $this->extraordinaria = $eur;
    }
    
    /**
     * Obtiene el valor de la deuda extraordinaria.
     * 
     * @return int Deuda extraordinaria.
     */
    public function getExtraordinaria() {
        return $this->extraordinaria;
    }
    
    /**
     * Obtiene el total de la deuda.
     * 
     * @return int Total de la deuda.
     */
    public function getDeuda() {
        return $this->ordinaria + $this->extraordinaria;
    }
    
    /**
     * Guarda los datos de la deuda en la base de datos.
     * 
     * @return boolean Devuelve TRUE si todo ha ido bien o FALSE si algo ha fallado.
     */
    public function grabar() {
        $fecha = $this->fecha;
        $apart = $this->codapar;
        $ordin = $this->ordinaria;
        $extra = $this->extraordinaria;
        
        return Funciones::gEjecutarSQL("REPLACE INTO DEUDAS (FECHA,CODAPAR,ORDINARIA,EXTRAORDINARIA) VALUES ('$fecha','$apart','$ordin','$extra')");
    }
    
    /**
     * Elimina los datos de la deuda de la base de datos.
     * 
     * @return boolean Devuelve TRUE si todo ha ido bien o FALSE si algo ha fallado.
     */
    public function eliminar() {
        $fecha = $this->fecha;
        $apart = $this->codapar;
        
        return Funciones::gEjecutarSQL("DELETE FROM DEUDAS WHERE FECHA='$fecha' AND CODAPAR='$apart'");
    }
}
