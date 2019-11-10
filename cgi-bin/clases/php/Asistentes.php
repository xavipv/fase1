<?php

/**
 * Clase Asistentes.
 */

/**
 * La clase Asistentes guarda los datos de los asistentes a una Junta General.
 * Permite manejar los datos de los asistentes y representados en una Juna.
 *
 * @author xavi
 */
class Asistentes {
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Fecha de la Junta a la que se ha asistido.
     * 
     * @var date Fecha en formato YYYY-MM-DD. 
     */
    private $fecha;
    
    /**
     * Controla como se ordenaran los datos de los asistentes, el orden puede ser:
     * <ul>
     * <li>0 - Por apartamento.</li>
     * <li>1 - Por propietarios.</li>
     * <li>2 - Por representantes.</li>
     * </ul>
     * 
     * @var int Orden 0, 1 o 2. 
     */
    private $orden;
    
    /**
     * Array cuyo codigo es el <b>codigo de apartamento</b> y los datos un array:
     * <ul>
     * <li>0 - Apartamento.</li>
     * <li>1 - Codigo de persona.</li>
     * <li>2 - Nombre del asistente.</li>
     * <li>3 - Representado, S/N.</li>
     * <li>4 - Voto, S/N.</li>
     * <li>5 - Coeficiente fase.</li>
     * <li>6 - Coeficiente bloque.</li>
     * <li>7 - Nombre del propietario.</li>
     * <li>8 - Nombre del representante.</li>
     * </ul>
     * 
     * @var array del tipo array('codapar'=>array('apartamento','codpers','nombre','repre','voto','coefi','bloque','propietario','representante')...) 
     */
    private $aAsistentes;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase.
     * 
     * @param date $fecha Fecha en cualquier formato.
     * @param int $orden Orden para los datos: 0, 1 o 2.
     */
    public function __construct($fecha, $orden=0) {
        $this->cargarAsistentes(Funciones::gConvertirFecha($fecha, TRUE), $orden);
    }
    
    //--- METODOS PRIVADOS ---------------------------------------------------//
    
