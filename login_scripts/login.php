<?php

if (!isset($_SESSION)) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

header('Content-Type: application/json');

require_once __DIR__ . './../config.php';
require_once __DIR__ . './../classes/Database.php';

$db = new Database;
$pdo = $db->connect();

if (
   !isset($_POST['username']) || !isset($_POST['password'])
   || empty($_POST['username']) || empty($_POST['password'])
) {
   echo json_encode([
      'error' => true,
      'msg' => 'Fill in all fields!',
      'status' => 'danger'
   ]);
   return;
}

$username = $_POST['username'];
$password = $_POST['password'];

try {
   $sql = "SELECT * FROM users WHERE username = :username OR email = :username LIMIT 1";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([
      'username' => $username
   ]);

   if (!$stmt->rowCount()) {
      echo json_encode([
         'error' => true,
         'msg' => 'Nieprawidłowe hasło!',
         'status' => 'danger'
      ]);
      return;
   }

   $row = $stmt->fetch();
   if (!password_verify($_POST['password'], $row['password'])) {
      echo json_encode([
         'error' => true,
         'msg' => 'Nieprawidłowe hasło!',
         'status' => 'danger'
      ]);
      return;
   }

   $_SESSION['user'] = $row;
   $_SESSION['user']['admin'] == 1 ? $_SESSION['user']['admin'] = true : $_SESSION['user']['admin'] = false;
   $_SESSION['user']['id'] = intval($_SESSION['user']['id']);
   if ($_SESSION['user']['picture'] == '') $_SESSION['user']['picture'] = $_CONFIG['app']['default_profile_picture_name'];
   $_SESSION['user']['logged'] = true;
   unset($_SESSION['user']['password']);

   echo json_encode([
      'error' => false,
      'msg' => 'Zalogowano pomyślnie!',
      'status' => 'success'
   ]);
} catch (PDOException $e) {
   echo json_encode([
      'error' => true,
      'msg' => 'Wystąpił błąd bazy danych!',
      'status' => 'danger'
   ]);
}

try {
   $sql = "UPDATE users SET last_time_online = NOW() WHERE id = :id LIMIT 1";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([
      'id' => $_SESSION['user']['id']
   ]);
} catch (PDOException $e) {
}
