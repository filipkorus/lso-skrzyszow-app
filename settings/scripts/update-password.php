<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/User.php';

$db = new Database;
$pdo = $db->connect();

if (!(isset($_POST['new_password']) && isset($_POST['password'])) || empty($_POST['new_password']) || empty($_POST['password'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Uzupełnij wszystkie pola!',
      'status' => 'danger'
   ]);
   return;
}

if (strlen($_POST['new_password']) < 6 || strlen($_POST['new_password']) > 30) {
   echo json_encode([
      'error' => true,
      'msg' => 'Hasło powinno posiadać od 6 do 30 znaków!',
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

User::updatePassword($_POST['new_password']);
