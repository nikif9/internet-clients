<?php
namespace App\Service;

use App\Model\User;
use App\Core\Database;

/**
 * Сервис для работы с аутентификацией.
 */
class AuthService {
    /**
     * Пытается авторизовать пользователя.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public static function login($username, $password) {
        $config = require __DIR__ . '/../../config/config.php';
        $pdo = Database::getInstance($config['db'])->getConnection();
        $user = User::findByUsername($username, $pdo);
        if ($user && $user->verifyPassword($password)) {
            $_SESSION['user_id'] = $user->id;
            return true;
        }
        return false;
    }
    
    /**
     * Проверяет, авторизован ли пользователь.
     *
     * @return bool
     */
    public static function check() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Разлогинивает пользователя.
     */
    public static function logout() {
        session_destroy();
    }
}
