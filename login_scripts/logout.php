<?php
session_start();

require_once __DIR__ . './../classes/Database.php';

$db = new Database;
$pdo = $db->connect();

try {
   $sql = "UPDATE users SET last_time_online = now() WHERE id = :id LIMIT 1";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([
      'id' => $_SESSION['user']['id']
   ]);
} catch (PDOException $e) {
}

session_unset();
header('Location: /');
