<?php
require_once __DIR__ . './../../assets/php/check-if-logged.php';
require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/Admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

$db = new Database();
$pdo = $db->connect();

header('Content-Type: application/json');

if (
   $_POST['row_id'] == '0' ?
   Admin::insertPoints($_POST['uid'], $_POST['month'], $_POST['year'], $_POST['points_plus'], $_POST['points_minus']) :
   Admin::updatePoints($_POST['row_id'], $_POST['points_plus'], $_POST['points_minus'])
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
