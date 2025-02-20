<?php
/**
 * Единая точка входа для приложения.
 */

session_start();
require_once __DIR__ . '/../src/autoload.php';

use App\Core\Router;
use App\Core\Request;

$request = new Request();

$router = new Router($request);

// Публичная страница (отображение дерева)
$router->get('/', 'App\Controller\SiteController@index');

// Страницы авторизации
$router->get('/login', 'App\Controller\AuthController@loginForm');
$router->post('/login', 'App\Controller\AuthController@login');
$router->get('/logout', 'App\Controller\AuthController@logout');

// Админ панель (требует авторизации)
$router->get('/admin', 'App\Controller\AdminController@index');
$router->post('/admin', 'App\Controller\AdminController@handlePost');
$router->get('/admin/delete', 'App\Controller\AdminController@delete');
$router->get('/admin/edit', 'App\Controller\AdminController@editForm');

// AJAX API эндпоинты
$router->get('/api/get_children', 'App\Controller\ApiController@getChildren');
$router->get('/api/get_description', 'App\Controller\ApiController@getDescription');

$router->dispatch();
