<?php

/**
 * Clase Info.
 */

/**
 * La clase Info obtiene informacion general.
 *
 * @author xavi
 */
class Info {

    //--- INSTANCIACION ------------------------------------------------------//
    
    public function __construct() {

    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//

    /**
     * Obtiene el nombre de la base de datos que se esta usando.
     * 
     * @return string Nombre de la base de datos.
     */
    public function getNombreBD() {
        $nom = "";
        $rRes = Funciones::gEjecutarSQL("SELECT DATABASE()");
        while($aRow = $rRes->fetch(PDO::FETCH_NUM)) {
            $nom = $aRow[0];
        }
        $rRes->closeCursor(); 
        return $nom;
    }
    
    /**
     * Obtiene los nombre de las tablas de la base de datos actual.
     * 
     * @return array del tipo array('tabla1','tabla2'...)
     */
    public function getNombreTablasBD() {
        $aNom = array();
        $rRes = Funciones::gEjecutarSQL("SHOW TABLES");
        while($aRow = $rRes->fetch(PDO::FETCH_NUM)) {
            $aNom[] = $aRow[0];
        }
        $rRes->closeCursor(); 
        return $aNom;
    }
}