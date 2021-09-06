<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/check-if-logged.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/User.php';

$db = new Database();
$pdo = $db->connect();
User::checkAccInfo();
?>
<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky">
   <nav class="uk-navbar-container uk-background-muted" uk-navbar>
      <div class="uk-navbar-right">
         <ul class="uk-navbar-nav">
            <li class="uk-visible@s"><a href="/profile.php">Profil</a></li>
            <?php if ($_SESSION['user']['admin']) : ?>
               <li>
                  <a href="#">Administracja <span class="uk-margin-remove" uk-icon="icon: lock;"></span></a>
                  <div class="uk-navbar-dropdown">
                     <ul class="uk-nav uk-navbar-dropdown-nav">
                        <li><a href="/admin/news/add/">Dodaj ogłoszenie <span class="uk-align-right uk-margin-remove" uk-icon="icon: file-text;"></span></a></li>
                        <li><a href="/admin/news/edit/">Edytuj ogłoszenia <span class="uk-align-right uk-margin-remove" uk-icon="icon: pencil;"></span></a></li>
                        <li><a href="/admin/points/">Punkty <i class="fas fa-table"></i></a></li>
                        <li><a href="/admin/ministerings/">Służenia <i class="fas fa-clipboard-list" style="font-size: 1.1rem;"></i></a></li>
                        <li><a href="/admin/users/manage/">Użytkownicy <span class="uk-align-right uk-margin-remove" uk-icon="icon: users;"></span></a></li>
                        <li><a href="/admin/users/add/">Dodaj użytkownika <span class="uk-align-right uk-margin-remove" uk-icon="icon: plus;"></a></li>
                     </ul>
                  </div>
               </li>
            <?php endif; ?>
            <li><a href="/news/display/">Ogłoszenia</a></li>
            <li>
               <a href="#">Ranking</a>
               <div class="uk-navbar-dropdown">
                  <ul class="uk-nav uk-navbar-dropdown-nav">
                     <li><a href="/ranking/month/">Miesięczny <span class="uk-align-right uk-margin-remove" uk-icon="icon: chevron-double-left;"></span></a></li>
                     <li><a href="/ranking/year/">Roczny <span class="uk-align-right uk-margin-remove" uk-icon="icon: calendar;"></span></a></li>
                  </ul>
               </div>
            </li>
            <li>
               <a href="/profile.php">Konto</a>
               <div class="uk-navbar-dropdown" uk-dropdown="pos: bottom-center;">
                  <ul class="uk-nav uk-navbar-dropdown-nav">
                     <li><a href="/settings/">Ustawienia <span class="uk-align-right uk-margin-remove" uk-icon="icon: cog;"></span></a></li>
                     <li><a href="/logout.php">Wyloguj się <span class="uk-align-right uk-margin-remove" uk-icon="icon: sign-out;"></span></a></li>
                     <hr>
                     <li><a href="/error/report/">Zgłoś błąd <span class="uk-align-right uk-margin-remove" uk-icon="icon: warning;"></span></a></li>
                  </ul>
               </div>
            </li>
            <li style="cursor: default;"><a href="#"></a></li>
         </ul>
      </div>
   </nav>
</div>