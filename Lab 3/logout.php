<?php
require_once __DIR__ . '/helpers/bootstrap.php';
require_once __DIR__ . '/helpers/auth.php';

auth_logout();
redirect('login.php');
?>
