<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Ministerings.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

$db = new Database();
$pdo = $db->connect();

header('Content-Type: application/json');

if (!isset($_POST['row_id']) || empty($_POST['row_id'])) {
   echo json_encode([
      'error' => true,
      'msg' => 'Wypełnij wszystkie pola!',
      'status' => 'danger'
   ]);
   return;
}

if (Ministerings::deleteRecord($_POST['row_id'])) {
   echo json_encode([
      'error' => false,
      'msg' => 'Zapisano zmiany!',
      'status' => 'success'
   ]);
   return;
}

echo json_encode([
   'error' => true,
   'msg' => 'database error',
   'status' => 'danger'
]);
