<?php
require_once 'config/koneksi.php';
require_once 'config/session.php';

Session::start();

if (Session::isLogin()) {
    header('Location: pages/dashboard.php');
} else {
    header('Location: auth/login.php');
}
exit;
?>