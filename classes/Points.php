<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';

if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) {
   header('Location: /profile.php');
   exit();
}

class Points
{

   public static function getRecordsMonth($month, $year)
   {

      require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Ranking.php';

      global $pdo;
      global $_CONFIG;

      try {
         $sql = "SELECT u.id as uid, u.picture, p.id as row_id, points_plus, points_minus
               FROM points as p
               INNER JOIN users as u ON p.uid = u.id
               WHERE u.role != 'ksiądz' AND p.month = :month AND p.year = :year
               /*GROUP BY u.id
               ORDER BY u.id ASC*/";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'month' => $month,
            'year' => $year
         ]);

         $data = ($stmt->rowCount() ? $stmt->fetchAll() : []);
         $users = Ranking::getUsersData();

         foreach ($users as $key => $user) {
            for ($i = 0; $i < sizeof($data); ++$i) {
               if ($data[$i]['uid'] == $user['id']) {
                  $users[$key]['points_plus'] = intval($data[$i]['points_plus']);
                  $users[$key]['points_minus'] = intval($data[$i]['points_minus']);
               }
            }

            if (!isset($users[$key]['points_plus'])) $users[$key]['points_plus'] = 0;
            if (!isset($users[$key]['points_minus'])) $users[$key]['points_minus'] = 0;

            if ($user['picture'] == '') $users[$key]['picture'] = $_CONFIG['app']['default_profile_picture_name'];
         }

         array_multisort(
            array_column($users, 'id'),
            SORT_ASC,
            $users
         );

         return [
            'error' => false,
            'msg' => 'data fetched sucessfully',
            'month' => $month,
            'year' => $year,
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

   public static function updateRecord($row_id, $points_plus, $points_minus)
   {
      global $pdo;
      try {
         $sql = "UPDATE points
            SET points_plus = :points_plus, points_minus = :points_minus
            WHERE id = :row_id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'row_id' => $row_id,
            'points_plus' => $points_plus,
            'points_minus' => $points_minus
         ]);

         return true;
      } catch (PDOException $e) {
         return false;
      }
   }

   public static function insertRecord($user_id, $month, $year, $points_plus, $points_minus)
   {
      global $pdo;
      try {
         $sql = "INSERT INTO points (uid, month, year, points_plus, points_minus) VALUES (:uid, :month, :year, :points_plus, :points_minus)";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'uid' => $user_id,
            'month' => $month,
            'year' => $year,
            'points_plus' => $points_plus,
            'points_minus' => $points_minus
         ]);

         return true;
      } catch (PDOException $e) {
         return false;
      }
   }

   public static function getRecordId($user_id, $month, $year)
   {
      global $pdo;
      try {
         $sql = "SELECT * FROM points WHERE uid = :uid AND month = :month AND year = :year";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'uid' => $user_id,
            'month' => $month,
            'year' => $year
         ]);

         return $stmt->rowCount() ? intval($stmt->fetch()['id']) : 0;
      } catch (PDOException $e) {
         return 0;
      }
   }
}
