<?php

if (!isset($_SESSION)) session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
header('Content-Type: application/json');

require_once __DIR__ . './config.php';
require_once __DIR__ . './classes/Database.php';
require_once __DIR__ . './classes/User.php';

$db = new Database;
$pdo = $db->connect();

if (!(isset($_POST['username']) && isset($_POST['password'])) || empty($_POST['username']) || empty($_POST['password'])
) {
   echo json_encode([
      'error' => true,
      'msg' => 'Fill in all fields!',
      'status' => 'danger'
   ]);
   return;
}

User::login($_POST['username'], $_POST['password']);
