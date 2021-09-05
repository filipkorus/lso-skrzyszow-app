<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/News.php';

$db = new Database();
$pdo = $db->connect();

if (!isset($_GET['news_id'])) {
   header('Location: /profile.php');
   exit();
}

$news = News::getById($_GET['news_id']);

if ($news == null) {
   header('Location: /profile.php');
   exit();
}
?>
<!DOCTYPE html>
<html lang="pl" class="uk-background-muted">

<head>
   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/head.php'; ?>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-margin">
      <div>
         <article class="uk-card uk-card-default uk-card-body">
            <h1 class="uk-article-title"><a class="uk-link-reset" href=""><?php echo $news['title']; ?></a></h1>
            <p class="uk-article-meta">Data publikacji: <?php echo $news['added_at']; ?><br>Autor: <a href="#"><?php echo $news['author']; ?></a></p>
            <div>
               <p class="uk-text-lead"><?php echo $news['body']; ?></p>
            </div>
         </article>
      </div>
   </div>

</body>

</html>
