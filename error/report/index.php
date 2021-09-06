<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/head.php'; ?>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-container-small">
      <form>
         <fieldset class="uk-fieldset">
            <legend class="uk-legend">Zgłaszanie błędów/uwag/sugestii</legend>
            <div class="uk-margin">
               <input class="uk-input" type="text" placeholder="Temat" name="title" autofocus required>
            </div>

            <div class="uk-margin">
               <textarea class="uk-textarea" name="body" cols="30" rows="10" placeholder="Treść (jeżeli zgłaszasz błąd - koniecznie napisz gdzie i kiedy występuje)" style="resize: none;" required></textarea>
            </div>

            <div class="uk-margin">
               <button class="uk-input" type="submit" style="cursor: pointer;">ZGŁOŚ BŁĄD</button>
            </div>

         </fieldset>
      </form>
   </div>

   <script>
      document.querySelector('form').onsubmit = async e => {
         e.preventDefault();

         const titleInput = document.querySelector('input[name=title]'),
            bodyInput = document.querySelector('textarea[name=body]');

         if (!(titleInput.value.length && bodyInput.value.length)) return UIkit.notification({
            message: 'Uzuepłnij wszystkie pola!',
            status: 'danger',
            timeout: 5000
         });

         const formData = new FormData();
         formData.append('title', titleInput.value);
         formData.append('body', bodyInput.value);

         const res = await fetch('./submit.php', {
            method: 'POST',
            body: formData
         });
         const data = await res.json();

         UIkit.notification({
            message: data.msg,
            status: data.status || 'danger',
            timeout: 5000
         });

         if (!data.error) {
            titleInput.value = '';
            bodyInput.value = '';
         }
      }
   </script>

</body>

</html>