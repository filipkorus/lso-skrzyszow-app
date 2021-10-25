<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/News.php';

$pdo = Database::connect();

if (!isset($_GET['news_id'])) {
   header('Location: /profile.php');
   exit();
}

$news = News::getById($_GET['news_id']);

if ($news == null) {
   header('Location: /profile.php');
   exit();
}

$html = '
<!DOCTYPE html>
<html lang="pl">
<head>
   <style>body{font-family: DejaVu Sans;}a{color: black;text-decoration: none;}</style>   
</head>
<body>
   <article>
      <h1>' . $news['title'] . '</h1>
      <div>
         <div>Data publikacji: ' . $news['added_at'] . '</div>
         <div>Autor: ' . $news['author'] . '</div>
      </div>
      <div>' . $news['body'] . '</div>
   </article>
</body>
</html>
';

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream($news['title'] . '.pdf');
