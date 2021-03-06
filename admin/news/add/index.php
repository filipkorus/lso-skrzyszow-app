<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';

if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) {
   header('Location: /profile.php');
   exit();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
?>
<!DOCTYPE html>
<html lang="pl" class="uk-background-muted">

<head>
   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/head.php'; ?>

   <!-- CKEditor JS -->
   <script src="/assets/js/ckeditor.js"></script>

   <!-- CKEditor CSS -->
   <link rel="stylesheet" href="/assets/css/ckeditor.css">

   <!-- CKEditor Init -->
   <script src="/assets/js/ckeditor-init.js" defer></script>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-container-small">
      <form>
         <fieldset class="uk-fieldset">
            <legend class="uk-legend">Dodaj ogłoszenie</legend>

            <div class="uk-margin">
               <input class="uk-input" type="text" placeholder="Tytuł" name="title" required autofocus>
            </div>

            <div class="uk-margin">
               <textarea name="ckeditor" placeholder="Treść"></textarea>
            </div>

            <div class="uk-margin">
               <button class="uk-input" type="submit" style="cursor: pointer;">DODAJ</button>
            </div>
         </fieldset>
      </form>
   </div>

   <script>
      document.querySelector('form').onsubmit = async e => {
         e.preventDefault();

         const titleInput = document.querySelector('input[name=title]'),
            body = editor.getData();

         if (!(titleInput.value.length && body.length)) return UIkit.notification({
            message: 'Uzupełnij wszystkie pola!',
            status: 'danger',
            timeout: 5000
         });

         const formData = new FormData();
         formData.append('title', titleInput.value);
         formData.append('body', body);

         const res = await fetch('./../add.php', {
            method: 'POST',
            body: formData
         });
         const data = await res.json();

         UIkit.notification({
            message: data.msg,
            status: data.status || 'danger',
            timeout: 5000
         });

         if (data.error) return;

         titleInput.value = '';
         editor.setData('');
      }
   </script>
</body>

</html>