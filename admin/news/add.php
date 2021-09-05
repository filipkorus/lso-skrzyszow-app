<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/News.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) {
   header('Location: /profile.php');
   exit();
}

$db = new Database();
$pdo = $db->connect();

header('Content-Type: application/json');

if (
   !(isset($_POST['title']) && isset($_POST['body'])) ||
   empty($_POST['title']) || empty($_POST['body'])
) {
   echo json_encode([
      'error' => true,
      'msg' => 'Uzupełnij wszystkie pola!',
      'status' => 'danger'
   ]);
   return;
}

News::add($_POST['title'], $_POST['body']);
