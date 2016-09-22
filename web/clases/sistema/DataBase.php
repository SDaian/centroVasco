<?php

namespace sistema;

/**
 * Configuracion de la base de datos del sistema
 * de centrovasco.com
 *
 * @author nicks
 */
class DataBase {
    
    private static $host = 'localhost';
    private static $port = 3306;
    private static $user = 'rx000652_CVdb';
    private static $pass = 'wuGApigu78';
    private static $dbname = 'rx000652_CVdb';
    
    public static function getHost() {
        return self::$host;
    }

    public static function getPort() {
        return self::$port;
    }

    public static function getUser() {
        return self::$user;
    }

    public static function getPass() {
        return self::$pass;
    }

    public static function getDbname() {
        return self::$dbname;
    }

}
