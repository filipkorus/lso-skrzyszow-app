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
      <div class="uk-overflow-auto">
         <table class="uk-table uk-table-divider uk-table-hover tablesorter">
            <thead>
               <tr>
                  <th class="uk-text-center">#ID</th>
                  <th class="uk-text-center">IMIĘ</th>
                  <th class="uk-text-center">NAZWISKO</th>
                  <th class="uk-text-center">STOPIEŃ</th>
                  <th class="uk-text-center sorter-false">SŁUŻENIA</th>
                  <th class="uk-text-center sorter-false">AKCJA</th>
               </tr>
            </thead>
            <tbody></tbody>
         </table>
      </div>
   </div>

   <div id="modal" class="uk-flex-top" uk-modal>
      <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
         <button class="uk-modal-close-default" type="button" uk-close></button>
         <div>
            <form>
               <fieldset class="uk-fieldset">
                  <legend class="uk-legend">Edytuj służenia</legend>
                  <div class="uk-margin uk-flex">
                     <input type="number" name="id" required hidden>
                     <input class="uk-input" type="text" name="name" readonly>
                     <input class="uk-input" type="text" name="last_name" readonly>
                  </div>

                  <div class="uk-margin">
                     <input class="uk-input" type="text" name="role" readonly>
                  </div>

                  <legend class="uk-legend">Służenia</legend>
                  <div class="uk-margin uk-flex">
                     <select class="uk-select" name="day_of_week1" required>
                        <option value="null">Dzień tygodnia</option>
                        <option value="1">Poniedziałek</option>
                        <option value="2">Wtorek</option>
                        <option value="3">Środa</option>
                        <option value="4">Czwartek</option>
                        <option value="5">Piątek</option>
                        <option value="6">Sobota</option>
                        <option value="7">Niedziela</option>
                        <option value="delete">Usuń służenie</option>
                     </select>
                     <input class="uk-input" type="text" onfocus="this.type='time'" placeholder="Godzina" name="hour1">
                     <input type="text" name="row_id1" hidden>
                  </div>

                  <div class="uk-margin uk-flex">
                     <select class="uk-select" name="day_of_week2" required>
                        <option value="null">Dzień tygodnia</option>
                        <option value="1">Poniedziałek</option>
                        <option value="2">Wtorek</option>
                        <option value="3">Środa</option>
                        <option value="4">Czwartek</option>
                        <option value="5">Piątek</option>
                        <option value="6">Sobota</option>
                        <option value="7">Niedziela</option>
                        <option value="delete">Usuń służenie</option>
                     </select>
                     <input class="uk-input" type="text" onfocus="this.type='time'" placeholder="Godzina" name="hour2">
                     <input type="text" name="row_id2" hidden>
                  </div>

                  <div class="uk-margin uk-flex">
                     <select class="uk-select" name="day_of_week3" required>
                        <option value="null">Dzień tygodnia</option>
                        <option value="1">Poniedziałek</option>
                        <option value="2">Wtorek</option>
                        <option value="3">Środa</option>
                        <option value="4">Czwartek</option>
                        <option value="5">Piątek</option>
                        <option value="6">Sobota</option>
                        <option value="7">Niedziela</option>
                        <option value="delete">Usuń służenie</option>
                     </select>
                     <input class="uk-input" type="text" onfocus="this.type='time'" placeholder="Godzina" name="hour3">
                     <input type="text" name="row_id3" hidden>
                  </div>

                  <div class="uk-margin">
                     <input class="uk-input" type="submit" name="submit" value="ZAPISZ ZMIANY" style="cursor: pointer;">
                  </div>

               </fieldset>
            </form>
         </div>
      </div>
   </div>

   <template>
      <tr>
         <td data-id class="uk-text-center"></td>
         <td data-name class="uk-text-center"></td>
         <td data-last_name class="uk-text-center"></td>
         <td data-role class="uk-text-center"></td>
         <td data-ministerings class="uk-text-center"></td>
         <td class="uk-text-center">
            <button class="uk-button uk-button-default" data-edit>EDYTUJ</button>
         </td>
      </tr>
   </template>

</body>

</html>