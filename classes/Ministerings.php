<?php
require_once __DIR__ . './../assets/php/check-if-logged.php';
require_once __DIR__ . './../config.php';

class Ministerings
{
   public static function getMinisterings()
   {
      global $pdo;
      try {
         $sql = "SELECT *
         FROM sluzenia as s
         INNER JOIN users as u ON u.id = s.uid
         WHERE u.id = :id";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'id' => $_SESSION['user']['id']
         ]);

         return ($stmt->rowCount() ? $stmt->fetchAll() : []);
      } catch (PDOException $e) {
         return [];
      }
   }
}
