<?php

if (!isset($_SESSION)) session_start();
if (!(isset($_SESSION['user']['logged']) && $_SESSION['user']['logged'])) {
   header('Location: /');
   exit();
}

if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) {
   header('Location: /profile.php');
   exit();
}

header('Content-Type: application/json');

require_once __DIR__ . './../../config.php';
require_once __DIR__ . './../../classes/Database.php';
require_once __DIR__ . './../../classes/Admin.php';

$db = new Database;
$pdo = $db->connect();

echo json_encode(
   isset($_GET['role'])
      ? Admin::getUsersWithRole($_GET['role'])
      : Admin::getAllUsers()
);
