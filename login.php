<?php

if (!isset($_SESSION)) session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/User.php';

$pdo = Database::connect();

if (!(isset($_POST['username']) && isset($_POST['password'])) || empty($_POST['username']) || empty($_POST['password'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Wypełnij wszystkie pola!',
      'status' => 'danger'
   ]);
   return;
}

User::login($_POST['username'], $_POST['password']);
