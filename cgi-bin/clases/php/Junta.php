<?php

/**
 * Clase Junta.
 */

/**
 * La clase Junta permite interactuar con los datos de una Junta General.
 * Permite agregar y obtener los datos generales de una Junta General.
 *
 * @author xavi
 */
class Junta {
    
    //--- CONSTANTES ---------------------------------------------------------//
    
    /**
     * Tipos posibles de las Juntas Generales.
     */
    const TIPOS = array('O'=>'Ordinaria','E'=>'Extraordinaria','I'=>'Informativa', 'G'=>'Garajes');
    
    /**
     * Convocatoria primera o segunda.
     */
    const CONVOCATORIAS = array('1'=>'Primera', '2'=>'Segunda');
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Fecha de la Junta.
     * 
     * @var date Fecha en formato YYYY-MM-DD. 
     */
    private $fecha;
    
    /**
     * Tipo de Junta General.
     * <ul>
     * <li>O - Ordinaria.</li>
     * <li>E - Extraordinaria.</li>
     * <li>I - Informativa.</li>
     * <li>G - Garajes.</li>
     * </ul>
     * 
     * @var string Tipo de Junta O/E/I. 
     */
    private $tipo;
    
    /**
     * Convocatoria en la que se realiza la Junta.
     * <ul>
     * <li>1 - Primera.</li>
     * <li>2 - Segunda.</li>
     * </ul>
     * 
     * @var int Numero de convocatoria 1 o 2. 
     */
    private $convocatoria;
    
    /**
     * Hora a la que comienza la Junta.
     * 
     * @var time Hora de comienzo. 
     */
    private $hora;
    
    /**
     * Codigo del presidente.
     * 
     * @var int Codigo de persona. 
     */
    private $presidente;
    
    /**
     * Codigo del vicepresidente primero.
     * 
     * @var int Codigo de persona. 
     */
    private $vicepresidente1;
    
    /**
     * Codigo del vicepresidente segundo.
     * 
     * @var int Codigo de persona. 
     */
    private $vicepresidente2;
    
    /**
     * Codigo del vocal primero.
     * 
     * @var int Codigo de persona. 
     */
    private $vocal1;
    
    /**
     * Codigo del vocal segundo.
     * 
     * @var int Codigo de persona. 
     */
    private $vocal2;
    
    /**
     * Codigo del vocal tercero.
     * 
     * @var int Codigo de persona. 
     */
    private $vocal3;
    
    /**
     * Codigo del vocal cuarto.
     * 
     * @var int Codigo de persona. 
     */
    private $vocal4;
    
    /**
     * Codigo del secretario.
     * 
     * @var int Codigo de persona. 
     */
    private $secretario;
    
    /**
     * Codigo de la administracion.
     * 
     * @var int Codigo de administracion. 
     */
    private $administracion;
    
    /**
     * Notas sobre la Junta.
     * 
     * @var string Notas. 
     */
    private $notas;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase.
     * 
     * @param date $fecha Fecha en cualquier formato.
     */
    public function __construct($fecha='') {
        $this->cargarJunta(Funciones::gConvertirFecha($fecha,TRUE));
    }
    
