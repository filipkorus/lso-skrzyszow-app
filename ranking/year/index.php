<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
?>
<!DOCTYPE html>
<html lang="pl" class="uk-background-muted">

<head>
   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/head.php'; ?>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
   <script src="./../../assets/js/tablesorter.min.js"></script>
   <script>
      const DEFAULT_PROFILE_PICTURE_NAME = '<?php echo $_CONFIG['app']['default_profile_picture_name']; ?>',
         PROFILE_PICTURES_PATH = '<?php echo $_CONFIG['app']['profile_pictures_path']; ?>',
         USER_ROLE = '<?php echo $_SESSION['user']['role'] ?>',
         RANKING = 'year';
   </script>
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
   <script src="./../index.min.js" defer></script>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-container-small uk-margin-top uk-margin-large-bottom">
      <div>
         <div>
            <fieldset class="uk-fieldset">
               <legend class="uk-legend">Wybierz rok</legend>

               <div class="uk-margin uk-flex">
                  <select class="uk-select" name="year" required>
                     <?php for ($i = intval(Date('Y')); $i >= 2018; --$i) : ?>
                        <option value="<?php echo $i; ?>" <?php if ($i == intval(Date('Y'))) echo 'selected'; ?>><?php echo $i; ?></option>
                     <?php endfor; ?>
                  </select>
               </div>

               <legend class="uk-legend">Wybierz stopień</legend>
               <select class="uk-select" name="role" required>
                  <option value="ministrant" <?php if ($_SESSION['user']['role'] === 'ministrant') echo 'selected'; ?>>Ministranci</option>
                  <option value="lektor" <?php if ($_SESSION['user']['role'] === 'lektor') echo 'selected'; ?>>Lektorzy</option>
                  <option value="" <?php if ($_SESSION['user']['role'] === 'ksiądz') echo 'selected'; ?>>Wszyscy</option>
               </select>

            </fieldset>
         </div>
      </div>
      <div class="uk-overflow-auto">
         <table class="uk-table uk-table-divider uk-table-hover tablesorter">
            <thead>
               <tr>
                  <th class="uk-text-center">MIEJSCE</th>
                  <th class="uk-text-center">NAZWISKO</th>
                  <th class="uk-text-center">IMIĘ</th>
                  <th class="uk-text-center">PUNKTY</th>
                  <th class="uk-text-center">STOPIEŃ</th>
                  <th class="uk-text-center sorter-false">ZDJĘCIE</th>
               </tr>
            </thead>
            <tbody></tbody>
         </table>
      </div>
   </div>

   <template>
      <tr>
         <td data-place class="uk-text-center"></td>
         <td data-last_name class="uk-text-center"></td>
         <td data-name class="uk-text-center"></td>
         <td data-points class="uk-text-center"></td>
         <td data-role class="uk-text-center"></td>
         <td class="uk-text-center">
            <img data-picture src="" class="uk-border-circle" width="40" height="40" />
         </td>
      </tr>
   </template>

</body>

</html>