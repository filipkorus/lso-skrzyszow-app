<?php
require_once __DIR__ . './../assets/php/check-if-logged.php';
require_once __DIR__ . './../config.php';

class Ranking
{
   public static function getPointsByMonthYear($month, $year)
   {
      global $pdo;
      try {
         $sql = "SELECT sum(points_plus) - sum(points_minus) as points
         FROM points as p
         INNER JOIN users as u ON p.uid = u.id
         WHERE p.month = :month AND p.year = :year AND u.id = :id
         GROUP BY uid
         ORDER BY points DESC";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'month' => $month,
            'year' => $year,
            'id' => $_SESSION['user']['id']
         ]);

         return ($stmt->rowCount() ? $stmt->fetch()['points'] : 0);
      } catch (PDOException $e) {
         return 0;
      }
   }

   public static function getPointsByYear($year)
   {
      global $pdo;
      try {
         $sql = "SELECT sum(points_plus) - sum(points_minus) as points
         FROM points as p
         INNER JOIN users as u ON p.uid = u.id
         WHERE p.year = :year AND u.id = :id
         GROUP BY uid
         ORDER BY points DESC";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'year' => $year,
            'id' => $_SESSION['user']['id']
         ]);

         return ($stmt->rowCount() ? $stmt->fetch()['points'] : 0);
      } catch (PDOException $e) {
         return 0;
      }
   }
}
