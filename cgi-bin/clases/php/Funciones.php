<?php

/**
 * Clase con funciones generales para usar en cualquier parte.
 * 
 * @author xavi
 */
class Funciones {
    
    //--- CONSTANTES ---------------------------------------------------------//
    
    /**
     * Simbolos que no se tendran en cuenta cuando se hagan las busquedas.
     */
    const SIMBOLOS = array('"','(',')','+','-','<','>','~','*');

    //--- BASE DE DATOS ------------------------------------------------------//

    /**
     * Ejecuta una sentencia SQL y devuelve los resultados.
     * 
     * @param string $sql Sentencia a ejecutar.
     * @return result Resultado de la ejecución.
     */
    public static function gEjecutarSQL($sql) {
        try {
            $rRes = Base::prepare($sql);
            $rRes->execute();
        } catch (Exception $exc) {
            $rRes = null;
            die("ERROR en Funciones:gEjecutarSQL::\n\n$sql\n\n" . $exc->getTraceAsString());
            exit();
        }
        return $rRes;
    }

    /**
     * Obtiene el siguiente codigo disponible en la base de datos para un campo numerico.
     * 
     * @param string $tab Tabla.
     * @param string $cam Nombre del campo numerico.
     * @return int Codigo de siguiente disponible.
     */
    public static function gSiguienteCodigo($tab, $cam) {
        $cod  = 1;
        $rRes = self::gEjecutarSQL("SELECT MAX($cam)+1 AS NUM FROM $tab;");
        while($aRow = $rRes->fetch(PDO::FETCH_ASSOC)) {
            $cod = $aRow['NUM'];
        }
        return $cod;
    }
    
    //--- FECHAS -------------------------------------------------------------//
    
    /**
     * Convierte una fecha de formato YYYY-MM-DD a DD-MM-YYYY o viceversa.
     * 
     * @param string $fecha Fecha a convertir.
     * @param boolean $base Indica si la fecha a convertir tiene el formato de base de datos o no.
     * @return string Fecha en el nuevo formato.
     */
    private static function gConvertirISO_Base($fecha, $base) {
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
    public static function gConvertirFecha($fecha, $base=TRUE) {
        $nueva = $fecha;
        
        if($fecha && substr($fecha, 4, 1) == '-' && substr($fecha, 7 , 1) == '-') {
            // Tenemos una fecha del tipo YYYY-MM-DD
            $nueva = ($base) ? $fecha : self::gConvertirISO_Base($fecha,TRUE);
            
        } elseif($fecha && substr($fecha, 2, 1) == '-' && substr($fecha, 5 , 1) == '-') {
            // Tenemos una fecha del tipo DD-MM-YYYY
            $nueva = (!$base) ? $fecha : self::gConvertirISO_Base($fecha,FALSE);
        }
        return $nueva;
    }

    //--- CADENAS ------------------------------------------------------------//

    /**
     * Codifica caracteres especiales en entidades HTML.
     * 
     * @param string $txt Texto a codificar.
     * @return string Texto codificado.
     */
    public static function gCodificar($txt) {
        return htmlspecialchars(trim($txt), ENT_QUOTES, 'UTF-8', FALSE);
    }

    /**
     * Decodifica entidades HTML a caracteres especiales. A las comillas les añade una barra.
     * 
     * @param string $txt Texto a decodificar.
     * @return string Texto decodificado.
     */
    public static function gDecodificar($txt) {
        return htmlspecialchars_decode($txt, ENT_QUOTES);
    }

    /**
     * Elimina de la lista de palabras los caracteres indicados en la constante SIMBOLOS.
     * Ejemplo: quitarCaracteres('La "casa" era +más grande que el *piso*') --> 'La casa era más grande que el piso'
     * 
     * @param string $lista Lista de palabras.
     * @return string Lista de palabras sin los simbolos indicados.
     */
    public static function qQuitarCaracteres($lista) {
        return ($lista) ? str_replace(self::SIMBOLOS, "", $lista) : "";
    }
    
    /**
     * Resalta las palabras indicadas dentro de un texto dado.
     * 
     * @param string $buscar Lista de palabras a buscar.
     * @param string $texto Texto donde se va a buscar.
     * @param string $clase Nombre de la clase CSS para remarcar el texto.
     * @return string Texto con la palabras resaltadas.
     */
    private static function gResaltar($buscar, $texto, $clase='')
    {
        $class = ($clase) ? "class=\"$clase\"" : "style=\"background-color: yellow; color: red;\"";
        $clave = explode(" ", $buscar);
        $num = count($clave);
        for($i=0; $i < $num; $i++)
        {  
            $clave[$i] = preg_replace('/(a|A|á|Á|à|À|ä|Ä)/', '(a|A|á|Á|à|À|ä|Ä)', $clave[$i]);
            $clave[$i] = preg_replace('/(e|E|é|É|è|È|ë|Ë)/', '(e|E|é|É|è|È|ë|Ë)', $clave[$i]);
            $clave[$i] = preg_replace('/(i|I|í|Í|ì|Ì|ï|Ï)/', '(i|I|í|Í|ì|Ì|ï|Ï)', $clave[$i]);
            $clave[$i] = preg_replace('/(o|O|ó|Ó|ò|Ò|ö|Ö)/', '(o|O|ó|Ó|ò|Ò|ö|Ö)', $clave[$i]);
            $clave[$i] = preg_replace('/(u|U|ú|Ú|ù|Ù|ü|Ü)/', '(u|U|ú|Ú|ù|Ù|ü|Ü)', $clave[$i]);
            $clave[$i] = preg_replace('/(ñ|Ñ)/', '(ñ|Ñ)', $clave[$i]);
            $texto = preg_replace("/(".trim($clave[$i]).")/Ui", "<span $class>\\1</span>" , $texto);
        }
        return $texto;
    }
    
    /**
     * Devuelve el texto indicado resaltando las palabras indicadas en la lista.
     * 
     * @param string $lista Lista de palabras a resaltar separadas por espacios.
     * @param string $texto Texto donde se resaltaran las palabras.
     * @param string $clase Clase CSS que se aplicara al resaltado.
     * @param int $min Numero minimo de caracteres para empezar a resaltar.
     * @return string Texto con las palabras de la lista resaltadas.
     */
    public static function gRemarcar($lista, $texto, $clase='', $min=2) {
        // Quita simbolos de busqueda que no se deben tener en cuenta.
        $buscar = Funciones::qQuitarCaracteres($lista);
        
        return (strlen($buscar) >= $min) ? self::gResaltar($buscar, $texto, $clase) : $texto;
    }


}