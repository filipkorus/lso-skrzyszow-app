<?php

if (!isset($_SESSION)) session_start();
if (!(isset($_SESSION['user']['logged']) && $_SESSION['user']['logged'])) {
   header('Location: /');
   exit();
}

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
      try {
         $sql = "SELECT * FROM users ORDER BY id";

         $stmt = $pdo->prepare($sql);
         $stmt->execute();

         return [
            'users' => $stmt->fetchAll(),
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
      try {
         $sql = "SELECT * FROM users WHERE role = :role ORDER BY id";

         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'role' => $role
         ]);

         return [
            'users' => $stmt->fetchAll(),
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
}
