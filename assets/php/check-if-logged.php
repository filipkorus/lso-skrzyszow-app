<?php
if(!isset($_SESSION)) session_start();
if (!(isset($_SESSION['user']['logged']) && $_SESSION['user']['logged'])) {
   header('Location: /');
   exit();
}
