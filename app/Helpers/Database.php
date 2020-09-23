<?php

namespace Syltaen;

use Medoo\Medoo;

abstract class Database {

  /**
   * @var Medoo
   */
   private static $_db = null;


   /**
    * Create a single instance of the database connection
    *
    * @return Medoo
    */
   public static function db()
   {
        if (is_null(self::$_db)) {
            self::$_db = new Medoo([
                "database_type" => "mysql",
                "database_name" => DB_NAME,
                "server"        => DB_HOST,
                "username"      => DB_USER,
                "password"      => DB_PASS
            ]);
        }

        return self::$_db;
   }


    /**
     * Transfert all method call to the Medoo instance
     *
     * @param string $method
     * @param array $arguments
     * @return mixed Result of the request
     */
   public static function __callStatic($method, $arguments)
   {
       return call_user_func_array([self::db(), $method], $arguments);
   }

}