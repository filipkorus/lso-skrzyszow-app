<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';

if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) {
   header('Location: /profile.php');
   exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/News.php';
?>
<!DOCTYPE html>
<html lang="pl" class="uk-background-muted">

<head>
   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/head.php'; ?>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
   <script src="/assets/js/tablesorter.min.js"></script>
   <style>
      th {
         cursor: pointer;
      }

      thead th {
         background-repeat: no-repeat;
         background-position: right center;
         outline: none;
      }

      thead th.up {
         padding-right: 10px;
         background-image: url(data:image/gif;base64,R0lGODlhFQAEAIAAAP///////yH5BAEAAAEALAAAAAAVAAQAAAINjI8Bya2wnINUMopZAQA7);
         filter: invert(1);
      }

      thead th.down {
         padding-right: 10px;
         background-image: url(data:image/gif;base64,R0lGODlhFQAEAIAAAP///////yH5BAEAAAEALAAAAAAVAAQAAAINjB+gC+jP2ptn0WskLQA7);
         filter: invert(1);
      }

      thead th.non {
         padding-right: 10px;
         background-image: url(data:image/gif;base64,R0lGODlhFQAJAIAAAP///////yH5BAEAAAEALAAAAAAVAAkAAAIXjI+AywnaYnhUMoqt3gZXPmVg94yJVQAAOw==);
         filter: invert(1);
      }
   </style>
   <script>
      $(document).ready(function() {
         $(".tablesorter").tablesorter({
            sortResetKey: 'ctrlKey',
            cssAsc: 'up',
            cssDesc: 'down',
            cssNone: 'non'
         });
      });
   </script>

   <!-- CKEditor JS -->
   <script src="/assets/js/ckeditor.js"></script>

   <!-- CKEditor CSS -->
   <link rel="stylesheet" href="/assets/css/ckeditor.css">

   <script src="./index.min.js" defer></script>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-container-small">
      <button class="uk-button uk-button-default uk-width-1-1" name="loadNews">ZAŁADUJ WIĘCEJ...</button>
      <div class="uk-overflow-auto">
         <table class="uk-table uk-table-divider uk-table-hover tablesorter">
            <thead>
               <tr>
                  <th class="uk-text-center">TYTUŁ</th>
                  <th class="uk-text-center">TEKST</th>
                  <th class="uk-text-center">DATA PUBLIKACJI</th>
                  <th class="uk-text-center">AUTOR</th>
                  <th class="uk-text-center sorter-false">AKCJA</th>
               </tr>
            </thead>
            <tbody></tbody>
         </table>
      </div>
   </div>

   <div id="modal" class="uk-modal-full" uk-modal>
      <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical uk-padding-large">
         <button class="uk-modal-close-default" type="button" uk-close id="modal-close"></button>
         <div>
            <form class="uk-overflow-auto">
               <fieldset class="uk-fieldset">
                  <legend class="uk-legend">Edytuj ogłoszenie</legend>

                  <div class="uk-margin uk-flex">
                     <label class="uk-width-1-2">
                        Data publikacji
                        <input class="uk-input" type="text" placeholder="Data publikacji" name="added_at" readonly>
                     </label>
                     <label class="uk-width-1-2">
                        Autor
                        <input class="uk-input" type="text" placeholder="Autor" name="author" readonly>
                     </label>
                     <input type="text" name="id" hidden>
                  </div>

                  <div class="uk-margin">
                     <label>
                        Tytuł
                        <input class="uk-input" type="text" placeholder="Tytuł" name="title" required>
                     </label>
                  </div>

                  <div class="uk-margin">
                     <textarea name="ckeditor" placeholder="Treść"></textarea>
                  </div>

                  <div class="uk-margin">
                     <button class="uk-input" type="submit" style="cursor: pointer;">ZAPISZ ZMIANY</button>
                  </div>

                  <div class="uk-margin">
                     <button class="uk-input" type="button" style="cursor: pointer;" onclick="document.getElementById('modal-close').click()">ANULUJ</button>
                  </div>
               </fieldset>
            </form>
         </div>
      </div>
   </div>

   <template>
      <tr>
         <td class="uk-text-center">
            <a data-title href="" target="_blank" class="uk-link-text"></a>
         </td>
         <td class="uk-text-center">
            <a data-body href="" target="_blank" class="uk-link-text"></a>
         </td>
         <td data-added_at class="uk-text-center"></td>
         <td data-author class="uk-text-center"></td>
         <td class="uk-text-center uk-flex">
            <button class="uk-button uk-button-default uk-width-1-2" data-edit>EDYTUJ</button>
            <button class="uk-button uk-button-default uk-width-1-2" data-delete onclick="if (confirm('Czy na pewno chcesz usunąć to ogłoszenie?')) deleteNews(this.parentElement.parentNode.querySelector('td[data-id]').textContent)">USUŃ</button>
         </td>
         <td data-id hidden></td>
      </tr>
   </template>

   <script>
      let editor;

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

</body>

</html>