<?php

/**
 * Clase Votacion.
 */

/**
 * La clase Votacion permite interactuar con los datos de una votacion.
 * Permite agregar y obtener los datos de una votacion en una Junta General.
 *
 * @author xavi
 */
class Votacion {
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Fecha de la votacion.
     * 
     * @var date
     */
    private $fecha;
    
    /**
     * Numero de votacion.
     * 
     * @var int
     */
    private $votacion;
    
    /**
     * Texto que se vota.
     * 
     * @var string 
     */
    private $texto;
    
    /**
     * Opcion 1 para votar.
     * 
     * @var string 
     */
    private $opcion1;
    
    /**
     * Opcion 2 para votar.
     * 
     * @var string 
     */
    private $opcion2;
    
    /**
     * Opcion 3 para votar.
     * 
     * @var string 
     */
    private $opcion3;
    
    /**
     * Opcion 4 para votar.
     * 
     * @var string 
     */
    private $opcion4;
    
    /**
     * El array tiene como indice el codigo del apartamento y como datos un array con:
     * <ul>
     * <li>0 - Asistente. S/N</li>
     * <li>1 - Con voto. S/N</li>
     * <li>2 - Presente. S/N</li>
     * <li>3 - Resultado 1. S/N</li>
     * <li>4 - Resultado 2. S/N</li>
     * <li>5 - Resultado 3. S/N</li>
     * <li>6 - Resultado 4. S/N</li>
     * </ul>
     * 
     * @var array del tipo array('apar'=>array('asis','vota','pres','res1','res2','res3','res4')...)
     */
    private $votos;
    
    /**
     * El array tiene como indice el codigo del apartamento y como datos un array con:
     * <ul>
     * <li>0 - Coeficiente.</li>
     * <li>1 - Coeficiente fase.</li>
     * <li>2 - Coeficiente bloque.</li>
     * </ul>
     * 
     * @var array del tipo array('apar'=>array('coe','cof','cob')...)
     */
    private $coeficientes;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase.
     * 
     * @param date $fecha Fecha de la votacion.
     * @param int $numvot Numero de votacion.
     */
    public function __construct($fecha, $numvot) {
        if($fecha && $numvot) {
            $this->cargarVotacion(Funciones::gConvertirFecha($fecha, TRUE), $numvot);
        }
    }
    
    //--- METODOS PRIVADOS ---------------------------------------------------//
    
