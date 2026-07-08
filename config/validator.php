<?php
class Validator {

    public static function sanitize($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    public static function required($input) {
        return !empty(trim($input));
    }

    public static function isNumber($input) {
        return is_numeric($input) && $input >= 0;
    }

    public static function minLength($input, $min) {
        return strlen(trim($input)) >= $min;
    }

    public static function maxLength($input, $max) {
        return strlen(trim($input)) <= $max;
    }

    public static function isImage($file) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        return in_array($ext, $allowed);
    }

    public static function maxFileSize($file, $maxSize = 2097152) {
        return $file['size'] <= $maxSize;
    }

    public static function isAlpha($input) {
        return preg_match('/^[a-zA-Z\s]+$/', trim($input));
    }

    public static function isPhone($input) {
        return preg_match('/^[0-9\+\-\s]{8,15}$/', trim($input));
    }

    public static function isUsername($input) {
        return preg_match('/^[a-zA-Z0-9_]+$/', trim($input));
    }
}
?>