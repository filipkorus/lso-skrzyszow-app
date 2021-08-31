<?php
if (!isset($_SESSION)) session_start();

if (!(isset($_SESSION['user']['logged']) && $_SESSION['user']['logged'])) {
   header('Location: /');
   exit();
}

require_once __DIR__ . './config.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
   <?php require_once __DIR__ . './assets/wireframe/head.php'; ?>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once __DIR__ . './assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-container-small uk-margin-large-top">
      <article class="uk-card uk-card-default uk-card-hover uk-card-body uk-margin">
         <header class="uk-comment-header">
            <div class="uk-grid-medium uk-flex-middle" uk-grid>
               <div class="uk-width-auto">
                  <img class="uk-comment-avatar uk-border-circle" src="<?php echo $_CONFIG['app']['profile_pictures_path'];
                  echo file_exists(__DIR__ . $_CONFIG['app']['profile_pictures_path'] . $_SESSION['user']['picture']) ?
                  $_SESSION['user']['picture'] : $_CONFIG['app']['default_profile_picture_name'];
                  
                  ?>" alt="zdjęcie profilowe" width="100" height="100" style="object-fit: cover;" loading="lazy">
               </div>
               <div class="uk-width-expand">
                  <h4 class="uk-comment-title uk-margin-remove"><?php echo $_SESSION['user']['username'] . ' (' . $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . ') - ' . ucfirst($_SESSION['user']['role']); ?></h4>
                  <hr>
                  <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                     <li>
                        Urodzony <?php echo formatDate($_SESSION['user']['birthdate']); ?>
                     </li>
                     <li>
                        <span style="text-transform: lowercase;">
                           <?php echo $_SESSION['user']['email']; ?>
                        </span>
                     </li>
                     <li>
                        <span>
                           +48 <?php echo implode(' ', str_split($_SESSION['user']['phone_no'], 3)); ?>
                        </span>
                     </li>
                  </ul>
               </div>
            </div>
         </header>
      </article>

      <div class="uk-child-width-1-3@m uk-grid-small uk-grid-match uk-margin-large-bottom" uk-grid>
         <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body">
               <h3 class="uk-card-title">Twoje punkty</h3>
               <ul>
                  <li>w zeszłym miesiącu: <b>21</b></li>
                  <li>w tym roku: <b>37</b></li>
               </ul>
            </div>
         </div>
         <div>
            <div class="uk-card uk-card-primary uk-card-hover uk-card-body">
               <h3 class="uk-card-title">Najbliższe służenia</h3>
               <ul>
                  <li>3 września, piątek, 6:30</li>
                  <li>5 września, niedziela, 11:00</li>
               </ul>
            </div>
         </div>
         <div>
            <div class="uk-card uk-card-secondary uk-card-hover uk-card-body">
               <h3 class="uk-card-title">Ostatni raz online</h3>
               <p><?php echo formatDate($_SESSION['user']['last_time_online']) . ' ' . Date('H:i', strToTime($_SESSION['user']['last_time_online'])); ?></p>
            </div>
         </div>
      </div>
   </div>

</body>

</html>

<?php
function formatDate($date)
{
   $m = Date('m', strToTime($date));

   switch ($m) {
      case 1:
         $m = 'stycznia';
         break;
      case 2:
         $m = 'lutego';
         break;
      case 3:
         $m = 'marca';
         break;
      case 4:
         $m = 'kwietnia';
         break;
      case 5:
         $m = 'maja';
         break;
      case 6:
         $m = 'czerwca';
         break;
      case 7:
         $m = 'lipca';
         break;
      case 8:
         $m = 'sierpnia';
         break;
      case 9:
         $m = 'września';
         break;
      case 10:
         $m = 'października';
         break;
      case 11:
         $m = 'listopada';
         break;
      case 12:
         $m = 'grudnia';
         break;
   }

   return Date('j', strToTime($date)) . " $m " . Date('Y', strToTime($date));
}
