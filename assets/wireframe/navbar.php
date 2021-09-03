<?php
if (!isset($_SESSION)) session_start();

if (!(isset($_SESSION['user']['logged']) && $_SESSION['user']['logged'])) {
   header('Location: /');
   exit();
}
?>
<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky">
   <nav class="uk-navbar-container uk-background-muted" uk-navbar>
      <div class="uk-navbar-right">
         <ul class="uk-navbar-nav">
            <li class=""><a href="/profile.php">Profil</a></li>
            <?php if ($_SESSION['user']['admin']) : ?>
               <li>
                  <a href="#">Administracja <span class="uk-margin-remove" uk-icon="icon: lock;"></span></a>
                  <div class="uk-navbar-dropdown">
                     <ul class="uk-nav uk-navbar-dropdown-nav">
                        <li><a href="/admin/points/">Punkty <i class="fas fa-table"></i></a></li>
                        <li><a href="/admin/ministerings/">Służenia <i class="fas fa-clipboard-list" style="font-size: 1.1rem;"></i></a></li>
                        <li>
                           <a href="#">Użytkownicy <span class="uk-align-right uk-margin-remove" uk-icon="icon: users;"></span></a>
                           <div class="uk-navbar-dropdown">
                              <ul class="uk-nav uk-navbar-dropdown-nav">
                                 <li><a href="/admin/users/manage/">Zarządzaj <span class="uk-align-right uk-margin-remove" uk-icon="icon: settings;"></span></a></li>
                                 <li><a href="/admin/users/add/">Dodaj nowego <span class="uk-align-right uk-margin-remove" uk-icon="icon: plus;"></span></a></li>
                              </ul>
                           </div>
                        </li>
                     </ul>
                  </div>
               </li>
            <?php endif; ?>
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
               <div class="uk-navbar-dropdown">
                  <ul class="uk-nav uk-navbar-dropdown-nav">
                     <li><a href="/settings/">Ustawienia <span class="uk-align-right uk-margin-remove" uk-icon="icon: cog;"></span></a></li>
                     <li><a href="/logout.php">Wyloguj się <span class="uk-align-right uk-margin-remove" uk-icon="icon: sign-out;"></span></a></li>
                  </ul>
               </div>
            </li>
            <li style="cursor: default;"><a href="#"></a></li>
         </ul>
      </div>
   </nav>
</div>