<?php

/**
 * Clase Votaciones.
 */

/**
 * La clase Votaciones guarda el contenido de todas las votaciones realizadas.
 * Permite obtener datos basicos y realizar busquedas en las votaciones guardadas.
 *
 * @author xavi
 */
class Votaciones {
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Guarda las votaciones realizadas.
     * Es un array que tiene como clave la <b>fecha</b> de la votacion que contiene
     * varios arrays cuyas claves son los <b>numeros</b> de la votacion y su contenido
     * la <b>fecha</b> en formato ISO.
     * 
     * @var array del tipo array('fecha'=>array('numvot'=>'fechaISO'...)...) 
     */
    private $aVotaciones;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase.
     */
    public function __construct() {
        $this->cargarVotaciones();
    }
    
    /**
     * Carga los datos de las votaciones.
     */
    private function cargarVotaciones() {
        $res = Funciones::gEjecutarSQL("SELECT DISTINCT FECHA,DATE_FORMAT(FECHA,'%d-%m-%Y') AS FECHAISO,NUMVOT FROM VOTOS ORDER BY FECHA DESC,NUMVOT");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $this->aVotaciones[$aRow['FECHA']][$aRow['NUMVOT']] = $aRow['FECHAISO'];
        }
        $res->closeCursor(); 
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    /**
     * Recarga los datos de las votaciones.
     */
    public function recargar() {
        $this->cargarVotaciones();
    }

    /**
     * Obtiene los datos de todas las votaciones.
     * 
     * @return array del tipo array('fecha'=>array('numvot'=>'fechaISO'...)...) 
     */
    public function getVotaciones() {
        return $this->aVotaciones;
    }
    
    /**
     * Obtiene los años de las diferentes votaciones.
     * 
     * @return array del tipo array('año1','año2','año3'...)
     */
    public function getVotacionesAnyos() {
        $aAny = array();
        $sAny = "";
        foreach (array_keys($this->aVotaciones) as $date) {
            $any = substr($date, 0, 4);
            if ($any != $sAny) {
                $aAny[] = $any;
                $sAny = $any;
            }
        }
        return $aAny;
    }
    
    /**
     * Obtiene la fecha de la ultima votacion realizada.
     * 
     * @return fecha en formato YYYY-MM-DD.
     */
    public function getUltimaFechaVotacion() {
        $aAnys = array_keys($this->aVotaciones);
        reset($aAnys);
        return current($aAnys);
    }
    
    /**
     * Comprueba si una votacion ya existe.
     * 
     * @param date $fecha Fecha de la votacion en cuaquier formato.
     * @param int $num Numero de votacion.
     * @return boolean devuelve TRUE si existe y FALSE si no existe.
     */
    public function existeVotacion($fecha, $num=1) {
        $bVot = FALSE;
        $date = Funciones::gConvertirFecha($fecha, TRUE); 
        if (array_key_exists($date, $this->aVotaciones)) {
            $aVot = $this->aVotaciones[$date];
            $bVot = array_key_exists($num, $aVot);
        }
        return $bVot;
    }
    
    /**
     * Obtiene el numero de la ultima votacion realizada en una fecha.
     * 
     * @param date $fecha Fecha de la votacion en cuaquier formato.
     * @return int Numero de la ultima votacion.
     */
    public function getUltimaVotacion($fecha) {
        $num  = 0;
        $date = Funciones::gConvertirFecha($fecha, TRUE);
        $aVot = array_keys($this->aVotaciones[$date]);
        foreach ($aVot as $numvot) {
            $num = ($numvot > $num) ? $numvot : $num;
        }
        return $num;
    }
}
