<?php
if (!isset($_SESSION)) session_start();

if (!(isset($_SESSION['user']['logged']) && $_SESSION['user']['logged'])) {
   header('Location: /');
   exit();
}
?>
<!DOCTYPE html>
<html lang="pl" class="uk-background-muted">

<head>
   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/head.php'; ?>

   <script src="./settings.min.js" defer></script>
</head>

<body class="uk-background-muted">

   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-container-small uk-margin-bottom">
      <div class="uk-child-width-1-2 uk-text-center uk-margin-top" uk-grid>
         <form name="username">
            <fieldset class="uk-fieldset uk-box-shadow-large uk-padding-small">
               <legend class="uk-legend">Zmień login</legend>
               <div class="uk-margin">
                  <input class="uk-input" type="text" placeholder="Nowy login" name="username" minlength="4" maxlength="30" required>
               </div>
               <div class="uk-margin">
                  <input class="uk-input" type="submit" style="cursor: pointer;" value="ZAPISZ">
               </div>
            </fieldset>
         </form>
         <form name="email">
            <fieldset class="uk-fieldset uk-box-shadow-large uk-padding-small">
               <legend class="uk-legend">Zmień adres e-mail</legend>
               <div class="uk-margin">
                  <input class="uk-input" type="email" placeholder="Nowy adres e-mail" name="email" required>
               </div>
               <div class="uk-margin">
                  <input class="uk-input" type="submit" style="cursor: pointer;" value="ZAPISZ">
               </div>
            </fieldset>
         </form>
      </div>
      <div class="uk-child-width-1-2 uk-text-center uk-margin-top" uk-grid>
         <form name="image">
            <fieldset class="uk-fieldset uk-box-shadow-large uk-padding-small">
               <legend class="uk-legend">Zmień zdjęcie profilowe</legend>
               <div uk-form-custom="target: true">
                  <input type="file" accept=".jpg, .jpeg, .png, .gif" name="image" required>
                  <input class="uk-input uk-width-1-1" type="text" placeholder="Wybierz zdjęcie" disabled name="file-label">
               </div>
               <div class="uk-margin">
                  <input class="uk-input" type="submit" style="cursor: pointer;" value="ZAPISZ">
               </div>
            </fieldset>
         </form>
         <form name="phone_no">
            <fieldset class="uk-fieldset uk-box-shadow-large uk-padding-small">
               <legend class="uk-legend">Zmień nr telefonu</legend>
               <div class="uk-margin">
                  <input class="uk-input" type="text" placeholder="Nowy nr telefonu" name="phone_no" required minlength="9" maxlength="9">
               </div>
               <div class="uk-margin">
                  <input class="uk-input" type="submit" style="cursor: pointer;" value="ZAPISZ">
               </div>
            </fieldset>
         </form>
      </div>
      <div class="uk-child-width-1-2 uk-text-center uk-margin-top" uk-grid>
         <form name="password">
            <fieldset class="uk-fieldset uk-box-shadow-large uk-padding-small">
               <legend class="uk-legend">Zmień hasło</legend>
               <div class="uk-margin">
                  <input class="uk-input" type="password" placeholder="Nowe hasło" name="pw1" minlength="6" maxlength="30" required>
               </div>
               <div class="uk-margin">
                  <input class="uk-input" type="password" placeholder="Powtórz nowe hasło" name="pw2" minlength="6" maxlength="30" required>
               </div>
               <div class="uk-margin">
                  <input class="uk-input" type="submit" style="cursor: pointer;" value="ZAPISZ">
               </div>
            </fieldset>
         </form>
         <form name="del-image">
            <fieldset class="uk-fieldset uk-box-shadow-large uk-padding-small">
               <legend class="uk-legend">Usuń zdjęcie profilowe</legend>
               <div class="uk-margin">
                  <input class="uk-input" type="submit" style="cursor: pointer;" value="USUŃ">
               </div>
            </fieldset>
         </form>
      </div>
   </div>

   <button class="uk-button uk-button-default" href="#modal" uk-toggle id="open-modal" hidden></button>

   <div id="modal" class="uk-flex-top" uk-modal>
      <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
         <button class="uk-modal-close-default" type="button" uk-close></button>
         <div>
            <div>
               <fieldset class="uk-fieldset uk-padding-top">
                  <legend class="uk-legend">Podaj hasło, aby zapisać zmiany</legend>
                  <div class="uk-margin">
                     <input class="uk-input" type="password" placeholder="Hasło" name="password-confirm" minlength="6" maxlength="30" required>
                  </div>
                  <div class="uk-margin">
                     <button class="uk-input" type="button" name="password-confirm" style="cursor: pointer;">ZAPISZ</button>
                  </div>
               </fieldset>
               </form>
            </div>
         </div>
      </div>
   </div>

</body>

</html>