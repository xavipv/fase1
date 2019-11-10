<?php

/**
 * Clase Acta.
 */

/**
 * La clase Acta permite interactuar con los datos de un acta determinada.
 * Permite agregar y obtener los datos relacionados con el acta de una Junta General.
 *
 * @author xavi
 */
class Acta {
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Fecha del acta.
     * 
     * @var date Fecha en formato YYYY-MM-DD. 
     */
    private $fecha;
    
    /**
     * Fecha inicial del acta. Se carga la crear la instancia y no se puede modificar.
     * Permite que se pueda cambiar la fecha del acta y no se dupliquen los datos.
     * 
     * @var date Fecha en formato YYYY-MM-DD
     */
    private $fechainicial;
    
    /**
     * Contiene los puntos de un acta.
     * El array tiene como indice el <b>codigo de punto</b> y como contenido:
     * <ul>
     * <li>0 - Texto del punto.</li>
     * <li>1 - Titulo del punto.</li>
     * <li>2 - Apartados del punto. Es un array cuyo indice es el <b>codigo del apartado</b> y como contenido:</li>
     * <ul>
     * <li>0 - Texto del apartado.</li>
     * <li>1 - Subtitulo del apartado.</li>
     * <li>2 - Texto del apartado.</li>
     * </ul>
     * </ul>
     * 
     * @var array del tipo array('pun'=>array(punto, titulo, array('apa'=>array(apart, subtit, texto)...))...)
     */
    private $puntos;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase.
     * 
     * @param date $fecha Fecha del acta en cualquier formato.
     */
    public function __construct($fecha) {
        $this->cargarActa(Funciones::gConvertirFecha($fecha, TRUE));
    }
    
    //--- METODOS PRIVADOS ---------------------------------------------------//
    
