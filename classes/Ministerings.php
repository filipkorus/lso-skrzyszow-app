<?php
require_once __DIR__ . './../assets/php/check-if-logged.php';
require_once __DIR__ . './../config.php';

class Ministerings
{
   public static function getMinisteringsDates()
   {
      global $pdo;
      try {
         $sql = "SELECT *
         FROM ministerings as m
         INNER JOIN users as u ON u.id = m.uid
         WHERE u.id = :id";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'id' => $_SESSION['user']['id']
         ]);

         if ($stmt->rowCount()) {
            $ministerings = $stmt->fetchAll();
            $arr = [];
            foreach ($ministerings as $item) {
               $date['date'] = Date(
                  'Y-m-d',
                  strtotime(
                     'next ' . getDayOfWeekName($item['day_of_week']),
                     time() - 3600 * Ministerings::getDecimalHours($item['hour'])
                  )
               );
               $date['hour'] = $item['hour'];
               $date['day_of_week'] = $item['day_of_week'];
               array_push($arr, $date);
            }

            array_multisort(
               array_column($arr, 'date'),
               SORT_ASC,
               $arr
            );

            return $arr;
         } else return [];
      } catch (PDOException $e) {
         return [];
      }
   }

   private static function getDecimalHours($time)
   {
      $time = explode(':', $time);
      return $time[0] + round($time[1] / 60, 2);
   }

   private static function getUsersMinisterings($user_id)
   {
      global $pdo;
      try {
         $sql = "SELECT * FROM ministerings WHERE uid = :id";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'id' => $user_id
         ]);

         if ($stmt->rowCount()) {
            $ministerings = $stmt->fetchAll();
            $arr = [];
            foreach ($ministerings as $item) {
               $date = [
                  'hour' => $item['hour'],
                  'day_of_week' => $item['day_of_week'],
                  'row_id' => $item['id']
               ];
               array_push($arr, $date);
            }

            array_multisort(
               array_column($arr, 'day_of_week'),
               SORT_ASC,
               $arr
            );

            return $arr;
         } else return [];
      } catch (PDOException $e) {
         return [];
      }
   }

   public static function getAllMinisterings()
   {
      global $pdo;
      global $_CONFIG;
      try {
         $sql = "SELECT id, username, name, last_name, role, picture
         FROM users WHERE role != 'ksiądz'";

         $stmt = $pdo->prepare($sql);
         $stmt->execute();

         if ($stmt->rowCount()) {
            $users = $stmt->fetchAll();
            foreach ($users as $key => $user) {
               $users[$key]['ministerings'] = Ministerings::getUsersMinisterings($user['id']);
               if ($user['picture'] == '') $users[$key]['picture'] = $_CONFIG['app']['default_profile_picture_name'];
            }
            return $users;
         } else return [];
      } catch (PDOException $e) {
         return [];
      }
   }

   public static function insertRecord($user_id, $day_of_week, $hour)
   {
      global $pdo;
      try {
         $sql = "INSERT INTO ministerings (uid, day_of_week, hour) VALUES (:uid, :day_of_week, :hour)";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'uid' => $user_id,
            'day_of_week' => $day_of_week,
            'hour' => $hour
         ]);

         return true;
      } catch (PDOException $e) {
         return false;
      }
   }

   public static function updateRecord($row_id, $day_of_week, $hour)
   {
      global $pdo;
      try {
         $sql = "UPDATE ministerings
            SET day_of_week = :day_of_week, hour = :hour
            WHERE id = :row_id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'row_id' => $row_id,
            'day_of_week' => $day_of_week,
            'hour' => $hour
         ]);

         return true;
      } catch (PDOException $e) {
         return false;
      }
   }

   public static function deleteRecord($row_id)
   {
      global $pdo;
      try {
         $sql = "DELETE FROM ministerings WHERE id = :row_id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'row_id' => $row_id
         ]);

         return true;
      } catch (PDOException $e) {
         return false;
      }
   }
}
