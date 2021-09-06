<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';

class Errors
{
   public static function reportNew($title, $body)
   {
      global $pdo;
      try {
         $sql = "INSERT INTO errors (reported_by_uid, title, body, reported_at) VALUES (:uid, :title, :body, NOW())";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'title' => $title,
            'body' => $body,
            'uid' => $_SESSION['user']['id']
         ]);
   
         echo json_encode([
            'error' => false,
            'msg' => 'Błąd został zgłoszony!',
            'status' => 'success'
         ]);
      } catch (PDOException $e) {
         echo json_encode([
            'error' => true,
            'msg' => 'Wystąpił błąd bazy danych!',
            'status' => 'danger'
         ]);
      }
   }
}
