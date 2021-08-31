<?php
if (!isset($_SESSION))
   session_start();

if (isset($_SESSION['user']['logged']) && $_SESSION['user']['logged']) {
   header('Location: profile.php');
   exit();
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
   <?php require_once __DIR__ . './assets/wireframe/head.php'; ?>

   <style>
      body.login {
         background-image: url('./assets/img/church.jpg');
         background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
      }

      @media screen and (min-width: 640px) {
         body.login {
            background-image: url('./assets/img/church.jpg');
         }
      }

      @media screen and (min-width: 960px) {
         body.login {
            background-image: url('./assets/img/church.jpg');
         }
      }

      @media screen and (min-width: 1200px) {
         body.login {
            background-image: url('./assets/img/church.jpg');
         }
      }

      @media screen and (min-width: 1600px) {
         body.login {
            background-image: url('./assets/img/church.jpg');
         }
      }
   </style>
</head>

<body class="login uk-cover-container uk-background-secondary uk-flex uk-flex-center uk-flex-middle uk-height-viewport uk-overflow-hidden uk-light" data-uk-height-viewport>
   <!-- overlay -->
   <div class="uk-position-cover uk-overlay-primary"></div>
   <!-- /overlay -->
   <div class="uk-position-bottom-center uk-position-small uk-visible@m uk-position-z-index">
      <span class="uk-text-small uk-text-muted">© 2021 LSO Skrzyszów</span>
   </div>
   <div class="uk-width-medium uk-padding-small uk-position-z-index" uk-scrollspy="cls: uk-animation-fade">

      <div class="uk-text-center uk-margin">
         <!-- <img src="./assets/img/logo.png" alt="Logo"> -->
         <span class="uk-text-lead uk-text-italic">LSO Skrzyszów</span>
      </div>

      <!-- login -->
      <form class="toggle-class">
         <fieldset class="uk-fieldset">
            <div class="uk-margin-small">
               <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                  <input class="uk-input uk-border-pill" required placeholder="Login lub e-mail" type="text" name="username" value="filek7"><!-- TODO: delete value props -->
               </div>
            </div>
            <div class="uk-margin-small">
               <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
                  <input class="uk-input uk-border-pill" required placeholder="Hasło" type="password" name="password" value="qwerty">
               </div>
            </div>
            <!-- <div class="uk-margin-small">
               <label><input class="uk-checkbox" type="checkbox"> Keep me logged in</label>
            </div> -->
            <div class="uk-margin-bottom">
               <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">ZALOGUJ SIĘ</button>
            </div>
         </fieldset>
      </form>
      <!-- /login -->

      <!-- recover password -->
      <form class="toggle-class" action="login-dark.html" hidden>
         <div class="uk-margin-small">
            <div class="uk-inline uk-width-1-1">
               <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: mail"></span>
               <input class="uk-input uk-border-pill" placeholder="E-mail" required type="text">
            </div>
         </div>
         <div class="uk-margin-bottom">
            <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">WYŚLIJ HASŁO</button>
         </div>
      </form>
      <!-- /recover password -->

      <!-- action buttons -->
      <!-- <div>
         <div class="uk-text-center">
            <a class="uk-link-reset uk-text-small toggle-class" data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade">Zapomniałeś hasła?</a>
            <a class="uk-link-reset uk-text-small toggle-class" data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade" hidden><span data-uk-icon="arrow-left"></span> Zaloguj się</a>
         </div>
      </div> -->
      <!-- action buttons -->
   </div>

   <script>
      document.querySelector('form').onsubmit = async e => {
         e.preventDefault();

         const username = document.querySelector('input[name=username]').value,
            password = document.querySelector('input[name=password]').value;

         if (!(username.length && password.length)) return;

         const formData = new FormData();
         formData.append('username', username);
         formData.append('password', password);

         const res = await fetch('./login_scripts/login.php', {
            method: 'POST',
            body: formData
         });
         const data = await res.json();

         UIkit.notification({
            message: data.msg,
            status: data.status || 'danger',
            timeout: 5000
         });

         if(!data.error) window.location.href = './profile.php';
         else document.querySelector('input[name=password]').value = '';
      }
   </script>

</body>

</html>