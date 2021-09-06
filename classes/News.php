<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

class News
{
   public static function add($title, $body)
   {
      global $pdo;
      if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) return;
      try {
         $sql = "INSERT INTO news (added_by_uid, title, body, added_at) VALUES (:added_by_uid, :title, :body, NOW())";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'added_by_uid' => $_SESSION['user']['id'],
            'title' => $title,
            'body' => $body
         ]);

         echo json_encode([
            'error' => false,
            'msg' => 'Dodano ogłoszenie!',
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

   public static function edit($news_id, $title, $body)
   {
      global $pdo;
      if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) return;
      try {
         $sql = "UPDATE news SET title = :title, body = :body WHERE id = :news_id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'title' => $title,
            'body' => $body,
            'news_id' => $news_id
         ]);

         echo json_encode([
            'error' => false,
            'msg' => 'Zapisano zmiany!',
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

   public static function delete($news_id)
   {
      global $pdo;
      if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) return;
      try {
         $sql = "DELETE FROM news WHERE id = :news_id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'news_id' => $news_id
         ]);

         echo json_encode([
            'error' => false,
            'msg' => 'Ogłoszenie zostało usunięte!',
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

   public static function getById($news_id)
   {
      global $pdo;
      global $_CONFIG;
      try {
         $sql = "SELECT n.id, n.title, n.body, n.added_at, CONCAT(u.name, ' ', u.last_name) AS author, u.picture AS author_picture
         FROM news AS n
         INNER JOIN users AS u
         ON u.id = n.added_by_uid
         WHERE n.id = :news_id";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'news_id' => $news_id
         ]);

         if ($stmt->rowCount()) {
            $news = $stmt->fetch();

            if ($news['author_picture'] === '') $news['author_picture'] = $_CONFIG['app']['default_profile_picture_name'];
            $news['added_at'] = News::formatDate($news['added_at']);

            return $news;
         }
         return null;
      } catch (PDOException $e) {
         return null;
      }
   }

   public static function get($n, $wo_body = false)
   {
      global $pdo;
      global $_CONFIG;
      try {
         $sql = "SELECT n.id, n.title, n.body, n.added_at, CONCAT(u.name, ' ', u.last_name) AS author, u.picture AS author_picture
         FROM news AS n
         INNER JOIN users AS u
         ON u.id = n.added_by_uid
         ORDER BY n.added_by_uid DESC
         LIMIT " . $n;

         $stmt = $pdo->prepare($sql);
         $stmt->execute();

         if ($stmt->rowCount()) {
            $news = $stmt->fetchAll();

            foreach ($news as $key => $item) {
               $news[$key]['added_at'] = News::formatDate($news[$key]['added_at']);
               if ($item['author_picture'] === '') $news[$key]['author_picture'] = $_CONFIG['app']['default_profile_picture_name'];
               if ($wo_body) unset($news[$key]['body']);
            }

            return [
               'error' => false,
               'msg' => 'Data fetched successfully',
               'disable_btn' => ($n > $stmt->rowCount()),
               'news' => $news
            ];
         }
         return [
            'error' => false,
            'msg' => 'No articles found!',
            'disable_btn' => true,
            'news' => []
         ];
      } catch (PDOException $e) {
         return [
            'error' => true,
            'msg' => 'Database error!',
            'disable_btn' => true,
            'news' => []
         ];
      }
   }

   private static function formatDate($date)
   {
      $m = Date('m', strToTime($date));

      switch ($m) {
         case 1:
            $m = 'stycznia';
            break;
         case 2:
            $m = 'lutego';
            break;
         case 3:
            $m = 'marca';
            break;
         case 4:
            $m = 'kwietnia';
            break;
         case 5:
            $m = 'maja';
            break;
         case 6:
            $m = 'czerwca';
            break;
         case 7:
            $m = 'lipca';
            break;
         case 8:
            $m = 'sierpnia';
            break;
         case 9:
            $m = 'września';
            break;
         case 10:
            $m = 'października';
            break;
         case 11:
            $m = 'listopada';
            break;
         case 12:
            $m = 'grudnia';
            break;
      }

      return Date('j', strToTime($date)) . " $m " . Date('Y', strToTime($date)) . ', ' . Date('H:i', strToTime($date));
   }
}
