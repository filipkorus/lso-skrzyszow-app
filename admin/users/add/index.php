<?php
if (!isset($_SESSION)) session_start();

if (!(isset($_SESSION['user']['logged']) && $_SESSION['user']['logged'])) {
   header('Location: /');
   exit();
}

if (!(isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'])) {
   header('Location: /profile.php');
   exit();
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
   <?php require_once __DIR__ . './../../../assets/wireframe/head.php'; ?>

   <script src="./index.min.js"></script>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once __DIR__ . './../../../assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-container-small">
      <form>
         <fieldset class="uk-fieldset">
            <legend class="uk-legend">Dodaj użytkownika</legend>
            <div class="uk-margin uk-flex">
               <input class="uk-input" type="text" placeholder="Imię" name="name" required>
               <input class="uk-input" type="text" placeholder="Nazwisko" name="last_name" required>
            </div>

            <div class="uk-margin">
               <input class="uk-input" type="text" id="form-stacked-text" placeholder="Data urodzenia" name="birthdate" onfocus="(this.type='date')" required>
            </div>

            <div class="uk-margin">
               <input class="uk-input" type="text" placeholder="Login" name="username" minlength="4" maxlength="30" required>
            </div>

            <div class="uk-margin">
               <input class="uk-input" type="text" placeholder="Hasło" name="password" minlength="6" maxlength="30" required>
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
               <input class="uk-input" type="submit" name="submit" value="DODAJ" style="cursor: pointer;">
            </div>

         </fieldset>
      </form>
   </div>

</body>

</html>