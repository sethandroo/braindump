<?php
// Only user credentials
define('USERNAME', 'admin');
define('PASSWORD_HASH', password_hash('yourpassword', PASSWORD_DEFAULT));
session_start();