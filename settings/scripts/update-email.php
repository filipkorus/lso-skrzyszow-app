<?php

require_once __DIR__ . './../../assets/php/check-if-logged.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

header('Content-Type: application/json');

require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/User.php';

$db = new Database;
$pdo = $db->connect();

if (!(isset($_POST['email']) && isset($_POST['password'])) || empty($_POST['email']) || empty($_POST['password'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Uzupełnij wszystkie pola!',
      'status' => 'danger'
   ]);
   return;
}

if (User::isEmailUsed($_POST['email'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Ten e-mail jest już zajęty!',
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

User::updateEmail($_POST['email']);