    /**
     * Carga los datos de la Junta.
     * 
     * @param date $fecha Fecha en formato YYYY-MM-DD.
     */
    private function cargarJunta($fecha='') { 
        $this->cargarJuntaOmision($fecha);
        if($fecha) {
            $rRes = Funciones::gEjecutarSQL("SELECT FECHA,TIPO,CONVOCATORIA,HORA,PRESIDENTE,VICEPRESIDENTE1,VICEPRESIDENTE2,VOCAL1,VOCAL2,VOCAL3,VOCAL4,SECRETARIO,ADMINISTRACION,NOTAS FROM JUNTAS WHERE FECHA='$fecha'");
            while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
                $this->fecha = $aRow['FECHA'];
                $this->tipo = $aRow['TIPO'];
                $this->convocatoria = $aRow['CONVOCATORIA'];
                $this->hora = $aRow['HORA'];
                $this->presidente = $aRow['PRESIDENTE'];
                $this->vicepresidente1 = $aRow['VICEPRESIDENTE1'];
                $this->vicepresidente2 = $aRow['VICEPRESIDENTE2'];
                $this->vocal1 = $aRow['VOCAL1'];
                $this->vocal2 = $aRow['VOCAL2'];
                $this->vocal3 = $aRow['VOCAL3'];
                $this->vocal4 = $aRow['VOCAL4'];
                $this->secretario = $aRow['SECRETARIO'];
                $this->administracion = $aRow['ADMINISTRACION'];
                $this->notas = $aRow['NOTAS'];
            }
            $rRes->closeCursor(); 
        }
    }
    
    /**
     * Carga los datos por omision de una Junta.
     * 
     * @param date $fecha Fecha en formato YYYY-MM-DD.
     */
    private function cargarJuntaOmision($fecha) {
        $this->fecha = $fecha;
        $this->tipo = '';
        $this->convocatoria = '';
        $this->hora = '';
        $this->presidente = 0;
        $this->vicepresidente1 = 0;
        $this->vicepresidente2 = 0;
        $this->vocal1 = 0;
        $this->vocal2 = 0;
        $this->vocal3 = 0;
        $this->vocal4 = 0;
        $this->secretario = 0;
        $this->administracion = 0;
        $this->notas = '';
    }
    
    /**
     * Asigna una fecha a la Junta.
     * 
     * @param date $fec Fecha en cualquier formato.
     */
    public function setFecha($fec) {
        if($fec) {
            $this->fecha = Funciones::gConvertirFecha($fec, TRUE);
        }
    }
    
    /**
     * Obtiene la fecha de la Junta en formato base.
     * 
     * @return date Fecha en formato YYYY-MM-DD.
     */
    public function getFecha() {
        return $this->fecha;
    }
    
    /**
     * Obtiene la fecha de la Junta en formato ISO.
     * 
     * @return date Fecha en formato DD-MM-YYYY.
     */
    public function getFechaISO() {
        return Funciones::gConvertirFecha($this->fecha, FALSE);
    }
    
    /**
     * Asigna el tipo de Junta General.
     * <ul>
     * <li>O - Ordinaria.</li>
     * <li>E - Extraordinaria.</li>
     * <li>I - Informativa.</li>
     * <li>G - Garajes.</li>
     * </ul>
     * 
     * @param string $tip Tipo de Junta O/E/I. 
     */
    public function setTipo($tip) {
        if(!$tip || array_key_exists(strtoupper($tip), self::TIPOS)) {
            $this->tipo = $tip;
        }
    }
    
    /**
     * Asigna el tipo de Junta General.
     * <ul>
     * <li>O - Ordinaria.</li>
     * <li>E - Extraordinaria.</li>
     * <li>I - Informativa.</li>
     * <li>G - Garajes.</li>
     * </ul>
     * 
     * @param boolean $bTxt Si es TRUE devuelve el texto (Ordinaria) y si es FALSE la inicial (O).
     * @return string Tipo de Junta.
     */
    public function getTipo($bTxt=FALSE) {
        return ($bTxt) ? self::TIPOS[$this->tipo] : $this->tipo;
    }
    
    /**
     * Obtiene los tipos de Juntas Generales que hay.
     * 
     * @return array del tipo array('O'=>'Ordinaria','E'=>'Extraordinaria','I'=>'Informativa','G'=>'Garajes')
     */
    public function getTipos() {
        return self::TIPOS;
    }
    
    /**
     * Asigna la convocatoria en la que se inicia la Junta.
     * <ul>
     * <li>1 - Primera.</li>
     * <li>2 - Segunda.</li>
     * </ul>
     * 
     * @param int $con Numero de convocatoria.
     */
    public function setConvocatoria($con) {        
        if (!$con || array_key_exists($con, self::CONVOCATORIAS)) {
            $this->convocatoria = $con;
        }
    }
    
    /**
     * Obtiene la convocatoria en que se ha realizado la Junta.
     * <ul>
     * <li>1 - Primera.</li>
     * <li>2 - Segunda.</li>
     * </ul>
     * 
     * @param boolean $bTxt Si es TRUE devuelve el texto (Primera) y si es FALSE el numero (1).
     * @return string Numero de convocatoria.
     */
    public function getConvocatoria($bTxt=FALSE) {
        return ($bTxt) ? self::CONVOCATORIAS[$this->convocatoria] : $this->convocatoria;
    }
    
    /**
     * Obtiene las convocatorias posibles.
     * 
     * @return array del tipo array('1'=>'Primera', '2'=>'Segunda')
     */
    public function getConvocatorias() {
        return self::CONVOCATORIAS;
    }
    
    /**
     * Asigna la hora en que comienza la Junta.
     * 
     * @param time $hora Hora en formato HH:MM
     */
    public function setHora($hora) {
        $this->hora = $hora;
    }
    
    /**
     * Obtiene la hora de la Junta.
     * 
     * @return time Hora en formato HH:MM
     */
    public function getHora() {
        return substr($this->hora, 0, 5);
    }
    
    /**
     * Asigna el presidente de la Junta.
     * 
     * @param int $per Codigo de persona.
     */
    public function setPresidente($per) {
        $this->presidente = $per;
    }
    
    /**
     * Obtiene el presidente de la Junta.
     * 
     * @param boolean $bPer Si es TRUE devuelve un objeto de la clase Persona, si es FALSE el codigo de persona.
     * @return mixed Instancia de Persona o codigo de persona.
     */
    public function getPresidente($bPer=FALSE) {
        return ($bPer) ? new Persona($this->presidente) : $this->presidente;
    }
    
    /**
     * Asigna el vicepresidente primero de la Junta.
     * 
     * @param int $per Codigo de persona.
     */
    public function setVicepresidente1($per) {
        $this->vicepresidente1 = $per;
    }
    
    /**
     * Obtiene el vicepresidente primero de la Junta.
     * 
     * @param boolean $bPer Si es TRUE devuelve un objeto de la clase Persona, si es FALSE el codigo de persona.
     * @return mixed Instancia de Persona o codigo de persona.
     */
    public function getVicepresidente1($bPer=FALSE) {
        return ($bPer) ? new Persona($this->vicepresidente1) : $this->vicepresidente1;
    }
    
    /**
     * Asigna el vicepresidente segundo de la Junta.
     * 
     * @param int $per Codigo de persona.
     */
    public function setVicepresidente2($per) {
        $this->vicepresidente2 = $per;
    }
    
    /**
     * Obtiene el vicepresidente segundo de la Junta.
     * 
     * @param boolean $bPer Si es TRUE devuelve un objeto de la clase Persona, si es FALSE el codigo de persona.
     * @return mixed Instancia de Persona o codigo de persona.
     */
    public function getVicepresidente2($bPer=FALSE) {
        return ($bPer) ? new Persona($this->vicepresidente2) : $this->vicepresidente2;
    }
    
    /**
     * Asigna el vocal 1 de la Junta.
     * 
     * @param int $per Codigo de persona.
     */
    public function setVocal1($per) {
        $this->vocal1 = $per;
    }
    
    /**
     * Obtiene el vocal 1 de la Junta.
     * 
     * @param boolean $bPer Si es TRUE devuelve un objeto de la clase Persona, si es FALSE el codigo de persona.
     * @return mixed Instancia de Persona o codigo de persona.
     */
    public function getVocal1($bPer=FALSE) {
        return ($bPer) ? new Persona($this->vocal1) : $this->vocal1;
    }
    
    /**
     * Asigna el vocal 2 de la Junta.
     * 
     * @param int $per Codigo de persona.
     */
    public function setVocal2($per) {
        $this->vocal2 = $per;
    }
    
    /**
     * Obtiene el vocal 2 de la Junta.
     * 
     * @param boolean $bPer Si es TRUE devuelve un objeto de la clase Persona, si es FALSE el codigo de persona.
     * @return mixed Instancia de Persona o codigo de persona.
     */
    public function getVocal2($bPer=FALSE) {
        return ($bPer) ? new Persona($this->vocal2) : $this->vocal2;
    }
    
    /**
     * Asigna el vocal 3 de la Junta.
     * 
     * @param int $per Codigo de persona.
     */
    public function setVocal3($per) {
        $this->vocal3 = $per;
    }
    
    /**
     * Obtiene el vocal 3 de la Junta.
     * 
     * @param boolean $bPer Si es TRUE devuelve un objeto de la clase Persona, si es FALSE el codigo de persona.
     * @return mixed Instancia de Persona o codigo de persona.
     */
    public function getVocal3($bPer=FALSE) {
        return ($bPer) ? new Persona($this->vocal3) : $this->vocal3;
    }
    
    /**
     * Asigna el vocal 4 de la Junta.
     * 
     * @param int $per Codigo de persona.
     */
    public function setVocal4($per) {
        $this->vocal4 = $per;
    }
    
    /**
     * Obtiene el vocal 4 de la Junta.
     * 
     * @param boolean $bPer Si es TRUE devuelve un objeto de la clase Persona, si es FALSE el codigo de persona.
     * @return mixed Instancia de Persona o codigo de persona.
     */
    public function getVocal4($bPer=FALSE) {
        return ($bPer) ? new Persona($this->vocal4) : $this->vocal4;
    }
    
    /**
     * Asigna el secretario de la Junta.
     * 
     * @param int $per Codigo de persona.
     */
    public function setSecretario($per) {
        $this->secretario = $per;
    }
    
    /**
     * Obtiene el secretario de la Junta.
     * 
     * @param boolean $bPer Si es TRUE devuelve un objeto de la clase Persona, si es FALSE el codigo de persona.
     * @return mixed Instancia de Persona o codigo de persona.
     */
    public function getSecretario($bPer=FALSE) {
        return ($bPer) ? new Persona($this->secretario) : $this->secretario;
    }
    
    /**
     * Asigna la administracion de la Juna.
     * 
     * @param int $adm Codigo de administracion.
     */
    public function setAdministracion($adm) {
        $this->administracion = $adm;
    }
    
    /**
     * Obtiene la administracion de la Junta.
     * 
     * @return int Codigo de administracion.
     */
    public function getAdministracion() {
        return $this->administracion;
    }
    
    /**
     * Asigna las notas de la Junta.
     * 
     * @param string $not Notas.
     */
    public function setNotas($not) {
        $this->notas = Funciones::gCodificar($not);
    }
    
    /**
     * Obtiene las notas de la Junta.
     * 
     * @return string Notas.
     */
    public function getNotas() {
        return Funciones::gDecodificar($this->notas);
    }
    
    /**
     * Graba los datos de la Junta General.
     * 
     * @return boolean Devuelve TRUE si todo ha ido bien o FALSE si ha fallado algo.
     */
    public function grabar() {
        $fec = $this->fecha;
        $tip = $this->tipo;
        $con = $this->convocatoria;
        $hor = $this->hora;
        $pre = ($this->presidente) ? $this->presidente : 0;
        $vi1 = ($this->vicepresidente1) ? $this->vicepresidente1 : 0;
        $vi2 = ($this->vicepresidente2) ? $this->vicepresidente2 : 0;
        $vo1 = ($this->vocal1) ? $this->vocal1 : 0;
        $vo2 = ($this->vocal2) ? $this->vocal2 : 0;
        $vo3 = ($this->vocal3) ? $this->vocal3 : 0;
        $vo4 = ($this->vocal4) ? $this->vocal4 : 0;
        $sec = ($this->secretario) ? $this->secretario : 0;
        $adm = ($this->administracion) ? $this->administracion : 0;
        $not = $this->notas;
        
        return Funciones::gEjecutarSQL("REPLACE INTO JUNTAS (FECHA,TIPO,CONVOCATORIA,HORA,PRESIDENTE,VICEPRESIDENTE1,VICEPRESIDENTE2,VOCAL1,VOCAL2,VOCAL3,VOCAL4,SECRETARIO,ADMINISTRACION,NOTAS) VALUES ('$fec','$tip','$con','$hor','$pre','$vi1','$vi2','$vo1','$vo2','$vo3','$vo4','$sec','$adm','$not')");
    }
    
    /**
     * Elimina los datos de la Junta General.
     * 
     * @return boolean Devuelve TRUE si todo ha ido bien o FALSE si ha fallado algo.
     */
    public function eliminar() {
        $fec = $this->fecha;
        return ($fec) ? Funciones::gEjecutarSQL("DELETE FROM JUNTAS WHERE FECHA='$fec'") : FALSE;
    }
}
