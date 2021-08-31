<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

header('Content-Type: application/json');

require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/User.php';
require_once __DIR__ . './../../classes/Admin.php';

$db = new Database;
$pdo = $db->connect();

if (
   !(isset($_POST['name']) && isset($_POST['last_name']) && isset($_POST['birthdate']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['phone_no']) && isset($_POST['role']) && isset($_POST['admin'])) ||
   empty($_POST['name']) || empty($_POST['last_name']) || empty($_POST['birthdate']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['phone_no']) || empty($_POST['role']) || empty($_POST['admin'])
) {
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

if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 30) {
   echo json_encode([
      'error' => true,
      'msg' => 'Hasło powinno posiadać od 6 do 30 znaków!',
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

if (User::isEmailUsed($_POST['email'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Ten e-mail jest już zajęty!',
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

Admin::addNewUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['phone_no'], $_POST['name'], $_POST['last_name'], $_POST['birthdate'], $_POST['role'], $_POST['admin'] == 'true' ? 1 : 0);
