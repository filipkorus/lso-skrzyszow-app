<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

class Database
{
   private $host;
   private $username;
   private $password;
   private $dbname;
   
   public function connect()
   {
      global $_CONFIG;
      $this->host = $_CONFIG['server']['database']['host'];
      $this->username = $_CONFIG['server']['database']['username'];
      $this->password = $_CONFIG['server']['database']['password'];
      $this->dbname = $_CONFIG['server']['database']['table'];
      $this->pdo = null;

      $attr = array(
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, # $row['title']
         // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, # $row->title
         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, # error mode
         PDO::ATTR_TIMEOUT => 3, # max waiting time (in seconds)
      );

      try {
         $dsn = "mysql:host=$this->host;dbname=$this->dbname";
         $this->pdo = new PDO($dsn, $this->username, $this->password, $attr);
      } catch (PDOException $e) {
         exit(json_encode([
            'error' => true,
            'msg' => 'Database connection failed'
         ]));
      }
      return $this->pdo;
   }
}
