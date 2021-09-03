<?php
require_once __DIR__ . './../../assets/php/check-if-logged.php';
require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/Ministerings.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

$db = new Database();
$pdo = $db->connect();

header('Content-Type: application/json');

if (
   intval($_POST['row_id']) == 0 ?
   Ministerings::insertRecord($_POST['uid'], $_POST['day_of_week'], $_POST['hour']) :
   Ministerings::updateRecord($_POST['row_id'], $_POST['day_of_week'], $_POST['hour'])
) {
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
