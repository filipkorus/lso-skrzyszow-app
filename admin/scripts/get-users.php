<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';

if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) {
   header('Location: /profile.php');
   exit();
}

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Admin.php';

$pdo = Database::connect();

echo json_encode(
   isset($_GET['role'])
      ? Admin::getUsersWithRole($_GET['role'])
      : Admin::getAllUsers()
);
