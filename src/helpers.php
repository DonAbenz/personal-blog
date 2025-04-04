<?php

if (!function_exists('dd')) {
    function dd(...$args) {
        echo '<pre>';
        foreach ($args as $arg) {
            var_dump($arg);
        }
        echo '</pre>';
        die;
    }
}

if (!function_exists('dump')) {
    function dump(...$args) {
        echo '<pre>';
        foreach ($args as $arg) {
            var_dump($arg);
        }
        echo '</pre>';
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url", true, 302);
        exit;
    }
}

if (!function_exists('hash_password')) {
    function hash_password($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}