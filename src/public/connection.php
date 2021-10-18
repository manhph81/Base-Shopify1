<?php

use SebastianBergmann\Environment\Console;

class DB
{
  private static $instance = NULl;
  public static function getInstance()
  {
    if (!isset(self::$instance)) {
      try {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbname = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $passw = $_ENV['DB_PASSWORD'];
        $sql =  'mysql:host='.$host.';port='.$port.';dbname='.$dbname;
        self::$instance = new PDO($sql, $username,  $passw );
        self::$instance->exec("SET NAMES 'utf8'");
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (self::$instance) {
          // create table settings
          $reqSetting = self::$instance->query('SHOW TABLES LIKE "dashboard"');
          if (!$reqSetting->rowCount() > 0) {
            $sqlSetting = "CREATE TABLE dashboard (
              id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              name_dashboard VARCHAR(50) NOT NULL,
              is_active bool NULL,
              reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
              );";
            $sqlSetting = $sqlSetting . 'INSERT INTO dashboard (name_dashboard,is_active) VALUES("Default",1),("New",1);';
            $reqSetting = self::$instance->query($sqlSetting);
          }


        }
      } catch (PDOException $ex) {
        die($ex->getMessage());
      }
    }
    return self::$instance;
  }
}
