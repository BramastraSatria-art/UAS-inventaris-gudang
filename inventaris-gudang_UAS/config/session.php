<?php
class Session {

    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setUser($id, $username, $nama, $role) {
        self::start();
        $_SESSION['user_id']       = $id;
        $_SESSION['user_username'] = $username;
        $_SESSION['user_nama']     = $nama;
        $_SESSION['user_role']     = $role;
        $_SESSION['login_time']    = time();
    }

    public static function isLogin() {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function isTimeout() {
        self::start();
        $timeout = 30 * 60; // 30 menit dalam detik
        if (isset($_SESSION['login_time'])) {
            if (time() - $_SESSION['login_time'] > $timeout) {
                self::destroy();
                return true;
            }
            // Perbarui waktu aktif
            $_SESSION['login_time'] = time();
        }
        return false;
    }

    public static function getId() {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }

    public static function getUsername() {
        self::start();
        return $_SESSION['user_username'] ?? null;
    }

    public static function getNama() {
        self::start();
        return $_SESSION['user_nama'] ?? null;
    }

    public static function getRole() {
        self::start();
        return $_SESSION['user_role'] ?? null;
    }

    public static function destroy() {
        self::start();
        $_SESSION = [];
        session_destroy();
    }
}
?>