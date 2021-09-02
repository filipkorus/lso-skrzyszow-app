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
      global $_CONFIG;

      try {
         if ($role != null) {
            $sql = "SELECT u.id as id, sum(points_plus) - sum(points_minus) as points
               FROM points as p
               INNER JOIN users as u ON p.uid = u.id
               WHERE u.role = :role AND p.month = :month AND p.year = :year
               GROUP BY id
               ORDER BY points DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
               'role' => $role,
               'month' => $month,
               'year' => $year
            ]);
         } else {
            $sql = "SELECT u.id as id, sum(points_plus) - sum(points_minus) as points
               FROM points as p
               INNER JOIN users as u ON p.uid = u.id
               WHERE u.role != 'ksiądz' AND p.month = :month AND p.year = :year
               GROUP BY id
               ORDER BY points DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
               'month' => $month,
               'year' => $year
            ]);
         }

         $data = ($stmt->rowCount() ? $stmt->fetchAll() : []);
         $users = Ranking::getUsersData($role == null ? null : $role);

         foreach ($users as $key => $user) {
            for ($i = 0; $i < sizeof($data); ++$i)
               if ($data[$i]['id'] == $user['id'])
                  $users[$key]['points'] = intval($data[$i]['points']);

            if (!isset($users[$key]['points'])) $users[$key]['points'] = 0;
            if ($user['picture'] == '') $users[$key]['picture'] = $_CONFIG['app']['default_profile_picture_name'];
         }

         array_multisort(
            array_column($users, 'points'),
            SORT_DESC,
            $users
         );

         return [
            'error' => false,
            'msg' => 'data fetched sucessfully',
            'points' => $users
         ];
      } catch (PDOException $e) {
         return [
            'error' => true,
            'msg' => 'database error',
            'points' => []
         ];
      }
   }

   public static function getAllPointsYear($year, $role = null)
   {
      global $pdo;
      global $_CONFIG;

      try {
         if ($role != null) {
            $sql = "SELECT u.id as id, sum(points_plus) - sum(points_minus) as points
               FROM points as p
               INNER JOIN users as u ON p.uid = u.id
               WHERE u.role = :role AND p.year = :year
               GROUP BY id
               ORDER BY points DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
               'role' => $role,
               'year' => $year
            ]);
         } else {
            $sql = "SELECT u.id as id, sum(points_plus) - sum(points_minus) as points
               FROM points as p
               INNER JOIN users as u ON p.uid = u.id
               WHERE u.role != 'ksiądz' AND p.year = :year
               GROUP BY id
               ORDER BY points DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
               'year' => $year
            ]);
         }

         $data = ($stmt->rowCount() ? $stmt->fetchAll() : []);
         $users = Ranking::getUsersData($role == null ? null : $role);

         foreach ($users as $key => $user) {
            for ($i = 0; $i < sizeof($data); ++$i)
               if ($data[$i]['id'] == $user['id'])
                  $users[$key]['points'] = intval($data[$i]['points']);

            if (!isset($users[$key]['points'])) $users[$key]['points'] = 0;
            if ($user['picture'] == '') $users[$key]['picture'] = $_CONFIG['app']['default_profile_picture_name'];
         }

         array_multisort(
            array_column($users, 'points'),
            SORT_DESC,
            $users
         );

         return [
            'error' => false,
            'msg' => 'data fetched sucessfully',
            'points' => $users
         ];
      } catch (PDOException $e) {
         return [
            'error' => true,
            'msg' => 'database error',
            'points' => []
         ];
      }
   }

   public static function getUsersData($role = null)
   {
      global $pdo;
      try {
         if ($role != null) {
            $sql = "SELECT id, name, last_name, username, role, picture
            FROM users
            WHERE role = :role AND role != 'ksiądz'
            ORDER BY last_name, name ASC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
               'role' => $role
            ]);
         } else {
            $sql = "SELECT id, name, last_name, username, role, picture
            FROM users
            WHERE role != 'ksiądz'
            ORDER BY last_name, name";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
         }
         return $stmt->rowCount() ? $stmt->fetchAll() : [];
      } catch (PDOException $e) {
         return [];
      }
   }
}
