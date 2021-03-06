<?php
$_CONFIG = [
   'server' => [
      'domain' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] === '80' || $_SERVER['SERVER_PORT'] === '443' ? '' : ':' . $_SERVER['SERVER_PORT']),
      'database' => [
         'host' => 'db_host.com',
         'username' => 'db_username',
         'password' => 'db_password',
         'table' => 'lso-skrzyszow'
      ]
   ],
   'app' => [
      'profile_pictures_path' => '/assets/img/profile-pics/',
      'default_profile_picture_name' => 'default.png',
      'login_page_background_image' => 'church.png'
   ]
];