    /**
     * Carga los datos del acta guardados en la base de datos.
     * El array tiene como indice el <b>codigo de punto</b> y como contenido:
     * <ul>
     * <li>0 - Texto del punto.</li>
     * <li>1 - Titulo del punto.</li>
     * <li>2 - Apartados del punto. Es un array cuyo indice es el <b>codigo del apartado</b> y como contenido:</li>
     * <ul>
     * <li>0 - Texto del apartado.</li>
     * <li>1 - Subtitulo del apartado.</li>
     * <li>2 - Texto del apartado.</li>
     * </ul>
     * </ul>
     * 
     * @param date $date Fecha del acta en formato YYYY-MM-DD.
     */
    private function cargarActa($date) {
        $aPuntos = array();
        $this->cargarActaOmision($date);
        if($date) {
            // Obtiene los puntos del acta.
            $rRes = Funciones::gEjecutarSQL("SELECT CODPUN,PUNTO,TITULO FROM ACTAS_PUNTOS WHERE FECHA='$date' ORDER BY CODPUN");
            while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
                $iPun = $aRow['CODPUN'];
                $sPun = Funciones::gDecodificar($aRow['PUNTO']);
                $sTit = Funciones::gDecodificar($aRow['TITULO']);
                $aApa = $this->cargarApartados($date, $iPun);   // Apartados del punto actual.
                $aPuntos[$iPun] = array($sPun, $sTit, $aApa);
            }
            $rRes->closeCursor(); 
        }
        $this->puntos = $aPuntos;
    }
    
    /**
     * Carga los datos de los apartados de un punto.
     * Los apartados se cargan en un array que tiene como indice el <b>codigo de apartado</b> y como datos:
     * <ul>
     * <li>0 - Texto del apartado.</li>
     * <li>1 - Subtitulo del apartado.</li>
     * <li>2 - Texto del apartado.</li>
     * </ul>
     * 
     * @param date $date Fecha en formato YYYY-MM-DD.
     * @param int $punto Numero de punto.
     * @return array con el formato array('apar'=>array(apartado, subtitulo, texto)...)
     */
    private function cargarApartados($date, $punto) {
        $aApa = array();
        $rRes = Funciones::gEjecutarSQL("SELECT CODAPA,APARTADO,SUBTITULO,TEXTO FROM ACTAS_TEXTOS WHERE FECHA='$date' AND CODPUN='$punto' ORDER BY CODAPA");
        while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
            $iApa = $aRow['CODAPA'];
            $sApa = Funciones::gDecodificar($aRow['APARTADO']);
            $sSub = Funciones::gDecodificar($aRow['SUBTITULO']);
            $sTxt = Funciones::gDecodificar($aRow['TEXTO']);
            $aApa[$iApa] = array($sApa, $sSub, $sTxt);
        }
        $rRes->closeCursor(); 
        return $aApa;
    }
    
    /**
     * Carga los datos por omision.
     * 
     * @param date $date Fecha en formato YYYY-MM-DD.
     */
    private function cargarActaOmision($date) {
        $this->fecha = $date;
        $this->fechainicial = $date;
        $this->puntos = array();
    }
    
    /**
     * Graba los datos de los apartados de un punto.
     * 
     * @param date $fecha Fecha en formato YYYY-MM-DD.
     * @param int $punto Punto a grabar.
     * @param array $apartados del punto en formato array('apar'=>array(apart, subtit, texto)...)
     * @return boolean TRUE si todo ha ido bien y FALSE si ha fallado algo.
     */
    private function grabarApartados($fecha, $punto, $apartados) {
        $bOK = TRUE;
        foreach ($apartados as $iApa => $aApart) {
            $sApa = $aApart[0];
            $sSub = $aApart[1];
            $sTxt = $aApart[2];
            if ($sTxt) {
                $bOK = (Funciones::gEjecutarSQL("REPLACE INTO ACTAS_TEXTOS (FECHA,CODPUN,CODAPA,APARTADO,SUBTITULO,TEXTO) VALUES ('$fecha','$punto','$iApa','$sApa','$sSub','$sTxt')")) ? $bOK : FALSE;
            }
        }
        return $bOK;
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    /**
     * Obtiene la fecha del acta en formato YYYY-MM-DD.
     * 
     * @return date Fecha en formato YYYY-MM-DD.
     */
    public function getFecha() {
        return $this->fecha;
    }
    
    /**
     * Asigna una nueva fecha al acta.
     * 
     * @param date $fecha Fecha en cualquier formato.
     */
    public function setFecha($fecha) {
        if (trim($fecha)) {
            $this->fecha = Funciones::gConvertirFecha($fecha, TRUE);
        }
    }

    /**
     * Obtiene la fecha del acta en formato DD-MM-YYYY.
     * 
     * @return date Fecha en formato DD-MM-YYYY.
     */
    public function getFechaIso() {
        return Funciones::gConvertirFecha($this->fecha, FALSE);
    }
    
    /**
     * Obtiene la fecha inicial con la que se ha creado la instancia del Acta.
     * 
     * @return date Fecha en formato YYYY-MM-DD.
     */
    public function getFechaInicial() {
        return $this->fechainicial;
    }
    
    /**
     * Obtiene el contenido de todos los puntos del acta.
     * El array tiene como indice el <b>codigo de punto</b> y como contenido:
     * <ul>
     * <li>0 - Texto del punto.</li>
     * <li>1 - Titulo del punto.</li>
     * <li>2 - Apartados del punto. Es un array cuyo indice es el <b>codigo del apartado</b> y como contenido:</li>
     * <ul>
     * <li>0 - Texto del apartado.</li>
     * <li>1 - Subtitulo del apartado.</li>
     * <li>2 - Texto del apartado.</li>
     * </ul>
     * </ul>
     * 
     * @return array del tipo array('pun'=>array(punto, titulo, array('apa'=>array(apart, subtit, texto)...))...)
     */
    public function getPuntos() {
        return $this->puntos;
    }
    
    /**
     * Obtiene el contenido de un punto del acta.
     * El array devuelto contiene los siguientes datos:
     * <ul>
     * <li>0 - Texto del punto.</li>
     * <li>1 - Titulo del punto.</li>
     * <li>2 - Apartados del punto. Es un array cuyo indice es el <b>codigo del apartado</b> y como contenido:</li>
     * <ul>
     * <li>0 - Texto del apartado.</li>
     * <li>1 - Subtitulo del apartado.</li>
     * <li>2 - Texto del apartado.</li>
     * </ul>
     * </ul>
     * 
     * @param int $pun Numero del punto.
     * @return array del tipo array(punto, titulo, array('apa'=>array(apart, subtit, texto)...))
     */
    public function getPunto($pun) {
        return $this->puntos[$pun];
    }
    
    /**
     * Asigna la letra o numero del punto indicado.
     * 
     * @param int $pun Numero de punto.
     * @param string $txt Letra o numero del punto.
     */
    public function setPuntoTxt($pun, $txt) {
        $this->puntos[$pun][0] = Funciones::gCodificar($txt);
    }
    
    /**
     * Obtiene el punto indicado (la letra o numero del punto).
     * 
     * @param int $pun Numero de punto.
     * @return string Letra o numero del punto.
     */
    public function getPuntoTxt($pun) {
        return $this->puntos[$pun][0];
    }
    
    /**
     * Asigna el titulo de un punto.
     * 
     * @param int $pun Numero de punto.
     * @param string $txt Texto del titulo.
     */
    public function setTitulo($pun, $txt) {
        $this->puntos[$pun][1] = Funciones::gCodificar($txt);
    }
    
    /**
     * Obtiene el titulo del punto indicado.
     * 
     * @param int $pun Numero de punto.
     * @return string Titulo del punto.
     */
    public function getTitulo($pun) {
        return $this->puntos[$pun][1];
    }
    
    /**
     * Obtiene los titulos de los puntos del acta.
     * 
     * @return array del tipo array('codpun'=>'Titulo'...)
     */
    public function getTitulos() {
        $aTits = array();
        $aPuns = $this->getPuntos();
        foreach ($aPuns as $iPun => $aPunto) {
            $sPun = $aPunto[0];
            $sTit = $aPunto[1];
            $aTits[$iPun] = ($sPun) ? "$sPun.- $sTit" : $sTit;
        }
        return $aTits;
    }
    
    /**
     * Obtiene los apartados de un punto.
     * Es un array cuyo indice es el <b>codigo del apartado</b> y como contenido:
     * <ul>
     * <li>0 - Texto del apartado.</li>
     * <li>1 - Subtitulo del apartado.</li>
     * <li>2 - Texto del apartado.</li>
     * </ul>
     * 
     * @param int $pun Numero de punto.
     * @return array del tipo array('apa'=>array(apar, subtit, texto)...)
     */
    public function getApartados($pun) {
        return $this->puntos[$pun][2];
    }
    
    /**
     * Obtiene el contenido de un apartado.
     * 
     * @param int $pun Numero de punto.
     * @param int $apa Numero de apartado.
     * @return array del tipo array(apar, subtit, texto)
     */
    public function getApartado($pun, $apa) {
        return $this->puntos[$pun][2][$apa];
    }
    
    /**
     * Asigna el numero o letra de un apartado.
     * 
     * @param int $pun Numero de punto.
     * @param int $apa Numero de apartado.
     * @param string $txt Numero o letra del apartado.
     */
    public function setApartadoTxt($pun, $apa, $txt) {
        $this->puntos[$pun][2][$apa][0] = Funciones::gCodificar($txt);
    }
    
    /**
     * Obtiene el apartado indicado (numero o letra).
     * 
     * @param int $pun Numero de punto.
     * @param int $apa Numero de apartado.
     * @return string Numero o letra del apartado.
     */
    public function getApartadoTxt($pun, $apa) {
        return $this->puntos[$pun][2][$apa][0];
    }
    
    /**
     * Asigna el subtitulo de un apartado.
     * 
     * @param int $pun Numero de punto.
     * @param int $apa Numero de apartado.
     * @param string $txt Texto del subtitulo.
     */
    public function setSubtitulo($pun, $apa, $txt) {
        $this->puntos[$pun][2][$apa][1] = Funciones::gCodificar($txt);
    }
    
    /**
     * Obtiene el subtitulo de un apartado.
     * 
     * @param int $pun Numero de punto.
     * @param int $apa Numero de apartado.
     * @return string Texto del subtitulo.
     */
    public function getSubtitulo($pun, $apa) {
        return $this->puntos[$pun][2][$apa][1];
    }
    
    /**
     * Asigna el texto del contenido de un apartado.
     * 
     * @param int $pun Numero de punto.
     * @param int $apa Numero de apartado.
     * @param string $txt Texto del apartado.
     */
    public function setTexto($pun, $apa, $txt) {
        $this->puntos[$pun][2][$apa][2] = Funciones::gCodificar($txt);
    }
    
    /**
     * Obtiene el texto de un apartado.
     * 
     * @param int $pun Numero de punto.
     * @param int $apa Numero de apartado.
     * @return string Texto del apartado.
     */
    public function getTexto($pun, $apa) {
        return $this->puntos[$pun][2][$apa][2];
    }
    
    /**
     * Indica si la fecha original del acta ha cambiado por otra diferente.
     * 
     * @return boolean TRUE si ha cambiado y FALSE si es la misma fecha.
     */
    public function hayCambioDeFecha() {
        return ($this->fecha == $this->fechainicial) ? FALSE : TRUE;
    }
    
    /**
     * Guarda los datos del acta actual.
     * 
     * @return boolean Devuelve TRUE si se ha grabado bien o FALSE si ha fallado.
     */
    public function grabar() {
        $bOK = TRUE;
        $feini = $this->fechainicial;   // Fecha inicial del acta.
        $fecha = $this->fecha;          // Fecha actual.
        $aPuns = $this->puntos;         // Array con los datos de los puntos.
        if ($feini && $feini != $fecha) {
            // Se ha cambiado la fecha del acta y no es una nueva. Primero habra que borrar los datos anteriores.
            if (!$this->eliminar()) {
                return FALSE;   // Si falla el borrado sale de la funcion y devuelve FALSE.
            }
        }
        foreach ($aPuns as $iPun => $aPunto) {
            $sPun = $aPunto[0];
            $sTit = $aPunto[1];
            $aApa = $aPunto[2];
            if ($sTit) {
                // Graba los datos del punto.
                $bOK = (Funciones::gEjecutarSQL("REPLACE INTO ACTAS_PUNTOS (FECHA,CODPUN,PUNTO,TITULO) VALUES ('$fecha','$iPun','$sPun','$sTit')")) ? $bOK : FALSE;
                if ($bOK) {
                    // Graba los datos de los apartados.
                    $bOK = ($this->grabarApartados($fecha, $iPun, $aApa)) ? $bOK : FALSE;
                }
            }
        }
        return $bOK;
    }
    
    /**
     * Elimina de la base de datos el acta actual.
     * 
     * @return boolean Devuelve TRUE si todo ha ido bien o FALSE si ha fallado.
     */
    public function eliminar() {
        $bOK = TRUE;
        $fecha = $this->fechainicial;
        // Elimina los apartados del acta.
        if ($fecha && Funciones::gEjecutarSQL("DELETE FROM ACTAS_TEXTOS WHERE FECHA='$fecha'")) {
            // Elimina los puntos del acta.
            $bOK = Funciones::gEjecutarSQL("DELETE FROM ACTAS_PUNTOS WHERE FECHA='$fecha'");
        } else {
            $bOK = FALSE;
        }
        return $bOK;
    }
}
