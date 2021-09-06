<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Ranking.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Ministerings.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/News.php';

$db = new Database();
$pdo = $db->connect();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/head.php'; ?>
</head>

<body class="uk-height-viewport uk-background-muted">

   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/wireframe/navbar.php'; ?>

   <div class="uk-container uk-margin-large-top">
      <article class="uk-card uk-card-default uk-card-hover uk-card-body uk-margin">
         <header class="uk-comment-header">
            <div class="uk-grid-medium uk-flex-middle" uk-grid>
               <div class="uk-width-auto">
                  <img class="uk-comment-avatar uk-border-circle" src="<?php echo $_CONFIG['app']['profile_pictures_path'];
                                                                        echo file_exists($_SERVER['DOCUMENT_ROOT'] . $_CONFIG['app']['profile_pictures_path'] . $_SESSION['user']['picture']) ?
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
                        <span class="uk-text-lowercase"><?php echo $_SESSION['user']['email']; ?></span>
                     </li>
                     <li>
                        <span>
                           +48 <?php echo implode(' ', str_split($_SESSION['user']['phone_no'], 3)); ?>
                        </span>
                     </li>
                     <li class="uk-visible@s">
                        O<span class="uk-text-lowercase">statnie logowanie: <?php echo formatDate($_SESSION['user']['last_time_online']) . ', ' . Date('H:i', strToTime($_SESSION['user']['last_time_online'])); ?></span>
                     </li>
                  </ul>
               </div>
            </div>
         </header>
      </article>

      <div class="uk-child-width-1-3@m uk-grid-small uk-grid-match uk-margin-large-bottom" uk-grid>
         <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body">
               <div class="" uk-grid>
                  <h3 class="uk-card-title uk-width-1-2">Ogłoszenia</h3>
                  <button class="uk-button uk-button-default uk-button-small uk-width-1-2 uk-padding-remove" name="loadNews">Załaduj więcej...</button>
               </div>
               <ul id="news" class="uk-padding-small" style="max-height: 15vh; overflow-y: scroll;"></ul>
            </div>
         </div>
         <div>
            <div class="uk-card uk-card-primary uk-card-hover uk-card-body">
               <h3 class="uk-card-title">Najbliższe służenia</h3>
               <ul>
                  <?php
                  $ministerings = Ministerings::getMinisteringsDates($_SESSION['user']['id']);
                  foreach ($ministerings as $item) {
                     $day = intval(Date('d', strtotime($item['date'])));
                     $month = getPolishMonthName(Date('m', strtotime($item['date'])), true);

                     echo "<li>$day $month, " . getDayOfWeekName($item['day_of_week'], true) . ', ' . $item['hour'] . '</li>';
                  }
                  if (!sizeof($ministerings)) echo '<li>Nie masz żadnych służeń&nbsp;😢</li>';
                  ?>
               </ul>
            </div>
         </div>
         <div>
            <div class="uk-card uk-card-secondary uk-card-hover uk-card-body">
               <h3 class="uk-card-title">Twoje punkty</h3>
               <ul>
                  <?php
                  $month = intval(Date('m'));
                  $year = intval(Date('Y'));

                  if ($month == 2) {
                     $month_2 = 12;
                     $year_2 = $year - 1;
                  } else {
                     $month_2 = $month - 2;
                     $year_2 = $year;
                  }

                  if ($month == 1) {
                     $month_1 = 12;
                     $year_1 = $year - 1;

                     $month_2 = 11;
                     $year_2 = $year - 1;
                  } else {
                     $month_1 = $month - 1;
                     $year_1 = $year;
                  }
                  ?>
                  <li>cały rok <?php echo Date('Y'); ?>: <b><?php echo Ranking::getUserPointsYear($year); ?></b></li>
                  <li><?php echo getPolishMonthName($month_2) . ' ' . $year;
                        ?>: <b><?php echo Ranking::getUserPointsByMonthYear($month_2, $year_2); ?></b></li>
                  <li><?php echo getPolishMonthName($month_1) . ' ' . $year;
                        ?>: <b><?php echo Ranking::getUserPointsByMonthYear($month_1, $year_1); ?></b></li>
                  <li><?php echo getPolishMonthName($month) . ' ' . $year;
                        ?>: <b><?php echo Ranking::getUserPointsByMonthYear($month, $year); ?></b></li>
               </ul>
            </div>
         </div>
      </div>
   </div>

   <template>
      <li>
         <a href="" target="_blank" class="uk-link-text"></a>
      </li>
   </template>

   <script>
      const loadMoreBtn = document.querySelector('button[name=loadNews]'),
         template = document.querySelector('template'),
         newsContainer = document.querySelector('#news');

      let newsCounter = 3;

      loadMoreBtn.onclick = () => {
         newsCounter += 3;
         loadNews();
      }

      loadNews();

      async function loadNews() {
         const res = await fetch(`./news/get.php?n=${newsCounter}&wo_body=1`);
         const data = await res.json();

         if (data.disable_btn) {
            loadMoreBtn.disabled = true;
            loadMoreBtn.hidden = true;
         }

         if (data.error) return newsContainer.innerHTML = '<li>Wystąpił błąd bazy danych!</li>';

         newsContainer.innerHTML = '';

         data.news.forEach(news => {
            const div = template.content.cloneNode(true);
            div.querySelector('a').href = '/news/' + news.id;
            div.querySelector('a').textContent = news.title;
            newsContainer.append(div);
         });
      }
   </script>

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

function getPolishMonthName($n, $odmiana = false)
{
   if ($odmiana) {
      switch ($n) {
         case 1:
            return 'stycznia';
         case 2:
            return 'lutego';
         case 3:
            return 'marca';
         case 4:
            return 'kwietnia';
         case 5:
            return 'maja';
         case 6:
            return 'czerwca';
         case 7:
            return 'lipca';
         case 8:
            return 'sierpnia';
         case 9:
            return 'września';
         case 10:
            return 'października';
         case 11:
            return 'listopada';
         case 12:
            return 'grudnia';
      }
   } else {
      switch ($n) {
         case 1:
            return 'styczeń';
         case 2:
            return 'luty';
         case 3:
            return 'marzec';
         case 4:
            return 'kwiecień';
         case 5:
            return 'maj';
         case 6:
            return 'czerwiec';
         case 7:
            return 'lipiec';
         case 8:
            return 'sierpień';
         case 9:
            return 'wrzesień';
         case 10:
            return 'październik';
         case 11:
            return 'listopad';
         case 12:
            return 'grudzień';
      }
   }
}

function getDayOfWeekName($n, $polish = false)
{
   $arrEn = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
   $arrPolish = ['poniedziałek', 'wtorek', 'środa', 'czwartek', 'piątek', 'sobota', 'niedziela'];
   return ($polish ? $arrPolish[$n - 1] : $arrEn[$n - 1]);
}
