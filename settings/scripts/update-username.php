<?php

require_once __DIR__ . './../../assets/php/check-if-logged.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

header('Content-Type: application/json');

require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/User.php';

$db = new Database;
$pdo = $db->connect();

if (!(isset($_POST['username']) && isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password']))) {
   echo json_encode([
      'error' => true,
      'msg' => 'Uzupełnij wszystkie pola!',
      'status' => 'danger'
   ]);
   return;
}

if (strlen($_POST['username']) < 4 || strlen($_POST['username']) > 30) {
   echo json_encode([
      'error' => true,
      'msg' => 'Login powienien posiadać od 4 do 30 znaków!',
      'status' => 'danger'
   ]);
   return;
}

if (User::isUsernameUsed($_POST['username'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Ten login jest już zajęty!',
      'status' => 'danger'
   ]);
   return;
}

if (!User::isPasswordCorrect($_POST['password'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Nieprawidłowe hasło!',
      'status' => 'danger'
   ]);
   return;
}

User::updateUsername($_POST['username']);
