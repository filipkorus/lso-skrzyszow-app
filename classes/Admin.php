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
}
