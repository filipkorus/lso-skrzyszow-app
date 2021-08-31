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
   <?php require_once __DIR__ . './../../assets/wireframe/head.php'; ?>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once __DIR__ . './../../assets/wireframe/navbar.php'; ?>

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

   <script>
      const inputs = document.querySelectorAll('input');

      document.querySelector('form').onsubmit = async e => {
         e.preventDefault();

         let f = false;
         inputs.forEach(input => {
            if (!input.value.length) {
               input.classList.add('uk-form-danger');
               f = true;
            } else input.classList.remove('uk-form-danger');
         });

         if (f) return UIkit.notification({
            message: 'Uzupełnij wszystkie pola!',
            status: 'danger',
            timeout: 5000
         });

         const formData = new FormData();
         formData.append('name', document.querySelector('input[name=name]').value);
         formData.append('last_name', document.querySelector('input[name=last_name]').value);
         formData.append('birthdate', document.querySelector('input[name=birthdate]').value);
         formData.append('username', document.querySelector('input[name=username]').value);
         formData.append('password', document.querySelector('input[name=password]').value);
         formData.append('email', document.querySelector('input[name=email]').value);
         formData.append('phone_no', document.querySelector('input[name=phone_no]').value);
         formData.append('role', document.querySelector('select[name=role]').value);
         formData.append('admin', document.querySelector('input[name=admin]').checked);

         const res = await fetch('./../scripts/add-user.php', {
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

         // clear all inputs
         document.querySelector('select[name=role]').selectedIndex = 0
         inputs.forEach(input => {
            if (input.type === 'text' || input.type === 'email' || input.type === 'date') input.value = '';
            else if (input.type === 'checkbox') input.checked = false;

            if (input.type === 'date') input.type = 'text';
         });
      }

      inputs.forEach(input => {
         input.addEventListener('keydown', () => {
            if (input.value.length) input.classList.remove('uk-form-danger');
         });
      });
   </script>

</body>

</html>