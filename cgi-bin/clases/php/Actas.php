<?php

/**
 * Clase Actas.
 */

/**
 * La clase Actas guarda el contenido basico de todas las actas de la Juntas Generales.
 * Permite obtener datos basicos y realizar busquedas en las actas guardadas.
 *
 * @author xavi
 */
class Actas {
    
    //--- CONSTANTES ---------------------------------------------------------//
    
    /**
     * Simbolos que no se tendran en cuenta cuando se hagan las busquedas.
     */
    const SIMBOLOS = array('"','(',')','+','-','<','>','~','*');
    
    //--- VARIABLES ----------------------------------------------------------//
    
    /**
     * Contiene los datos basicos de todas las actas.
     * Es un array cuyo indice es la <b>fecha del acta</b> en formato YYYY-MM-DD y como datos:
     * <ul>
     * <li>0 - Fecha en formato DD-MM-YYYY.</li>
     * <li>1 - Numero de puntos que tiene el acta.</li>
     * </ul>
     * 
     * @var array del tipo array('fecha'=>array(fechaISO, puntos)...) 
     */
    private $aActas;
    
    //--- INSTANCIACION ------------------------------------------------------//
    
    /**
     * Constructor de la clase.
     */
    public function __construct() {
        $this->cargarActas();
    }
    
    //--- METODOS PRIVADOS ---------------------------------------------------//
    
