<?php
require_once __DIR__ . './../../../assets/php/check-if-logged.php';

if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) {
   header('Location: /profile.php');
   exit();
}
require_once __DIR__ . './../../../config.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
   <?php require_once __DIR__ . './../../../assets/wireframe/head.php'; ?>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
   <script src="./../../../assets/js/tablesorter.min.js"></script>
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
         $(".tablesorter").tablesorter({
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

   <?php require_once __DIR__ . './../../../assets/wireframe/navbar.php'; ?>

   <div class="uk-container-large uk-align-center">
      <progress class="uk-progress uk-flex-middle" value="0" max="100" id="bar" hidden></progress>
      <div class="uk-overflow-auto">
         <table class="uk-table uk-table-divider uk-table-hover tablesorter">
            <thead>
               <tr>
                  <th class="uk-text-center">#ID</th>
                  <th class="uk-text-center sorter-false">ZDJĘCIE</th>
                  <th class="uk-text-center">IMIĘ</th>
                  <th class="uk-text-center">NAZWISKO</th>
                  <th class="uk-text-center">STOPIEŃ</th>
                  <th class="uk-text-center">DATA URODZENIA</th>
                  <th class="uk-text-center">LOGIN</th>
                  <th class="uk-text-center">E-MAIL</th>
                  <th class="uk-text-center">NR TEL.</th>
                  <th class="uk-text-center">ADMIN</th>
                  <th class="uk-text-center">ONLINE</th>
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
                  <legend class="uk-legend">Edytuj dane</legend>
                  <div class="uk-margin">
                     <img src="" alt="zdjęcie profilowe" class="uk-border-circle" width="40" height="40">
                     <label><input class="uk-checkbox" type="checkbox" name="delete-picture">&nbsp;Usuń zdjęcie profilowe</label>
                  </div>
                  <div class="uk-margin uk-flex">
                     <input type="text" name="id" hidden required>
                     <input class="uk-input" type="text" placeholder="Imię" name="name" required>
                     <input class="uk-input" type="text" placeholder="Nazwisko" name="last_name" required>
                  </div>

                  <div class="uk-margin">
                     <input class="uk-input" type="date" placeholder="Data urodzenia" name="birthdate" required>
                  </div>

                  <div class="uk-margin">
                     <input class="uk-input" type="text" placeholder="Login" name="username" minlength="4" maxlength="30" required>
                  </div>

                  <div class="uk-margin">
                     <input class="uk-input" type="email" placeholder="Adres e-mail" name="email">
                  </div>

                  <div class="uk-margin">
                     <input class="uk-input" type="text" placeholder="Numer telefonu (bez spacji oraz nr kierunkowego)" name="phone_no" required>
                  </div>

                  <div class="uk-margin">
                     <select class="uk-select" name="role" required>
                        <option value="ministrant">Ministrant</option>
                        <option value="lektor">Lektor</option>
                        <option value="ksiądz">Ksiądz</option>
                     </select>
                  </div>

                  <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                     <label><input class="uk-checkbox" type="checkbox" name="admin"> Administrator</label>
                  </div>

                  <div class="uk-margin">
                     <input class="uk-input" type="text" placeholder="Online" name="online" readonly disabled required>
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
         <td class="uk-text-center">
            <img data-picture src="" class="uk-border-circle" width="35" height="35" />
         </td>
         <td data-picture_name hidden></td>
         <td data-name class="uk-text-center"></td>
         <td data-last_name class="uk-text-center"></td>
         <td data-role class="uk-text-center"></td>
         <td data-birthdate class="uk-text-center"></td>
         <td data-username class="uk-text-center"></td>
         <td data-email class="uk-text-center"></td>
         <td data-phone_no class="uk-text-center"></td>
         <td data-admin class="uk-text-center"></td>
         <td data-online class="uk-text-center"></td>
         <td class="uk-text-center">
            <button class="uk-button uk-button-default" data-edit>EDYTUJ</button>
         </td>
      </tr>
   </template>

</body>

</html>