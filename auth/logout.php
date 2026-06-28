<?php
require_once '../config/session.php';

Session::start();
Session::destroy();

header('Location: ../auth/login.php');
exit;
?>