# LSO Skrzyszów Web App

### About
This application provides altar boys' points counting for every month and displays next shifts. It has got a news feed implemented as well. Every user can change his own profile picture, username, password, etc. Admin can manage users, insert/edit their points or shifts. What is more, he has permission to publish announcements or edit/delete them too.

### Technologies
Project is created with:
* PHP
* MySQL
* JavaScript
* UI Kit

### Setting up
1. Rename `config.example.php` file to `config.php`.
2. Create new MySQL Database and import `create-database.sql` file.
3. Specify your database's host, username, password and table name in file `config.php` in lines from 6th to 9th.
4. Upload all files (except `create-database.sql` file) to HTTP server.
