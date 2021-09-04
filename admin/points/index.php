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
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
   <script src="./../../assets/js/tablesorter.min.js"></script>
   <script>
      const DEFAULT_PROFILE_PICTURE_NAME = '<?php echo $_CONFIG['app']['default_profile_picture_name']; ?>',
         PROFILE_PICTURES_PATH = '<?php echo $_CONFIG['app']['profile_pictures_path']; ?>';
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
         $('.tablesorter').tablesorter({
            sortResetKey: 'ctrlKey',
            cssAsc: 'up',
            cssDesc: 'down',
            cssNone: 'non'
         });
      });
   </script>
   <script src="./index.min.js" defer></script>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-container-small uk-margin-top uk-margin-large-bottom">
      <div>
         <div>
            <fieldset class="uk-fieldset">
               <legend class="uk-legend">Wybierz miesiąc oraz rok</legend>
               <div class="uk-margin uk-flex">
                  <select class="uk-select" name="month" required>
                     <option value="1" <?php if (Date('m') == 1) echo 'selected'; ?>>Styczeń</option>
                     <option value="2" <?php if (Date('m') == 2) echo 'selected'; ?>>Luty</option>
                     <option value="3" <?php if (Date('m') == 3) echo 'selected'; ?>>Marzec</option>
                     <option value="4" <?php if (Date('m') == 4) echo 'selected'; ?>>Kwiecień</option>
                     <option value="5" <?php if (Date('m') == 5) echo 'selected'; ?>>Maj</option>
                     <option value="6" <?php if (Date('m') == 6) echo 'selected'; ?>>Czerwiec</option>
                     <option value="7" <?php if (Date('m') == 7) echo 'selected'; ?>>Lipiec</option>
                     <option value="8" <?php if (Date('m') == 8) echo 'selected'; ?>>Sierpień</option>
                     <option value="9" <?php if (Date('m') == 9) echo 'selected'; ?>>Wrzesień</option>
                     <option value="10" <?php if (Date('m') == 10) echo 'selected'; ?>>Październik</option>
                     <option value="11" <?php if (Date('m') == 11) echo 'selected'; ?>>Listopad</option>
                     <option value="12" <?php if (Date('m') == 12) echo 'selected'; ?>>Grudzień</option>
                  </select>
                  <select class="uk-select" name="year" required>
                     <?php for ($i = intval(Date('Y')); $i >= 2018; --$i) : ?>
                        <option value="<?php echo $i; ?>" <?php if ($i == intval(Date('Y'))) echo 'selected'; ?>><?php echo $i; ?></option>
                     <?php endfor; ?>
                  </select>
               </div>
            </fieldset>
         </div>
      </div>
      <div class="uk-overflow-auto">
         <table class="uk-table uk-table-divider uk-table-hover tablesorter">
            <thead>
               <tr>
                  <th class="uk-text-center">#ID</th>
                  <th class="uk-text-center">IMIĘ</th>
                  <th class="uk-text-center">NAZWISKO</th>
                  <th class="uk-text-center">STOPIEŃ</th>
                  <th class="uk-text-center sorter-false">PUNKTY DODATNIE</th>
                  <th class="uk-text-center sorter-false">PUNKTY UJEMNE</th>
               </tr>
            </thead>
            <tbody></tbody>
         </table>
      </div>
      <button class="uk-input uk-margin-top" style="cursor: pointer;" onclick="savePoints()">ZAPISZ PUNKTY</button>
   </div>

   <template>
      <tr>
         <td data-id class="uk-text-center"></td>
         <td data-name class="uk-text-center"></td>
         <td data-last_name class="uk-text-center"></td>
         <td data-role class="uk-text-center"></td>
         <td data-points_plus class="uk-text-center">
            <input class="uk-input" type="number" step="1" name="points_plus" placeholder="Punkty (+)">
         </td>
         <td data-points_minus class="uk-text-center">
            <input class="uk-input" type="number" step="1" name="points_minus" placeholder="Punkty (-)">
         </td>
      </tr>
   </template>

</body>

</html>