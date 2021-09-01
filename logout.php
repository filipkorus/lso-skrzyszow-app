<?php

require_once __DIR__ . './classes/Database.php';
require_once __DIR__ . './classes/User.php';

$db = new Database;
$pdo = $db->connect();

User::logout();
