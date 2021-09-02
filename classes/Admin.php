<?php

require_once __DIR__ . './../assets/php/check-if-logged.php';

if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) {
   header('Location: /profile.php');
   exit();
}

class Admin
{
   public static function addNewUser($username, $email, $password, $phone_no, $name, $last_name, $birthdate, $role, $admin = false)
   {
      global $pdo;
      try {
         $sql = "INSERT INTO users (username, email, password, phone_no, name, last_name, birthdate, picture, role, admin, last_time_online) 
            VALUES (:username, :email, :password, :phone_no, :name, :last_name, :birthdate, :picture, :role, :admin, now())";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'name' => $name,
            'last_name' => $last_name,
            'birthdate' => $birthdate,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'phone_no' => $phone_no,
            'role' => $role,
            'admin' => $admin,
            'picture' => ''
         ]);

         echo json_encode([
            'error' => false,
            'msg' => 'Dodano użytkownika do bazy danych!',
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

   public static function getAllUsers()
   {
      global $pdo;
      global $_CONFIG;
      try {
         $sql = "SELECT * FROM users ORDER BY id";

         $stmt = $pdo->prepare($sql);
         $stmt->execute();

         $users = $stmt->fetchAll();
         $res = [];

         foreach ($users as $key => $user) {
            unset($users[$key]['password']);
            $users[$key]['picture'] = ($user['picture'] == '' ? $_CONFIG['app']['default_profile_picture_name'] : $users[$key]['picture']);
            $users[$key]['admin'] = ($user['admin'] == 1 ? true : false);
            $users[$key]['id'] = intval($users[$key]['id']);
         }

         return [
            'users' => $users,
            'error' => false,
            'msg' => 'data fetched successfully'
         ];
      } catch (PDOException $e) {
         return [
            'users' => [],
            'error' => true,
            'msg' => 'database error'
         ];
      }
   }

   public static function getUsersWithRole($role)
   {
      global $pdo;
      global $_CONFIG;
      try {
         $sql = "SELECT * FROM users WHERE role = :role ORDER BY id";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'role' => $role
         ]);

         $users = $stmt->fetchAll();
         $res = [];

         foreach ($users as $key => $user) {
            unset($users[$key]['password']);
            $users[$key]['picture'] = ($user['picture'] == '' ? $_CONFIG['app']['default_profile_picture_name'] : $users[$key]['picture']);
            $users[$key]['admin'] = ($user['admin'] == 1 ? true : false);
            $users[$key]['id'] = intval($users[$key]['id']);
         }

         return [
            'users' => $users,
            'error' => false,
            'msg' => 'data fetched successfully'
         ];
      } catch (PDOException $e) {
         return [
            'users' => [],
            'error' => true,
            'msg' => 'database error'
         ];
      }
   }

   public static function editUser($id, $username, $email, $phone_no, $name, $last_name, $birthdate, $role, $admin, $delele_picture = false)
   {
      global $pdo;

      if ($delele_picture) Admin::deleteUsersPicture($id);

      try {
         $sql = "UPDATE users SET
            username = :username, email = :email, phone_no = :phone_no, name = :name, last_name = :last_name, birthdate = :birthdate, picture = :picture, role = :role, admin = :admin WHERE id = :id";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'id' => $id,
            'name' => $name,
            'last_name' => $last_name,
            'birthdate' => $birthdate,
            'username' => $username,
            'email' => $email,
            'phone_no' => $phone_no,
            'role' => $role,
            'admin' => $admin,
            'picture' => ($delele_picture ? '' : Admin::getUsersPictureNameDb($id))
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

   public static function isPhoneNoUsedBy($phone_no, $id)
   {
      global $pdo;
      try {
         $sql = "SELECT * FROM users WHERE phone_no = :phone_no AND id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'phone_no' => $phone_no,
            'id' => $id
         ]);
         return $stmt->rowCount() ? true : false;
      } catch (PDOException $e) {
         return true;
      }
   }

   public static function isEmailUsedBy($email, $id)
   {
      global $pdo;
      try {
         $sql = "SELECT * FROM users WHERE email = :email AND id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'email' => $email,
            'id' => $id
         ]);
         return $stmt->rowCount() ? true : false;
      } catch (PDOException $e) {
         return true;
      }
   }

   public static function isUsernameUsedBy($username, $id)
   {
      global $pdo;
      try {
         $sql = "SELECT * FROM users WHERE username = :username AND id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'username' => $username,
            'id' => $id
         ]);
         return $stmt->rowCount() ? true : false;
      } catch (PDOException $e) {
         return true;
      }
   }

   public static function deleteUsersPicture($id)
   {
      global $_CONFIG;
      global $pdo;

      $current_picture_name = Admin::getUsersPictureNameDb($id);
      if ($current_picture_name !== '')
         if (file_exists(__DIR__ . '../../' . $_CONFIG['app']['profile_pictures_path'] . $current_picture_name))
            unlink(__DIR__ . '../../' . $_CONFIG['app']['profile_pictures_path'] . $current_picture_name);

      try {
         $sql = "UPDATE users SET picture = :picture WHERE id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'picture' => '',
            'id' => $id
         ]);

         return __DIR__ . '../../' . $_CONFIG['app']['profile_pictures_path'] . $current_picture_name;
      } catch (PDOException $e) {
      }
   }

   private static function getUsersPictureNameDb($id)
   {
      global $pdo;
      global $_CONFIG;
      try {
         $sql = "SELECT * FROM users WHERE id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'id' => $id
         ]);

         return $stmt->fetch()['picture'];
      } catch (PDOException $e) {
         return '';
      }
   }

   public static function getPointsRecordsMonth($month, $year)
   {

      require_once __DIR__ . './Ranking.php';

      global $pdo;
      global $_CONFIG;

      try {
         $sql = "SELECT u.id as uid, p.id as row_id, points_plus, points_minus
               FROM points as p
               INNER JOIN users as u ON p.uid = u.id
               WHERE u.role != 'ksiądz' AND p.month = :month AND p.year = :year
               GROUP BY u.id
               ORDER BY u.id";

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
                  $users[$key]['row_id'] = intval($data[$i]['row_id']);
               }
            }

            if (!isset($users[$key]['points_plus'])) $users[$key]['points_plus'] = 0;
            if (!isset($users[$key]['points_minus'])) $users[$key]['points_minus'] = 0;
            if (!isset($users[$key]['row_id'])) $users[$key]['row_id'] = 0;

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

   public static function updatePoints($row_id, $points_plus, $points_minus)
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

   public static function insertPoints($uid, $month, $year, $points_plus, $points_minus)
   {
      global $pdo;
      try {
         $sql = "INSERT INTO points (uid, month, year, points_plus, points_minus) VALUES (:uid, :month, :year, :points_plus, :points_minus)";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'uid' => $uid,
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
}
