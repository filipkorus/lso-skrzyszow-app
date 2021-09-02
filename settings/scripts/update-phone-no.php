<?php

require_once __DIR__ . './../../assets/php/check-if-logged.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

header('Content-Type: application/json');

require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/User.php';

$db = new Database;
$pdo = $db->connect();

if (!(isset($_POST['phone_no']) && isset($_POST['password'])) || empty($_POST['phone_no']) || empty($_POST['password'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Uzupełnij wszystkie pola!',
      'status' => 'danger'
   ]);
   return;
}

if (User::isPhoneNoUsed($_POST['phone_no'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Ten numer telefonu jest już używany!',
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

User::updatePhoneNo($_POST['phone_no']);
