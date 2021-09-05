<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/News.php';

header('Content-Type: application/json');

$db = new Database();
$pdo = $db->connect();

echo json_encode(
   News::get(
      (isset($_GET['n']) && is_numeric($_GET['n'])) ? $_GET['n'] : 1,
      isset($_GET['wo_body']) ?? true
   )
);
