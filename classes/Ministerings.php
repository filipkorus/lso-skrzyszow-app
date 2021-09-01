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
         FROM sluzenia as s
         INNER JOIN users as u ON u.id = s.uid
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
}