    /**
     * Carga los datos de la votacion guardados en la base de datos.
     * 
     * @param date $fecha Fecha de la votacion.
     * @param int $numvot Numero de votacion.
     */
    private function cargarVotacion($fecha, $numvot) { 
        $this->cargarVotacionOmision($fecha, $numvot); 
        if($fecha && $numvot) {
            $rRes = Funciones::gEjecutarSQL("SELECT FECHA,NUMVOT,TEXTO,OPCION1,OPCION2,OPCION3,OPCION4 FROM VOTACIONES WHERE FECHA='$fecha' AND NUMVOT='$numvot'");
            while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
                $this->fecha = $aRow['FECHA'];
                $this->votacion = $aRow['NUMVOT'];
                $this->texto = Funciones::gDecodificar($aRow['TEXTO']);
                $this->opcion1 = Funciones::gDecodificar($aRow['OPCION1']);
                $this->opcion2 = Funciones::gDecodificar($aRow['OPCION2']);
                $this->opcion3 = Funciones::gDecodificar($aRow['OPCION3']);
                $this->opcion4 = Funciones::gDecodificar($aRow['OPCION4']);
            }
            $rRes->closeCursor(); 
            
            // Carga los votos y coeficientes.
            $this->cargarVotos($fecha, $numvot);
        }
    }
    
    /**
     * Carga los votos de la votacion actual.
     * Los arrays que se cargan tienen como indice el codigo del apartamento y como datos un array:
     * Votaciones:
     * <ul>
     * <li>0 - Asistente. S/N</li>
     * <li>1 - Con voto. S/N</li>
     * <li>2 - Presente. S/N</li>
     * <li>3 - Resultado 1. S/N</li>
     * <li>4 - Resultado 2. S/N</li>
     * <li>5 - Resultado 3. S/N</li>
     * <li>6 - Resultado 4. S/N</li>
     * </ul>
     * Coeficientes:
     * <ul>
     * <li>0 - Coeficiente.</li>
     * <li>1 - Coeficiente fase.</li>
     * <li>2 - Coeficiente bloque.</li>
     * </ul>
     * 
     * @param date $fecha Fecha de la votacion.
     * @param int $numvot Numero de votacion.
     */
    private function cargarVotos($fecha, $numvot) { 
        $aVotos = array();
        $aCoefi = array();
        if($fecha && $numvot) {
            $rRes = Funciones::gEjecutarSQL("SELECT V.CODAPAR,V.ASISTE,V.VOTA,V.PRESENTE,V.RESULTADO1,V.RESULTADO2,V.RESULTADO3,V.RESULTADO4,A.COEFICIENTE,A.COEFICIENTEFASE,A.COEFICIENTEBLOQ FROM VOTOS V LEFT JOIN APARTAMENTOS A ON A.CODAPAR=V.CODAPAR WHERE V.FECHA='$fecha' AND V.NUMVOT='$numvot' ORDER BY V.CODAPAR");
            while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
                $aVotos[$aRow['CODAPAR']] = array($aRow['ASISTE'],$aRow['VOTA'],$aRow['PRESENTE'],$aRow['RESULTADO1'],$aRow['RESULTADO2'],$aRow['RESULTADO3'],$aRow['RESULTADO4']);
                $aCoefi[$aRow['CODAPAR']] = array($aRow['COEFICIENTE'],$aRow['COEFICIENTEFASE'],$aRow['COEFICIENTEBLOQ']);
            }
            $rRes->closeCursor(); 
        }
        $this->votos = $aVotos;
        $this->coeficientes = $aCoefi;
    }
    
    /**
     * Carga los datos de la votacion por omision.
     * 
     * @param date $fecha Fecha de la votacion.
     * @param int $numvot Numero de votacion.
     */
    private function cargarVotacionOmision($fecha, $numvot) {
        $this->fecha = $fecha;
        $this->votacion = $numvot;
        $this->texto = '';
        $this->opcion1 = '';
        $this->opcion2 = '';
        $this->opcion3 = '';
        $this->opcion4 = '';
        $this->votos = array();
        $this->coeficientes = array();
    }
    
    /**
     * Graba los votos de la votacion.
     * 
     * @return boolean Devuelve TRUE si todo ha ido bien o FALSE si ha habido algun error.
     */
    private function grabarVotos() {
        $bOK = TRUE;
        $fec = $this->fecha;
        $vot = $this->votacion; 
        $aVo = $this->votos;    // array('apar'=>array('asis','vota','pres','res1','res2','res3','res4')
        
        // Primero borra todos los votos anteriores, despues graba los nuevos votos.
        if (Funciones::gEjecutarSQL("DELETE FROM VOTOS WHERE FECHA='$fec' AND NUMVOT='$vot'")) {
            foreach ($aVo as $apa => $aDat) {
                $asis = $aDat[0];
                $vota = $aDat[1];
                $pres = $aDat[2];
                $res1 = $aDat[3];
                $res2 = $aDat[4];
                $res3 = $aDat[5];
                $res4 = $aDat[6];
                $b = Funciones::gEjecutarSQL("INSERT INTO VOTOS (FECHA,NUMVOT,CODAPAR,ASISTE,VOTA,PRESENTE,RESULTADO1,RESULTADO2,RESULTADO3,RESULTADO4) VALUES ('$fec','$vot','$apa','$asis','$vota','$pres','$res1','$res2','$res3','$res4')");
                $bOK = (!$b) ? FALSE : $bOK;
            }
            // Recarga los datos.
            $this->cargarVotacion($fec, $vot);
        }
        return $bOK;
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    /**
     * Obtiene la fecha de la votacion.
     * 
     * @return date en formato YYYY-MM-DD.
     */
    public function getFecha() {
        return $this->fecha;
    }
    
    /**
     * Obtiene la fecha de la votacion.
     * 
     * @return date en formato DD-MM-YYYY.
     */
    public function getFechaISO() {
        return Funciones::gConvertirFecha($this->fecha, FALSE);
    }
    
    /**
     * Obtiene el numero de votacion.
     * 
     * @return int Numero de votacion.
     */
    public function getVotacion() {
        return $this->votacion;
    }
    
    /**
     * Asigna el texto de la votacion.
     * 
     * @param string $txt Texto de la votacion.
     */
    public function setTexto($txt) {
        $this->texto = Funciones::gCodificar($txt);
    }
    
    /**
     * Obtiene el texto de la votacion.
     * 
     * @return string Texto de la votacion.
     */
    public function getTexto() {
        return $this->texto;
    }
    
    /**
     * Asigna el texto de la primera opcion para la votacion.
     * 
     * @param string $opt Texto de la opcion.
     */
    public function setOpcion1($opt) {
        $this->opcion1 = Funciones::gCodificar($opt);
    }
    
    /**
     * Obtiene el texto de la primera opcion para la votacion.
     * 
     * @return string Texto de la opcion.
     */
    public function getOpcion1() {
        return $this->opcion1;
    }
    
    /**
     * Asigna el texto de la segunda opcion para la votacion.
     * 
     * @param string $opt Texto de la opcion.
     */
    public function setOpcion2($opt) {
        $this->opcion2 = Funciones::gCodificar($opt);
    }
    
    /**
     * Obtiene el texto de la segunda opcion para la votacion.
     * 
     * @return string Texto de la opcion.
     */
    public function getOpcion2() {
        return $this->opcion2;
    }
    
    /**
     * Asigna el texto de la tercera opcion para la votacion.
     * 
     * @param string $opt Texto de la opcion.
     */
    public function setOpcion3($opt) {
        $this->opcion3 = Funciones::gCodificar($opt);
    }
    
    /**
     * Obtiene el texto de la tercera opcion para la votacion.
     * 
     * @return string Texto de la opcion.
     */
    public function getOpcion3() {
        return $this->opcion3;
    }
    
    /**
     * Asigna el texto de la cuarta opcion para la votacion.
     * 
     * @param string $opt Texto de la opcion.
     */
    public function setOpcion4($opt) {
        $this->opcion4 = Funciones::gCodificar($opt);
    }
    
    /**
     * Obtiene el texto de la cuarta opcion para la votacion.
     * 
     * @return string Texto de la opcion.
     */
    public function getOpcion4() {
        return $this->opcion4;
    }
    
    /**
     * Obtiene los resultados de la votacion actual.
     * El array tiene como indice el codigo del apartamento y como datos un array con:
     * <ul>
     * <li>0 - Asistente. S/N</li>
     * <li>1 - Con voto. S/N</li>
     * <li>2 - Presente. S/N</li>
     * <li>3 - Resultado 1. S/N</li>
     * <li>4 - Resultado 2. S/N</li>
     * <li>5 - Resultado 3. S/N</li>
     * <li>6 - Resultado 4. S/N</li>
     * </ul>
     * 
     * @var array del tipo array('apar'=>array('asis','vota','pres','res1','res2','res3','res4')...)
     */
    public function getVotos() {
        return $this->votos;
    }
    
    /**
     * Asigna el voto de un apartamento.
     * 
     * @param int $apa
     * @param string $asi Asistente. S/N.
     * @param string $vot Con voto. S/N.
     * @param string $pre Presente. S/N.
     * @param string $re1 Resultado 1. S/N.
     * @param string $re2 Resultado 2. S/N.
     * @param string $re3 Resultado 3. S/N.
     * @param string $re4 Resultado 4. S/N.
     */
    public function setVoto($apa, $asi, $vot, $pre, $re1, $re2, $re3, $re4) {
        if($apa) {
            $this->votos[$apa] = array($asi, $vot, $pre, $re1, $re2, $re3, $re4);
        }
    }
    
    /**
     * El array devuelto contiene los siguientes datos:
     * <ul>
     * <li>0 - Asistente. S/N</li>
     * <li>1 - Con voto. S/N</li>
     * <li>2 - Presente. S/N</li>
     * <li>3 - Resultado 1. S/N</li>
     * <li>4 - Resultado 2. S/N</li>
     * <li>5 - Resultado 3. S/N</li>
     * <li>6 - Resultado 4. S/N</li>
     * </ul>
     * 
     * @param int $apa Codigo de apartamento.
     * @return array del tipo array('asis','vota','pres','res1','res2','res3','res4')
     */
    public function getVoto($apa) {
        $aDat = array();
        if($apa && isset($this->votos[$apa])) {
            $aDat = $this->votos[$apa];
        }
        return $aDat;
    }
    
    /**
     * Obtiene los coeficientes de los apartamentos.
     * El array devuelto tiene como indice el numero de apartamento y como datos un array:
     * <ul>
     * <li>0 - Coeficiente.</li>
     * <li>1 - Coeficiente fase.</li>
     * <li>2 - Coeficiente bloque.</li>
     * </ul>
     * 
     * @return array del tipo array('apar'=>array('coe','cof','cob')...)
     */
    public function getCoeficientes() {
        return $this->coeficientes;
    }
    
    /**
     * Obtiene los coeficientes de un apartamento.
     * El array devuelto tiene el siguiente formato:
     * <ul>
     * <li>0 - Coeficiente.</li>
     * <li>1 - Coeficiente fase.</li>
     * <li>2 - Coeficiente bloque.</li>
     * </ul>
     * 
     * @param int $apa Codigo de apartamento.
     * @return array del tipo array('coe','cof','cob')
     */
    public function getCoeficiente($apa) {
        return $this->coeficientes[$apa];
    }

    /**
     * Obtiene las sumas de la votacion.
     * El array devuelto tiene cuatro indices de la siguiente forma:
     * <ul>
     * <li>'asis' => array($asis, $vots, $votn, $pres);</li>
     * <li>'opci' => array($opc1, $opc2, $opc3, $opc4);</li>
     * <li>'urba' => array($urb1, $urb2, $urb3, $urb4);</li>
     * <li>'fase' => array($fas1, $fas2, $fas3, $fas4);</li>
     * <li>'bloq' => array($blo1, $blo2, $blo3, $blo4);</li>
     * </ul>
     * 
     * @return array con las sumas calculadas.
     */
    public function getSumas() {
        $aVot = $this->votos;
        $aCoe = $this->coeficientes;
        
        $asis = 0; $vots = 0; $votn = 0; $pres = 0;
        $opc1 = 0; $opc2 = 0; $opc3 = 0; $opc4 = 0;
        $urb1 = 0; $urb2 = 0; $urb3 = 0; $urb4 = 0;
        $fas1 = 0; $fas2 = 0; $fas3 = 0; $fas4 = 0;
        $blo1 = 0; $blo2 = 0; $blo3 = 0; $blo4 = 0;
        
        foreach ($aVot as $apa => $aDat) {
            $asis += ($aDat[0] == 'S') ? 1 : 0;
            $vots += ($aDat[1] == 'S') ? 1 : 0;
            $votn += ($aDat[1] == 'S') ? 0 : 1;
            $pres += ($aDat[2] == 'S') ? 1 : 0;
            
            $opc1 += ($aDat[3] == 'S') ? 1 : 0;
            $opc2 += ($aDat[4] == 'S') ? 1 : 0;
            $opc3 += ($aDat[5] == 'S') ? 1 : 0;
            $opc4 += ($aDat[6] == 'S') ? 1 : 0;
            
            $urb1 += ($aDat[3] == 'S') ? $aCoe[$apa][0] : 0;
            $urb2 += ($aDat[4] == 'S') ? $aCoe[$apa][0] : 0;
            $urb3 += ($aDat[5] == 'S') ? $aCoe[$apa][0] : 0;
            $urb4 += ($aDat[6] == 'S') ? $aCoe[$apa][0] : 0;
            
            $fas1 += ($aDat[3] == 'S') ? $aCoe[$apa][1] : 0;
            $fas2 += ($aDat[4] == 'S') ? $aCoe[$apa][1] : 0;
            $fas3 += ($aDat[5] == 'S') ? $aCoe[$apa][1] : 0;
            $fas4 += ($aDat[6] == 'S') ? $aCoe[$apa][1] : 0;
            
            $blo1 += ($aDat[3] == 'S') ? $aCoe[$apa][2] : 0;
            $blo2 += ($aDat[4] == 'S') ? $aCoe[$apa][2] : 0;
            $blo3 += ($aDat[5] == 'S') ? $aCoe[$apa][2] : 0;
            $blo4 += ($aDat[6] == 'S') ? $aCoe[$apa][2] : 0;
        }
        $aSumas['asis'] = array($asis, $vots, $votn, $pres);
        $aSumas['opci'] = array($opc1, $opc2, $opc3, $opc4);
        $aSumas['urba'] = array($urb1, $urb2, $urb3, $urb4);
        $aSumas['fase'] = array($fas1, $fas2, $fas3, $fas4);
        $aSumas['bloq'] = array($blo1, $blo2, $blo3, $blo4);
        
        return $aSumas;
    }
    
    /**
     * Graba todos los datos de la votacion.
     * 
     * @return boolean Devuelve TRUE si todo ha ido bien o FALSE si ha habido algun error.
     */
    public function grabar() {
        $res = FALSE;
        $fec = $this->fecha;
        $vot = $this->votacion;
        $txt = $this->texto;
        $op1 = $this->opcion1;
        $op2 = $this->opcion2;
        $op3 = $this->opcion3;
        $op4 = $this->opcion4;
        
        if ($fec) {
            $res = Funciones::gEjecutarSQL("REPLACE INTO VOTACIONES (FECHA,NUMVOT,TEXTO,OPCION1,OPCION2,OPCION3,OPCION4) VALUES ('$fec','$vot','$txt','$op1','$op2','$op3','$op4')");
            if ($res) {
                $res = $this->grabarVotos();
            }
        }
        return $res;
    }
}
