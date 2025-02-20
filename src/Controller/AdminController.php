<?php
namespace App\Controller;

use App\Core\Request;
use App\Service\AuthService;
use App\Model\TreeItem;
use App\Service\TreeService;
use App\Service\Validator;

/**
 * Контроллер для администрирования.
 */
class AdminController {
    /**
     * @var \PDO Объект подключения к БД.
     */
    protected $db;
    
    /**
     * Конструктор.
     */
    public function __construct() {
        $config = require __DIR__ . '/../../config/config.php';
        $this->db = \App\Core\Database::getInstance($config['db'])->getConnection();
    }
    
    /**
     * Отображает админ-панель.
     *
     * @param Request $request
     */
    public function index(Request $request) {
        if (!AuthService::check()) {
            header('Location: /login');
            exit;
        }
        $tree = TreeService::getTree($this->db);
        require __DIR__ . '/../View/admin.php';
    }
    
    /**
     * Обрабатывает POST-запросы в админке (добавление/редактирование).
     *
     * @param Request $request
     */
    public function handlePost(Request $request) {
        if (!AuthService::check()) {
            header('Location: /login');
            exit;
        }
        $data = $request->getPost();
        $operation = isset($data['operation']) ? $data['operation'] : '';
        if ($operation === 'add') {
            $validator = new Validator($data);
            $validator->required('title');
            if (!$validator->isValid()) {
                $_SESSION['errors'] = $validator->getErrors();
            } else {
                TreeItem::create($data['title'], $data['description'], $data['parent_id'], $this->db);
            }
        } elseif ($operation === 'edit') {
            $validator = new Validator($data);
            $validator->required('title');
            if (!$validator->isValid()) {
                $_SESSION['errors'] = $validator->getErrors();
            } else {
                TreeItem::update($data['id'], $data['title'], $data['description'], $data['parent_id'], $this->db);
            }
        }
        header('Location: /admin');
        exit;
    }

    /**
     * Удаляет элемент по его ID (а также его потомков).
     *
     * @param \App\Core\Request $request
     */
    public function delete(\App\Core\Request $request)
    {
        // Проверка аутентификации
        if (!AuthService::check()) {
            header('Location: /login');
            exit;
        }

        // Получаем ID элемента из GET-параметров
        $id = $request->getQuery('id');
        if (!$id) {
            $_SESSION['error'] = 'Идентификатор элемента не передан';
            header('Location: /admin');
            exit;
        }

        // Вызываем метод удаления в модели (рекурсивное удаление потомков)
        TreeItem::delete($id, $this->db);

        // Редирект обратно в админ-панель
        header('Location: /admin');
        exit;
    }
    
    /**
     * Отображает форму редактирования элемента.
     *
     * @param Request $request
     */
    public function editForm(Request $request) {
        if (!AuthService::check()) {
            header('Location: /login');
            exit;
        }
        
        $id = $request->getQuery('id');
        if (!$id) {
            header('Location: /admin');
            exit;
        }
        
        $element = TreeItem::getById($id, $this->db);
        if (!$element) {
            $_SESSION['error'] = "Элемент не найден";
            header('Location: /admin');
            exit;
        }
        
        $tree = TreeService::getTree($this->db);
        require __DIR__ . '/../View/admin_edit.php';
    }
}
