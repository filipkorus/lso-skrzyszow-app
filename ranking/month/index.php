<?php
require_once __DIR__ . './../../assets/php/check-if-logged.php';
require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/Ranking.php';

$db = new Database();
$pdo = $db->connect();

header('Content-Type: application/json');
echo json_encode(Ranking::getAllPointsMonth(8, 2021));
