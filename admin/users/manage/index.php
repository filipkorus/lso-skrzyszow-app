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
require_once __DIR__ . './../../../config.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
   <?php require_once __DIR__ . './../../../assets/wireframe/head.php'; ?>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once __DIR__ . './../../../assets/wireframe/navbar.php'; ?>

   <div class="uk-container">
      <progress class="uk-progress uk-flex-middle" value="0" max="100" id="bar" hidden></progress>
      <div class="uk-overflow-auto">
         <table class="uk-table uk-table-divider uk-table-hover">
            <thead>
               <tr>
                  <th class="uk-text-center">#ID</th>
                  <th class="uk-text-center">ZDJĘCIE</th>
                  <th class="uk-text-center">IMIĘ</th>
                  <th class="uk-text-center">NAZWISKO</th>
                  <th class="uk-text-center">STOPIEŃ</th>
                  <th class="uk-text-center">DATA URODZENIA</th>
                  <th class="uk-text-center">LOGIN</th>
                  <th class="uk-text-center">E-MAIL</th>
                  <th class="uk-text-center">NR TEL.</th>
                  <th class="uk-text-center">ADMIN</th>
                  <th class="uk-text-center">ONLINE</th>
                  <th class="uk-text-center">AKCJA</th>
               </tr>
            </thead>
            <tbody></tbody>
         </table>
      </div>
   </div>

   <template>
      <tr>
         <td data-id class="uk-text-center"></td>
         <td class="uk-text-center">
            <img data-picture src="" class="uk-border-circle" width="40" height="40" />
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
            <button class="uk-button uk-button-default" type="button" data-role="update">EDIT</button>
         </td>
      </tr>
   </template>

   <script>
      getUsers();

      const tbody = document.querySelector('tbody'),
         template = document.querySelector('template');

      let users;

      async function getUsers() {
         const res = await fetch('./../../scripts/get-users.php');
         const data = await res.json();

         users = data.users;
         data.users.forEach(user => {
            const div = template.content.cloneNode(true);

            user.birthdate = new Date(user.birthdate);
            user.last_time_online = new Date(user.last_time_online);

            div.querySelector('[data-id]').textContent = user.id;
            div.querySelector('[data-picture]').src = '<?php echo $_CONFIG['app']['profile_pictures_path']; ?>' + user.picture;
            div.querySelector('[data-picture_name]').textContent = user.picture;
            div.querySelector('[data-name]').textContent = user.name;
            div.querySelector('[data-last_name]').textContent = user.last_name;
            div.querySelector('[data-role]').textContent = user.role;
            div.querySelector('[data-birthdate]').textContent = formatDate(user.birthdate);
            div.querySelector('[data-username]').textContent = user.username;
            div.querySelector('[data-email]').textContent = user.email;
            div.querySelector('[data-phone_no]').textContent = user.phone_no.match(/.{1,3}/g).join('-');
            div.querySelector('[data-admin]').textContent = user.admin ? 'TAK' : 'NIE';
            div.querySelector('[data-online]').textContent = formatDate(user.last_time_online) + formatTime(user.last_time_online);

            tbody.append(div);
         });
      }

      function formatDate(date) {
         let day = date.getDate(),
            month = date.getMonth() + 1;
         day = (day < 10 ? `0${day}` : day);
         month = (month < 10 ? `0${month}` : month);
         return `${day}-${month}-${date.getFullYear()}`;
      }

      function formatTime(time) {
         let minutes = time.getMinutes();
         return `${time.getHours()}:${minutes < 10 ? '0' + minutes : minutes}`;
      }
   </script>

</body>

</html>