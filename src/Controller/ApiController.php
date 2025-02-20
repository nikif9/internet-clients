<?php
namespace App\Controller;

use App\Core\Request;
use App\Model\TreeItem;
use App\Service\TreeService;

/**
 * Контроллер для AJAX API запросов.
 */
class ApiController {
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
     * Возвращает HTML потомков для указанного родительского элемента.
     *
     * @param Request $request
     */
    public function getChildren(Request $request) {
        $parent_id = $request->getQuery('parent_id');
        if (!$parent_id) {
            echo json_encode(['success' => false, 'message' => 'parent_id не передан']);
            exit;
        }
        $children = TreeItem::getChildren($parent_id, $this->db);
        $html = TreeService::renderChildrenHtml($children, $this->db);
        echo json_encode(['success' => true, 'html' => $html]);
    }
    
    /**
     * Возвращает описание элемента.
     *
     * @param Request $request
     */
    public function getDescription(Request $request) {
        $id = $request->getQuery('id');
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'id не передан']);
            exit;
        }
        $item = TreeItem::getById($id, $this->db);
        if ($item) {
            echo json_encode(['success' => true, 'description' => $item['description']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Элемент не найден']);
        }
    }
}
