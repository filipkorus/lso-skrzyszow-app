<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/User.php';

$db = new Database;
$pdo = $db->connect();

User::logout();
