<?php
namespace App\Core;

/**
 * Класс для обработки HTTP-запроса.
 */
class Request {
    /**
     * Возвращает HTTP-метод запроса.
     *
     * @return string
     */
    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Возвращает URI запроса без GET-параметров.
     *
     * @return string
     */
    public function getUri() {
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        return $uri;
    }

    /**
     * Возвращает данные POST-запроса.
     *
     * @param string|null $key Ключ, если требуется отдельное значение.
     * @return mixed
     */
    public function getPost($key = null) {
        if ($key !== null) {
            return isset($_POST[$key]) ? $_POST[$key] : null;
        }
        return $_POST;
    }

    /**
     * Возвращает данные GET-запроса.
     *
     * @param string|null $key Ключ, если требуется отдельное значение.
     * @return mixed
     */
    public function getQuery($key = null) {
        if ($key !== null) {
            return isset($_GET[$key]) ? $_GET[$key] : null;
        }
        return $_GET;
    }
}