    /**
     * Carga los asistentes a una junta determinada ordenados por la opcion elegida.
     * Devuelve un array cuyo codigo es el <b>codigo de apartamento</b> y los datos un array:
     * <ul>
     * <li>0 - Apartamento.</li>
     * <li>1 - Codigo de persona.</li>
     * <li>2 - Nombre del asistente.</li>
     * <li>3 - Representado, S/N.</li>
     * <li>4 - Voto, S/N.</li>
     * <li>5 - Coeficiente fase.</li>
     * <li>6 - Coeficiente bloque.</li>
     * <li>7 - Nombre del propietario.</li>
     * <li>8 - Nombre del representante.</li>
     * </ul>
     * 
     * @param date $fecha Fecha en formato YYYY-MM-DD.
     * @param int $orden Orden. 0 - Por apartamento. 1 - Por propietario. 2 - Por representante.
     * @return array del tipo array('codapar'=>array('apartamento','codpers','nombre','repre','voto','coefi','bloque','propietario','representante')...)
     */
    private function cargarAsistentes($fecha, $orden=0) {
        $this->cargarDatosOmision($fecha, $orden);
        switch ($orden) {
            case 1 : $orden = "PROPIETARIO, A.CODAPAR"; break;
            case 2 : $orden = "REPRESENTANTE, A.CODAPAR"; break;
            default: $orden = "A.CODAPAR"; break;
        }
        if ($fecha) {
            $rRes = Funciones::gEjecutarSQL("SELECT A.CODAPAR,CONCAT(A.PORTAL,'-',A.PISO,A.LETRA) AS APARTAMENTO,JA.CODPERS,CONCAT(P.APELLIDOS,' ',P.NOMBRE) AS ASISTENTE,JA.REPRESENTADO,JA.VOTO,A.COEFICIENTEFASE,A.COEFICIENTEBLOQ,IF(JA.REPRESENTADO='S',(SELECT CONCAT(PE.APELLIDOS,' ',PE.NOMBRE) AS PERSONA FROM PROPIETARIOS PR LEFT JOIN PERSONAS PE ON PR.CODPERS=PE.CODPERS WHERE PR.CODAPAR=A.CODAPAR AND IFNULL(PR.BAJA,'9999-99-99')=(SELECT MIN(IFNULL(BAJA,'9999-99-99')) FROM PROPIETARIOS WHERE CODAPAR=PR.CODAPAR AND IFNULL(BAJA,'9999-99-99')>'$fecha') ORDER BY IFNULL(PR.BAJA,'9999-99-99') DESC,PR.ORDEN LIMIT 1),CONCAT(P.APELLIDOS,' ',P.NOMBRE)) AS PROPIETARIO,IF(JA.REPRESENTADO='S',CONCAT(P.APELLIDOS,' ',P.NOMBRE),'') AS REPRESENTANTE FROM ASISTENTES JA LEFT JOIN APARTAMENTOS A ON A.CODAPAR=JA.CODAPAR LEFT JOIN PERSONAS P ON P.CODPERS=JA.CODPERS WHERE JA.FECHA='$fecha' ORDER BY $orden");
            while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
                $this->aAsistentes[$aRow['CODAPAR']] = array($aRow['APARTAMENTO'],$aRow['CODPERS'], $aRow['ASISTENTE'], $aRow['REPRESENTADO'],$aRow['VOTO'],$aRow['COEFICIENTEFASE'],$aRow['COEFICIENTEBLOQ'],$aRow['PROPIETARIO'],$aRow['REPRESENTANTE']);
            }
            $rRes->closeCursor();
        }
    }
    
    /**
     * Carga los datos por omision.
     * 
     * @param date $fecha Fecha en formato YYYY-MM-DD.
     * @param int $orden Orden. 0 - Por apartamento. 1 - Por propietario. 2 - Por representante.
     */
    private function cargarDatosOmision($fecha, $orden) {
        $this->fecha = $fecha;
        $this->orden = ($orden != 0 && $orden != 1 && $orden != 2) ? 0 : $orden;
        $this->aAsistentes = array();
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    /**
     * Asigna una nueva fecha.
     * 
     * @param date $fecha Fecha en cualquier formato.
     */
    public function setFecha($fecha) {
        $date = Funciones::gConvertirFecha($fecha, TRUE);
        $orden = $this->orden;
        if ($date && $date != $this->fecha) {
            // Si cambia la fecha, recarga los datos.
            $this->cargarAsistentes($date, $orden);
        }
    }
    
    /**
     * Obtiene la fecha de la Junta a la que se asiste.
     * 
     * @return date Fecha en formato YYYY-MM-DD.
     */
    public function getFecha() {
        return $this->fecha;
    }
    
    /**
     * Obtiene la fecha de la Junta a la que se asiste, en formato ISO.
     * 
     * @return date Fecha en formato DD-MM-YYYY.
     */
    public function getFechaISO() {
        return Funciones::gConvertirFecha($this->fecha, FALSE);
    }
    
    /**
     * Se asigna un nuevo orden para los datos de los asistentes.
     * El orden puede ser:
     * <ul>
     * <li>0 - Por apartamento.</li>
     * <li>1 - Por propietarios.</li>
     * <li>2 - Por representantes.</li>
     * </ul>
     * 
     * @param int $orden Tipo de orden: 0, 1 o 2.
     */
    public function setOrden($orden) {
        $ord = ($orden != 0 && $orden != 1 && $orden != 2) ? 0 : $orden;
        if ($ord != $this->orden) {
            // Si cambia el orden, recarga los datos.
            $fecha = $this->fecha;
            $this->cargarAsistentes($fecha, $ord);
        }
    }
    
    /**
     * Devuelve el orden de los datos de los asistentes.
     * El orden puede ser:
     * <ul>
     * <li>0 - Por apartamento.</li>
     * <li>1 - Por propietarios.</li>
     * <li>2 - Por representantes.</li>
     * </ul>
     * 
     * @return Tipo de orden: 0, 1 o 2.
     */
    public function getOrden() {
        return $this->orden;
    }
    
    /**
     * Obtiene los datos de los asistentes a una Junta.
     * Array cuyo codigo es el <b>codigo de apartamento</b> y los datos un array:
     * <ul>
     * <li>0 - Apartamento.</li>
     * <li>1 - Codigo de persona.</li>
     * <li>2 - Nombre del asistente.</li>
     * <li>3 - Representado, S/N.</li>
     * <li>4 - Voto, S/N.</li>
     * <li>5 - Coeficiente fase.</li>
     * <li>6 - Coeficiente bloque.</li>
     * <li>7 - Nombre del propietario.</li>
     * <li>8 - Nombre del representante.</li>
     * </ul>
     * 
     * @return array del tipo array('codapar'=>array('apartamento','codpers','nombre','repre','voto','coefi','bloque','propietario','representante')...)
     */
    public function getAsistentes() {
        return $this->aAsistentes;
    }
    
    /**
     * Obtiene el numero de asistentes, representados y votos de la junta actual.
     * Los datos se devuelven en un array del siguiente tipo:
     * array(
     *      'prop' => array('propietarios', 'distintos', 'con voto', 'sin voto', 'coeficiente'),
     *      'repr' => array('representados', 'distintos', 'con voto', 'sin voto', 'coeficiente')
     * )
     * 
     * @return array con las sumas de los asistentes.
     */
    public function getSumas() {
        $aDatos = $this->getAsistentes();
        $aAsi = array();
        $aPer = array();
        $aRep = array();
        $prop = 0;
        $repr = 0;
        $vosp = 0;
        $vonp = 0;
        $vosr = 0;
        $vonr = 0;
        $cofp = 0;
        $cofr = 0;
        
        foreach ($aDatos as $aAsistente) {
            if($aAsistente[3] == 'S') {
                // Representante.
                $repr++;                                    // Numero de respresentantes.
                $aRep[$aAsistente[1]] = $aAsistente[1];     // Para el numero de respresentantes diferentes.
                $vosr += ($aAsistente[4] == 'S') ? 1 : 0;   // Representantes con voto.
                $vonr += ($aAsistente[4] == 'N') ? 1 : 0;   // Representantes sin voto.
                $cofr += $aAsistente[5];                    // Coeficiente fase.
            } else {
                // Propietario.
                $prop++;
                $aPer[$aAsistente[1]] = $aAsistente[1];     // Para el numero de propietarios diferentes.
                $vosp += ($aAsistente[4] == 'S') ? 1 : 0;   // Propietarios con voto.
                $vonp += ($aAsistente[4] == 'N') ? 1 : 0;   // Propietarios sin voto.
                $cofp += $aAsistente[5];                    // Coeficiente fase.
            }
        }
        $aAsi['prop'] = array($prop, count($aPer), $vosp, $vonp, $cofp);
        $aAsi['repr'] = array($repr, count($aRep), $vosr, $vonr, $cofr);
        return $aAsi;
    }
    
    /**
     * Elimina los datos de todos los asistentes a una Junta.
     * 
     * @return boolean Devuelve TRUE si todo ha ido bien o FALSE si ha fallado algo.
     */
    public function eliminar() {
        $fecha = $this->fecha;
        return ($fecha) ? Funciones::gEjecutarSQL("DELETE FROM ASISTENTES WHERE FECHA='$fecha'") : FALSE;
    }
}
