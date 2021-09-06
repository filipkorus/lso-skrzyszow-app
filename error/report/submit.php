<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Errors.php';

header('Content-Type: application/json');

if (!(isset($_POST['title']) && isset($_POST['body'])) || empty($_POST['title']) || empty($_POST['body'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Uzuepłnij wszystkie pola!',
      'status' => 'danger'
   ]);
   return;
}

$db = new Database();
$pdo = $db->connect();

Errors::reportNew($_POST['title'], $_POST['body']);
