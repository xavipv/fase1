<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author xavi
 */
class Base {
    
    // Fichero INI que guarda los datos de conexion.
    const FICHERO = _BASE_;

    private static $link = null;
    
    /**
     * Crea una nueva conexión a la base de datos o devuelve una existente.
     * 
     * @return \Base Conexión a la base de datos.
     */
    private static function getLink() {
        // Si ya existe una conexión se devuelve.
        if (self::$link) {
            return self::$link;
        } 
        
        // Comprueba que el fichero de los datos de conexión existe y obtiene sus datos.
        $parse = self::comprobarFichero(self::FICHERO);
        $driver = $parse["db_driver"];
        $dsn = "${driver}:";
        $user = $parse["db_user"];
        $password = $parse["db_password"];
        $options = $parse["db_options"];
        $attributes = $parse["db_attributes"];
        
        foreach ($parse["dsn"] as $k => $v) {
            $dsn .= "${k}=${v};";
        }
        
        try { 
            // Crea una nueva conexión con la base de datos.
            self::$link = new PDO($dsn, $user, $password, $options);
        
            // Añade atributos a la conexión creada.
            foreach ($attributes as $k => $v) {
                self::$link->setAttribute(constant("PDO::{$k}"), constant("PDO::{$v}"));
            }
            
            // Codificado con utf8
            self::$link->query("SET NAMES 'utf8'");
            
            // Devuelve la conexión que se ha creado.
            return self::$link;
            
        } catch (PDOException $e) {
            // Error de conexión a la base de datos.
            echo "<pre>$dsn<br />$user</pre>";
            die('Error al conectar con la base de datos: <pre>' . $e->getMessage() . '</pre>');
        }
    }
    
    /**
     * Comprueba si el fichero INI existe.
     * Si no existe muestra un error y termina, si existe devuelve los datos en un array.
     * 
     * @param string $file Fichero INI a comprobar.
     * @return array Datos del fichero.
     */
    private static function comprobarFichero($file) {
        if (!is_file($file)) {
            // El fichero no existe, termina.
            die("Error, no se puede encontrar el fichero $file");
            exit();
        } else {
            // El fichero existe, lo devuelve en un array.
            return parse_ini_file(self::FICHERO, TRUE);
        }
    }

    /**
     * Función de retrollamada.
     * 
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public static function __callStatic($name, $args) {
        $callback = array(self::getLink(), $name);
        return call_user_func_array($callback, $args);
    }
}
