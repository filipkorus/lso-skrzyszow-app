<?php
require_once __DIR__ . './../../assets/php/check-if-logged.php';
require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/Points.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

$db = new Database();
$pdo = $db->connect();

header('Content-Type: application/json');

$record_id = Points::getRecordId($_POST['uid'], $_POST['month'], $_POST['year']);

if (
   $record_id ?
   Points::updateRecord($record_id, $_POST['points_plus'], $_POST['points_minus']) :
   Points::insertRecord($_POST['uid'], $_POST['month'], $_POST['year'], $_POST['points_plus'], $_POST['points_minus'])
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
