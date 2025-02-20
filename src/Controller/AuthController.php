<?php
namespace App\Controller;

use App\Core\Request;
use App\Service\AuthService;
use App\Service\Validator;

/**
 * Контроллер авторизации.
 */
class AuthController {
    /**
     * Отображает форму входа.
     *
     * @param Request $request
     */
    public function loginForm(Request $request) {
        require __DIR__ . '/../View/login.php';
    }

    /**
     * Обрабатывает POST-запрос авторизации.
     *
     * @param Request $request
     */
    public function login(Request $request) {
        $data = $request->getPost();
        $validator = new Validator($data);
        $validator->required('username')->required('password');
        if (!$validator->isValid()) {
            $_SESSION['errors'] = $validator->getErrors();
            header('Location: /login');
            exit;
        }
        if (AuthService::login($data['username'], $data['password'])) {
            header('Location: /admin');
            exit;
        } else {
            $_SESSION['error'] = "Неверный логин или пароль";
            header('Location: /login');
            exit;
        }
    }

    /**
     * Разлогинивает пользователя.
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        AuthService::logout();
        header('Location: /login');
        exit;
    }
}
