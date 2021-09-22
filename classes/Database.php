<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

class Database
{
   
   public static function connect()
   {
      global $_CONFIG;
      $host = $_CONFIG['server']['database']['host'];
      $username = $_CONFIG['server']['database']['username'];
      $password = $_CONFIG['server']['database']['password'];
      $dbname = $_CONFIG['server']['database']['table'];
      $pdo = null;

      $attr = array(
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, # $row['title']
         // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, # $row->title
         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, # error mode
         PDO::ATTR_TIMEOUT => 3, # max waiting time (in seconds)
      );

      try {
         $dsn = "mysql:host=$host;dbname=$dbname";
         $pdo = new PDO($dsn, $username, $password, $attr);
      } catch (PDOException $e) {
         exit(json_encode([
            'error' => true,
            'msg' => 'Database connection failed'
         ]));
      }
      return $pdo;
   }
}