    /**
     * Carga los datos basicos de las actas.
     * Los datos se guardan en un array cuyo indice es la <b>fecha del acta</b> en formato YYYY-MM-DD y como datos:
     * <ul>
     * <li>0 - Fecha en formato DD-MM-YYYY.</li>
     * <li>1 - Numero de puntos que tiene el acta.</li>
     * </ul>
     */
    private function cargarActas() {
        $this->aActas = array();
        $res = Funciones::gEjecutarSQL("SELECT FECHA,DATE_FORMAT(FECHA,'%d-%m-%Y') AS FECHAISO,COUNT(*) AS PUNTOS FROM ACTAS_PUNTOS GROUP BY FECHA ORDER BY FECHA DESC");
        while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
            $this->aActas[$aRow['FECHA']] = array($aRow['FECHAISO'], $aRow['PUNTOS']);
        }
        $res->closeCursor(); 
    }
    
    /**
     * Resalta las palabras indicadas dentro de un texto dado.
     * 
     * @param string $buscar Lista de palabras a buscar.
     * @param string $texto Texto donde se va a buscar.
     * @param string $clase Nombre de la clase CSS para remarcar el texto.
     * @return string Texto con la palabras resaltadas.
     */
    private function resaltar($buscar, $texto, $clase='')
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
     * Ejecuta el SQL de busqueda de datos en la base.
     * El array devuelto tiene la siguiente estructura:
     * <ul>
     * <li>0 - Fecha.</li>
     * <li>1 - Codigo del punto.</li>
     * <li>2 - Numero o letra del punto.</li>
     * <li>3 - Titulo del punto.</li>
     * <li>4 - Codigo del apartado.</li>
     * <li>5 - Numero o letra del apartado.</li>
     * <li>6 - Subtitulo del apartado.</li>
     * <li>7 - Texto del apartado.</li>
     * <li>8 - Relevancia de la busqueda.</li>
     * </ul>
     * 
     * @param string $sql Sentencia SQL.
     * @return array del tipo array(fecha, codpun, punto, titulo, codapa, apartado, subtitulo, texto, relevancia)
     */
    private function buscar($sql) {
        $aRe = array();
        if ($sql) {
            $res = Funciones::gEjecutarSQL($sql);
            while($aRow = $res->fetch(PDO::FETCH_ASSOC)) {
                //$aRe[] = array($aRow['FECHA'], html_entity_decode($aRow['CODPUN'],ENT_QUOTES,'UTF-8'), html_entity_decode($aRow['PUNTO'],ENT_QUOTES,'UTF-8'), html_entity_decode($aRow['TITULO'],ENT_QUOTES,'UTF-8'), $aRow['CODAPA'], html_entity_decode($aRow['APARTADO'],ENT_QUOTES,'UTF-8'), html_entity_decode($aRow['SUBTITULO'],ENT_QUOTES,'UTF-8'), html_entity_decode($aRow['TEXTO'],ENT_QUOTES,'UTF-8'), $aRow['RELEVANCIA']);
                $aRe[] = array($aRow['FECHA'], Funciones::gDecodificar($aRow['CODPUN']), Funciones::gDecodificar($aRow['PUNTO']), Funciones::gDecodificar($aRow['TITULO']), $aRow['CODAPA'], Funciones::gDecodificar($aRow['APARTADO']), Funciones::gDecodificar($aRow['SUBTITULO']), Funciones::gDecodificar($aRow['TEXTO']), $aRow['RELEVANCIA']);
            }
            $res->closeCursor();
        }
        return $aRe;
    }
    
    //--- METODOS PUBLICOS ---------------------------------------------------//
    
    /**
     * Recarga los datos de las Actas.
     */
    public function recargar() {
        $this->cargarActas();
    }
    
    /**
     * Obtiene los datos de todas las actas.
     * Se devuelve un array cuyo indice es la <b>fecha del acta</b> en formato YYYY-MM-DD y como datos:
     * <ul>
     * <li>0 - Fecha en formato DD-MM-YYYY.</li>
     * <li>1 - Numero de puntos que tiene el acta.</li>
     * </ul>
     * 
     * @return array del tipo array('fecha'=>array(fechaISO, puntos)...)
     */
    public function getActas() {
        return $this->aActas;
    }
    
    /**
     * Obtiene los años de las diferentes actas.
     * 
     * @return array del tipo array(año1, año2...)
     */
    public function getActasAnyos() {
        $aAny = array();
        $sAny = "";
        foreach (array_keys($this->aActas) as $date) {
            $any = substr($date, 0, 4);
            if ($any != $sAny) {
                $aAny[] = $any;
                $sAny = $any;
            }
        }
        return $aAny;
    }
    
    /**
     * Obtiene la fecha de la ultima acta.
     * 
     * @return date Fecha de la ultima acta.
     */
    public function getUltimaActa() {
        $aFec = array_keys($this->aActas);
        reset($aFec);
        return current($aFec);
    }
    
    /**
     * Comprueba si la fecha de un acta existe.
     * 
     * @param date $fecha Fecha en cualquier formato.
     * @return boolean TRUE si existe o FALSE si no existe.
     */
    public function existeActa($fecha) {
        $date = Funciones::gConvertirFecha($fecha, TRUE);
        return array_key_exists($date, $this->aActas);
    }
    
    /**
     * Realiza una busqueda en lenguaje natural.
     * El array devuelto tiene la siguiente estructura:
     * <ul>
     * <li>0 - Fecha.</li>
     * <li>1 - Codigo del punto.</li>
     * <li>2 - Numero o letra del punto.</li>
     * <li>3 - Titulo del punto.</li>
     * <li>4 - Codigo del apartado.</li>
     * <li>5 - Numero o letra del apartado.</li>
     * <li>6 - Subtitulo del apartado.</li>
     * <li>7 - Texto del apartado.</li>
     * <li>8 - Relevancia de la busqueda.</li>
     * </ul>
     * 
     * @param string $texto Texto a buscar.
     * @return array Resultados de la busqueda en un array del tipo array(fecha, codpun, punto, titulo, codapa, apartado, subtit, texto, relevancia)
     */
    public function busquedaNatural($texto) {
        $txt = Funciones::gCodificar(Funciones::qQuitarCaracteres($texto));
        return $this->buscar("SELECT P.FECHA,P.CODPUN,P.PUNTO,P.TITULO,T.CODAPA,T.APARTADO,T.SUBTITULO,T.TEXTO,(MATCH(P.TITULO) AGAINST ('$txt' IN NATURAL LANGUAGE MODE) + MATCH(T.SUBTITULO,T.TEXTO) AGAINST ('$txt' IN NATURAL LANGUAGE MODE)) AS RELEVANCIA FROM ACTAS_PUNTOS P LEFT JOIN ACTAS_TEXTOS T ON P.FECHA=T.FECHA AND P.CODPUN=T.CODPUN WHERE MATCH(P.TITULO) AGAINST ('$txt' IN NATURAL LANGUAGE MODE) OR MATCH(T.SUBTITULO,T.TEXTO) AGAINST ('$txt' IN NATURAL LANGUAGE MODE) ORDER BY RELEVANCIA DESC");
    }
    
    /**
     * Realiza una busqueda booleana.
     * El array devuelto tiene la siguiente estructura:
     * <ul>
     * <li>0 - Fecha.</li>
     * <li>1 - Codigo del punto.</li>
     * <li>2 - Numero o letra del punto.</li>
     * <li>3 - Titulo del punto.</li>
     * <li>4 - Codigo del apartado.</li>
     * <li>5 - Numero o letra del apartado.</li>
     * <li>6 - Subtitulo del apartado.</li>
     * <li>7 - Texto del apartado.</li>
     * <li>8 - Relevancia de la busqueda.</li>
     * </ul>
     * 
     * @param string $texto Texto a buscar.
     * @return array Resultados de la busqueda en un array del tipo array(fecha, codpun, punto, titulo, codapa, apartado, subtit, texto, relevancia)
     */
    public function busquedaBooleana($texto) {
        $txt = Funciones::gCodificar($texto);
        return $this->buscar("SELECT P.FECHA,P.CODPUN,P.PUNTO,P.TITULO,T.CODAPA,T.APARTADO,T.SUBTITULO,T.TEXTO,(MATCH(P.TITULO) AGAINST ('$txt' IN BOOLEAN MODE) + MATCH(T.SUBTITULO,T.TEXTO) AGAINST ('$txt' IN BOOLEAN MODE)) AS RELEVANCIA FROM ACTAS_PUNTOS P LEFT JOIN ACTAS_TEXTOS T ON P.FECHA=T.FECHA AND P.CODPUN=T.CODPUN WHERE MATCH(P.TITULO) AGAINST ('$txt' IN BOOLEAN MODE) OR MATCH(T.SUBTITULO,T.TEXTO) AGAINST ('$txt' IN BOOLEAN MODE) ORDER BY RELEVANCIA DESC");
    }
    
    /**
     * Realiza una busqueda literal.
     * El array devuelto tiene la siguiente estructura:
     * <ul>
     * <li>0 - Fecha.</li>
     * <li>1 - Codigo del punto.</li>
     * <li>2 - Numero o letra del punto.</li>
     * <li>3 - Titulo del punto.</li>
     * <li>4 - Codigo del apartado.</li>
     * <li>5 - Numero o letra del apartado.</li>
     * <li>6 - Subtitulo del apartado.</li>
     * <li>7 - Texto del apartado.</li>
     * <li>8 - Relevancia de la busqueda. En este caso siempre sera 1.</li>
     * </ul>
     * 
     * @param string $texto Texto a buscar.
     * @return array Resultados de la busqueda en un array del tipo array(fecha, codpun, punto, titulo, codapa, apartado, subtit, texto, relevancia=1)
     */
    public function busquedaLiteral($texto) {
        $txt = Funciones::gCodificar(Funciones::qQuitarCaracteres($texto));
        return $this->buscar("SELECT P.FECHA,P.CODPUN,P.PUNTO,P.TITULO,T.CODAPA,T.APARTADO,T.SUBTITULO,T.TEXTO,1 AS RELEVANCIA FROM ACTAS_PUNTOS P LEFT JOIN ACTAS_TEXTOS T ON P.FECHA=T.FECHA AND P.CODPUN=T.CODPUN WHERE P.TITULO LIKE '%$txt%' OR T.SUBTITULO LIKE '%$txt%' OR T.TEXTO LIKE '%$txt%' ORDER BY P.FECHA DESC,P.CODPUN,T.CODAPA");
    }
}
