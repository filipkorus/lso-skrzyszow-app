<?php
require_once __DIR__ . './../assets/php/check-if-logged.php';
require_once __DIR__ . './../config.php';

class Ranking
{
   public static function getUserPointsByMonthYear($month, $year)
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

   public static function getUserPointsYear($year)
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

   public static function getAllPointsMonth($month, $year, $role = null)
   {
      global $pdo;

      try {
         if ($role != null) {
            $sql = "SELECT uid as id, name, last_name, role, sum(points_plus) - sum(points_minus) as points
               FROM points as p
               INNER JOIN users as u ON p.uid = u.id
               WHERE p.month = :month AND p.year = :year AND u.role = :role
               GROUP BY uid
               ORDER BY points DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
               'month' => $month,
               'year' => $year,
               'role' => $role
            ]);
         } else {
            $sql = "SELECT uid as id, name, last_name, role, sum(points_plus) - sum(points_minus) as points
               FROM points as p
               INNER JOIN users as u ON p.uid = u.id
               WHERE p.month = :month AND p.year = :year
               GROUP BY uid
               ORDER BY points DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
               'month' => $month,
               'year' => $year
            ]);
         }

         return $stmt->rowCount() ? $stmt->fetchAll() : [];
      } catch (PDOException $e) {
         return [];
      }
   }
}
