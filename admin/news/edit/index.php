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
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-container-small uk-margin-top uk-margin-bottom">
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
   <!-- TODO: FIXME: zrobic caly panel edycji (ten) -->


   <script>
      let ediotr;

      ClassicEditor
         .create(document.querySelector('textarea[name=ckeditor]'), {
            toolbar: {
               items: [
                  'heading',
                  '|',
                  'fontSize',
                  'fontColor',
                  'fontFamily',
                  'fontBackgroundColor',
                  '|',
                  'bold',
                  'italic',
                  'underline',
                  '|',
                  'alignment',
                  '|',
                  'numberedList',
                  'bulletedList',
                  '|',
                  'insertTable',
                  'horizontalLine',
                  'link',
                  'blockQuote',
                  'code',
                  'highlight',
                  'specialCharacters',
                  '|',
                  'indent',
                  'outdent',
                  '|',
                  'undo',
                  'redo',
                  '|',
                  'sourceEditing'
               ]
            },
            language: 'pl',
            table: {
               contentToolbar: [
                  'tableColumn',
                  'tableRow',
                  'mergeTableCells',
                  'tableCellProperties',
                  'tableProperties'
               ]
            },
            licenseKey: '',
         })
         .then(newEditor => {
            editor = newEditor;
         })
         .catch(error => {
            console.error('Oops, something went wrong!');
            console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
            console.warn('Build id: 5nm2tema1l6r-ciour6nn4q13');
            console.error(error);
         });
   </script>
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