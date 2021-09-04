<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Ministerings.php';

$db = new Database();
$pdo = $db->connect();

header('Content-Type: application/json');

echo json_encode (
   Ministerings::getAllMinisterings()
);
