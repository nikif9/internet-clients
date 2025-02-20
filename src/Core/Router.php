<?php
namespace App\Core;

/**
 * Простой роутер для маршрутизации запросов.
 */
class Router {
    /**
     * @var Request Экземпляр запроса.
     */
    protected $request;

    /**
     * @var array Список маршрутов.
     */
    protected $routes = ['GET' => [], 'POST' => []];

    /**
     * Конструктор.
     *
     * @param Request $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Регистрирует GET-маршрут.
     *
     * @param string $uri URI маршрута.
     * @param string $action Контроллер@метод.
     */
    public function get($uri, $action) {
        $this->routes['GET'][$this->normalizeUri($uri)] = $action;
    }

    /**
     * Регистрирует POST-маршрут.
     *
     * @param string $uri URI маршрута.
     * @param string $action Контроллер@метод.
     */
    public function post($uri, $action) {
        $this->routes['POST'][$this->normalizeUri($uri)] = $action;
    }

    /**
     * Нормализует URI (убирает конечный слэш).
     *
     * @param string $uri
     * @return string
     */
    protected function normalizeUri($uri) {
        return rtrim($uri, '/') ?: '/';
    }

    /**
     * Диспетчеризует запрос и вызывает соответствующий метод контроллера.
     */
    public function dispatch() {
        $uri = $this->normalizeUri($this->request->getUri());
        $method = $this->request->getMethod();
        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            return $this->callAction($action);
        }
        http_response_code(404);
        echo "404 Not Found";
        exit;
    }

    /**
     * Вызывает действие контроллера.
     *
     * @param string $action Формат "Контроллер@метод".
     */
    protected function callAction($action) {
        list($controller, $method) = explode('@', $action);
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            if (method_exists($controllerInstance, $method)) {
                return $controllerInstance->$method($this->request);
            }
        }
        http_response_code(500);
        echo "500 Internal Server Error";
        exit;
    }
}
