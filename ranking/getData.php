<?php
require_once __DIR__ . './../assets/php/check-if-logged.php';
require_once __DIR__ . './../config.php';
require_once __DIR__ . './../classes/Database.php';
require_once __DIR__ . './../classes/Ranking.php';

$db = new Database();
$pdo = $db->connect();

header('Content-Type: application/json');

echo json_encode (
   isset($_GET['ranking']) && $_GET['ranking'] == 'month' ?
   Ranking::getAllPointsMonth(
      isset($_GET['month']) ? $_GET['month'] : Date('m'),
      isset($_GET['year']) ? $_GET['year'] : Date('Y'),
      isset($_GET['role']) ? $_GET['role'] : null
   ) :
   Ranking::getAllPointsYear(
      isset($_GET['year']) ? $_GET['year'] : Date('Y'),
      isset($_GET['role']) ? $_GET['role'] : null
   )
);
