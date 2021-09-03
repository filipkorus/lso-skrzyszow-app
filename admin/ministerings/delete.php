<?php
require_once __DIR__ . './../../assets/php/check-if-logged.php';
require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/Ministerings.php';

$db = new Database();
$pdo = $db->connect();

header('Content-Type: application/json');

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
